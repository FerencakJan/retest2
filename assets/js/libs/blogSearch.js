document.onreadystatechange = function () {
  function eventCheckboxHandler(event) {
    event.preventDefault();

    let checkboxElement = document.getElementById(
      event.target.getAttribute('data-id')
    );

    checkboxElement.checked = !checkboxElement.checked;

    if (checkboxElement.checked) {
      event.target.classList.add('is-open');
    } else {
      event.target.classList.remove('is-open');
    }

    let subCategoryCheckbox = document.getElementsByClassName(
      'blog-category-checkbox'
    );
    for (let i = 0; i < subCategoryCheckbox.length; i++) {
      if (
        subCategoryCheckbox.hasOwnProperty(i) &&
        subCategoryCheckbox[i] !== checkboxElement
      ) {
        subCategoryCheckbox[i].checked = false;
      }
    }

    document.getElementById('blog-search-form').submit();
  }

  function eventSelectHandler() {
    document.getElementById('blog-search-form').submit();
  }

  if (document.readyState == 'interactive') {
    let subCategoryCheckbox = document.getElementsByClassName(
      'blog-category-checkbox'
    );
    for (let i = 0; i < subCategoryCheckbox.length; i++) {
      if (
        subCategoryCheckbox.hasOwnProperty(i) &&
        subCategoryCheckbox[i].checked
      ) {
        let button = document.querySelector(
          '[data-id="' + subCategoryCheckbox[i].id + '"]'
        );
        button.classList.add('is-open');
      }
    }

    let subCateogoryButtons = document.getElementsByClassName(
      'blog-category-button'
    );
    for (let i = 0; i < subCateogoryButtons.length; i++) {
      if (subCateogoryButtons.hasOwnProperty(i)) {
        subCateogoryButtons[i].addEventListener('click', eventCheckboxHandler);
      }
    }

    let blogSearchOrderBy = document.getElementsByClassName('select-items');
    for (let i = 0; i < blogSearchOrderBy.length; i++) {
      blogSearchOrderBy[i].addEventListener('click', eventSelectHandler);
    }
  }
};
