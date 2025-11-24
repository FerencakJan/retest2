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
         <div class="estate__next__gallery">
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
      ?>
         </p>
     </div>
     <div class="estate__action estate__action--center">
         <a href="#modal-question" class="js-modal-open btn btn--gray btn--forward"
             data-advert-id="<?php echo $advert_id; ?>">
             <!-- <span class="icon icon--icon-envelope">
                     <svg class="icon__svg">
                         <use xlink:href="#icon-envelope"></use>
                     </svg>
                 </span> -->
             Napsat dotaz
         </a>

         <a href="<?php echo LeadingSlash::checkLink($alias_); ?>" class="btn btn--forward">
             <!-- <span class="icon icon--icon-next">
                     <svg class="icon__svg">
                         <use xlink:href="#icon-next"></use>
                     </svg>
                 </span> -->
             Detail nemovitosti
         </a>
     </div>
 </div>