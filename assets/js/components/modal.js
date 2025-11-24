// modal okna
function modal() {
  // otevření okna
  $('.js-modal-open').on('click', function () {
    // zjištění ID okna z atributu rel
    let modalName = $(this).attr('href');

    // otevření okna
    $(modalName).addClass('is-open');
    $('body').addClass('is-unscrollable');
  });

  // zavření modal okna
  $('.js-modal-close').on('click', function (event) {
    event.preventDefault();
    $(this).closest('.modal').removeClass('is-open');
    $('body').removeClass('is-unscrollable');
  });

  // zavření okna kliknutím na pozadí
  $('.modal').on('click', function (event) {
    event.preventDefault();
    $(this).removeClass('is-open');
    $('body').removeClass('is-unscrollable');
  });

  // zamezení zavření po kliknutí na tělo modalu
  $('.modal__body').on('click', function (event) {
    event.stopPropagation();
  });
}
