function gooogleMapsInit() {
  var addressLines = document.getElementsByClassName('address-autocomplete');
  for (var i = 0; i < addressLines.length; i++) {
    setCallback(addressLines[i]);
  }
}

function setCallback(element) {
  var autocomplete = new window.google.maps.places.Autocomplete(element, {
    types: ['geocode'],
    componentRestrictions: { country: language },
  });
  var callbackFunction = function () {
    var place = this.getPlace();
    var parent = $(element).closest('div');
    const localityInputMain = document.getElementById('locality-input');
    const localityInputFull = document.getElementById('locality-input-full');

    var fa = place.formatted_address ? place.formatted_address : '';
    parent.find("[name='sql[locality][locality][city]']").val(fa);
    // upravit inputy
    if (fa !== '') {
      localityInputMain.innerHTML = fa;
      localityInputFull.innerHTML = fa;
    }

    var type = place.types && place.types[0] ? place.types[0] : '';
    parent.find("[name='sql[locality][locality][types]']").val(type);

    if (place.address_components) {
      for (var i = 0; i < place.address_components.length; i++) {
        if (place.address_components[i].types[0] == 'postal_code') {
          parent
            .find("[name='sql[locality][locality][zip_code]']")
            .val(place.address_components[i].long_name);
        }
      }
    } else {
      parent.find("[name='sql[locality][locality][zip_code]']").val('');
    }

    var locationLat = parent.find("[name='sql[locality][location][lat]']");
    var locationLng = parent.find("[name='sql[locality][location][lng]']");
    try {
      locationLat.val(place.geometry.location.lat());
      locationLng.val(place.geometry.location.lng());
    } catch (e) {
      locationLat.val('');
      locationLng.val('');
    }

    var viewPortSouht = parent.find("[name='sql[locality][viewport][south]']");
    var viewPortWest = parent.find("[name='sql[locality][viewport][west]']");
    var viewPortNorth = parent.find("[name='sql[locality][viewport][north]']");
    var viewPortEast = parent.find("[name='sql[locality][viewport][east]']");
    try {
      var southEast = place.geometry.viewport.getSouthWest();
      viewPortSouht.val(southEast.lat());
      viewPortWest.val(southEast.lng());
      var northEast = place.geometry.viewport.getNorthEast();
      viewPortNorth.val(northEast.lat());
      viewPortEast.val(northEast.lng());
    } catch (e) {
      viewPortSouht.val('');
      viewPortWest.val('');
      viewPortNorth.val('');
      viewPortEast.val('');
    }
    return false;
  };
  autocomplete.addListener('place_changed', callbackFunction);
}
