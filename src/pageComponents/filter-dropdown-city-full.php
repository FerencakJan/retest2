<div class="filter__dropdown filter__dropdown--city">
    <div class="filter__dropdown-top">
        <label for="">Lokalita</label>
        <div class="filter__input-text" id="locality-input-full">Např.: Mladá Boleslav</div>
    </div>
    <div class="filter__dropdown-inside">
        <div class="filter__dropdown-inside-wrap">
            <div class="filter__dropdown-full">
                <input name="sql[locality][locality][input]"
                    <?php if(isset($formData["locality"]["locality"]["input"])) { echo " value=\"{$formData["locality"]["locality"]["input"]}\" "; } ?>
                    class="filter__input address-autocomplete pac-input" type="text"
                    placeholder="Napište lokalitu, kterou hledáte nebo vyberte níže">
                <input type="hidden" name="sql[locality][locality][city]"
                    <?php if(isset($formData["locality"]["locality"]["city"])) { echo " value=\"{$formData["locality"]["locality"]["city"]}\" "; } ?>>
                <input type="hidden" name="sql[locality][locality][zip_code]"
                    <?php if(isset($formData["locality"]["locality"]["zip_code"])) { echo " value=\"{$formData["locality"]["locality"]["zip_code"]}\" "; } ?>>
                <input type="hidden" name="sql[locality][locality][types]"
                    <?php if(isset($formData["locality"]["locality"]["types"])) { echo " value=\"{$formData["locality"]["locality"]["types"]}\" "; } ?>>
                <input type="hidden" name="sql[locality][location][lat]"
                    <?php if(isset($formData["locality"]["location"]["lat"])) { echo " value=\"{$formData["locality"]["location"]["lat"]}\" "; } ?>>
                <input type="hidden" name="sql[locality][location][lng]"
                    <?php if(isset($formData["locality"]["location"]["lng"])) { echo " value=\"{$formData["locality"]["location"]["lng"]}\" "; } ?>>
                <input type="hidden" name="sql[locality][viewport][south]"
                    <?php if(isset($formData["locality"]["viewport"]["south"])) { echo " value=\"{$formData["locality"]["viewport"]["south"]}\" "; } ?>>
                <input type="hidden" name="sql[locality][viewport][west]"
                    <?php if(isset($formData["locality"]["viewport"]["west"])) { echo " value=\"{$formData["locality"]["viewport"]["west"]}\" "; } ?>>
                <input type="hidden" name="sql[locality][viewport][north]"
                    <?php if(isset($formData["locality"]["viewport"]["north"])) { echo " value=\"{$formData["locality"]["viewport"]["north"]}\" "; } ?>>
                <input type="hidden" name="sql[locality][viewport][east]"
                    <?php if(isset($formData["locality"]["viewport"]["east"])) { echo " value=\"{$formData["locality"]["viewport"]["east"]}\" "; } ?>>
            </div>
            <div class="filter__dropdown-inside-wrap-2">
                <div class="filter__dropdown-property-wrap">

                    <?php $displayAll = true;$regionActive=19; foreach ($portal->getCompanyTranslator($displayAll)->getRegions() as $regionKey => $region)
            { ?>
                    <div class="property <?php echo ($regionKey === $regionActive ? "is-active" : "" ); ?>"
                        data-region-f="<?php echo $regionKey; ?>"><?php echo $region['name']; ?>
                        <input type="checkbox" name="sql[locality_kraj_kod][]" value="<?php echo $regionKey; ?>"
                            onclick="formRegionClick(this);" class="form-checkbox__input city-checkbox"
                            <?php if(isset($formData["locality_kraj_kod"]) && in_array($regionKey,$formData["locality_kraj_kod"])){ echo 'checked'; } ?>>
                    </div>

                    <?php } ?>
                </div>
                <div class="filter__dropdown-break"></div>
                <?php $displayAll = true; $regionActive=19; foreach ($portal->getCompanyTranslator($displayAll)->getRegions() as $regionKey => $region)
          { ?>
                <div class="filter__dropdown-checkbox-wrap <?php echo ($regionKey !== $regionActive ? "is-hidden" : "" ); ?>"
                    data-region-f="<?php echo $regionKey; ?>">
                    <?php foreach ($region['subRegions'] as $subRegionKey => $subRegion)
                  { ?>
                    <div class="form-checkbox">
                        <input class="f-subregion f-locality-checkbox connect-locality-checkbox"
                            name="sql[locality_okres_kod][<?php echo $regionKey; ?>][<?php echo $subRegionKey; ?>]"
                            type="checkbox" id="f-checkbox<?php echo $subRegionKey; ?>"
                            value="<?php echo $subRegionKey; ?>" data-subregion-f-regionkey="<?php echo $regionKey; ?>"
                            <?php if(isset($formData["locality_okres_kod"]) && in_array($subRegionKey,$formData["locality_okres_kod"][$regionKey])){ echo 'checked'; } ?>
                            data-name="<?php echo $subRegion['name']; ?>">
                        <span class="form-checkbox__box"></span>
                        <label class="form-label"
                            for="f-checkbox<?php echo $subRegionKey; ?>"><?php echo $subRegion['name']; ?></label>
                    </div>
                    <?php } ?>

                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>