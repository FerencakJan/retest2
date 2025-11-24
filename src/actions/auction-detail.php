<?php

@include "../src/helpers/pageHelpers.php";
include_once(__DIR__."/../database/Auctions.php");

$auctionsService = new Auctions($portal);
$propertyService = new Property($portal);

$auction = $auctionsService->findAuctionDetail($_GET['id']);
$property = $propertyService->findById($auction['idz']);

$photos = unserialize($property['photos']);
$videosUns = unserialize($property['videos']);

$videos = [];
$youtubeVideos = [];

foreach ($videosUns as $item) {
  if (strpos($item['url'], 'youtube')) {
    parse_str( parse_url( $item['url'], PHP_URL_QUERY ), $youtubeId );
    $youtubeVideos[] = $youtubeId['v'];
  } elseif (strpos($item['url'], 'youtu.be')) {
    $youtubeId = ltrim(parse_url( $item['url'], PHP_URL_PATH ),'/');
    $youtubeVideos[] = $youtubeId;
  } else {
    $videos[] = $item['url'];
  }
}

$propertyProperties = $propertyService->findPropertiesForProperty($property['advert_id']);

$brokersService = new Brokers($portal);
$companyService = new Office($portal);

$broker = $brokersService->findByBrokerId($property['broker_id']);
$company = $companyService->byId($property['company_id']);
$data = $portal->getPageData()['getData'];

$scoreEnable = true;
if(isset($property['score']['disable']) && ($property['score']['disable']+0) === 1){
  $scoreEnable = false;
}
$globalListResource = new \Portal\Helpers\Resources\GlobalList('cz');
$translator = $portal->getGlobalListResource();

$start = new DateTime($auction['datum_aukce_start']);
$end = new DateTime($auction['datum_aukce_end']);

?>

