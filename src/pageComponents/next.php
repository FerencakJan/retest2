<section class="next estate__next">
    <article class="container container--content" role="article">
        <!-- <h2 class="title title--content next__title">Mohlo by Vás zajímat&hellip;</h2> -->
        <div class="next__wrap">
            <?php
      $data['sql_alt']['company_country_id'] = $portal->getPortalCountryId();
      $data['sql_alt']['advert_status_eu'] = array(51, 52);
      $data['sql_alt']['not_id'] = array($property['advert_id']);

//
      if ($propertyService !== null){
        $similarProperties = $propertyService->search($data['sql_alt'], 0, 2 , false, true, true);
      }
      foreach($similarProperties['rows'] as $similarProperty){
        echo $portal->render('/estate-next.php', [
          'similarProperty' => $similarProperty,
          'globalListResource' => $globalListResource,
        ]);
      }
      ?>
        </div>
    </article>
</section>