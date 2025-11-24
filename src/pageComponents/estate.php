 <?php
  if (isset($similarProperty)){
    extract($similarProperty);
  }

  if (isset($favoriteProperty)){
    extract($favoriteProperty);
  }

  $photosUns = unserialize($photos);
  ?>
<div class="estate">
  <div class="estate__image">
    <div class="estate__gallery">
      <?php foreach ($photosUns as $photo) { ?>
        <?php $src = str_replace('_b/', '_s/', $photo); ?>
        <img loading="lazy" src="<?php echo $src ?>" alt="<?php echo $title ?>">
      <?php } ?>
    </div>
  </div>
  <div class="estate__content">
    <h3 class="title title--sm estate__title"><?php echo $title; ?></h3>
    <p class="estate__location"><?php echo $advert_locality; ?></p>
    <p class="title title--next estate__price"><?php
      if($advert_price == 999999999 || $advert_price == 0)
      {
        echo 'Info v RK';
      }
      else
      {
        echo number_format($advert_price,0, ' ', ' ');
        echo $globalListResource->getByNameId($advert_price_unit_eu);
      }
//      ?><!-- <small>150 000 Kč / m<sup>2</sup></small>-->
    </p>
    <div class="badge-wrap badge-wrap--compact">
      <?php
      $advertDescription = unserialize($advert_description);
      foreach($advertDescription as $key=>$value) { ?>
        <a href="#" class="badge badge--estate <?php if ($key == 0) { ?>is-active<?php } ?>"><?php echo $value; ?></a>
      <?php } ?>
    </div>
    <div class="estate__action">
      <a href="#modal-question" class="js-modal-open btn btn--gray btn--forward" data-advert-id="<?php echo $advert_id; ?>">
        <span class="icon icon--icon-envelope">
          <svg class="icon__svg"><use xlink:href="#icon-envelope"></use></svg>
        </span>
        Napsat dotaz
      </a>

      <a href="<?php echo LeadingSlash::checkLink($alias_); ?>" class="btn btn--forward">
        <span class="icon icon--icon-next">
          <svg class="icon__svg"><use xlink:href="#icon-next"></use></svg>
        </span>
        Zobrazit detail
      </a>
    </div>
<!--    <div class="estate__icon">
      <span class="icon icon--icon-video">
        <svg class="icon__svg"><use xlink:href="#icon-video"></use></svg>
      </span>
    </div>-->
    <div class="estate__favourite <?php echo $portal->isPropertyAdvertIdInFavorites($advert_id)? 'is-active' : ''; ?>" onclick="toggleFavorite(<?php echo $advert_id; ?>, this)">
      <wow-tooltip class="wowtooltip">
        <div class="wowtooltip__label" data-tooltip-placeholder>
          <span class="icon icon--icon-favourite">
            <svg class="icon__svg"><use xlink:href="#icon-favourite"></use></svg>
          </span>
          <span class="icon icon--icon-favourite-fill">
            <svg class="icon__svg"><use xlink:href="#icon-favourite-fill"></use></svg>
          </span>
        </div>
        <div class="wowtooltip-dropdown" data-tooltip-dropdown>
          <div role="tooltip" class="wowtooltip-dropdown__content" onclick="toggleFavorite(<?php echo $advert_id; ?>, this)">Přidat&nbsp;do&nbsp;oblíbených</div>
        </div>
      </wow-tooltip>
    </div>
  </div>
</div>