<main class="main">

    <?php @include "../src/pageComponents/breadcrumbs-gray.php" ?>

    <section class="detail-top">
        <article class="container container--content" role="article">
            <div class="detail-top__overview">
                <?php if($scoreEnable){ ?>
                <?php $starValue = ScoreFormatter::formate($property['score_avg']); ?>
                <?php @include "../src/pageComponents/rating.php" ?>
                <?php } ?>

                <?php @include "../src/pageComponents/add-favourite.php" ?>
            </div>
            <h1 class="title title--lg detail-top__title">
                <!-- <a href="<?php if(isSet($ajax)) { echo '#'; } else { echo '/'; } ?>" class="title__back"
                    id="breadcrumbsBack">Zpět</a> -->
                <?php echo $auction['nazev']; ?>
            </h1>
            <div class="detail-top__info">
                <h2 class="title title--top detail-top__subtitle">
                    <?php echo number_format($auction['aktualni_cena'],0, ' ', ' '); ?> Kč</h2>
                <a href="#modal" class="info js-modal-open">
                    <span class="icon icon--icon-info">
                        <svg class="icon__svg">
                            <use xlink:href="#icon-info"></use>
                        </svg>
                    </span>
                    Je možná lepší cena
                </a>
            </div>
            <div class="estate__data">
                <dl>
                    <div class="estate__data-item">
                        <dt>Aukce otevřena:</dt>
                        <dd><strong><?php echo $start->format('d.m.Y H:i') . ' - ' . $end->format('d.m.Y H:i'); ?></strong>
                        </dd>
                    </div>

                    <?php if($auction['vyvolavaci_cena'] > 0) { ?>
                    <div class="estate__data-item">
                        <dt>Vyvolávací cena</dt>
                        <dd><strong><?php echo number_format($auction['vyvolavaci_cena'],0, ' ', ' '); ?> Kč</strong>
                        </dd>
                    </div>
                    <?php } ?>

                    <div class="estate__data-item">
                        <dt>Aktuální cena</dt>
                        <dd><strong><?php echo number_format($auction['aktualni_cena'],0, ' ', ' '); ?> Kč</strong></dd>
                    </div>
                    <div class="estate__data-item">
                        <dt>Min. příhoz</dt>
                        <dd><strong><?php echo number_format($auction['minimalni_prihoz'],0, ' ', ' '); ?> Kč</strong>
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="detail-top__action-menu">
                <div class="action-menu">
                    <div class="action-menu__wrap">
                        <a href="#agent" class="js-modal-open">
                            <span class="icon icon--icon-phone">
                                <svg class="icon__svg">
                                    <use xlink:href="#icon-phone"></use>
                                </svg>
                            </span>
                            Volat makléři
                        </a>
                        <a href="#inquiry">
                            <span class="icon icon--icon-message">
                                <svg class="icon__svg">
                                    <use xlink:href="#icon-message"></use>
                                </svg>
                            </span>
                            Napsat makléři
                        </a>
                        <a href="#" onclick="window.print();">
                            <span class="icon icon--icon-print">
                                <svg class="icon__svg">
                                    <use xlink:href="#icon-print"></use>
                                </svg>
                            </span>
                            Vytisknout inzerát
                        </a>
                    </div>
                </div>
                <?php if (isset($data['urlset']['fcb_group']) && strpos($data['urlset']['fcb_group'], 'https://') !== false) { ?>
                <?php @include "../src/pageComponents/join.php" ?>
                <?php } ?>
            </div>
            <form class="auction__form auction__form--sign-up">
                <input type="username" name="text" placeholder="Vaše přihlašovací jméno" required>
                <div class="auction__sign-up__divider"></div>
                <input type="email" name="password" placeholder="Vaše heslo" required>
                <button class="btn btn--md">Vstoupit do aukce</button>
            </form>
            <div class="auction__grid">
                <div class="auction__wrapper">
                    <div class="auction__time">
                        <div class="auction__time__label">Zbývající čas:</div>
                        <div class="auction__time__value">03:02:31</div>
                    </div>
                    <form class="auction__form auction__form--bid">
                        <label for="bidValue">Váš příhoz:</label>
                        <div class="number-input">
                            <button type="button" id="minusBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>

                            <div class="number-input__input">
                                <input type="text" id="bidValue" name="bidValue" required />
                            </div>

                            <button type="button" id="plusBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                        <button class="btn btn--md">Přihodit</button>
                    </form>
                </div>
            </div>
        </article>
    </section>

    <section class="description">

        <?php
        $bids = $auctionsService->findAuctionBids($_GET['id']);
        if (!empty($bids)) { ?>
        <article class="auction__table">
            <div class="container">
                <div class="table__wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Dražitel</th>
                                <th>Datum</th>
                                <th class="right">Výše příhozu</th>
                                <th class="right">Původní cena</th>
                                <th class="right">Nová cena</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bids as $bid) {
                        // $auctioneer = $auctionsService->findAuctioneerName($bid['id_drazitele']);
                        $bidderName = $auctioneer['jmeno'] . ' ' . $auctioneer['prijmeni'];
                    ?>
                            <tr>
                                <td><?php echo $bidderName; ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($bid['datum'])); ?></td>
                                <td class="right"><?php echo number_format($bid['bid'], 0, ' ', ' '); ?> Kč</td>
                                <td class="right"><b><?php echo number_format($bid['cena_puvodni'], 0, ' ', ' '); ?>
                                        Kč</b></td>
                                <td class="right"><b><?php echo number_format($bid['cena_nova'], 0, ' ', ' '); ?> Kč</b>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
        <?php } ?>

        <article class=" container container--text" role="article">
            <?php @include "../src/pageComponents/interested.php" ?>

            <div class="description__form" id="inquiry">
                <h3 class="title title--compact">Mám dotaz k nemovitosti</h3>
                <form action="<?php echo $portal->formsTargetUrl(); ?>/mlift/services/save_form.php" method="post">
                    <div class="row row--form">
                        <div class="col col--2">
                            <div class="form-item">
                                <label for="name">Jméno</label>
                                <input name="form[jmeno]" type="text" id="name"
                                    value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_FIRST_NAME); ?>">
                            </div>
                        </div>
                        <div class="col col--2">
                            <div class="form-item">
                                <label for="lastname">Příjmení</label>
                                <input name="form[prijmeni]" type="text" id="lastname"
                                    value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_LAST_NAME); ?>">
                            </div>
                        </div>
                        <div class="col col--2">
                            <div class="form-item">
                                <label for="email">Email</label>
                                <input name="form[email]" type="email" id="email"
                                    value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_EMAIL); ?>">
                            </div>
                        </div>
                        <div class="col col--2">
                            <div class="form-item">
                                <label for="phone">Telefon</label>
                                <input name="form[telefon]" type="tel" id="phone"
                                    value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_PHONE); ?>">
                            </div>
                        </div>
                        <input type="hidden" class="input" name="form[advert_id][]"
                            value="[<?php echo $property['advert_id']; ?>]">
                        <div class="col">
                            <div class="form-item">
                                <label for="message">Zpráva</label>
                                <textarea name="form[dotaz]" id="message" rows="6"
                                    placeholder="<?php echo Portal::DEFAULT_QUESTION ?>"><?php $lastQuestion = $portal->getCustomerInfo(Portal::CUSTOMER_INFO_LAST_ANSWER); if($lastQuestion != ''){echo $lastQuestion;} ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row row--form-bottom">
                        <div class="col col--auto">
                            <div class="form-checkbox form-checkbox--inside">
                                <input type="checkbox" name="checkbox" id="checkbox-gdpr">
                                <span class="form-checkbox__box"></span>
                                <label class="form-label" for="checkbox-gdpr">Souhlasím s ochranou <a
                                        href="https://www.eurobydleni.cz/souhlas-se-zpracovanim-osobnich-udaju-gdpr/text/12780"
                                        target="_blank">osobních údajů</a></label>
                            </div>
                        </div>
                        <div class="col col--auto">
                            <div class="form-checkbox form-checkbox--inside">
                                <input type="checkbox" name="checkbox" id="checkbox-newsletter">
                                <span class="form-checkbox__box"></span>
                                <label class="form-label" for="checkbox-newsletter">Souhlas se zasíláním <a
                                        href="https://www.eurobydleni.cz/souhlas-se-zasilanim-obchodnich-sdeleni/text/12795"
                                        target="_blank">obchodních sdělení</a></label>
                            </div>
                        </div>
                        <div class="col col--auto col--submit">
                            <button class="btn btn--md" type="submit">Odeslat dotaz</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="description__agent" id="agent">
                <?php @include "../src/pageComponents/agent.php" ?>
            </div>
        </article>
    </section>

    <?php @include "../src/pageComponents/back.php" ?>

</main>

<?php @include "../src/pageComponents/footer.php" ?>