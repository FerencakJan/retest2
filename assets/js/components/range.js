function range() {
  const slider = document.getElementById('rangeslider');
  const rangeFrom = document.getElementById('rangeslider-from');
  const rangeFromFull = document.getElementById('f-rangeslider-from');
  const rangeTo = document.getElementById('rangeslider-to');
  const rangeToFull = document.getElementById('f-rangeslider-to');
  let rangeFromValue = rangeFrom.value;
  if (rangeFromValue === undefined || rangeFromValue === null) {
    rangeFromValue = 1000000;
  }
  let rangeToValue = rangeTo.value;
  if (rangeToValue == undefined || rangeToValue === null) {
    rangeToValue = 20000000;
  }

  noUiSlider.create(slider, {
    start: [rangeFromValue, rangeToValue],
    step: 1000,
    connect: true,
    range: {
      min: 0,
      max: 30000000,
    },
  });

  slider.noUiSlider.on('update', function (values, handle) {
    var value = values[handle];

    if (handle) {
      rangeTo.value = parseInt(value);
      rangeToFull.value = parseInt(value);
      viewRangePrice(rangeFrom, rangeTo);
    } else {
      rangeFrom.value = parseInt(value);
      rangeFromFull.value = parseInt(value);
      viewRangePrice(rangeFrom, rangeTo);
    }
  });

  rangeFrom.addEventListener('change', function () {
    slider.noUiSlider.set([this.value, null]);
  });

  rangeTo.addEventListener('change', function () {
    slider.noUiSlider.set([null, this.value]);
  });
}
