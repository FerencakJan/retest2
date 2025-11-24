<?php
@include "../src/helpers/pageHelpers.php";
$propertyRepo = new Property($portal);
$globalListResource = new \Portal\Helpers\Resources\GlobalList('cz');

$pageData = $portal->getPageData();

$page = 1; // TODO pagination

$propertiesOffset = ($page * Constants::DEFAULT_LISTING_COUNT) - Constants::DEFAULT_LISTING_COUNT;
$propertyIds = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : [];
$propertiesQueryResult = null;
if (count($propertyIds) > 0)
{
    // todo vyresit prepinatelny sorting
    $propertiesQueryResult = $propertyRepo->findByIds($propertyIds, $propertiesOffset, Constants::DEFAULT_LISTING_COUNT, 'sort-default');
}

?>
<main class="main">
<!--    --><?php //@include "../src/pageComponents/filter.php" ?>
    <section class="listing listing--filter">
        <article class="container container--xlg" role="article">
            <div class="listing__wrap">
                <div class="listing__content">
                    <div class="listing__top">
                        <div class="listing__top-col">
<!--                            --><?php //@include "../src/pageComponents/rating.php" ?>
                        </div>
                        <div class="listing__top-col">
                            <?php @include "../src/pageComponents/join-sm.php" ?>
                            <a href="#search-advanced" class="btn btn--secondary btn--sm js-modal-open">Uložit jako
                                poptávku</a>
                        </div>
                    </div>
                    <div class="listing__heading">
                        <h1 class="title listing__title">Mé oblíbené</h1>
                    </div>

<!--                    --><?php //@include "../src/pageComponents/location-filter.php" ?>


                      <?php if($propertiesQueryResult) { ?>
                        <div class="listing__grid">
                          <?php foreach ($propertiesQueryResult['rows'] as $favoriteProperty){
                            echo $portal->render('/estate.php', [
                              'favoriteProperty' => $favoriteProperty,
                              'globalListResource' => $globalListResource,
                            ]);
                          } ?>
                        </div>
                      <?php } else { ?>
<!--                        TODO: nic zde neni osetrit textem -->
                      <?php } ?>


<!--                      --><?php //for()
//                        <?php @include "../src/pageComponents/estate.php" ?>
<!--                        --><?php //@include "../src/pageComponents/estate-heart.php" ?>
<!--                        --><?php //@include "../src/pageComponents/banner.php" ?>
<!--                        --><?php //@include "../src/pageComponents/estate-no-image.php" ?>
<!--                        --><?php //@include "../src/pageComponents/estate-alert.php" ?>
<!--                        --><?php //@include "../src/pageComponents/estate.php" ?>
<!--                        --><?php //@include "../src/pageComponents/estate.php" ?>
<!--                        --><?php //@include "../src/pageComponents/estate.php" ?>


<!--                    --><?php //@include "../src/pageComponents/interested.php" ?>
<!--                    --><?php //@include "../src/pageComponents/pagination.php" ?>

                    <div class="listing__additional">
                        <h2 class="title title--compact">Byty (460+)</h2>
                        <p>Poohlížíte se po bytě? Na Eurobydleni.cz je pro Vás připravena opravdu velká nabídka bytů.
                            Není proto divu, že byty ve své bohaté nabídce lákají. Na našem internetovém portálu můžete
                            najít všechny možné byty jak k pronájmu, tak i ke koupi. Záleží pak čistě na vás, zde si
                            vyberete nějakou starší zástavbu, či nový byt.</p>
                        <h3 class="title title--next">Jaké byty zvolit</h3>
                        <p>Všechny druhy bytů mají své kouzlo. Je pravdou, že mnoho lidí, kteří hledají nové byty, mají
                            již předem jasno, zda by si přáli bydlet v nové zástavbě. Naopak u starších bytů je prvním
                            předpokladem právě to, že si na samém počátku ještě budete muset svůj nově koupený byt třeba
                            i zrekonstruovat. Na druhou stranu díky tomu, že ve velké míře jsou starší byty levnější,
                            můžete si do nich nějakou částku investovat. Tím více, pokud jste si byt přímo koupili.</p>
                        <div class="read-more" data-height="200">
                          <h3 class="title title--next">Lorem Ipsum Title</h3>
                          <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Delectus dolores accusantium labore ab placeat vero officiis aliquam hic dolorem unde pariatur molestias, earum voluptate, repellat ex maiores omnis culpa a. Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt nam reiciendis nemo doloribus voluptatum enim fugiat. Cum commodi ex, reprehenderit doloribus earum nisi quod dolore, sequi enim officia ratione? Enim!</p>
                        </div>
                        <div class="line">
                          <button class="btn btn--sm btn--nofocus line__after read-more__btn js-read-more"><span>Číst dále</span><span>Zobrazit méně</span></button>
                        </div>
                    </div>

                </div>
                <div class="listing__map">
                  <script>
                    <?php
                    $geoJson = new GeoJson();
                    foreach ($propertiesQueryResult['rows'] as $mapProperty) {
                      $geoJson->addPoint(
                        $mapProperty['locality_latitude'],
                        $mapProperty['locality_longitude'],
                        array(
                          'advert_id' => $mapProperty['advert_id'],
                          'image' => $mapProperty['photo'],
                          'link' => $mapProperty['alias_'],
                          'textTop' => htmlspecialchars(preg_replace('/\s+/', ' ',$mapProperty['title']), ENT_QUOTES, 'UTF-8'),
                          'textBottom' => $mapProperty['advert_price'] == 999999999 || $mapProperty['advert_price'] === 0?
                            'Info v RK' :
                            number_format($mapProperty['advert_price'],0, ' ', ' ') . ' ' . $globalListResource->getByNameId($mapProperty['advert_price_unit_eu'])));
                    }
                    ?>

                    function getMarkers(){
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
