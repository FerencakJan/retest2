<div class="diary-item">
    <div class="estate__action">
        <div class="btn btn--forward">
            <span class="icon icon--icon-next">
                <svg class="icon__svg">
                    <use xlink:href="#icon-next"></use>
                </svg>
            </span>
            Číst dále
        </div>
    </div>

    <div class="diary-item__image">
        <a href="<?php echo '/blog/' . generateSlug($article['perex_title']) . "/${article['id']}/"; ?>">
            <img src="<?php echo $article['image_url']; ?>" alt="<?php echo $article['perex_title']; ?>">
        </a>
    </div>
    <a href="<?php echo '/blog/' . generateSlug($article['perex_title']) . "/${article['id']}/"; ?>"">
      <h3 class=" title title--sm title--600"><?php echo $article['perex_title']; ?></h3>
    </a>
    <p class="diary-item__date"><?php echo (new \DateTime($article['date']))->format('d.m.Y'); ?></p>
    <p class="diary-item__perex"><?php echo $article['perex']; ?></p>
    <a href="<?php echo '/blog/' . generateSlug($article['perex_title']) . "/${article['id']}/"; ?>"
        class="diary-item__link">Číst dále</a>
</div>