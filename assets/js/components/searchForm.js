document.addEventListener('DOMContentLoaded', function (event) {
  if (document.getElementById('listing-form')) {
    const saleTypeInputs = document.querySelectorAll(
      "input[id^='s-checkbox-advert-function-eu-']"
    );
    const advertTypeInputs = document.querySelectorAll(
      "input[id^='s-checkbox-advert-type-eu-']"
    );
    const checkboxInputs = document.querySelectorAll(
      "input[id^='s-propertySubTypeCheckbox-']"
    );
    const rangeSliderFrom = document.getElementById('rangeslider-from');
    const rangeSliderTo = document.getElementById('rangeslider-to');
    if (rangeSliderFrom !== null && rangeSliderFrom.valueOf !== undefined) {
      viewRangePrice(rangeSliderFrom, rangeSliderTo);
    }

    let checkedLocalitiesInputs = document.getElementsByClassName(
      'locality-checkbox'
    );
    viewCheckedLocality(checkedLocalitiesInputs);
    const subregions = document.getElementsByClassName('subregion');
    subregions.forEach(function (subregion) {
      subregion.addEventListener('change', (event) => {
        let subregionKey = subregion.dataset.subregionRegionkey;
        if (event.currentTarget.checked) {
          clearAllGoogleSearchWhenChecked();
          // if subregion is checked - check also master region
          let region = document.querySelectorAll(
            '[data-region="' + subregionKey + '"]'
          );
          region[0].children[0].checked = true;
          viewCheckedLocality(checkedLocalitiesInputs);
        } else {
          // check if any other subregion is still checked - if not uncheck master region
          event.currentTarget.checked = false;
          let tmpSubregions = document.querySelectorAll(
            '[data-subregion-regionkey="' + subregionKey + '"]'
          );
          let tmpChecked = false;
          tmpSubregions.forEach(function (tmpSubregion) {
            if (tmpSubregion.checked === true) {
              tmpChecked = true;
            }
          });
          if (tmpChecked === false) {
            let region = document.querySelectorAll(
              '[data-region="' + subregionKey + '"]'
            );
            region[0].children[0].checked = false;
          }
          viewCheckedLocality(checkedLocalitiesInputs);
        }
      });
    });

    // zobrazení/schování příslušných checkboxů
    const advertTypeEu = document.querySelectorAll(
      'input[id^="s-checkbox-advert-type-eu-"]'
    );
    const allWraps = document.querySelectorAll('[class*=" s-checkbox-wrap-"]');
    advertTypeEu.forEach(function (advertType) {
      advertType.addEventListener('click', (event) => {
        let masterId = event.currentTarget.value;
        if (event.currentTarget.checked) {
          allWraps.forEach(function (wrap) {
            wrap.classList.add('is-hidden');
          });
          let currentWrap = document.getElementsByClassName(
            's-checkbox-wrap-' + masterId
          );
          currentWrap[0].classList.remove('is-hidden');
        } else {
          let currentWrap = document.getElementsByClassName(
            's-checkbox-wrap-' + masterId
          );
          currentWrap[0].classList.add('is-hidden');
        }
        viewCheckedEstateType(saleTypeInputs, advertTypeInputs, checkboxInputs);
      });
    });

    // zaškrtnutí všech checkboxů při kliknutí na tlačítko vše
    const insideWrapCheckboxes = document.querySelectorAll(
      'input[id^="s-inside-wrap-checkbox-"]'
    );
    insideWrapCheckboxes.forEach(function (insideWrapCheckbox) {
      insideWrapCheckbox.addEventListener('change', (event) => {
        let masterId = event.currentTarget.dataset.masterIdValue;
        if (event.currentTarget.checked) {
          changeAllCheckboxesInWrap(masterId);
        } else {
          changeAllCheckboxesInWrap(masterId, true);
        }
        viewCheckedEstateType(saleTypeInputs, advertTypeInputs, checkboxInputs);
      });
    });

    // uprava hodnot v inputu pokud se zmeni advert function
    saleTypeInputs.forEach(function (saleTypeInput) {
      saleTypeInput.addEventListener('click', () => {
        viewCheckedEstateType(saleTypeInputs, advertTypeInputs, checkboxInputs);
      });
    });

    // uprava hodnot v inputu pokud se zmeni advert type
    advertTypeInputs.forEach(function (advertTypeInput) {
      advertTypeInput.addEventListener('click', () => {
        viewCheckedEstateType(saleTypeInputs, advertTypeInputs, checkboxInputs);
      });
    });

    // uprava hodnot v inputu pokud se zmeni advert subtype checkboxy
    checkboxInputs.forEach(function (checkboxInput) {
      checkboxInput.addEventListener('click', () => {
        viewCheckedEstateType(saleTypeInputs, advertTypeInputs, checkboxInputs);
      });
    });

    if (rangeSliderFrom) {
      rangeSliderFrom.addEventListener('change', () => {
        viewRangePrice(rangeSliderFrom, rangeSliderTo);
      });

      rangeSliderTo.addEventListener('change', () => {
        viewRangePrice(rangeSliderFrom, rangeSliderTo);
      });
    }
  }
});

