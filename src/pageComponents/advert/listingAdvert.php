<?php

function renderListingAdvert(Portal $portal, array $advert): string
{
  $translator = $portal->getGlobalListResource();
  $unserializedPhotos = unserialize($advert['photos']);

  $video = $advert['videos'] ?? null;

  if (count($unserializedPhotos)) {
    $photos = implode('', array_map(static function ($photo) {
      return "<img loading=\"lazy\" src=\"{$photo}\">";
    }, $unserializedPhotos));
  } else {
    $photos = '<img src="/mlift/images/missing.png">';
  }

  $advertPrice = (int)$advert['advert_price'];

  $advertPricePrepared = 'Info v RK';
  if ($advertPrice !== 999999999 && $advertPrice !== 0) {
    $advertPricePrepared = number_format($advertPrice,0, ' ', ' ');
    $advertPricePrepared .= ' ' . $translator->getByNameId($advert['advert_price_unit_eu']);
  }

  return (
    '<div class="estate">
        <div class="estate__image">
          <div class="estate__gallery">
            ' . $photos . '
          </div>
        </div>
        <div class="estate__content">
          <h3 class="title title--sm estate__title">' . $advert['title'] . '</h3>
          <p class="estate__location">' . $advert['advert_locality'] . '</p>
          <p class="title title--next estate__price">'.$advertPricePrepared.'</p>
          <div class="badge-wrap badge-wrap--compact">
            <a href="#" class="badge badge--estate is-active">'.ucfirst($translator->getByNameId($advert['advert_function_eu'])).'</a>
            <a href="#" class="badge badge--estate">'.$translator->getByNameId($advert['advert_type_eu']).'</a>
            <a href="#" class="badge badge--estate">'.$translator->getByNameId($advert['advert_subtype_eu']).'</a>
          </div>
          <div class="estate__action">
            <a href="#modal-question" class="js-modal-open btn btn--gray btn--forward">
              <span class="icon icon--icon-envelope">
                <svg class="icon__svg"><use xlink:href="#icon-envelope"></use></svg>
              </span>
              Napsat dotaz
            </a>

            <a class="btn btn--forward detail-link" data-link-id="' . $advert['advert_id'] .'">
              <span class="icon icon--icon-next">
                <svg class="icon__svg"><use xlink:href="#icon-next"></use></svg>
              </span>
              Zobrazit detail
            </a>
          </div>
          ' . ($video ? '<div class="estate__icon">
            <span class="icon icon--icon-video">
              <svg class="icon__svg"><use xlink:href="#icon-video"></use></svg>
            </span>
          </div>' : '') . '
          <div class="estate__favourite '. ($portal->isPropertyAdvertIdInFavorites($advert['advert_id'])? "is-active" : "") . '" onclick="toggleFavorite('. $advert['advert_id'] . ', this)">
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
                <div role="tooltip" class="wowtooltip-dropdown__content">Přidat&nbsp;do&nbsp;oblíbených</div>
              </div>
            </wow-tooltip>
          </div>
        </div>
      </div>'
  );
}
