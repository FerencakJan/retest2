<?php
$blogRepo = new Blog($portal);
$article = $blogRepo->findForDetail($portal->getPageData()['id']);
$data = $portal->getPageData()['getData'];
?>

<main class="main">
  <?php @include "../src/pageComponents/breadcrumbs.php" ?>

  <section class="blog-detail">

    <article class="container container--xlg">
      <div class="blog-detail__hero-image">
        <img src="/build/img/blog-hero-image.jpg" alt="">
      </div>
    </article>

    <article class="container container--text" role="article">
      <div class="blog-detail__content">
        <?php echo $article['text'] ?>
        <?php //@include "../src/pageComponents/gallery-simple.php" ?>
      </div>
    </article>
  </section>

  <?php @include "../src/pageComponents/diary.php" ?>

</main>

<?php @include "../src/pageComponents/footer.php" ?>
