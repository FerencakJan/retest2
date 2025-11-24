function canEditValue(value) {
  var starredCookie = getCookie('starred');
  if (starredCookie !== '') {
    var saved = JSON.parse(starredCookie);

    return saved.indexOf(value) === -1;
  }

  return true;
}

function addStarValue(value) {
  var starredCookie = getCookie('starred');
  var values = [];
  if (starredCookie !== '') {
    values = JSON.parse(starredCookie);
  }

  values.push(value);

  var date = new Date();
  date.setTime(date.getTime() + 730 * 24 * 60 * 60 * 1000);
  expires = '; expires=' + date.toUTCString();

  document.cookie = 'starred=' + JSON.stringify(values) + expires + '; path=/';
}

function initializeStarsElement(element) {
  const ratingElement = element.querySelector('.rating__stars');

  const avg = Number(ratingElement.getAttribute('data-stars-avg'));
  const count = Number(ratingElement.getAttribute('data-stars-cnt'));
  const value = Number(ratingElement.getAttribute('data-score-value'));

  const advertId = ratingElement.getAttribute('data-advert-id');
  const scoreType = ratingElement.getAttribute('data-score-type');

  let enterMode = false;

  const showStarsValue = (currentValue, enableHalf = true) => {
    const starsVals = ['blank', 'blank', 'blank', 'blank', 'blank'];
    for (let i = 0; i < currentValue; i++) {
      starsVals[i] = 'full';
    }
    if (enableHalf && avg - Math.floor(currentValue) > 0.5) {
      starsVals[Math.floor(currentValue)] = 'half';
    }

    const stars = element.querySelectorAll('.icon');
    stars.forEach(function (star, index) {
      star.classList.remove('icon--icon-star-blank');
      star.classList.remove('icon--icon-star-half');
      star.classList.remove('icon--icon-star-full');

      star.classList.add(`icon--icon-star-${starsVals[index]}`);
      star
        .querySelector('use')
        .setAttribute('xlink:href', `#icon-star-${starsVals[index]}`);
    });
  };

  if (!canEditValue(advertId)) {
    return false;
  } else {
    element.classList.remove('box-rating--canrate');

    addStarValue(advertId);
  }

  ratingElement.addEventListener('mouseenter', function () {
    console.log(true);
    if (element.classList.has('box-rating--canrate')) {
      enterMode = true;
    }
  });

  ratingElement.addEventListener('mouseleave', function () {
    console.log(false);
    enterMode = false;
    showStarsValue(avg);
  });

  element.querySelectorAll('.icon').forEach(function (star) {
    star.addEventListener('mouseenter', function (element) {
      const starElement = event.target;
      const index = Number(starElement.getAttribute('data-value'));

      showStarsValue(index + 1, false);
    });

    star.addEventListener('click', function (event) {
      const starElement = event.target;

      var value = Number(starElement.getAttribute('data-value')) + 1;

      if (!canEditValue(advertId)) {
        return false;
      } else {
        element.classList.remove('box-rating--canrate');
        addStarValue(id);
      }

      var formData = new FormData();
      formData.append('form[form_type]', '6');
      formData.append('form[secure_code]', '730405/2459');
      formData.append('form[score_type]', scoreType);
      formData.append('form[score_value]', advertId);
      formData.append('form[score]', value);

      $.ajax({
        type: 'POST',
        url: '/mlift/services/save_form.php',
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
          if (typeof data === 'string') {
            if (data[0] !== '{') {
              data = data.substr(1);
            }

            data = JSON.parse(data);
          }

          if (data.form.score.avg != null) {
            setStarValue(element, data.form.score.avg, data.form.score.cnt);
          }

          showFlashMessage(data.form.status, data.form.message);
        },
      });
    });
  });
}
