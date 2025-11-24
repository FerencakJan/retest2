<a href="<?php echo isset($data['urlset']['close_url'])? $data['urlset']['close_url'] : $portal->getUrl(); ?>" class="back">
  <article class="container container--content" role="article">
    <div class="back__wrap">
      <p>Vrátit se na přehled nemovitostí</p>
      <div class="back__arrow">
        <span class="icon icon--icon-forward">
          <svg class="icon__svg"><use xlink:href="#icon-forward"></use></svg>
        </span>
      </div>
    </div>
  </article>
</a>
