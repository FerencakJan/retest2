<div class="modal" id="modal" role="dialog" aria-labelledby="dialog-title-1">

    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-1">Modal headline</h2>

            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam ligula pede, sagittis quis, interdum
                ultricies, scelerisque eu. Nulla quis diam. Pellentesque arcu. Proin mattis lacinia justo. Mauris dictum
                facilisis augue. Maecenas lorem. Pellentesque ipsum</p>

            <p><button class="btn btn--md js-modal-close">Close</button></p>

        </div>

    </div>

</div>

<div class="modal modal--search" id="search-advanced" role="dialog" aria-labelledby="dialog-title-2">

    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-2">Rozšíření hledání</h2>

            <form id="full-search-form" method="POST" action="/search-form/">
                <!-- <div class="form__row">
          <div class="form__item form__item--grow search__location">
            <label class="search__label" for="location">Lokalita</label>
            <input class="search__input" type="text" id="location" placeholder="Např.: Mladá Boleslav">
          </div>
        </div> -->
                <div class="form__row">
                    <div class="form__item form__item--full">
                        <div class="filter filter--location">
                            <?php include "filter-dropdown-city-full.php" ?>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Typ inzerátu</label></div>
                    <div class="form__item">
                        <div class="form-radio__wrap">
                            <?php
              foreach ($portal->getCompanyTranslator()->getProperty(1) as $functionId => $functionValue) { ?>
                            <div class="form-radio form-radio--tag">
                                <input type="radio" name="sql[advert_function_eu][]" value="<?php echo $functionId; ?>"
                                    id="f-checkbox-advert-function-eu-<?php echo $functionId; ?>" <?php if ((isset($formData["advert_function_eu"]) && in_array($functionId, $formData["advert_function_eu"]))) {
                    echo " checked";
                  } ?> data-name="<?php echo ucfirst($functionValue["name"]); ?>">
                                <label class="form-label"
                                    for="f-checkbox-advert-function-eu-<?php echo $functionId; ?>"><?php echo ucfirst($functionValue["name"]); ?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Typ nemovitosti</label></div>
                    <div class="form__item form__item--limit">

                        <div class="form-radio__wrap form-radio__wrap--2">
                            <?php
                foreach ($portal->getCompanyTranslator()->getProperty(6) as $propertyId => $property) { ?>
                            <div class="form-radio form-radio--tag">
                                <input type="radio" name="sql[advert_type_eu][]" value="<?php echo $propertyId ?>"
                                    id="f-checkbox-advert-type-eu-<?php echo $propertyId ?>" <?php if ((isset($formData["advert_type_eu"]) && in_array($propertyId, $formData["advert_type_eu"]))) {
                    echo " checked";
                  } ?> data-name="<?php echo $property['name'] ?>">
                                <label class="form-label" for="f-checkbox-advert-type-eu-<?php echo $propertyId ?>">
                                    <span
                                        class="icon icon--<?php echo \Portal\Helpers\CssClassHelper\CssClassHelper::cssIcon($propertyId); ?>">
                                        <svg class="icon__svg">
                                            <use
                                                xlink:href="#<?php echo \Portal\Helpers\CssClassHelper\CssClassHelper::cssIcon($propertyId); ?>">
                                            </use>
                                        </svg>
                                    </span>
                                    <span><?php echo $property['name'] ?></span>
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!--        <div class="form__row form__row--simple">-->
                <!--          <div class="form__item form__item--full">-->
                <!--            <div class="filter filter--simple">-->
                <!--              --><?php //include "filter-dropdown.php" ?>
                <!--            </div>-->
                <!--          </div>-->
                <!--        </div>-->
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Dispozice</label></div>
                    <div class="form__item form__item--limit">
                        <?php foreach ($portal->getCompanyTranslator()->getProperty(6) as $propertyId => $property) { ?>
                        <?php
              $array = $portal->getCompanyTranslator()->getProperty(6);
              $firstKey = key($array); ?>
                        <div
                            class="form-checkbox__wrap form-checkbox__limit f-checkbox-wrap-<?php echo $propertyId ?><?php if ($propertyId !== $firstKey){ echo ' is-hidden'; } ?>">

                            <div class="form-checkbox">
                                <input type="checkbox" id="f-inside-wrap-checkbox-<?php echo $propertyId ?>"
                                    data-master-f-id-value="<?php echo $propertyId ?>">
                                <span class="form-checkbox__box"></span>
                                <label class="form-label"
                                    for="f-inside-wrap-checkbox-<?php echo $propertyId ?>">Vše</label>
                            </div>

                            <?php

              echo $portal->render(
                "filter-property-sale-type-full.php",
                array(
                  "propertySubTypes" => $portal->getCompanyTranslator()->getProperty($propertyId),
                  "masterId" => $propertyId,
                )
              ); ?>

                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Cena</label></div>
                    <div class="form__item form__item--limit">
                        <div class="price-inputs">
                            <div class="price-inputs__item">
                                <label for="price-from">Od</label>
                                <input name="sql[advert_price_min]" type="number" id="f-rangeslider-from"
                                    <?php if(isset($formData["advert_price_min"])) { echo " value=\"{$formData["advert_price_min"]}\" "; } else { echo " value=\"0\" ";} ?>>
                            </div>
                            <div class="price-inputs__item">
                                <label for="price-to">Do</label>
                                <input name="sql[advert_price_max]" type="number" id="f-rangeslider-to"
                                    <?php if(isset($formData["advert_price_max"])) { echo " value=\"{$formData["advert_price_max"]}\" "; } else { echo " value=\"30000000\" ";}?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Plocha</label></div>
                    <div class="form__item form__item--limit">
                        <div class="price-inputs">
                            <div class="price-inputs__item">
                                <label for="usable-area-min">Od(m2)</label>
                                <input name="sql[usable_area_min]" type="text" id="usable-area-min" value="80">
                            </div>
                            <div class="price-inputs__item">
                                <label for="usable-area-max">Do(m2)</label>
                                <input name="sql[usable_area_max]" type="text" id="usable-area-max" value="200">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Vlastnictví</label></div>
                    <div class="form__item form__item--limit">
                        <div class="form-checkbox__wrap form-checkbox__limit-2">
                            <?php $propertys = $portal->getGlobalListResource()->getByIdn(120);
              foreach ($propertys as $propertyKey => $property)
              {
                ?>
                            <div class="form-checkbox">
                                <input type="checkbox" name="sql[ownership_eu][]"
                                    id="checkbox-uwnership-eu-<?php echo $propertyKey; ?>"
                                    value="<?php echo $propertyKey; ?>"
                                    <?php if(isset($formData["ownership_eu"]) && in_array($propertyKey,$formData["ownership_eu"])){ echo 'checked'; } ?>>
                                <span class="form-checkbox__box"></span>
                                <label class="form-label"
                                    for="checkbox-uwnership-eu-<?php echo $propertyKey; ?>"><?php echo $property['nazev']; ?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Konstrukce</label></div>
                    <div class="form__item form__item--limit">
                        <div class="form-checkbox__wrap form-checkbox__limit-2">
                            <?php $propertys = $portal->getGlobalListResource()->getByIdn(113);
              foreach ($propertys as $propertyKey => $property)
              {
              ?>
                            <div class="form-checkbox">
                                <input type="checkbox" name="sql[building_type_eu][]"
                                    id="checkbox-building-type-<?php echo $propertyKey; ?>"
                                    value="<?php echo $propertyKey; ?>"
                                    <?php if(isset($formData["building_type_eu"]) && in_array($propertyKey,$formData["building_type_eu"])){ echo 'checked'; } ?>>
                                <span class="form-checkbox__box"></span>
                                <label class="form-label"
                                    for="checkbox-building-type-<?php echo $propertyKey; ?>"><?php echo $property['nazev']; ?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Typ budovy</label></div>
                    <div class="form__item form__item--limit">
                        <div class="form-checkbox__wrap form-checkbox__limit-2">
                            <?php $propertys = $portal->getGlobalListResource()->getByIdn(145);
              foreach ($propertys as $propertyKey => $property)
              {
                ?>
                            <div class="form-checkbox">
                                <input type="checkbox" name="sql[object_kind][]"
                                    id="checkbox-object-kind-<?php echo $propertyKey; ?>"
                                    value="<?php echo $propertyKey; ?>"
                                    <?php if(isset($formData["object_kind"]) && in_array($propertyKey,$formData["object_kind"])){ echo 'checked'; } ?>>
                                <span class="form-checkbox__box"></span>
                                <label class="form-label"
                                    for="checkbox-object-kind-<?php echo $propertyKey; ?>"><?php echo $property['nazev']; ?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Vybavení</label></div>
                    <div class="form__item form__item--limit">
                        <div class="form-checkbox__wrap form-checkbox__limit-2">
                            <?php $propertys = $portal->getGlobalListResource()->getByIdn(98);
              foreach ($propertys as $propertyKey => $property)
              {
              ?>
                            <div class="form-checkbox">
                                <input type="checkbox" name="sql[advert_equipment][]"
                                    id="checkbox-advert-equipment-<?php echo $propertyKey; ?>"
                                    value="<?php echo $propertyKey; ?>"
                                    <?php if(isset($formData["advert_equipment"]) && in_array($propertyKey,$formData["advert_equipment"])){ echo 'checked'; } ?>>
                                <span class="form-checkbox__box"></span>
                                <label class="form-label"
                                    for="checkbox-advert-equipment-<?php echo $propertyKey; ?>"><?php echo $property['nazev']; ?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Charakteristika</label></div>
                    <div class="form__item form__item--limit">
                        <div class="form-checkbox__wrap form-checkbox__limit-2">
                            <?php $propertys = $portal->getGlobalListResource()->getByIdn(73);
              foreach ($propertys as $propertyKey => $property)
              {
                ?>
                            <div class="form-checkbox">
                                <input type="checkbox" name="sql[advert_character][]"
                                    id="checkbox-advert-character-<?php echo $propertyKey; ?>"
                                    value="<?php echo $propertyKey; ?>"
                                    <?php if(isset($formData["advert_character"]) && in_array($propertyKey,$formData["advert_character"])){ echo 'checked'; } ?>>
                                <span class="form-checkbox__box"></span>
                                <label class="form-label"
                                    for="checkbox-advert-character-<?php echo $propertyKey; ?>"><?php echo $property['nazev']; ?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form__row">
                    <div class="form__item form__item--label"><label for="">Umístění v obci</label></div>
                    <div class="form__item form__item--limit">
                        <div class="form-checkbox__wrap form-checkbox__limit-2">
                            <?php $propertys = $portal->getGlobalListResource()->getByIdn(137);
              foreach ($propertys as $propertyKey => $property)
              {
                ?>
                            <div class="form-checkbox">
                                <input type="checkbox" name="sql[object_location][]"
                                    id="checkbox-object-location-<?php echo $propertyKey; ?>"
                                    value="<?php echo $propertyKey; ?>"
                                    <?php if(isset($formData["object_location"]) && in_array($propertyKey,$formData["object_location"])){ echo 'checked'; } ?>>
                                <span class="form-checkbox__box"></span>
                                <label class="form-label"
                                    for="checkbox-object-location-<?php echo $propertyKey; ?>"><?php echo $property['nazev']; ?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="js-save-request is-hidden">
                    <div class="form__row form__row--credentials">
                        <div class="form__item form__item--classic">
                            <div class="form__input">
                                <label for="name">Jméno</label>
                                <input type="text" id="name" value="Ondřej">
                            </div>
                        </div>
                        <div class="form__item form__item--classic">
                            <div class="form__input">
                                <label for="surname">Příjmení</label>
                                <input type="text" id="surname" value="Žáček">
                            </div>
                        </div>
                        <div class="form__item form__item--classic">
                            <div class="form__input">
                                <label for="email">Email</label>
                                <input type="email" id="email" value="me@ondrejzacek.cz">
                            </div>
                        </div>
                        <div class="form__item form__item--classic">
                            <div class="form__input">
                                <label for="tel">Telefon</label>
                                <input type="tel" id="tel" value="720 492 873">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form__row form__row--bottom">
                    <div class="form__item">
                        <button class="btn btn--text-icon js-modal-close"><span class="icon icon--icon-settings">
                                <svg class="icon__svg">
                                    <use xlink:href="#icon-settings"></use>
                                </svg>
                            </span>Schovat rozšířené možnosti</button>
                    </div>
                    <div class="form__item">
                        <div class="btn-wrap">
                            <div class="form-checkbox form-checkbox--light">
                                <input type="checkbox" name="checkbox-save-request" id="checkbox-save-request">
                                <span class="form-checkbox__box"></span>
                                <label class="form-label" for="checkbox-save-request">Uložit poptávku</label>
                            </div>
                            <button class="btn">Zobrazit výsledky</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>

    </div>

</div>

<!-- vytvořit poptávku -->
<div class="modal" id="modal-demand" role="dialog" aria-labelledby="dialog-title-3">

    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-3">Vytvořit poptávku</h2>

            <form action="<?php echo $portal->formsTargetUrl(); ?>/mlift/services/save_form.php" method="post"
                class="async-form">
                <input name="form[form_type]" type="hidden" value="5">
                <?php //echo $portal->render("forms/fullSearchForm.php",array('portal' =>$portal, 'context' => Portal::REQUEST_FORM, 'noGoogleSearch' => true, 'displayFullProperties' => true)); ?>
            </form>

        </div>

    </div>

</div>

<!-- login -->
<div class="modal" id="login" role="dialog" aria-labelledby="dialog-title-4">
    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-4">Přihlášení</h2>
            <form action="/urbium-login" data-default="/urbium-login"
                data-checked="<?php echo $portal->formsTargetUrl(); ?>/mlift/services/save_form.php" method="post"
                class="async-form">
                <div class="row row--form">
                    <input name="form[form_type]" type="hidden" value="4">
                    <div class="col">
                        <div class="form-item form-item--simple">
                            <!-- <label for="login-4">Přihlašovací jméno</label> -->
                            <input name="form[username]" placeholder="Přihlašovací jméno" type="text" id="login-4">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-item form-item--simple">
                            <!-- <label for="password-4">Heslo</label> -->
                            <input name="form[password]" placeholder="Heslo" type="password" id="password-4">
                        </div>
                    </div>
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" id="use-old-urbium">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="use-old-urbium">Přihlásit se do starého Urbia</label>
                </div>

                <div class="row row--form-bottom">
                    <div class="col col--auto">
                        <a href="#passforgotten" class="btn btn--text">Zapomněl jsem heslo</a>
                    </div>
                    <div class="col col--auto col--submit">
                        <button type="submit" class="btn btn--md">Přihlásit se</button>
                    </div>
                </div>
            </form>
            <div class="modal__bottom-action">
                <p>Ještě nemám svůj účet. <a href="#register" class="js-modal-close js-modal-open">Chci se
                        registrovat.</a></p>
            </div>
        </div>

    </div>
</div>

<!-- registrace -->
<div class="modal" id="register" role="dialog" aria-labelledby="dialog-title-5">

    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-5">Registrace</h2>

            <form action="<?php echo $portal->formsTargetUrl(); ?>/mlift/services/save_form.php" method="post"
                class="async-form">
                <input name="form[form_type]" type="hidden" value="3">
                <div class="row row--form">
                    <div class="col col--3">
                        <div class="form-item">
                            <div class="custom-select">
                                <select name="form[stat_id]">
                                    <option>Vyberte stát</option>
                                    <option value="263">Česká republika</option>
                                    <option value="264">Slovensko</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[nazev_spolecnosti]" type="text" placeholder="Název společnosti">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[ico]" type="text" placeholder="IČ">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[dic]" type="text" placeholder="DIČ">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[ulice]" type="text" placeholder="Ulice, ČP">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple form-item--double">
                            <input name="form[mesto]" type="text" placeholder="Město">
                            <input name="form[psc]" type="text" placeholder="PSČ">
                        </div>
                    </div>
                </div>

                <div class="break"></div>

                <div class="row row--form">
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[jmeno]" type="text" placeholder="Jméno">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[prijmeni]" type="text" placeholder="Příjmení">
                        </div>
                    </div>
                </div>

                <div class="break"></div>

                <div class="row row--form row--center">
                    <div class="col col--auto">
                        <label>Vyberte software</label>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <div class="custom-select">
                                <select name="form[software]">
                                    <?php
                  $softwareResource = new \Portal\Helpers\Resources\SoftwareResource();
                  $softwareData = $softwareResource->getData();
                  foreach($softwareData as $value)
                  {
                    echo "<option value=\"{$value["id"]}\">{$value["nazev"]}</option>";
                  }
                  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="break"></div>

                <h3>Přihlašovací údaje</h3>
                <div class="row row--form">
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[email]" type="text" placeholder="Email / Uživatelské jméno">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[email2]" type="email" placeholder="Email další">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[heslo]" type="password" placeholder="Heslo">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[heslo2]" type="password" placeholder="Heslo znovu">
                        </div>
                    </div>
                </div>

                <div class="break"></div>

                <h3>Fakturační údaje (nevyplňujte jsou-li shodné s adresou společnosti)</h3>
                <div class="row row--form">
                    <div class="col">
                        <div class="form-item form-item--simple">
                            <input name="form[nazev_spolecnosti2]" type="text" placeholder="Název společnosti">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[ico2]" type="text" placeholder="IČ">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[dic2]" type="text" placeholder="DIČ">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[ulice2]" type="text" placeholder="Ulice, ČP">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple form-item--double">
                            <input name="form[mesto2]" type="text" placeholder="Město">
                            <input name="form[psc2]" type="text" placeholder="PSČ">
                        </div>
                    </div>
                </div>

                <div class="break"></div>

                <h3>Spojení telefon, fax, mobil, www</h3>
                <div class="row row--form">
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[mobil][0]" type="tel" placeholder="Mobil">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[fax][0]" type="text" placeholder="Fax">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[telefon][0]" type="text" placeholder="Telefon">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item form-item--simple">
                            <input name="form[www]" type="text" placeholder="www">
                        </div>
                    </div>
                    <!-- pro pridani -->
                    <div class="col col--2 is-hidden" id="js-add-mobile">
                        <div class="form--item">
                            <input name="form[mobil][1]" type="tel" placeholder="Mobil">
                        </div>
                    </div>
                    <div class="col col--2 is-hidden" id="js-add-fax">
                        <div class="form-item">
                            <input name="form[fax][1]" type="text" placeholder="Fax">
                        </div>
                    </div>
                    <div class="col col--2 is-hidden" id="js-add-telefon">
                        <div class="form-item">
                            <input name="form[telefon][1]" type="text" placeholder="Telefon">
                        </div>
                    </div>
                </div>

                <div class="row row--form row--start">
                    <div class="col col--auto col--start"><a href="#" class="btn btn--gray btn--sm js-toggle"
                            data-target="js-add-mobile" data-toggle-class="active"
                            data-toggle-text="Odebrat mobil">Přidat mobil</a></div>
                    <div class="col col--auto col--start"><a href="#" class="btn btn--gray btn--sm js-toggle"
                            data-target="js-add-telefon" data-toggle-class="active"
                            data-toggle-text="Odebrat telefon">Přidat telefon</a></div>
                    <div class="col col--auto col--start"><a href="#" class="btn btn--gray btn--sm js-toggle"
                            data-target="js-add-fax" data-toggle-class="active" data-toggle-text="Odebrat fax">Přidat
                            fax</a></div>
                </div>

                <div class="break break--sm"></div>

                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-5-1">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-5-1">Souhlas s ochranou <a href="#" target="_blank">osobních
                            údajů</a></label>
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-5-2">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-5-2">Souhlas se zasíláním <a href="#"
                            target="_blank">obchodních sdělení</a></label>
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-5-3">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-5-3">Souhlas s <a href="#" target="_blank">obchodními
                            podmínkami</a></label>
                </div>

                <?php if(in_array($portal->getClientIP(), array('46.29.224.53'))) { ?>
                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox">Zdarma na pul roku</label>
                </div>
                <?php } ?>

                <div class="row row--form-bottom">
                    <div class="col col--auto col--submit">
                        <button type="submit" class="btn btn--md">Registrovat se</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>

<!-- zapomenuté heslo -->
<div class="modal" id="modal-password" role="dialog" aria-labelledby="dialog-title-6">

    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-6">Zapomněl jsem heslo</h2>

            <form action="<?php echo $portal->formsTargetUrl(); ?>/mlift/services/save_form.php" method="post"
                class="async-form">
                <input name="form[form_type]" type="hidden" value="2">
                <div class="row row--form">
                    <div class="col">
                        <div class="form-item form-item--simple">
                            <!-- <label for="forgotten-password-6">Přihlašovací jméno nebo email</label> -->
                            <input name="form[email_password]" id="forgotten-password-6" type="email"
                                placeholder="Přihlašovací jméno nebo email">
                        </div>
                    </div>
                </div>
                <div class="row row--form-bottom">
                    <div class="col col--auto col--submit">
                        <button type="submit" class="btn btn--md">Odeslat nové heslo</button>
                    </div>
                </div>
            </form>

        </div>

    </div>

</div>

<!-- dotaz -->
<div class="modal" id="modal-question" role="dialog" aria-labelledby="dialog-title-7">
    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-7">Máte otázku?</h2>

            <form action="<?php echo $portal->formsTargetUrl(); ?>/mlift/services/save_form.php" method="post"
                class="async-form">
                <div class="row row--form">
                    <input name="form[form_type]" type="hidden" value="1">
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="name-7">Jméno</label>
                            <input name="form[jmeno]" type="text" id="name-7"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_FIRST_NAME); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="surname-7">Příjmení</label>
                            <input name="form[prijmeni]" type="text" id="surname-7"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_LAST_NAME); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="email-7">E-mail</label>
                            <input name="form[email]" type="email" id="email-7"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_EMAIL); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="phone-7">Telefon</label>
                            <input name="form[telefon]" type="text" id="Telefon"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_PHONE); ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-item">
                            <label for="message-7">Zpráva</label>
                            <textarea name="form[dotaz]" id="message-7"
                                rows="6"><?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_LAST_ANSWER); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-7-1">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-7-1">Souhlas s ochranou <a href="#" target="_blank">osobních
                            údajů</a></label>
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-7-2">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-7-2">Souhlas se zasíláním <a href="#"
                            target="_blank">obchodních sdělení</a></label>
                </div>

                <div class="row row--form-bottom">
                    <div class="col col--auto col--submit">
                        <button type="submit" class="btn btn--md">Odeslat zprávu</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>

<!-- lepší cena -->
<div class="modal" id="modal-better-price" role="dialog" aria-labelledby="dialog-title-8">

    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-8">Chci lepší cenu</h2>

            <form action="<?php echo $portal->formsTargetUrl(); ?>/mlift/services/save_form.php" method="post"
                class="async-form">
                <div class="row row--form">
                    <input name="form[form_type]" type="hidden" value="1">
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="name-8">Jméno</label>
                            <input name="form[jmeno]" type="text" id="name-8"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_FIRST_NAME); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="surname-8">Příjmení</label>
                            <input name="form[prijmeni]" type="text" id="surname-8"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_LAST_NAME); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="email-8">E-mail</label>
                            <input name="form[email]" type="email" id="email-8"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_EMAIL); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="phone-8">Telefon</label>
                            <input name="form[telefon]" type="text" id="phone-8"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_PHONE); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="better-price-8">Cena</label>
                            <input name="form[cena]" type="text" id="better-price-8" value=""
                                onkeypress="return isNumberKey(event)" onkeyup="return separateNumber(event)">
                        </div>
                    </div>

                    <!-- TODO: start netuším jestli to tady musí být -->
                    <input type="hidden" name="form[advert_id][]">
                    <input type="hidden" name="form[cena_puvodni]">
                    <!-- TODO: end netuším jestli to tady musí být -->

                    <div class="col">
                        <div class="form-item">
                            <label for="message-8">Zpráva</label>
                            <textarea name="form[dotaz]" id="message-8"
                                rows="6"><?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_LAST_ANSWER); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-8-1">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-8-1">Souhlas s ochranou <a href="#" target="_blank">osobních
                            údajů</a></label>
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-8-2">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-8-2">Souhlas se zasíláním <a href="#"
                            target="_blank">obchodních sdělení</a></label>
                </div>

                <div class="row row--form-bottom">
                    <div class="col col--auto col--submit">
                        <button type="submit" class="btn btn--md">Odeslat zprávu</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>

