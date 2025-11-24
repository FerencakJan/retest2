// filterinside.js

// Celý dropdown s lokalitou
const dropdownCity = document.querySelector('.filter__dropdown--city');

// Tlačidlá a bloky vo vnútri dropdownu
const buttonInputSearch = dropdownCity
  ? dropdownCity.querySelector(
    '.filter__dropdown-inside__header--button.is-button-input-search'
  )
  : null;

const buttonChoose = dropdownCity
  ? dropdownCity.querySelector(
    '.filter__dropdown-inside__header--button.is-button-choose'
  )
  : null;

const resetButton = dropdownCity
  ? dropdownCity.querySelector('#reset-filter-inside')
  : null;

const inputSearch = dropdownCity
  ? dropdownCity.querySelector('.filter__dropdown-inside__input-search')
  : null;

const choose = dropdownCity
  ? dropdownCity.querySelector('.filter__dropdown-inside__choose')
  : null;

/**
 * Prepínanie medzi "Vyhledat dle názvu" a "Vybrat ze seznamu"
 */
if (buttonInputSearch && inputSearch && choose && buttonChoose) {
  buttonInputSearch.addEventListener('click', () => {
    inputSearch.removeAttribute('hidden');
    choose.setAttribute('hidden', '');

    buttonChoose.removeAttribute('hidden');
    buttonInputSearch.setAttribute('hidden', '');
  });
}

if (buttonChoose && inputSearch && choose && buttonInputSearch) {
  buttonChoose.addEventListener('click', () => {
    inputSearch.setAttribute('hidden', '');
    choose.removeAttribute('hidden');

    buttonChoose.setAttribute('hidden', '');
    buttonInputSearch.removeAttribute('hidden');
  });
}

/**
 * Funkcia, ktorá na základe stavu checkboxov
 * nastaví, ktoré bloky krajov (okresy) majú byť viditeľné.
 *
 * – ak je kraj CHECKED => box ostáva aktívny a viditeľný
 * – ak má kraj aspoň jeden okres CHECKED => box je aktívny a viditeľný
 * – ak nie je nič v kraji zaškrtnuté => box sa schová
 * – ak sú zaškrtnuté len okresy, automaticky zaškrtneme aj kraj
 */
function refreshRegionBlocks() {
  if (!dropdownCity) return;

  const regionBlocks = dropdownCity.querySelectorAll(
    '.filter__dropdown-checkbox-wrap'
  );

  regionBlocks.forEach((block) => {
    const regionId = block.getAttribute('data-region');
    if (!regionId) return;

    const regionCheckbox = dropdownCity.querySelector(
      `.city-checkbox[value="${regionId}"]`
    );
    if (!regionCheckbox) return;

    const property = regionCheckbox.closest('.property');

    const hasRegion = regionCheckbox.checked;
    const hasSub =
      block.querySelector('input.subregion:checked') !== null;

    // ak sú zaškrtnuté len okresy, dorovnaj aj kraj
    if (hasSub && !hasRegion) {
      regionCheckbox.checked = true;
    }

    const shouldShow = regionCheckbox.checked || hasSub;

    if (shouldShow) {
      if (property) property.classList.add('is-active');
      block.classList.remove('is-hidden');
      block.removeAttribute('hidden');
      block.style.display = '';
    } else {
      if (property) property.classList.remove('is-active');
      block.classList.add('is-hidden');
      block.setAttribute('hidden', 'hidden');
      block.style.display = 'none';
    }
  });
}

/**
 * Reset – vráti všetko do defaultu:
 *  – vymaže Google input
 *  – odškrtne všetky kraje + okresy
 *  – schová bloky okresov
 *  – vráti placeholder "Např.: Mladá Boleslav"
 */
if (resetButton && dropdownCity) {
  resetButton.addEventListener('click', () => {
    // Reset input field (Google autocomplete)
    const inputField = dropdownCity.querySelector('.address-autocomplete');
    if (inputField) {
      inputField.value = '';
    }

    // Reset všetkých checkboxov (kraje + okresy)
    const checkboxes = dropdownCity.querySelectorAll(
      '.city-checkbox, .locality-checkbox'
    );
    checkboxes.forEach((checkbox) => {
      checkbox.checked = false;
    });

    // Prepnutie späť na "Vybrat ze seznamu"
    if (inputSearch && choose && buttonChoose && buttonInputSearch) {
      inputSearch.setAttribute('hidden', '');
      choose.removeAttribute('hidden');
      buttonChoose.setAttribute('hidden', '');
      buttonInputSearch.removeAttribute('hidden');
    }

    // Reset textu "Lokalita"
    const localityInput = document.getElementById('locality-input');
    if (localityInput) {
      localityInput.textContent = 'Např.: Mladá Boleslav';
      localityInput.title = 'Např.: Mladá Boleslav';
    }

    // vymaž hodnoty z hidden inputov pre google search (ak sú globálne funkcie)
    if (typeof clearAllGoogleSearchWhenChecked === 'function') {
      clearAllGoogleSearchWhenChecked();
    }

    // prepočet viditeľnosti krajov/okresov
    refreshRegionBlocks();
  });
}

