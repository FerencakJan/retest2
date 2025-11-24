function initializeCustomSelect(customSelectElement) {
  const selElmnt = customSelectElement.getElementsByTagName("select")[0];

  let selectedElement = customSelectElement.querySelector('.select-selected')
  if (selectedElement) {
    customSelectElement.removeChild(selectedElement);
  }

  // if(!selectedElement){
  selectedElement = document.createElement("DIV");
  selectedElement.setAttribute("class", "select-selected");
  selectedElement.id = selElmnt.options[selElmnt.selectedIndex].id;

  customSelectElement.appendChild(selectedElement);

  selectedElement.addEventListener("click", function (e) {
    /*when the select box is clicked, close any other select boxes,
    and open/close the current select box:*/
    e.stopPropagation();
    closeAllSelect(this);
    this.nextSibling.classList.toggle("select-hide");
    this.classList.toggle("select-arrow-active");
    this.parentNode.classList.toggle("select-arrow-active");
    
    let selectItems = this.nextSibling.querySelectorAll('.select-item'),
        selectItemsWidth = [];
    for (const selectItem of selectItems) {
      selectItem.style.display = 'inline-block';
      selectItemsWidth.push(selectItem.offsetWidth);
    }
    //this.nextSibling.style.width = Math.max(...selectItemsWidth) + 'px';
    for (const selectItem of selectItems) {
      selectItem.style.display = '';
    }
  });
  // }

  let itemsHolder = customSelectElement.querySelector('.select-items');
  if (itemsHolder) {
    customSelectElement.removeChild(itemsHolder);
  }

  itemsHolder = document.createElement("DIV");
  itemsHolder.setAttribute("class", "select-items select-hide");

  let reseted = false;
  for (let j = 0; j < selElmnt.length; j++) {
    // if(!selElmnt.options[j].hasAttribute('disabled')) {
    if (!selElmnt.options[j].disabled) {
      const c = document.createElement("DIV");

      c.setAttribute("class", "select-item");
      c.id = selElmnt.options[j].id;
      c.innerHTML = selElmnt.options[j].innerHTML;

      c.addEventListener("click", function (e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h;
        if (this.parentNode.classList.contains('simplebar-content')) {
          s = this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.getElementsByTagName("select")[0];
        } else {
          s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        }
        if (this.parentNode.classList.contains('simplebar-content')) {
          h = this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.previousSibling;
        } else {
          h = this.parentNode.previousSibling;
        }

        for (i = 0; i < s.length; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.id = this.id;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            for (k = 0; k < y.length; k++) {
              y[k].classList.remove("same-as-selected");
            }
            this.classList.add('same-as-selected');
            break;
          }
        }

        h.click();
        s.dispatchEvent(new Event('change'));
      });
      itemsHolder.appendChild(c);
    } else {
      if (selElmnt.options[j].selected) {
        selElmnt.options[j].selected = false;
        selElmnt.selectedIndex = -1;
        reseted = true;

      }
    }
  }

  if (reseted) {
    for (let x = 0; x < selElmnt.length; x++) {
      if (!selElmnt.options[x].disabled || (selElmnt.options[x].disabled && selElmnt.options[x].value === '')) {
        selElmnt.selectedIndex = x;
        selElmnt.options[x].selected = true;
        break;
      }
    }
  }

  if (selElmnt.selectedIndex !== -1) {
    selectedElement.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  }

  customSelectElement.appendChild(itemsHolder);
}

const customSelects = document.getElementsByClassName("custom-select");
for (let i = 0; i < customSelects.length; i++) {
  initializeCustomSelect(customSelects[i]);
  new SimpleBar(document.querySelectorAll('.select-items')[i], {
    autoHide: false
  });
}

function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
      y[i].parentNode.classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}

/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);
