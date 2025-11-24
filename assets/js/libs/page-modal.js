$(document).ready(function () {
  function setCookie(key, value, expiry) {
    let expires = new Date();
    expires.setTime(expires.getTime() + expiry * 24 * 60 * 60 * 1000);
    document.cookie =
      key + '=' + value + ';expires=' + expires.toUTCString() + '; path=/';
  }

  function getCookie(key) {
    let keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
  }

  let saveCookie = false;
  let clickCloseOnExitButton = false;

  $('.page-modal-redirect').click(function (e) {
    if (saveCookie) {
      saveCookie = false;
      let redirectUrl = $(this).attr('href');
      if (redirectUrl !== '') {
        window.location = $(this).attr('href');
        return;
      } else {
        $('.page-modal-close-modal').trigger('click');
        return;
      }
    }

    e.preventDefault();
    let pageModalId = $(this).closest('.modal-popup').data('page-modal-id');
    saveCookie = true;
    setCookie(pageModalId, true, 365);
    $(this).trigger('click');
  });

  $('.page-modal-close-modal').click(function (e) {
    let pageModalId = $(this).closest('.modal-popup').data('page-modal-id');
    setCookie(pageModalId, true, 365);
  });

  $('.page-modal-close-exit-modal').click(function () {
    clickCloseOnExitButton = true;
  });

  $(document).mouseleave(function () {
    if (!clickCloseOnExitButton) {
      if ($('.page-modal-on-exit').length) {
        $('.modal-popup.is-open').removeClass('is-open');
      }
      $('.page-modal-on-exit').addClass('is-open');
    }
  });

  $('.page-modal-delay').each(function () {
    let that = this;
    let t = setTimeout(function () {
      $(that).addClass('is-open');
    }, 1000 * $(that).data('page-modal-delay'));
  });
});
