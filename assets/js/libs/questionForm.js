let contactSubmit = {
  submitButton: $('#question_submit'),
  fullNameInput: $('#question_fullName'),
  phoneInput: $('#question_phoneNumber'),
  phoneNumberError: $('#phone-number-error'),
  phoneError: false,

  init: function () {
    this.form = this.submitButton.closest('form');
    toastr.options.positionClass = 'toast-bottom-center';

    let app = this;
    this.submitButton.on('click', function (event) {
      if (app.form[0].checkValidity()) {
        event.preventDefault();
        if (!app.phoneError && app.checkPhoneInput()) {
          $('.error-messages').remove();
          app.submitForm(app.form.serialize());
        }
      }
    });

    this.fullNameInput.keyup(function () {
      $(this).val($(this).val().replace(/\d+/, ''));
    });
    this.phoneInput.keyup(function () {
      $(this).val($(this).val().replace(/\D/g, '').replace('420', ''));
      if ($(this).val().length > 9) {
        app.phoneNumberError.removeClass('is-hidden');
        app.phoneError = true;
      } else {
        app.phoneNumberError.addClass('is-hidden');
        app.phoneError = false;
      }
    });
  },
  submitForm: function (serializedForm) {
    let app = this;
    $.ajax({
      url: this.form.attr('action'),
      method: 'POST',
      data: serializedForm,
      beforeSend: function () {
        app.disableButton(true);
      },
      success: function (response) {
        if (response.ok) {
          app.showMessage(response.message, 'success');
        } else {
          app.showMessage(response.message, 'warning');
        }
      },
      error: function (response) {
        app.showMessage(response.responseJSON.message, 'error');
      },
      complete: function (jqXHR, status) {
        app.enableButton();
        app.form[0].reset();
      },
    });
  },
  showMessage: function (message, type) {
    toastr[type](message);
  },
  disableButton: function () {
    this.submitButton.attr('disabled', 'disabled');
  },
  enableButton: function () {
    this.submitButton.attr('disabled', false);
  },
  checkPhoneInput: function () {
    if (this.phoneInput.val().length !== 9) {
      this.phoneNumberError.removeClass('is-hidden');
      this.phoneError = true;

      return false;
    } else {
      this.phoneNumberError.addClass('is-hidden');
      this.phoneError = false;

      return true;
    }
  },
};

$(function () {
  contactSubmit.init();
});
