function windowHeight() {
  document.getElementsByTagName('body')[0].style.minHeight = '100%';

  let height = Math.max(
    document.body.scrollHeight, document.documentElement.scrollHeight,
    document.body.offsetHeight, document.documentElement.offsetHeight,
    document.body.clientHeight, document.documentElement.clientHeight
  );

  document.getElementsByTagName('body')[0].style.minHeight = height + 'px';
}

$(document).ready(function(){
  windowHeight();
});

window.addEventListener("orientationchange", function() {
  windowHeight();
}, false);

$(window).on('resize', function () {
  windowHeight();
});
