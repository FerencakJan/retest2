function getCookie(cname) {
  var name = cname + '=';
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1);
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return '';
}

var favouriteProperties = [];
var favouritedCookie = getCookie('favorites');

if (favouritedCookie !== '') {
  favouriteProperties = JSON.parse(favouritedCookie);
}

function toggleFavorite(advertId, element) {
  var index = favouriteProperties.indexOf(advertId);
  if (index !== -1) {
    favouriteProperties.splice(index, 1);
  } else {
    favouriteProperties.push(advertId);
  }
  document.cookie =
    'favorites=' + JSON.stringify(favouriteProperties) + '; path=/';
  $('.favorites-count').text(favouriteProperties.length);
  if (element.classList.contains('is-active')) {
    element.classList.remove('is-active');
  } else {
    element.classList.add('is-active');
  }
}