function changeAllCheckboxesInWrap(masterId, uncheck = false) {
  let checkboxes = document.querySelectorAll(
    '[data-master-id="' + masterId + '"]'
  );
  checkboxes.forEach(function (checkbox) {
    checkbox.checked = uncheck === false;
  });
}

function viewCheckedLocality(checkedLocalitiesInputs) {
  const localityInput = document.getElementById('locality-input');
  let checkedLocalitiesArray = [];
  checkedLocalitiesInputs.forEach(function (checkedLocality) {
    if (checkedLocality.checked) {
      checkedLocalitiesArray.push(checkedLocality.dataset.name);
    }
  });

  if (checkedLocalitiesArray.length > 0) {
    localityInput.innerHTML = checkedLocalitiesArray.join(', ');
    localityInput.title = checkedLocalitiesArray.join(', ');
  } else {
    localityInput.innerHTML = 'Např.: Mladá Boleslav';
    localityInput.title = '';
  }
}

function clearAllGoogleSearchWhenChecked() {
  const googleSearchInputs = document.querySelectorAll(
    "input[name^='sql[locality]']"
  );
  googleSearchInputs.forEach(function (searchInput) {
    searchInput.value = null;
  });
}

function viewCheckedEstateType(
  saleTypeInputs,
  advertTypeInputs,
  checkboxInputs
) {
  const estateTypeInput = document.getElementById('estate-type-input');
  const filterDiv = document.getElementById('filter-simple-select');

  let checkedSaleTypeArray = [];
  let checkedAdvertTypeArray = [];
  let checkboxArray = [];

  if (filterDiv) {
    filterDiv.classList.add('is-selected');
  }

  saleTypeInputs.forEach(function (saleTypeInput) {
    if (saleTypeInput.checked) {
      checkedSaleTypeArray.push(saleTypeInput.dataset.name);
    }
  });

  advertTypeInputs.forEach(function (advertTypeInput) {
    if (advertTypeInput.checked) {
      checkedAdvertTypeArray.push(advertTypeInput.dataset.name);
    }
  });

  checkboxInputs.forEach(function (checkboxInput) {
    if (checkboxInput.checked) {
      checkboxArray.push(checkboxInput.dataset.name);
    }
  });

  let outputText = '';
  if (checkedSaleTypeArray.length > 0) {
    outputText =
      outputText +
      '<a href="#" class="badge badge--light is-active">' +
      checkedSaleTypeArray[0] +
      '</a>';
  }

  if (checkedAdvertTypeArray.length > 0) {
    outputText =
      outputText +
      '<a href="#" class="badge badge--light is-active">' +
      checkedAdvertTypeArray[0] +
      '</a>';
  }

  if (checkboxArray.length > 0) {
    outputText =
      outputText +
      '<a href="#" class="badge badge--light">' +
      checkboxArray[0] +
      '</a>';
  }

  if (checkboxArray.length > 1) {
    outputText =
      outputText +
      '<a href="#" class="badge badge--light">+ ' +
      (checkboxArray.length - 1) +
      '</a>';
  }

  estateTypeInput.innerHTML = outputText;
}

function viewRangePrice(rangeSliderFrom, rangeSliderTo) {
  const rangePriceInput = document.getElementById('range-price-input');

  let fromValue = rangeSliderFrom.value;
  let toValue = rangeSliderTo.value;
  let fromText = '';
  let toText = '';

  if (fromValue < 1000000) {
    fromText = fromValue / 1000 + ' tis';
  } else {
    fromText = fromValue / 1000000 + ' mil';
  }

  if (toValue < 1000000) {
    toText = toValue / 1000 + ' tis';
  } else {
    toText = toValue / 1000000 + ' mil';
  }

  rangePriceInput.innerHTML =
    '<span>' +
    fromText +
    '</span>' +
    '<span class="filter__dropdown-selection-break">-</span>' +
    '<span>' +
    toText +
    '</span>';
}
