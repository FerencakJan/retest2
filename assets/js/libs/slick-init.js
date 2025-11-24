$(document).ready(function () {
  $('.carousel--partner--slider').slick({
    infinite: true,
    slidesToScroll: 1,
    dots: false,
    arrows: false,
    fade: true,
    autoplay: true,
    autoplaySpeed: 2000,
  });

  let time = 7;
  let $bar, $slick, isPause, tick, percentTime;
  $slick = $('.carousel--slider');
  $slick.on('init', function (event, slick) {
    $('.slider-progress--slide--max').html('0' + slick.slideCount);
  });
  $slick.slick({
    dots: false,
    pauseOnDotsHover: true,
    mobileFirst: true,
    infinite: true,
    arrows: false,
    // fade: true,
    draggable: true,
  });
  $bar = $('.slider-progress .progress');
  $('.homepage-header--slider--text').on({
    mouseenter: function () {
      isPause = true;
    },
    mouseleave: function () {
      isPause = false;
    },
  });
  $slick.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
    // startProgressbar();
    $('.slider-progress--slide--current').html('0' + (nextSlide + 1));
  });
  function startProgressbar() {
    resetProgressbar();
    percentTime = 0;
    isPause = false;
    tick = setInterval(interval, 10);
  }
  function interval() {
    if (isPause === false) {
      percentTime += 1 / (time + 0.1);
      $bar.css({
        width: percentTime + '%',
      });
      if (percentTime >= 100) {
        $slick.slick('slickNext');
        startProgressbar();
      }
    }
  }
  function resetProgressbar() {
    $bar.css({
      width: 0 + '%',
    });
    clearTimeout(tick);
  }
  startProgressbar();
});
