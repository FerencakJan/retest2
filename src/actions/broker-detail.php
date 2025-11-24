<?php
@include "../src/helpers/pageHelpers.php";
$propertyService = new Property($portal);
$brokersService = new Brokers($portal);
$translator = $portal->getGlobalListResource();

$propertiesMaxOnPage = 8;
$page = $portal->getPageNumber();
$propertiesOffset = ($page * $propertiesMaxOnPage  - $propertiesMaxOnPage);

$broker = $brokersService->findByBrokerId($portal->getPageData()['id']);
$properties = $propertyService->byBrokerId($broker['broker_id'], $propertiesOffset, $propertiesMaxOnPage);
$numberOfProperties = $properties['totalSum'];

?>
<main class="main">
    <?php @include "../src/pageComponents/breadcrumbs.php" ?>

    <section class="sub-header__full">
        <article class="container container--content" role="article">
            <?php
            echo $portal->render("broker.php",  array(
                'member' => $broker,
                'numberOfProperties' => $numberOfProperties
            ));
            ?>
        </article>
    </section>

    <section class="directory-top">
        <article class="container container--text" role="article">
            <p class="directory-top__count">Aktuálně v nabídce: <strong><?php echo $numberOfProperties; ?> nemovitostí</strong></p>
        </article>
    </section>

    <?php if ($numberOfProperties > 0) { ?>
        <section class="next estate__next">
            <article class="container container--text" role="article">
                <div id="estate-wrapper" data-type="broker" data-id="<?php echo $broker['broker_id']; ?>" data-page="2" class="next__wrap">
                    <?php foreach ($properties['rows'] as $property) {
                        echo $portal->render('/estate-next.php', [
                            'similarProperty' => $property,
                            'globalListResource' => $translator,
                        ]);
                    } ?>
                </div>
            </article>
        </section>
    <?php } ?>

    <?php
    $maxPage = ceil($numberOfProperties / $propertiesMaxOnPage);

    if ($maxPage > 1) { ?>
        <div class="flex justify-center mt-lg">
            <a id="load-estates" href="#" class="btn load-broker-adverts">
                Zobrazit další nemovitosti
            </a>
        </div>
    <?php } ?>

    <?php if(count($properties['rows']) > 0) { ?>
        <script>
            <?php
            $geoJson = new GeoJson();
            foreach ($properties['rows'] as $property) {
                $geoJson->addPoint(
                    $property['locality_latitude'],
                    $property['locality_longitude'],
                    array(
                        'advert_id' => $property['advert_id'],
                        'show_marker' => !$property['do_not_show_marker'],
                        'image' => $property['photo'],
                        'link' => $property['alias_'],
                        'textTop' => htmlspecialchars(preg_replace('/\s+/', ' ', $property['title']), ENT_QUOTES, 'UTF-8'),
                        'textBottom' => $property['advert_price'] == 999999999 || $property['advert_price'] === 0 ?
                            'Info v RK' :
                            number_format($property['advert_price'], 0, ' ', ' ') . ' ' . $translator->getByNameId($property['advert_price_unit_eu'])
                    )
                );
            }
            ?>

            function getMarkers() {
                return JSON.parse(<?php echo $geoJson->getHtml(); ?>);
            }
        </script>
        <?php @include "../src/pageComponents/estate-agency-next.php"; ?>
    <?php } ?>

    <?php if ($broker['company_id']) {  ?>
        <a href="<?php echo "/detail-rk/{$broker['company_id']}/" ?>" class="back">
            <article class="container container--content" role="article">
                <div class="back__wrap">
                    <p>Na detail realitní kanceláře</p>
                    <div class="back__arrow">
                        <span class="icon icon--icon-forward">
                            <svg class="icon__svg">
                                <use xlink:href="#icon-forward"></use>
                            </svg>
                        </span>
                    </div>
                </div>
            </article>
        </a>
    <?php } ?>

</main>

<?php @include "../src/pageComponents/footer.php" ?>