<?php

//error_reporting(0);

@include "../src/helpers/pageHelpers.php";

use Portal\Helpers\Resources\PropertyCountResource;



  $propertyService = new Property($portal);



  $property = $propertyService->findById($portal->getPageData()['id']);

  $companyData = $portal->getCompanyTranslator();

  $data = $portal->getPageData();

  $globalListResource = new \Portal\Helpers\Resources\GlobalList('cz');



  $propertyCount = (new PropertyCountResource($portal))->getData();

  $mostlySearched = (new MostlySearched($portal->getLanguage()))->getAll();

?>

<main class="main">

  <section class="front">



    <div class="front__map">

      <script>

        <?php

        $geoJson = new GeoJson();

        $latitude = null;

        $longitude = null;



        $query = @unserialize (file_get_contents('http://ip-api.com/php/'));

        if ($query && $query['status'] == 'success') {

          $latitude = $query['lat'];

          $longitude = $query['lon'];

        }



        $lastQueryData = LastQuerySession::getLastQueryData() ? LastQuerySession::getLastQueryData()['locality_okres_kod'] : null;

        $okres = $lastQueryData ? key(reset($lastQueryData)) : null;



        $mapProperties = [];

        if ($latitude !== null && $longitude !== null) {

          $mapProperties = $propertyService->getPropertiesBasedOnUserLocation($latitude,$longitude);

        } elseif ($okres !== null) {

          $mapProperties = $propertyService->getPropertiesByOkres($okres);

        } else {

          $mapProperties = $propertyService->getLast30ForHomepageMap();

        }

        if($mapProperties){

          foreach ($mapProperties as $mapProperty) {

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



    <article class="container">

      <?php include "../src/pageComponents/search.php" ?>

    </article>

  </section>



  <section class="offer">

    <article class="container" role="article">

      <div class="offer__top">

        <h2 class="title">Nabídka v&nbsp;krajských městech</h2>

        <div class="offer__nav slider-nav">

          <div class="offer__nav-arrow slider-nav-prev">

            <span class="icon icon--icon-arrow-left">

              <svg class="icon__svg"><use xlink:href="#icon-arrow-left"></use></svg>

            </span>

          </div>

          <div class="offer__nav-arrow slider-nav-next">

            <span class="icon icon--icon-arrow-right">

              <svg class="icon__svg"><use xlink:href="#icon-arrow-right"></use></svg>

            </span>

          </div>

        </div>

      </div>



      <div class="slider">

          <?php

          $regions = $companyData->getRegions();

          unset($regions[27]);

          $cities = (new KrajCityHelper())->getKrajCities();

          foreach ($regions as $regionKey => $region) { ?>

          <div class="offer-item">

            <img class="offer-item__bg" src="https://eurobydleni.cz/mlift/images/items/kraj/<?php echo $regionKey?>.jpg" alt="">

              <div class="offer-item__front">

                <h3><a href="reality/<?php echo $region['url'] ?>/"><?php echo $cities[$regionKey] ?></a></h3>

                <p><?php echo $propertyCount['propertyCountByCity'][$regionKey] ?> nabídek</p>

              </div>

              <div class="offer-item__back">

                <div class="offer-item__back-top">

                  <h3><a href="reality/<?php echo $region['url'] ?>/"><?php echo $region['name'] ?></a></h3>

                  <p><?php echo $propertyCount['propertyCountByCity'][$regionKey] ?> nabídek</p>

                </div>

                <div class="offer-item__property_types_wrapper">

                  <div class="offer-item__property_types">

                    <ul>

                      <li><a href="byty/<?php echo $region['url'] ?>/">Byty</a></li>

                      <li><a href="domy/<?php echo $region['url'] ?>/">Domy</a></li>

                      <li><a href="rekreacni-objekty/<?php echo $region['url'] ?>/">Rekreační</a></li>

                      <li><a href="komercni-nemovitosti/<?php echo $region['url'] ?>/">Komerční</a></li>

                      <li><a href="pozemky/<?php echo $region['url'] ?>/">Pozemky</a></li>

                      <li><a href="ostatni/<?php echo $region['url'] ?>/">Ostatní</a></li>

                    </ul>

                  </div>

                  <div class="offer-item__property_types">

                    <ul>

                      <li><a href="reality/<?php echo $region['url'] ?>/prodej/">Prodej</a></li>

                      <li><a href="reality/<?php echo $region['url'] ?>/pronajem/">Pronájem</a></li>

                    </ul>

                  </div>

                </div>

              </div>

          </div>

          <?php } ?>

      </div>



      <h3 class="title title--md">Rychlé lokality</h3>

      <div class="badge-wrap">

      <?php

      $regionsData = $companyData->getRegions();

      $regionsArray = [];

      foreach ($regionsData as $regions) {

        foreach ($regions['subRegions'] as $region) { ?>

            <a href="reality/<?php echo $region['url'] ?>/" class="badge"><?php echo $region['name'] ?></a>

       <?php }

      }

      ?>

      </div>

    </article>

  </section>



  <section class="offer">

    <article class="container" role="article">

      <div class="offer__top">

        <h2 class="title">Nabídka inzerce</h2>

        <div class="offer__nav">

          <div class="offer__nav-arrow slider-nav-prev-2">

            <span class="icon icon--icon-arrow-left">

              <svg class="icon__svg"><use xlink:href="#icon-arrow-left"></use></svg>

            </span>

          </div>

          <div class="offer__nav-arrow slider-nav-next-2">

            <span class="icon icon--icon-arrow-right">

              <svg class="icon__svg"><use xlink:href="#icon-arrow-right"></use></svg>

            </span>

          </div>

        </div>

      </div>



      <div class="slider-2">

        <?php

        $properties = $companyData->getProperty(6);

        foreach ($properties as $propertyKey => $property) { ?>

          <div class="offer-item">

            <img  class="offer-item__bg" src="https://eurobydleni.cz/mlift/images/items/typ_nemovitosti/<?php echo $propertyKey ?>.jpg" alt="">

            <div class="offer-item__front">

              <h3><?php echo $property['name'] ?></h3>

              <p><?php echo $propertyCount['propertyCountByCategory'][$propertyKey] ?> nabídek</p>

            </div>

            <div class="offer-item__back">

              <div class="offer-item__back-top">

                <h3><?php echo $property['name'] ?></h3>

                <p><?php echo $propertyCount['propertyCountByCategory'][$propertyKey] ?> nabídek</p>

              </div>

              <div class="offer-item__back-wrap">

                <div class="offer-item__back-block">

                  <a href="<?php echo $property['url'] ?>/prodej/">

                    <h4>Prodej</h4>

                  </a>

                  <ul>

                    <?php

                      foreach ($companyData->getProperty($propertyKey) as $propertyType) { ?>

                        <li><a href="<?php echo sprintf('/%s/%s/prodej', $property['url'] , $propertyType['url']) ?>/"><?php echo $propertyType['name'] ?></a></li>

                      <?php } ?>

                  </ul>

                </div>

                <div class="offer-item__back-block">

                  <a href="<?php echo $property['url'] ?>/pronajem/">

                    <h4>Pronájem</h4>

                  </a>

                  <ul>

                    <?php

                    foreach ($companyData->getProperty($propertyKey) as $propertyType) { ?>

                      <li><a href="<?php echo sprintf('/%s/%s/pronajem', $property['url'] , $propertyType['url']) ?>/"><?php echo $propertyType['name'] ?></a></li>

                    <?php } ?>

                  </ul>

                </div>

              </div>

            </div>

          </div>

        <?php } ?>

      </div>



      <h3 class="title title--md">Často hledané nemovitosti</h3>

      <div class="badge-wrap">

        <?php

        foreach ($mostlySearched as $item) { ?>

          <a href="<?php echo $item['url'] ?>" class="badge"><?php echo $item['nazev'] ?></a>

        <?php } ?>

      </div>



    </article>

  </section>



  <?php @include "../src/pageComponents/diary.php" ?>



</main>



<?php @include "../src/pageComponents/footer.php" ?>
<style>
  /* map musí brať kliky a ovládače majú byť pod headerom */
  .front__map::before,
  .front__map::after,
  .map-wrap::before,
  .map-wrap::after { pointer-events: none !important; }

  .map-wrap { position: relative; z-index: 1; pointer-events: auto !important; }
  #js-map, #js-map * { pointer-events: auto !important; }

  .leaflet-control-container { z-index: 10000 !important; }
  .leaflet-top,
  .leaflet-control-container .leaflet-top { top: 90px !important; } /* dolaď podľa výšky headera */

  /* === Zoom bar + naše tretie tlačidlo ===================================== */

  /* použijeme class 'map-has-toggle' (JS ju pridá na .leaflet-control-zoom) */
  .leaflet-control-zoom.map-has-toggle a.mapstyle-btn {
    width: 30px;                /* rovnaké ako natívny Leaflet */
    height: 30px;
    line-height: 30px;
    padding: 0;
    display: block;
    text-align: center;
    font-size: 16px;            /* veľkosť emoji/SVG */
    background: #fff;
    color: #333;
  }

  /* keď je v bare tretie tlačidlo, odstránime spodné zaoblenie z "−" */
  .leaflet-control-zoom.map-has-toggle a.leaflet-control-zoom-out {
    border-bottom-left-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
  }

  /* a dáme spodné zaoblenie nášmu tlačidlu (posledné v bare) */
  .leaflet-control-zoom.map-has-toggle a.mapstyle-btn {
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
  }

  /* jemný hover efekt ako natívne */
  .leaflet-control-zoom.map-has-toggle a.mapstyle-btn:hover {
    background: #f4f4f4;
  }

  /* ak je zoom bar vpravo/ľavo, nech sa to pekne nalepí – Leaflet to rieši sám,
     ale pre istotu bez extra medzier: */
  .leaflet-control-zoom.map-has-toggle a.mapstyle-btn { margin: 0; }
  .leaflet-control-layers { display: none !important; }
</style>

<script>
  // ---- cookies + geo helpers + layers toggle ----
  (function(){
    function setCookie(name, value, days) {
      var expires = "";
      if (days) {
        var d = new Date();
        d.setTime(d.getTime() + days*24*60*60*1000);
        expires = "; expires=" + d.toUTCString();
      }
      document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/; SameSite=Lax";
    }
    function getCookie(name) {
      var m = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([$?*|{}\[\]\\/+^])/g, '\\$1') + '=([^;]*)'));
      return m ? decodeURIComponent(m[1]) : null;
    }
    function requestBrowserGeo() {
      return new Promise(function(resolve, reject){
        if (!navigator.geolocation) return reject(new Error("no-geo"));
        navigator.geolocation.getCurrentPosition(function(pos){
          resolve({ lat: pos.coords.latitude, lng: pos.coords.longitude });
        }, function(err){
          reject(err || new Error("geo-denied"));
        }, { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 });
      });
    }

    // Aplikuje centrum/zoom na Leaflet mapu
    function applyLeafletCenterFallback() {
      if (!window.L) return;          // Leaflet nenačítaný
      if (!window.mapboxMap) return;  // mapa ešte neexistuje
      var map = window.mapboxMap;

      var DEFAULT = [50.0755, 14.4378]; // Praha
      var savedAllowed = getCookie('geo_allowed') === '1';
      var glat = parseFloat(getCookie('geo_lat'));
      var glng = parseFloat(getCookie('geo_lng'));
      var hasMarkers = Array.isArray(window.solvedMarkers) && window.solvedMarkers.length > 0;

      if (hasMarkers) {
        // necháme pôvodný fitBounds a jemne pritlačíme zoom aspoň na 14
        setTimeout(function(){
          try {
            var z = map.getZoom();
            if (typeof z === 'number' && z < 14) map.setZoom(14);
          } catch(_){}
        }, 250);
        return;
      }

      // Bez markerov: geo cookie -> geo prompt -> Praha
      if (savedAllowed && isFinite(glat) && isFinite(glng)) {
        map.setView([glat, glng], 14);
        return;
      }

      if (getCookie('geo_prompted') !== '1' && 'geolocation' in navigator) {
        setCookie('geo_prompted','1',30);
        requestBrowserGeo().then(function(pos){
          setCookie('geo_allowed','1',365);
          setCookie('geo_lat', String(pos.lat), 365);
          setCookie('geo_lng', String(pos.lng), 365);
          map.setView([pos.lat, pos.lng], 14);
        }).catch(function(){
          setCookie('geo_allowed','0',365);
          map.setView(DEFAULT, 14);
        });
      } else {
        map.setView(DEFAULT, 14);
      }
    }

    // --- Leaflet layers toggle (Mapa / Letecká / Letecká + popisky)
    function tryAddLayersToggle() {
      if (!window.L || !window.mapboxMap) return;
      if (window.__leaflet_layer_toggle_added__) return; // pridaj len raz
      var map = window.mapboxMap;

      // nájdi existujúcu základnú dlaždicovú vrstvu (prvú TileLayer)
      var streetLayer = null;
      map.eachLayer(function(layer){
        if (!streetLayer && layer instanceof L.TileLayer) {
          streetLayer = layer;
        }
      });

      // ak žiadna dlaždicová vrstva nebeží (nemalo by sa stať), vytvor OSM a pridaj
      if (!streetLayer) {
        streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors',
          maxZoom: 19,
          subdomains: 'abc'
        }).addTo(map);
      }

      // satelitná + popisky
      var esriSat = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        { attribution: 'Imagery &copy; Esri, Maxar, Earthstar Geographics', maxZoom: 19 }
      );
      var labels = L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png',
        { attribution: '&copy; CARTO', maxZoom: 19, subdomains: 'abcd' }
      );

      var baseLayers = {
        'Mapa': streetLayer,
        'Letecká': esriSat,
        'Letecká + popisky': L.layerGroup([esriSat, labels])
      };

      L.control.layers(baseLayers, null, {
        position: 'topright',
        collapsed: false
      }).addTo(map);

      window.__leaflet_layer_toggle_added__ = true;
    }

    // Obalíme initOpenStreetMap, aby sa po ňom spustili fallback + toggle
    if (typeof window.initOpenStreetMap === 'function') {
      var __origInit = window.initOpenStreetMap;
      window.initOpenStreetMap = function(element){
        try { __origInit.apply(this, arguments); } finally {
          setTimeout(function(){
            applyLeafletCenterFallback();
            tryAddLayersToggle();
          }, 60);
        }
      };
    }

    // Istota: ak by sa init spustil inde, skús to ešte po window.load
    function lateInit() {
      setTimeout(function(){
        applyLeafletCenterFallback();
        tryAddLayersToggle();
      }, 220);
    }
    if (document.readyState === 'complete') {
      lateInit();
    } else {
      window.addEventListener('load', lateInit);
    }

    // Helpers pre rýchly test v konzole
    window.__map_cookies__ = { getCookie, setCookie };
    window.__map_apply_fallback__ = applyLeafletCenterFallback;
  })();
  (function () {
    // počkaj, kým existuje mapa aj zoom bar
    var poll = setInterval(function(){
      if (window.mapboxMap && window.L && document.querySelector('.leaflet-control-zoom')) {
        clearInterval(poll);
        addButtonIntoZoom(window.mapboxMap);
      }
    }, 200);

    function addButtonIntoZoom(map) {
      var zoomBar = document.querySelector('.leaflet-control-zoom.leaflet-bar');
      if (!zoomBar) return;

      // označ zoom bar, aby sa chytali naše CSS
      zoomBar.classList.add('map-has-toggle');

      // základná vrstva (prvý TileLayer na mape), fallback na OSM
      var streetLayer = null;
      map.eachLayer(function(layer){
        if (!streetLayer && layer instanceof L.TileLayer) streetLayer = layer;
      });
      if (!streetLayer) {
        streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors', maxZoom: 19, subdomains: 'abc'
        }).addTo(map);
      }

      // satelit + popisky
      var satLayer = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        { attribution: 'Imagery © Esri, Maxar, Earthstar Geographics', maxZoom: 19 }
      );
      var labelsLayer = L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png',
        { attribution: '&copy; CARTO', maxZoom: 19, subdomains: 'abcd' }
      );

      var isSatellite = false;

      // vytvor tretie tlačidlo rovnakej veľkosti, nalepené pod "−"
      var btn = document.createElement('a');
      btn.className = 'mapstyle-btn';
      btn.href = '#';
      btn.title = 'Prepínač mapy';
      btn.setAttribute('aria-label','Prepínač mapy');
      btn.innerHTML = '🛰️'; // môžeš nahradiť SVG
      zoomBar.appendChild(btn);

      L.DomEvent.disableClickPropagation(btn);
      L.DomEvent.on(btn, 'click', L.DomEvent.stop);

      btn.addEventListener('click', function () {
        if (!isSatellite) {
          if (streetLayer) map.removeLayer(streetLayer);
          satLayer.addTo(map);
          labelsLayer.addTo(map);
          btn.innerHTML = '🗺️';
          btn.title = 'Zobraziť základnú mapu';
          isSatellite = true;
        } else {
          map.removeLayer(satLayer);
          map.removeLayer(labelsLayer);git
          if (streetLayer) streetLayer.addTo(map);
          btn.innerHTML = '🛰️';
          btn.title = 'Zobraziť leteckú mapu';
          isSatellite = false;
        }
      });
    }
  })();
</script>
</script>

