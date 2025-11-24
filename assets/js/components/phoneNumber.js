function phoneNumber() {
  $('.hidden-phone-number').on('click', function () {
    showPhoneNumber(this);
  });

  function showPhoneNumber(element, sendResponse) {
    var $element = $(element);
    var phone = $element.data('phone');
    var advertId = $element.data('advert-id');
    $element.attr('href', 'tel:' + phone);
    $element.text(phone);
    $element.attr('onclick', '');

    if (typeof sendResponse == 'undefined') {
      $.ajax({
        url: '/mlift/services/save_form.php',
        method: 'POST',
        data: {
          form: {
            form_type: 1,
            show_tel: 1,
            advert_id: advertId,
            secure_code: '730405/2459',
          },
        },
      });

      var otherNumbers = $('.hidden-phone-number');
      otherNumbers.each(function (i, element) {
        showPhoneNumber(element, false);
      });
    }

    console.log($element);
    if ($element.parent().hasClass('track-phone-number')) {
      trackShowNumber();
    }

    return false;
  }

  function trackShowNumber() {
    try {
      ga('send', 'event', 'detail', 'show-contact', 'show-contact', 1);
    } catch (e) {}

    console.log('tracking show contact');
  }
}
