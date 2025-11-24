const minusBtn = document.getElementById("minusBtn");
const plusBtn = document.getElementById("plusBtn");
const bidValue = document.getElementById("bidValue");
const step = 10000;

function getNumericValue(value) {
  const rawNumber = parseInt(value.replace(/[^\d]+/g, ""), 10);
  return isNaN(rawNumber) ? 10000 : rawNumber;
}

function formatWithSpaces(value) {
  return value.toLocaleString("cs-CZ") + " Kč";
}

function setFormattedValue(num) {
  bidValue.value = formatWithSpaces(num);
}

if (bidValue) {
  setFormattedValue(getNumericValue(bidValue.value));
}

if (minusBtn) {
  minusBtn.addEventListener("click", function () {
    let currentVal = getNumericValue(bidValue.value);
    currentVal -= step;
    if (currentVal < step) {
      currentVal = step;
    }
    setFormattedValue(currentVal);
  });
}

if (plusBtn) {
  plusBtn.addEventListener("click", function () {
    let currentVal = getNumericValue(bidValue.value);
    currentVal += step;
    setFormattedValue(currentVal);
  });
}

if (bidValue) {
  bidValue.addEventListener("blur", function () {
    setFormattedValue(getNumericValue(bidValue.value));
  });
}
