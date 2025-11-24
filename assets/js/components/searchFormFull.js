document.addEventListener('DOMContentLoaded', function (event) {
  if (document.getElementById('full-search-form')) {
    let checkedLocalitiesInputs = document.getElementsByClassName(
      'f-locality-checkbox'
    );
    viewCheckedLocalityFull(checkedLocalitiesInputs);
    const subregions = document.getElementsByClassName('f-subregion');
    subregions.forEach(function (subregion) {
      subregion.addEventListener('change', (event) => {
        let subregionKey = subregion.dataset.subregionFRegionkey;
        if (event.currentTarget.checked) {
          clearAllGoogleSearchWhenCheckedFull();
          // if subregion is checked - check also master region
          let region = document.querySelectorAll(
            '[data-region-f="' + subregionKey + '"]'
          );
          region[0].children[0].checked = true;
          viewCheckedLocalityFull(checkedLocalitiesInputs);
        } else {
          // check if any other subregion is still checked - if not uncheck master region
          event.currentTarget.checked = false;
          let tmpSubregions = document.querySelectorAll(
            '[data-subregion-f-regionkey="' + subregionKey + '"]'
          );
          let tmpChecked = false;
          tmpSubregions.forEach(function (tmpSubregion) {
            if (tmpSubregion.checked === true) {
              tmpChecked = true;
            }
          });
          if (tmpChecked === false) {
            let region = document.querySelectorAll(
              '[data-region-f="' + subregionKey + '"]'
            );
            region[0].children[0].checked = false;
          }
          viewCheckedLocalityFull(checkedLocalitiesInputs);
        }
      });
    });

    // zobrazení/schování příslušných checkboxů
    const advertTypeEu = document.getElementById('full-estate-select');
    const allWraps = document.querySelectorAll('[class*=" f-checkbox-wrap-"]');
    if (advertTypeEu) {
      advertTypeEu.addEventListener('change', (event) => {
        let masterId = event.currentTarget.selectedOptions[0].value;
        allWraps.forEach(function (wrap) {
          wrap.classList.add('is-hidden');
        });
        let currentWrap = document.getElementsByClassName(
          'f-checkbox-wrap-' + masterId
        );
        currentWrap[0].classList.remove('is-hidden');
      });
    }

    // zaškrtnutí všech checkboxů při kliknutí na tlačítko vše
    const insideWrapCheckboxes = document.querySelectorAll(
      'input[id^="f-inside-wrap-checkbox-"]'
    );
    insideWrapCheckboxes.forEach(function (insideWrapCheckbox) {
      insideWrapCheckbox.addEventListener('change', (event) => {
        let masterId = event.currentTarget.dataset.masterFIdValue;
        if (event.currentTarget.checked) {
          changeAllCheckboxesInWrapFull(masterId);
        } else {
          changeAllCheckboxesInWrapFull(masterId, true);
        }
      });
    });
  }
});

function changeAllCheckboxesInWrapFull(masterId, uncheck = false) {
  let checkboxes = document.querySelectorAll(
    '[data-master-f-id="' + masterId + '"]'
  );
  checkboxes.forEach(function (checkbox) {
    checkbox.checked = uncheck === false;
  });
}

function viewCheckedLocalityFull(checkedLocalitiesInputs) {
  const localityInput = document.getElementById('locality-input-full');
  let checkedLocalitiesArray = [];
  checkedLocalitiesInputs.forEach(function (checkedLocality) {
    if (checkedLocality.checked) {
      checkedLocalitiesArray.push(checkedLocality.dataset.name);
    }
  });
  if (checkedLocalitiesArray.length > 1) {
    localityInput.innerHTML =
      checkedLocalitiesArray[0] + ' +' + (checkedLocalitiesArray.length - 1);
  } else if (checkedLocalitiesArray.length === 1) {
    localityInput.innerHTML = checkedLocalitiesArray[0];
  } else {
    localityInput.innerHTML = 'Např.: Mladá Boleslav';
  }
}

function clearAllGoogleSearchWhenCheckedFull() {
  const googleSearchInputs = document.querySelectorAll(
    "input[name^='sql[locality]']"
  );
  googleSearchInputs.forEach(function (searchInput) {
    searchInput.value = null;
  });
}
