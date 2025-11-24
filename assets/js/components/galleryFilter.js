//  filtrovnání v rámci galerie
function galleryFilter() {
  $('.js-gallery-filter').on('click', function (e) {
    e.preventDefault();
    let galleryName = $(this).attr('href');
    let galleryWrap = $('.gallery__content .simplebar-content-wrapper');

    $('.js-gallery-filter').removeClass('is-active');
    $(this).addClass('is-active');

    galleryWrap.animate(
      {
        scrollLeft:
          $(galleryName).offset().left -
          galleryWrap.offset().left +
          galleryWrap.scrollLeft(),
      },
      300
    );
  });
}
