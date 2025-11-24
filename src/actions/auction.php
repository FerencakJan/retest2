<?php
@include "../src/helpers/pageHelpers.php";

$auctionsService = new Auctions($portal);
$propertyService = new Property($portal);

$pageData = $portal->getPageData();
$page = solvePageNumber();

$data = $portal->getGetDataResponse();
$AUCTIONS_COUNT = 5;

$officesOffset = ($page * $AUCTIONS_COUNT - $AUCTIONS_COUNT);
$auctions = $auctionsService->getAuctionsForSummary($officesOffset, $AUCTIONS_COUNT);
$maxPage = ceil($auctions['totalSum'] / $AUCTIONS_COUNT);
$searchParam = isset($_POST['search']) ? filter_var($_POST['search'], FILTER_SANITIZE_STRING) : null;
$globalListResource = new \Portal\Helpers\Resources\GlobalList('cz');
?>

<main class="main">

  <?php echo $portal->render("breadcrumbs-full.php",array("data" => $pageData['getData'])); ?>

  <section class="listing listing--filter">
    <article class="container container--xlg" role="article">
      <div class="listing__wrap">
        <div class="listing__content">
          <h1 class="title title--semi">Aukce nemovitostí, on-line dražby domů a bytů</h1>
          <p>Aktuální seznam nemovitostí v aukci. Máte jedinečnou možnost získat svůj nový dům či byt v dražbě. Dražby
            nemovitostí, stejně jako dražby bytů nebo dražby na domy Vám mohou získat svoje nové bydlení za rozumnější
            cenu. Zkuste pořídit svoje nové bydlení v aukci formou dražby.</p>
          <div class="listing__grid">
            <?php
            $count = 0;
            if (null !== $auctions) {
            $count = $auctions['totalSum'];
            ?>
            <?php foreach ($auctions['rows'] as $auction) { ?>
              <?php
              $property = $propertyService->findById($auction['idz']);
              $propertyPhotos = $property ? unserialize($property['photos']) : null;
              ?>
              <div class="estate estate--auction">
                <div class="estate__image">
                  <div class="estate__gallery">
                    <?php
                    if ($propertyPhotos !== null) {
                      foreach ($propertyPhotos as $propertyPhoto) { ?>
                        <img loading="lazy" src="<?php echo $propertyPhoto; ?>" alt="">
                      <?php }
                    } else { ?>
                      <img loading="lazy" src="<?php echo $auction['photo']; ?>" alt="">
                    <?php } ?>
                  </div>
                </div>
                <div class="estate__content">
                  <h3 class="title title--sm estate__title"><?php echo $auction['nazev']; ?></h3>
                  <p class="estate__location"><?php echo $property['advert_locality'] ?></p>
                  <div class="estate__data">
                    <dl>
                      <div class="estate__data-item">
                        <dt>Aukce otevřena:</dt>
                        <dd><strong><?php
                            $start = new DateTime($auction['datum_aukce_start']);
                            $end = new DateTime($auction['datum_aukce_end']);

                            echo '<b>' . $start->format('d.m.Y H:i') . ' - ' . $end->format('d.m.Y H:i') . '</b>';
                            ?></strong></dd>
                      </div>
                      <div class="estate__data-item">
                        <dt>Vyvolávací cena</dt>
                        <dd><strong><?php echo number_format($auction['vyvolavaci_cena'], 0, ' ', ' '); ?> Kč</strong>
                        </dd>
                      </div>
                      <div class="estate__data-item">
                        <dt>Aktuální cena</dt>
                        <dd><strong><?php echo number_format($auction['aktualni_cena'], 0, ' ', ' '); ?> Kč</strong>
                        </dd>
                      </div>
                      <div class="estate__data-item">
                        <dt>Min. příhoz</dt>
                        <dd><strong><?php echo number_format($auction['minimalni_prihoz'], 0, ' ', ' '); ?> Kč</strong>
                        </dd>
                      </div>
                    </dl>
                  </div>
                  <div class="estate__action">
                    <a href="/detail-aukce/<?php echo $auction['id']; ?>" class="btn btn--forward">
                      <span class="icon icon--icon-next">
                        <svg class="icon__svg"><use xlink:href="#icon-next"></use></svg>
                      </span>
                      Do aukce
                    </a>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
          <?php
          if ($auctions['totalSum'] > $AUCTIONS_COUNT) {

            $baseUrl = $_SERVER['REDIRECT_URL'];
            $posPage = strpos($baseUrl, '/page-');
            if ($posPage !== false) {
              $baseUrl = substr($baseUrl, 0, $posPage);
            }

            $pager = new Pager($baseUrl, $baseUrl, $maxPage, $page, $searchParam != null ? array('search' => $_GET['search']) : array());
            echo $pager->render(true);
          }
          }
          ?>
        </div>
        <div class="listing__map">
          <script>
            <?php
            $geoJson = new GeoJson();
            foreach ($auctions['rows'] as $mapProperty) {
              $tmpProperty = $propertyService->findById($mapProperty['idz']);
              if ($tmpProperty) {
                $geoJson->addPoint(
                  $tmpProperty['locality_latitude'],
                  $tmpProperty['locality_longitude'],
                  array(
                    'advert_id' => $tmpProperty['advert_id'],
                    'image' => $tmpProperty['photo'],
                    'link' => $tmpProperty['alias_'],
                    'textTop' => htmlspecialchars(preg_replace('/\s+/', ' ', $tmpProperty['title']), ENT_QUOTES, 'UTF-8'),
                    'textBottom' => $tmpProperty['advert_price'] == 999999999 || $tmpProperty['advert_price'] === 0 ?
                      'Info v RK' :
                      number_format($tmpProperty['advert_price'], 0, ' ', ' ') . ' ' . $globalListResource->getByNameId($tmpProperty['advert_price_unit_eu'])));
              }
            }
            ?>

            function getMarkers() {
              return JSON.parse(<?php echo $geoJson->getHtml(); ?>);
            }
          </script>
          <div class="map-wrap">
            <div id="js-map"></div>
          </div>
        </div>
      </div>
    </article>
  </section>

</main>

<?php @include "../src/pageComponents/footer.php" ?>