/**
 * Klik na celý box kraja ( .property )
 * – namiesto starého JS, ktorý schovával ostatné kraje,
 *   tu len togglujeme daný kraj a necháme ostatné na pokoji
 */
document.addEventListener(
  'click',
  (event) => {
    const property = event.target.closest(
      '.filter__dropdown--city .property'
    );
    if (!property) return;

    // nechceme, aby nejaký starý handler prebil toto správanie
    event.preventDefault();
    event.stopPropagation();

    const regionCheckbox = property.querySelector('.city-checkbox');
    if (!regionCheckbox) return;

    // toggle kraja
    regionCheckbox.checked = !regionCheckbox.checked;

    const regionId = regionCheckbox.value;
    const block = dropdownCity.querySelector(
      `.filter__dropdown-checkbox-wrap[data-region="${regionId}"]`
    );

    // ak kraj vypíname, vypneme aj jeho okresy
    if (!regionCheckbox.checked && block) {
      block
        .querySelectorAll('input.subregion')
        .forEach((sub) => (sub.checked = false));
    }

    // pri klikaní na kraje rušíme Google search
    if (typeof clearAllGoogleSearchWhenChecked === 'function') {
      clearAllGoogleSearchWhenChecked();
    }

    // update textu "Lokalita" podľa označených okresov
    if (typeof viewCheckedLocality === 'function') {
      const allLocalityCheckboxes = dropdownCity.querySelectorAll(
        '.locality-checkbox'
      );
      viewCheckedLocality(allLocalityCheckboxes);
    }

    refreshRegionBlocks();
  },
  true // capture – aby sme predbehli staré jQuery handlery
);

/**
 * Zmena pri kliknutí priamo na checkbox kraja (nie len box)
 */
document.addEventListener(
  'change',
  (event) => {
    const target = event.target;

    // Kraje (city-checkbox)
    if (
      target.matches(
        '.filter__dropdown--city .city-checkbox'
      )
    ) {
      const regionCheckbox = target;
      const regionId = regionCheckbox.value;
      const block = dropdownCity.querySelector(
        `.filter__dropdown-checkbox-wrap[data-region="${regionId}"]`
      );

      if (!regionCheckbox.checked && block) {
        block
          .querySelectorAll('input.subregion')
          .forEach((sub) => (sub.checked = false));
      }

      if (typeof clearAllGoogleSearchWhenChecked === 'function') {
        clearAllGoogleSearchWhenChecked();
      }

      if (typeof viewCheckedLocality === 'function') {
        const allLocalityCheckboxes = dropdownCity.querySelectorAll(
          '.locality-checkbox'
        );
        viewCheckedLocality(allLocalityCheckboxes);
      }

      refreshRegionBlocks();
      return;
    }

    // Okresy (subregion / locality-checkbox)
    if (
      target.matches(
        '.filter__dropdown--city input.subregion'
      ) ||
      target.matches(
        '.filter__dropdown--city .locality-checkbox'
      )
    ) {
      const sub = target;
      const regionId = sub.getAttribute(
        'data-subregion-regionkey'
      );

      if (regionId && dropdownCity) {
        const regionCheckbox = dropdownCity.querySelector(
          `.city-checkbox[value="${regionId}"]`
        );
        // ak je zaškrtnutý okres, zapni aj príslušný kraj
        if (regionCheckbox && sub.checked) {
          regionCheckbox.checked = true;
        }
      }

      if (typeof clearAllGoogleSearchWhenChecked === 'function') {
        clearAllGoogleSearchWhenChecked();
      }

      if (typeof viewCheckedLocality === 'function') {
        const allLocalityCheckboxes = dropdownCity.querySelectorAll(
          '.locality-checkbox'
        );
        viewCheckedLocality(allLocalityCheckboxes);
      }

      refreshRegionBlocks();
      return;
    }
  },
  true
);

/**
 * Inicializácia pri načítaní – aby sa hneď po load-e
 * ukázali všetky kraje s predvybranými okresmi (z $formData)
 */
if (dropdownCity) {
  refreshRegionBlocks();
}
