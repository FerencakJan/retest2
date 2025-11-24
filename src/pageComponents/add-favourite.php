<div class="add-favourite <?php echo $portal->isPropertyAdvertIdInFavorites($property['advert_id'])? 'is-active' : ''; ?>" onclick="toggleFavorite(<?php echo $property['advert_id']; ?>, this)">
  <div class="add-favourite__icon">
    <span class="icon icon--icon-favourite">
      <svg class="icon__svg"><use xlink:href="#icon-favourite"></use></svg>
    </span>
    <span class="icon icon--icon-favourite-fill">
      <svg class="icon__svg"><use xlink:href="#icon-favourite-fill"></use></svg>
    </span>
  </div>
  <p>Přidat k oblíbeným</p>
</div>
