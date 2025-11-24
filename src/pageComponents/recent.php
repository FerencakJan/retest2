<?php
  $mostlySearched = (new MostlySearched($portal->getLanguage()))->getAll();
?>
<section class="recent">
  <article class="container" role="article">
    <h3 class="title title--md">Často hledané nemovitosti</h3>
    <div class="badge-wrap">
      <?php
      foreach ($mostlySearched as $item) { ?>
        <a href="<?php echo $item['url'] ?>" class="badge"><?php echo $item['nazev'] ?></a>
      <?php } ?>
    </div>
  </article>
</section>
