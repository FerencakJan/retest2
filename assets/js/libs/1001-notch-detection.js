function notchDetection() {
  document.body.classList.remove('js-not-notch');
  document.body.classList.add('js-notch');
  if (parseInt(window.getComputedStyle(document.querySelectorAll('.container')[0]).paddingLeft) <= 0) {
    document.body.classList.add('js-not-notch');
    document.body.classList.remove('js-notch');
  }
}

$(document).ready(function(){
  notchDetection();
});

window.addEventListener("orientationchange", function() {
  notchDetection();
}, false);

$(window).on('resize', function () {
  notchDetection();
});
