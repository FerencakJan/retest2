<?php
@include "../src/helpers/pageHelpers.php";

$officeService = new Office($portal);
$propertyService = new Property($portal);
$office = $officeService->byId($portal->getPageData()["id"]);

$brokersService = new Brokers($portal);
$brokers = $brokersService->findByCompanyId($office["company_id"]);
$translator = $portal->getGlobalListResource();

$brokersMaxOnPage = 6;
$propertiesMaxOnPage = 8;

$brokers = $brokersService->findByCompanyIdPaginated($office["company_id"], 0, $brokersMaxOnPage);
$brokersCount = $brokersService->countBrokersCompanyId($office["company_id"]);

$data = $portal->getPageData()['getData'];
?>

<main class="main">

    <?php @include "../src/pageComponents/breadcrumbs.php" ?>

    <section class="sub-header__full">
        <article class="container container--content" role="article">
            <?php
            echo $portal->render("directory-item-detail.php",  array(
                'office' => $office
            ));
            ?>
        </article>
    </section>
    <section class="directory-top">

        <article class="container container--text" role="article">
            <p class="directory-top__count">
                Aktuálně v nabídce: <strong><?php echo $office['property_cnt']; ?> nemovitostí</strong>
            </p>

            <div class="directory-top__about">
                <h2 class="title title--compact">O nás</h2>
                <p><?php echo $office["description"] ?></p>
            </div>

          <div class="divider"></div>

          <div class="directory-top__about">
            <h2 class="title title--compact">Makléři</h2>
          </div>


          <div id="broker-wrapper" data-id="<?php echo $office["company_id"]; ?>" data-page="2" class="team">
                <?php foreach ($brokers as $member) {
                    echo $portal->render("team-member.php",  array(
                        'member' => $member
                    ));
                } ?>
            </div>


          <?php
            $maxPage = ceil($brokersCount / $brokersMaxOnPage);
            if ($maxPage > 1) { ?>
                <div class="flex justify-center mt-lg">
                    <a id="load-brokers" href="#" class="btn">Zobrazit další makléře</a>
                </div>
            <?php } ?>
        </article>
    </section>

  <div class="divider"></div>

  <div class="directory-top__about">
    <h2 class="title title--compact">Nemovitosti v nabídce</h2>
  </div>

  <?php
    $similarProperties = $propertyService->findCompanyNextItemsByCompanyId($office['company_id'], 0, $propertiesMaxOnPage + 1);
    if ($similarProperties['totalSum'] > 0) { ?>
        <section class="next estate__next">
            <article  class="container container--content" role="article">
                <div id="estate-wrapper" data-type="company" data-id="<?php echo $office["company_id"]; ?>" data-page="2" class="next__wrap">
                    <?php
                    foreach (array_slice($similarProperties['rows'], 0, $propertiesMaxOnPage) as $similarProperty) {
                        echo $portal->render('/estate-next.php', [
                            'similarProperty' => $similarProperty,
                            'globalListResource' => $translator,
                        ]);
                    }
                    ?>
                </div>
            </article>
        </section>

        <?php
        if (count($similarProperties['rows']) > $propertiesMaxOnPage) {
        ?>
            <div class="flex justify-center mt-lg">
                <a id="load-estates" href="#" class="btn load-adverts">
                    Zobrazit další nemovitosti
                </a>
            </div>
    <?php }

        @include "../src/pageComponents/estate-agency-all-estates.php";
    }
    ?>

</main>

<?php @include "../src/pageComponents/footer.php" ?>
