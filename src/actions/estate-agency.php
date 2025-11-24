<?php
@include "../src/helpers/pageHelpers.php";
?>

<main class="main">
  <?php
  $blogRepo = new Office($portal);

  // vyhľadávanie podľa názvu RK – TERAZ CEZ GET
  $searchParam = isset($_GET['search'])
    ? filter_var($_GET['search'], FILTER_SANITIZE_STRING)
    : null;

  // radenie – TERAZ CEZ GET
  $orderKey = isset($_GET['order']) ? $_GET['order'] : null;

  // ak niekde používaš filter, tiež ho ťahaj z GET
  $filter = isset($_GET['filter']) && $_GET['filter'] !== '' ? $_GET['filter'] : null;

  /**
   * Mapovanie hodnoty z query parametra "order" na konkrétne ORDER BY,
   * ktoré sa posiela do Office::forSummary().
   *
   * NÁZVY STĹPCOV:
   *  - "name"         = názov realitnej kancelárie
   *  - "property_cnt" = alias/stĺpec s počtom nemovitostí (overené)
   */
  switch ($orderKey) {
    case 'name_asc':
      // DB poradie je tu v podstate jedno, budeme to ešte raz triediť v PHP
      $order = 'name asc';
      break;
    case 'name_desc':
      $order = 'name desc';
      break;
    case 'count_desc':
      $order = 'property_cnt desc';
      break;
    case 'count_asc':
      $order = 'property_cnt asc';
      break;
    default:
      $order = null; // default radenie vo forSummary()
      break;
  }

  $okresKod = null;
  $data = $portal->getPageData()['getData'];
  if (isset($data['sql']['okres_kod']) && $data['sql']['okres_kod'] > 0) {
    $okresKod = $data['sql']['okres_kod'];
  }

  // TODO hardcoded
  $OFFICES_COUNT = 16;
  $page = $portal->getPageNumber();
  $officesOffset = ($page * $OFFICES_COUNT  - $OFFICES_COUNT);

  $officeService = new Office($portal);

  $officesQueryResult = $officeService->forSummary(
    $portal->getPortalCountryId(),
    $officesOffset,
    $OFFICES_COUNT,
    $searchParam,
    $okresKod,
    $order,
    $filter
  );
  //$officesQueryResult = $officeService->getAll();
  $offices = $officesQueryResult['rows'];
  $maxPage = ceil($officesQueryResult['totalSum'] / $OFFICES_COUNT);

  // 🔧 dodatočné radenie v PHP pre A–Z / Z–A, aby nebol bordel v strede zoznamu
  if (!empty($offices) && is_array($offices)) {
    if ($orderKey === 'name_asc') {
      usort($offices, function ($a, $b) {
        $an = mb_strtolower($a['name'] ?? '', 'UTF-8');
        $bn = mb_strtolower($b['name'] ?? '', 'UTF-8');
        return $an <=> $bn; // A–Z
      });
    } elseif ($orderKey === 'name_desc') {
      usort($offices, function ($a, $b) {
        $an = mb_strtolower($a['name'] ?? '', 'UTF-8');
        $bn = mb_strtolower($b['name'] ?? '', 'UTF-8');
        return $bn <=> $an; // Z–A
      });
    }
  }
  ?>

  <?php @include "../src/pageComponents/breadcrumbs-full.php" ?>

  <section class="listing listing--filter listing__agencies">
    <article class="container container--xlg" role="article">
      <div class="listing__wrap">
        <div class="listing__content">
          <?php
          // horný search bar pre realitky
          echo $portal->render("search-filter-agencies.php", array(
            'search' => $searchParam
          ));
          ?>

          <h1 class="title title--lg"><?php echo $data['html']['h1-text']; ?></h1>
          <?php echo $data['html']['desc-text']; ?>

          <?php
          // aktuálne zvolený key pre radenie (do selectu)
          $currentOrderKey = $orderKey ?? '';
          ?>

          <!-- TOOLBAR NA ŘAZENÍ – vyzerá ako listing sort, ale bez poptávky a filtra -->
          <div class="listing__top">
            <div class="listing__top-col">
              <!-- ľavý stĺpec nechávame prázdny kvôli layoutu -->
            </div>

            <div class="listing__top-col">
              <form method="GET" action="/realitni-kancelar/">
                <!-- zachovanie aktuálneho search a filtra pri zmene radenia -->
                <?php if ($searchParam !== null && $searchParam !== '') { ?>
                  <input type="hidden" name="search"
                         value="<?php echo htmlspecialchars($searchParam, ENT_QUOTES); ?>">
                <?php } ?>
                <?php if (!empty($filter)) { ?>
                  <input type="hidden" name="filter"
                         value="<?php echo htmlspecialchars($filter, ENT_QUOTES); ?>">
                <?php } ?>

                <div class="location-filter__select">
                  <div class="custom-select custom-select--filter">
                    <select
                      class="cookie-save-input-value"
                      name="order"
                      id="agencies-sort"
                      onchange="this.form.submit()"
                    >
                      <option value="">Výchozí řazení</option>

                      <option value="name_asc"
                        <?php if ($currentOrderKey === 'name_asc') echo 'selected'; ?>>
                        Dle abecedy A–Z
                      </option>

                      <option value="name_desc"
                        <?php if ($currentOrderKey === 'name_desc') echo 'selected'; ?>>
                        Dle abecedy Z–A
                      </option>

                      <option value="count_desc"
                        <?php if ($currentOrderKey === 'count_desc') echo 'selected'; ?>>
                        Počet nemovitostí 9–0
                      </option>

                      <option value="count_asc"
                        <?php if ($currentOrderKey === 'count_asc') echo 'selected'; ?>>
                        Počet nemovitostí 0–9
                      </option>
                    </select>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /listing__top -->

          <div class="listing__grid">
            <?php
            foreach ($offices as $office) {
              echo '<div class="estate">';
              echo $portal->render("directory-item.php",  array(
                'office' => $office
              ));
              echo '</div>';
            }
            ?>
          </div>
        </div>
        <div class="listing__map">
          <script>
            <?php
            $geoJson = new GeoJson();
            foreach ($offices as $office) {
              if ($office['locality_latitude'] > 0 && $office['locality_longitude'] > 0) {
                $geoJson->addPoint(
                  $office['locality_latitude'],
                  $office['locality_longitude'],
                  array(
                    'advert_id'  => $office['advert_id'],
                    'image'      => $office['logo_url'],
                    'link'       => $office['alias_'],
                    'textTop'    => htmlspecialchars(
                      preg_replace('/\s+/', ' ', $office['name']),
                      ENT_QUOTES,
                      'UTF-8'
                    ),
                    'textBottom' => '',
                  )
                );
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

  <section class="directory-top">
    <article class="container container--text" role="article">
    </article>
  </section>

  <section class="directory">
    <article class="container" role="article">
      <div class="directory__grid">
      </div>
      <?php
      if ($officesQueryResult['totalSum'] > $OFFICES_COUNT) {

        $baseUrl = $_SERVER['REDIRECT_URL'];
        $posPage = strpos($baseUrl, '/page-');
        if ($posPage !== false) {
          $baseUrl = substr($baseUrl, 0, $posPage);
        }

        // 🔁 prenášaj search + order + filter do stránkovania
        $queryParams = [];
        if ($searchParam !== null && $searchParam !== '') {
          $queryParams['search'] = $searchParam;
        }
        if ($orderKey !== null && $orderKey !== '') {
          $queryParams['order'] = $orderKey;
        }
        if ($filter !== null && $filter !== '') {
          $queryParams['filter'] = $filter;
        }

        $pager = new Pager(
          $baseUrl,
          $baseUrl,
          $maxPage,
          $page,
          $queryParams
        );
        echo $pager->render(true);
      }
      ?>
    </article>
  </section>

  <?php @include "../src/pageComponents/diary.php" ?>
</main>

<?php @include "../src/pageComponents/footer.php" ?>