<!-- krátka poptávka -->
<div class="modal" id="modal-short-question" role="dialog" aria-labelledby="dialog-title-9">

    <div class="modal__body">

        <div class="modal__close js-modal-close">
            <div class="icon icon--icon-times">
                <svg class="icon__svg">
                    <use xlink:href="#icon-times"></use>
                </svg>
            </div>
        </div>

        <div class="modal__content">

            <h2 class="title" id="dialog-title-9">Vytvořit poptávku</h2>

            <label for="">Filtrace</label>
            <ul class="">
                <?php
        $cloudMax = is_array($data['cloud'])? count($data['cloud']) : 0;
        for($i = 0;$i < $cloudMax;$i++)
        {
          echo "<li class=\"in-tags__item\">{$data['cloud'][$i]}</li>";
        }
        ?>
            </ul>

            <form action="<?php echo $portal->formsTargetUrl(); ?>/mlift/services/save_form.php" method="post"
                class="async-form">
                <div class="row row--form">
                    <input name="form[form_type]" type="hidden" value="5">
                    <?php // TODO: vůbec netuším jestli to tady musí být ?>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="name-9">Jméno</label>
                            <input name="form[jmeno]" type="text" id="name-9"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_FIRST_NAME); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="surname-9">Přijmení</label>
                            <input name="form[prijmeni]" type="text" id="surname-9"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_LAST_NAME); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="email-9">E-mail</label>
                            <input name="form[email]" type="email" id="email-9"
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_EMAIL); ?>">
                        </div>
                    </div>
                    <div class="col col--2">
                        <div class="form-item">
                            <label for="phone-9">Telefon</label>
                            <input name="form[telefon]" type="tel" id="phone-9" placeholder=""
                                value="<?php echo $portal->getCustomerInfo(Portal::CUSTOMER_INFO_PHONE); ?>">
                        </div>
                    </div>
                </div>

                <?php
