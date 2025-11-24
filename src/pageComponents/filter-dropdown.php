<?php

use Portal\Helpers\CssClassHelper\CssClassHelper;

if ($propertySubTypes === null) {
  $propertySubTypes = $portal->getCompanyTranslator()->
  getProperty(isset($formData["advert_type_eu"][0]) ? $formData["advert_type_eu"][0] : 7);
}
$propertySubTypesCount = count($propertySubTypes);
$propertySubTypeKeys = array_keys($propertySubTypes);
//var_dump("VVVVVVVVVVVVVVVV");
//var_dump($formData["advert_subtype_eu"]);die;

?>


<div class="filter__dropdown">
    <div class="filter__dropdown-top">
        <label for="">Nemovitost</label>
        <div class="filter__input-text">Např.: Pronájem 2+1</div>
        <div class="badge-wrap" id="estate-type-input">
        </div>
    </div>
    <div class="filter__dropdown-inside filter__dropdown-inside--property">
        <div class="filter__dropdown-inside-wrap">
            <!--      Typ - prodej/pronájem/dražba-->
            <div class="filter__dropdown-property-wrap">
                <?php
        foreach ($portal->getCompanyTranslator()->getProperty(1) as $functionId => $functionValue) { ?>
                <div class="property-checkbox">
                    <input type="radio" name="sql[advert_function_eu][]" value="<?php echo $functionId; ?>"
                        id="s-checkbox-advert-function-eu-<?php echo $functionId; ?>" <?php if ((isset($formData["advert_function_eu"]) && in_array($functionId, $formData["advert_function_eu"]))) {
                echo " checked";
              } ?> data-name="<?php echo ucfirst($functionValue["name"]); ?>">
                    <label class="property-checkbox__label"
                        for="s-checkbox-advert-function-eu-<?php echo $functionId; ?>"><?php echo ucfirst($functionValue["name"]); ?></label>
                </div>
                <?php } ?>
            </div>
            <div class="filter__dropdown-break"></div>
            <div class="filter__dropdown-property-wrap filter__dropdown-property-wrap--2">
                <!--        Druh - byty,domy,atd-->
                <?php
        foreach ($portal->getCompanyTranslator()->getProperty(6) as $propertyId => $property) { ?>
                <div class="property-checkbox property-checkbox--icon <?php if ((isset($formData["advert_type_eu"]) && in_array($propertyId, $formData["advert_type_eu"]))) {
              echo 'is-active';
            } ?>">
                    <input type="radio" name="sql[advert_type_eu][]"
                        id="s-checkbox-advert-type-eu-<?php echo $propertyId ?>" value="<?php echo $propertyId ?>" <?php if ((isset($formData["advert_type_eu"]) && in_array($propertyId, $formData["advert_type_eu"]))) {
                echo 'checked';
              } ?> data-name="<?php echo $property['name']; ?>">
                    <label class="property-checkbox__label" for="s-checkbox-advert-type-eu-<?php echo $propertyId ?>">
                        <span class="icon icon--<?php echo CssClassHelper::cssIcon($propertyId); ?>">
                            <svg class="icon__svg">
                                <use xlink:href="#<?php echo CssClassHelper::cssIcon($propertyId); ?>"></use>
                            </svg>
                        </span>
                        <?php echo $property['name'] ?>
                    </label>
                </div>
                <?php } ?>
            </div>
            <div class="filter__dropdown-break"></div>
            <?php /*if (!isset($formData["advert_type_eu"][0])) { */?>
            <!--
      <div class="filter__dropdown-checkbox-wrap" id="checkbox-wrap-7">
        <div class="form-checkbox">
          <input type="checkbox" id="checkbox-7" value="all">
          <span class="form-checkbox__box"></span>
          <label class="form-label" for="checkbox-7">Vše</label>
        </div>
        <?php
/*
        echo $portal->render(
          "filter-property-sale-type.php",
          array(
            "propertySubTypes" => $portal->getCompanyTranslator()->getProperty(7),
            "selected" => array(),
            "masterId" => 7,
          )
        ); */?>
      </div>
      --><?php /*} else { */?>
            <?php //print_r($portal->getCompanyTranslator()->getProperty(6));die; ?>
            <?php foreach ($portal->getCompanyTranslator()->getProperty(6) as $propertyId => $property) { ?>
            <?php
          $array = $portal->getCompanyTranslator()->getProperty(6);
          $firstKey = key($array); ?>
            <div
                class="filter__dropdown-checkbox-wrap s-checkbox-wrap-<?php echo $propertyId ?><?php if ($propertyId !== $firstKey){ echo ' is-hidden'; } ?>">
                <div class="form-checkbox">
                    <input type="checkbox" id="s-inside-wrap-checkbox-<?php echo $propertyId ?>"
                        data-master-id-value="<?php echo $propertyId ?>">
                    <span class="form-checkbox__box"></span>
                    <label class="form-label" for="s-inside-wrap-checkbox-<?php echo $propertyId ?>">Vše</label>
                </div>
                <?php

        echo $portal->render(
          "filter-property-sale-type.php",
          array(
            "propertySubTypes" => $portal->getCompanyTranslator()->getProperty($propertyId),
            "selected" => $formData,
            "masterId" => $propertyId,
          )
        ); ?>

            </div>

            <?php } ?>
            <button class="btn btn--just-icon filter__dropdown--float-close js-property-close-btn" type="button">
                <div class="icon icon--icon-times">
                    <svg class="icon__svg">
                        <use xlink:href="#icon-times"></use>
                    </svg>
                </div>
            </button>
            <button class="btn btn--sm filter__dropdown--float-btn js-property-close-btn"
                type="button">Potvrdit</button>
        </div>
    </div>
</div>