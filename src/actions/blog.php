<main class="main">
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    $data = $portal->getPageData()['getData'];
    ?>
    <?php @include "../src/pageComponents/breadcrumbs.php" ?>

    <?php
    $blogRepo = new Blog($portal);

        $searchValue = isset($_POST['search']) ? filter_var($_POST['search'], FILTER_SANITIZE_STRING) : '';

        $order = $_POST['order'] !== null && $_POST['order'] !== '' ? str_replace('_', ' ', $_POST['order']) : null;
//        $filter = $_POST['filter'] !== null && $_POST['filter'] !== '' ? $_POST['filter'] : null;

        if ($searchValue == '') {
          $main_diary_articles = $blogRepo->findForHomepage();
        }
        $pageData = $portal->getPageData();
        $articles = $blogRepo->findForListing($portal->getPageNumber(), $searchValue, $order);
        $articleGroups = $blogRepo->orgByMonths($articles['articles'], $order);
        $mode = 'blog';
        //$main_items = $blogRepo->findForHomepage();

    ?>
    <section class="blog">
        <article class="container container--text" role="article">
            <h1 class="title title--lg">Deník makléře</h1>
        </article>
        <article class="container container--content">
            <?php
              echo $portal->render("search-filter-blog.php", array(
                      'search' => $searchValue
              ));

            ?>
            <div class="diary__grid diary__grid--four">
                <?php foreach ($articleGroups as $group) { ?>
                <?php
                        foreach ($group["articles"] as $article) {
                            echo $portal->render("diary-item.php",  array(
                                'article' => $article
                            ));
                        }
                        ?>

                <?php } ?>
            </div>
            <?php
                if ($mode === 'blog') {
                    $articlesCount = $articles['count'];
                    $maxPage = ceil(($articlesCount - 1) / 16);
                    if ($maxPage != 1) {
                        //include_once('mlift/library/helpers/pageHelpers.php');
                        @include "../src/helpers/pageHelpers.php" ;
                        //$listerUrl = $data['urlset']['lister_url'];
                        $pager = new Pager('/blog/', '/blog', $maxPage, $portal->getPageNumber(), array(), true);
                        echo $pager->render(true);
                    }
                }
            ?>
        </article>
    </section>
</main>

<?php @include "../src/pageComponents/footer.php" ?>