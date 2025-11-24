document.addEventListener('DOMContentLoaded', function (event) {
  if (
    document.getElementById('full-search-form') &&
    document.getElementById('listing-form')
  ) {
    const saleTypeInputs = document.querySelectorAll(
      "input[id^='s-checkbox-advert-function-eu-']"
    );
    const advertTypeInputs = document.querySelectorAll(
      "input[id^='s-checkbox-advert-type-eu-']"
    );
    const checkboxInputs = document.querySelectorAll(
      "input[id^='s-propertySubTypeCheckbox-']"
    );

    // checkboxes for main city
    const cityCheckboxes = document.getElementsByClassName('city-checkbox');
    cityCheckboxes.forEach(function (cityCheckbox) {
      cityCheckbox.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
          let value = event.currentTarget.value;
          let checkboxes = document.querySelectorAll(
            "input[name^='sql[locality_kraj_kod][]'][value='" + value + "']"
          );
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = true;
          });
        } else {
          let value = event.currentTarget.value;
          let checkboxes = document.querySelectorAll(
            "input[name^='sql[locality_kraj_kod][]'][value='" + value + "']"
          );
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
          });
        }
      });
    });

    // checkboxes for city parts
    const cityLocalityCheckboxes = document.getElementsByClassName(
      'connect-locality-checkbox'
    );
    let checkedLocalitiesInputs = document.getElementsByClassName(
      'locality-checkbox'
    );
    let checkedLocalitiesInputsFull = document.getElementsByClassName(
      'f-locality-checkbox'
    );
    cityLocalityCheckboxes.forEach(function (cityLocalityCheckbox) {
      cityLocalityCheckbox.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
          let value = event.currentTarget.value;
          let checkboxes = document.querySelectorAll(
            "input[class*='subregion'][value='" + value + "']"
          );
          let masterRegionKey;
          if (event.currentTarget.dataset.subregionFRegionkey !== undefined) {
            masterRegionKey = event.currentTarget.dataset.subregionFRegionkey;
          } else {
            masterRegionKey = event.currentTarget.dataset.subregionRegionkey;
          }

          checkboxes.forEach(function (checkbox) {
            checkbox.checked = true;
          });
          let cityCheckbox = document.querySelectorAll(
            "input[class*='city-checkbox'][value='" + masterRegionKey + "']"
          );
          cityCheckbox[0].checked = true;
          viewCheckedLocality(checkedLocalitiesInputs);
          viewCheckedLocalityFull(checkedLocalitiesInputsFull);
        } else {
          let value = event.currentTarget.value;
          let masterRegionKey;
          if (event.currentTarget.dataset.subregionFRegionKey !== undefined) {
            masterRegionKey = event.currentTarget.dataset.subregionFRegionKey;
          } else {
            masterRegionKey = event.currentTarget.dataset.subregionRegionKey;
          }

          let checkboxes = document.querySelectorAll(
            "input[class*='subregion'][value='" + value + "']"
          );
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
          });
          let cityCheckbox = document.querySelectorAll(
            "input[class*='city-checkbox'][value='" + masterRegionKey + "']"
          );
          cityCheckbox[0].checked = true;
          viewCheckedLocality(checkedLocalitiesInputs);
          viewCheckedLocalityFull(checkedLocalitiesInputsFull);
        }
      });
    });

    // checkboxes for checkbox-advert-function-eu
    const advertFunctionsCheckboxes = document.querySelectorAll(
      "input[id*='checkbox-advert-function-eu']"
    );
    advertFunctionsCheckboxes.forEach(function (advertFunctionsCheckbox) {
      advertFunctionsCheckbox.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
          let value = event.currentTarget.value;
          let checkboxes = document.querySelectorAll(
            "input[id*='checkbox-advert-function-eu'][value='" + value + "']"
          );
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = true;
          });
          viewCheckedEstateType(
            saleTypeInputs,
            advertTypeInputs,
            checkboxInputs
          );
        } else {
          let value = event.currentTarget.value;
          let checkboxes = document.querySelectorAll(
            "input[id*='checkbox-advert-function-eu'][value='" + value + "']"
          );
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
          });
          viewCheckedEstateType(
            saleTypeInputs,
            advertTypeInputs,
            checkboxInputs
          );
        }
      });
    });

    // checkboxes for checkbox-advert-type-eu
    const advertTypeCheckboxes = document.querySelectorAll(
      "input[id*='checkbox-advert-type-eu']"
    );
    // set select to selected option
    advertTypeCheckboxes.forEach(function (advertTypeCheckbox) {
      advertTypeCheckbox.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
          let value = event.currentTarget.value;
          let select = document.querySelectorAll(
            "option[id*='checkbox-advert-type-eu'][value='" + value + "']"
          );
          select[0].selected = true;
          viewCheckedEstateType(
            saleTypeInputs,
            advertTypeInputs,
            checkboxInputs
          );
        } else {
          let value = event.currentTarget.value;
          let select = document.querySelectorAll(
            "option[id*='checkbox-advert-type-eu'][value='" + value + "']"
          );
          select[0].selected = false;
          viewCheckedEstateType(
            saleTypeInputs,
            advertTypeInputs,
            checkboxInputs
          );
        }
      });
    });

    // check checkboxes when select option is chosen
    const advertTypeEu = document.getElementById('full-estate-select');
    advertTypeEu.addEventListener('change', (event) => {
      let value = event.currentTarget.selectedOptions[0].value;
      let advertTypeCheckbox = document.querySelectorAll(
        "input[id*='checkbox-advert-type-eu'][value='" + value + "']"
      );
      advertTypeCheckbox.checked = true;
      viewCheckedEstateType(saleTypeInputs, advertTypeInputs, checkboxInputs);
    });

    // checkboxes all check
    const insideWrapCheckboxes = document.querySelectorAll(
      "input[id*='-inside-wrap-checkbox-']"
    );
    insideWrapCheckboxes.forEach(function (insideWrapCheckbox) {
      insideWrapCheckbox.addEventListener('change', (event) => {
        let value = 0;
        if (event.currentTarget.dataset.masterIdValue !== undefined) {
          value = event.currentTarget.dataset.masterIdValue;
        } else {
          value = event.currentTarget.dataset.masterFIdValue;
        }
        let checkboxes1 = document.querySelectorAll(
          "input[id*='-propertySubTypeCheckbox-'][data-master-id='" +
            value +
            "']"
        );
        let checkboxes2 = document.querySelectorAll(
          "input[id*='-propertySubTypeCheckbox-'][data-master-f-id='" +
            value +
            "']"
        );

        if (event.currentTarget.checked) {
          checkboxes1.forEach(function (checkbox) {
            checkbox.checked = true;
          });
          checkboxes2.forEach(function (checkbox) {
            checkbox.checked = true;
          });
          viewCheckedEstateType(
            saleTypeInputs,
            advertTypeInputs,
            checkboxInputs
          );
        } else {
          checkboxes1.forEach(function (checkbox) {
            checkbox.checked = false;
          });
          checkboxes2.forEach(function (checkbox) {
            checkbox.checked = false;
          });
          viewCheckedEstateType(
            saleTypeInputs,
            advertTypeInputs,
            checkboxInputs
          );
        }
      });
    });

    // checkboxes for propertySubTypeCheckbox
    const propertySubTypeCheckboxes = document.querySelectorAll(
      "input[id*='-propertySubTypeCheckbox-']"
    );
    propertySubTypeCheckboxes.forEach(function (propertySubTypeCheckbox) {
      propertySubTypeCheckbox.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
          let value = event.currentTarget.value;
          let checkboxes = document.querySelectorAll(
            "input[id*='-propertySubTypeCheckbox-'][value='" + value + "']"
          );
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = true;
          });
          viewCheckedEstateType(
            saleTypeInputs,
            advertTypeInputs,
            checkboxInputs
          );
        } else {
          let value = event.currentTarget.value;
          let checkboxes = document.querySelectorAll(
            "input[id*='-propertySubTypeCheckbox-'][value='" + value + "']"
          );
          checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
          });
          viewCheckedEstateType(
            saleTypeInputs,
            advertTypeInputs,
            checkboxInputs
          );
        }
      });
    });

    // price inputs
    const minPriceInputs = document.querySelectorAll(
      'input[name="sql[advert_price_min]"]'
    );
    const maxPriceInputs = document.querySelectorAll(
      'input[name="sql[advert_price_max]"]'
    );

    minPriceInputs.forEach(function (minPrice) {
      minPrice.addEventListener('change', (event) => {
        let value = event.currentTarget.value;
        minPriceInputs.forEach(function (input) {
          input.value = value;
        });
        viewRangePrice(minPriceInputs[0], maxPriceInputs[0]);
      });
    });

    maxPriceInputs.forEach(function (maxPrice) {
      maxPrice.addEventListener('change', (event) => {
        let value = event.currentTarget.value;
        maxPriceInputs.forEach(function (input) {
          input.value = value;
        });
        viewRangePrice(minPriceInputs[0], maxPriceInputs[0]);
      });
    });
  }
});
