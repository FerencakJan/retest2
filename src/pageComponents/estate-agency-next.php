<section class="next estate__next">
  <article class="container container--content" role="article">

    <section class="around">
      <article class="container container--content" role="article">
        <h2 class="title title--content next__title">Naše nabídka nemovitostí</h2>
        <div class="around__map">
          <div class="map-wrap">
            <div id="js-map"></div>
          </div>
        </div>
      </article>
    </section>

    <div class="next__wrap">
      <?php

      foreach ($similarProperties['rows'] as $similarProperty) {
        echo $portal->render('/estate-next.php', [
          'similarProperty' => $similarProperty,
          'globalListResource' => $translator,
        ]);
      }
      ?>
    </div>

  </article>
</section>