//        $requestData = strtolower($_SERVER['REQUEST_METHOD']) == "post"? $_POST : $data["sql"];
//
//
//
//        function renderSQLInput($beforeKeys, $data)
//        {
//          foreach($data as $key => $value){
//            $keysArray = $beforeKeys;
//            $keysArray[] = count($keysArray) == 0? $key : "[{$key}]";
//            if(is_array($value))
//            {
//              renderSQLInput($keysArray, $value);
//            }
//            else
//            {
//              if($value != "")
//              {
//                $keys = implode("",$keysArray);
//                echo "<input type='hidden' value='{$value}' name='$keys'>";
//              }
//            }
//          }
//        }
//
//        renderSQLInput(array(),$requestData);

        ?>


                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-9-1">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-9-1">Souhlas s ochranou <a href="#" target="_blank">osobních
                            údajů</a></label>
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox-9-2">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="checkbox-9-2">Souhlas se zasíláním <a href="#"
                            target="_blank">obchodních sdělení</a></label>
                </div>

                <div class="row row--form-bottom">
                    <div class="col col--auto">
                        <a href="#" class="btn btn--text">Upravit poptávku</a>
                    </div>
                    <div class="col col--auto col--submit">
                        <button type="submit" class="btn btn--md">Uložit poptávku</button>
                    </div>
                </div>

            </form>

        </div>

    </div>
</div>