$(document).ready(function () {
  // obecne
  var bodyWidth = window.innerWidth;

  // události při resize okna
  $(window).on('resize', function () {
    // osetreni, zda se velikost zmenila
    if (bodyWidth !== window.innerWidth) {
      // nastavíme novou šířku
      bodyWidth = window.innerWidth;
      // zresetuj menu
      resetMenu();
      // zavři filtrování
      resetFilter();
    }
  });

  // mobilní menu
  function switchMenu() {
    // označíme zda je menu zavřeno či nikoliv
    if ($('.nav-switcher').hasClass('is-open')) {
      $('.nav-switcher, .nav').removeClass('is-open');
      $('.header').removeClass('is-nav-open');
      $('body').removeClass('is-unscrollable');
    } else {
      $('.nav-switcher, .nav').addClass('is-open');
      $('.header').addClass('is-nav-open');
      $('.filter__dropdown').removeClass('is-open');
      $('.filter__wrap').removeClass('is-active');
      $('body').addClass('is-unscrollable');
    }
  }
  // při změně rozlišení resetujeme menu
  function resetMenu() {
    $('.nav-switcher, .nav').removeClass('is-open');
    $('.header').removeClass('is-nav-open');
    $('body').removeClass('is-unscrollable');
  }
  // reset filtrování
  function resetFilter() {
    $('.filter__dropdown').removeClass('is-open');
    $('.filter__wrap').removeClass('is-active');
  }

  document.addEventListener('click', resetFilter);

  // spouštěč
  $('.nav-switcher').on('click', function () {
    switchMenu();
  });

  // události při načtení stránky
  $(window).on('load', function () {
    // AOS fix pro první načítání
    // AOS.refresh();
    if ($('.offer-item__back').length) {
      for (let i = 0; i < $('.offer-item__back').length; i++) {
        new SimpleBar($('.offer-item__back')[i], {
          autoHide: true,
        });
      }
    }
    if ($('.gallery__content').length) {
      for (let i = 0; i < $('.gallery__content').length; i++) {
        new SimpleBar($('.gallery__content')[i], {
          autoHide: false,
        });
      }
    }
    // if ($(".filter__dropdown-checkbox-wrap").length) {
    //   for (let i = 0; i < $(".filter__dropdown-checkbox-wrap").length; i++) {
    //     //console.log(i);
    //     new SimpleBar($(".filter__dropdown-checkbox-wrap")[i], {
    //       autoHide: true,
    //     });
    //   }
    // }
    // if ($('.filter__dropdown-property-wrap').length) {
    //   for (let i = 0; i < $('.filter__dropdown-property-wrap').length; i++) {
    //     new SimpleBar($('.filter__dropdown-property-wrap')[i], {
    //       autoHide: false,
    //     });
    //   }
    // }
    if ($('.gallery__menu-wrap').length) {
      for (let i = 0; i < $('.gallery__menu-wrap').length; i++) {
        new SimpleBar($('.gallery__menu-wrap')[i], {
          autoHide: false,
        });
      }
    }
    // if ($('.filter__dropdown-inside').length) {
    //   for (let i = 0; i < $('.filter__dropdown-inside').length; i++) {
    //     new SimpleBar($('.filter__dropdown-inside')[i], {
    //       autoHide: false,
    //     });
    //   }
    // }
  });

  $('.async-form').each(function (i, element) {
    var $element = $(element);
    initAjaxForm($element);
  });
  $('.async-form').each(function (i, element) {
    var $element = $(element);
    initAjaxForm($element);
  });

  // události při scroolování
  $(window).on('scroll', function () {
    //...
  });

  // modal okna
  if ($('.modal').length) {
    modal();
    for (let i = 0; i < $('.modal__body').length; i++) {
      new SimpleBar($('.modal__body')[i], {
        autoHide: false,
      });
    }
    // fix na simplebar element (custom-select) uvnitř simplebaru (modalu, který používá simplebar)
    const customSelects = document.getElementsByClassName('custom-select');
    for (let i = 0; i < customSelects.length; i++) {
      initializeCustomSelect(customSelects[i]);
      new SimpleBar(document.querySelectorAll('.select-items')[i], {
        autoHide: false,
      });
    }
  }

  if ($('.header--front').length) {
    $(window).scroll(function () {
      let $header = $('.header--front');
      $scrollHeight = $('.search').offset().top;
      if ($(window).scrollTop() > $scrollHeight) {
        $header.addClass('header--fixed');
      } else {
        $header.removeClass('header--fixed');
      }
    });
  }

  if ($('.slider').length) {
    $('.slider').slick({
      lazyLoad: 'ondemand',
      slidesToShow: 4,
      slidesToScroll: 1,
      arrows: true,
      prevArrow: $('.slider-nav-prev'),
      nextArrow: $('.slider-nav-next'),
      dots: false,
      infinite: true,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 3,
          },
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 574,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });
  }

  if ($('.slider-2').length) {
    $('.slider-2').slick({
      lazyLoad: 'ondemand',
      slidesToShow: 2,
      slidesToScroll: 1,
      arrows: true,
      prevArrow: $('.slider-nav-prev-2'),
      nextArrow: $('.slider-nav-next-2'),
      dots: false,
      infinite: true,
      responsive: [
        {
          breakpoint: 574,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });
  }

  // youtube video v modálním okně
  if ($('.js-video-start').length) {
    //new ModalVideo('.js-video-start', { channel: 'youtube' });
    $('.js-video-start').modalVideo({
      youtube: {
        controls: 1,
        autoplay: 1,
      },
    });
  }

  // galerie
  if ($('.js-gallery').length) {
    $('.js-gallery a').simpleLightbox({
      showCounter: false,
    });
  }

  // galerie filtrování
  if ($('.js-gallery-filter').length) {
    galleryFilter();
  }

  // pin kliknutí přidání activní classy
  if ($('.pin').length) {
    $('.pin').on('click', function () {
      $('.pin').removeClass('is-open');
      $(this).addClass('is-open');
    });
  }

  if ($('.js-property-close-btn').length) {
    $('.js-property-close-btn').on('click', function () {
      $('.filter__dropdown').removeClass('is-open');
      $('.filter__wrap').removeClass('is-active');
    });
  }

  // filter dropdown
  if ($('.filter').length) {
    $('.filter__dropdown-top').on('click', function (e) {
      closeAllSelect();
      e.stopPropagation();
      if ($(this).closest('.filter__dropdown').hasClass('is-open')) {
        $('.filter__dropdown').removeClass('is-open');
        $('.filter__wrap').removeClass('is-active');
        if ($(this).closest('.filter').hasClass('filter--location')) {
          $(this).closest('.filter').addClass('is-selected');
        }
        if ($(this).closest('.filter').hasClass('filter--simple')) {
          $(this).closest('.filter').addClass('is-selected');
        }
      } else {
        $('.filter__dropdown').removeClass('is-open');
        $(this).closest('.filter__dropdown').addClass('is-open');
        if ($(this).closest('.filter__wrap').length) {
          $(this)
            .closest('.filter__wrap')
            .animate(
              {
                scrollLeft:
                  $(this).offset().left -
                  $(this).closest('.filter__wrap').offset().left +
                  $(this).closest('.filter__wrap').scrollLeft(),
              },
              300
            );
        }
        $('.filter__wrap').addClass('is-active');
        if ($('.iframe-map').length) {
          $('.iframe-map').removeClass('is-active');
        }
      }
      if (
        $(this).closest('.filter__dropdown').hasClass('filter__dropdown--city')
      ) {
        $(this).closest('.filter__dropdown').addClass('is-selected');
      }
    });

    $('.filter__dropdown-inside').on('click', function (e) {
      e.stopPropagation();
    });
  }

  // property
  const properties = document.getElementsByClassName('property');
  const filterDropdowns = document.getElementsByClassName(
    'filter__dropdown-checkbox-wrap'
  );
  if (properties.length) {
    properties.forEach(function (property) {
      property.addEventListener('click', (event) => {
        let regionId = event.currentTarget.dataset.region;
        properties.forEach(function (item) {
          item.classList.remove('is-active');
        });
        event.currentTarget.classList.add('is-active');

        //set proper region id
        let tmpRegionId = 0;
        if (event.currentTarget.dataset.region !== undefined) {
          tmpRegionId = event.currentTarget.dataset.region;
        } else {
          tmpRegionId = event.currentTarget.dataset.regionF;
        }

        filterDropdowns.forEach(function (filterDropdown) {
          filterDropdown.classList.add('is-hidden');
        });

        let mainWraps = document.querySelectorAll(
          '[class^="filter__dropdown-checkbox-wrap"][data-region="' +
            tmpRegionId +
            '"]'
        );
        mainWraps[0].classList.remove('is-hidden');
        let fullWraps = document.querySelectorAll(
          '[class^="filter__dropdown-checkbox-wrap"][data-region-f="' +
            tmpRegionId +
            '"]'
        );
        fullWraps[0].classList.remove('is-hidden');
      });
    });

    $('.filter__dropdown-inside').on('click', function (e) {
      e.stopPropagation();
    });
  }

  // cenové rozpětí slider
  if ($('.range').length) {
    range();
  }

  // mapa a activní scrollování v rámci mapy
  if ($('.iframe-map').length) {
    $('.iframe-map').on('click', function () {
      $(this).addClass('is-active');
      resetFilter();
    });
  }

  // slider minigalerie
  if ($('.estate__gallery').length) {
    $('.estate__gallery').slick({
      infinite: true,
      slidesToScroll: 1,
      lazyLoad: 'ondemand',
      dots: true,
      arrows: true,
      fade: false,
      autoplay: false,
    });
  }

  // slider minigalerie
  if ($('.estate__next__gallery').length) {
    $('.estate__next__gallery').slick({
      infinite: true,
      slidesToScroll: 1,
      lazyLoad: 'ondemand',
      dots: false,
      arrows: false,
      fade: false,
      autoplay: true,
    });
  }

  // vlastnosti nemovitosti zobrazit více / méně
  if ($('.js-show-properties').length) {
    $('.js-show-properties').on('click', function () {
      if ($(this).hasClass('is-active')) {
        $(this).removeClass('is-active');
        $(this).next('.properties__more').slideUp(300);
      } else {
        $(this).addClass('is-active');
        $(this).next('.properties__more').slideDown(300);
      }
    });
  }

  // číst dále
  if ($('.js-read-more').length) {
    $('.js-read-more').on('click', function () {
      var $content = $(this).parent().prev('.read-more');
      if ($(this).hasClass('is-active')) {
        $(this).removeClass('is-active');
        $content.animate({ height: Number($content.attr('data-height')) }, 300);
        $content.removeClass('is-active');
      } else {
        $(this).addClass('is-active');
        $content.attr('data-height', $content.outerHeight());
        $content.animate({ height: $content[0].scrollHeight }, 300);
        $content.addClass('is-active');
      }
    });
  }

  // uložiž jako poptávku
  if ($('#checkbox-save-request').length) {
    $('#checkbox-save-request').on('click', function () {
      if ($(this).prop('checked') == true) {
        $('.js-save-request').slideDown(100);
      } else {
        $('.js-save-request').slideUp(100);
      }
    });
  }

  // zobrazit telefonní číslo
  if ($('.hidden-phone-number').length) {
    phoneNumber();
  }

  //favorites
  var favouriteProperties = [];
  var favouritedCookie = getCookie('favorites');

  if (favouritedCookie !== '') {
    favouriteProperties = JSON.parse(favouritedCookie);
  }
  $('.favorites-count').text(favouriteProperties.length);

  //iniciace mapy

  if ($('#js-map').length > 0) {
    initMapBox();
  }

  // wrap okolo mapy, teprve až po kliknutí na mapu je možné scrollovat v mapě
  if ($('.map-wrap').length > 0) {
    $('.map-wrap').on('click', function () {
      $(this).addClass('is-active');
    });
  }

  // $(".cookie-save-input-value").change(function() {
  //   console.log('------');
  //   var element = $(this);
  //   console.log(element);
  //   var date = new Date();
  //   date.setFullYear(date.getFullYear() + 10);
  //   document.cookie = element.attr("name") + '=' + encodeURI(element.val()) +'; path=/; expires=' + date.toGMTString();
  //
  //   if(element.attr("name") == "listing-sort")
  //   {
  //     var form = $("#listing-form");
  //     if(form){
  //       form.submit();
  //     }
  //     location.reload();
  //   }
  // });

  // ajax request pro listing detail
  if (document.getElementById('listing-form')) {
    // set detail button to send ajax request
    const linkButtons = document.getElementsByClassName('detail-link');
    const mainListing = document.getElementById('mainListing');
    const originUrl = window.location.pathname;
    const originTitle = document.title;
    linkButtons.forEach(function (link) {
      link.addEventListener('click', (event) => {
        event.preventDefault();
        let advertId = link.dataset.linkId;
        $.ajax(files + '/api/property.php?propertyId=' + advertId).success(
          function (data) {
            window.history.pushState(
              { propertyId: advertId },
              $('title').text(),
              data.setUrl
            );
            trackChangeUrl();

            document.title = data.metatags.title;
            let newElement = document.createElement('div');
            newElement.innerHTML = data.propertyBlock;
            mainListing.classList.add('is-hidden');
            mainListing.parentNode.insertBefore(newElement, mainListing);
            window.scrollTo({ top: 0, left: 0, behavior: 'instant' });

            let backButton = document.getElementById('breadcrumbsBack');
            backButton.addEventListener('click', () => {
              newElement.remove();
              mainListing.classList.remove('is-hidden');
              document.title = originTitle;
              window.history.pushState(null, $('title').text(), originUrl);
              trackChangeUrl();
            });

            //init JS gallery
            if ($('.js-gallery').length) {
              $('.js-gallery a').simpleLightbox({
                showCounter: false,
              });
            }

            //init mapbox
            initMapBox();

            if (window.mapboxMap) {
              for (var i = 0; i < window.solvedMarkers.length; i++) {
                if (
                  window.solvedMarkers.hasOwnProperty(i) &&
                  window.solvedMarkers[i].advert_id == advertId
                ) {
                  window.mapboxMap.flyTo(window.solvedMarkers[i]._latlng, 17);
                  window.propertyMarkerToSelect = window.solvedMarkers[i];
                }
              }
            }
          }
        );
      });
    });
  }

  document.querySelectorAll('.rating').forEach(function (element) {
    initializeStarsElement(element);
  });

  initializeLoadMoreEstates();
  initializeLoadMoreBrokers();
});

// Číst další
$(document).on('click', '#js-show-details', function (e) {
  e.preventDefault(e);

  var $this = $(this),
    toggleText = $this.attr('data-text'),
    $details = $this.parents('.js-details'),
    $content = $details.children('.js-details__in');

  if ($this.hasClass('active')) {
    //pokud je jiz otevreny
    $content.animate(
      {
        height: Number($content.attr('data-height')),
      },
      300
    );
  } else {
    //pokud je schovany
    $content.attr('data-height', $content.outerHeight());
    $content.animate(
      {
        height: $content[0].scrollHeight,
      },
      300
    );
  }

  $this.toggleClass('active');
  $content.toggleClass('active');
  $this.attr('data-text', $this.text()).text(toggleText);
});

const locationFilter = document.querySelector('.cookie-save-input-value');
const listingForm = document.getElementById('listing-form');
if (locationFilter) {
  locationFilter.addEventListener('change', function (event) {
    let date = new Date();
    date.setFullYear(date.getFullYear() + 10);
    document.cookie =
      locationFilter.getAttribute('name') +
      '=' +
      encodeURI(locationFilter.value) +
      '; path=/; expires=' +
      date.toGMTString();

    if (locationFilter.getAttribute('name').includes('listing-sort')) {
      console.log(listingForm);
      if (listingForm) {
        listingForm.submit();
      }
    }
  });
}

function initAjaxForm($form) {
  $form.ajaxForm({
    beforeSubmit: function (arr, $form, options) {
      $('.form-status').remove();

      if (!checkAgreement($form)) {
        return false;
      }

      for (var i = 0; i < arr.length; i++) {
        if (arr[i].name == 'form[advert_id][]' && arr[i].value != '') {
          var parsed = JSON.parse(arr[i].value);
          for (var y = 0; y < parsed.length; y++) {
            arr.push({ name: 'form[advert_id][]', value: parsed[y] });
          }
          arr.splice(i, 1);
          break;
        }
      }
      for (var j = 0; j < formAttr.length; j++) {
        arr.push(formAttr[j]);
      }
      arr.push({ name: 'form[secure_code]', value: '730405/2459' });

      if ($form.hasClass('short-request')) {
        console.log('short');
        var values = $('#listing-form').serializeArray();
        for (var k = 0; k < values.length; k++) {
          arr.push(values[k]);
        }
      }
    },
    success: function (data, statusText, xhr, $form) {
      var parsedData = JSON.parse(data.trim());

      if (parsedData.form.modal == 0) {
        $popup = $('#js-popup');
        $popup.removeClass('opened');
        $('body').removeClass('overflow');
      }

      showFlashMessage(parsedData.form.status, parsedData.form.message);

      var style = '';
      switch (parsedData.form.status) {
        case 1:
          style = 'success';
          break;
        case 2:
          style = 'error';
          break;
      }

      $form
        .find('button[type=submit]')
        .before(
          '<p class="form-status ' +
            style +
            '">' +
            parsedData.form.message +
            '</p>'
        );

      if (
        typeof parsedData.replace !== 'undefined' &&
        typeof parsedData.replace.url !== 'undefined'
      ) {
        setTimeout(function () {
          window.location = parsedData.replace.url;
        }, 3000);
      }
    },
  });
}

function checkAgreement(form) {
  var $form = $(form);
  var reqInputs = $form.find('.agreement');
  for (var i = 0; i < reqInputs.length; i++) {
    if (reqInputs.hasOwnProperty(i) && reqInputs[i].checked == false) {
      var span = $(reqInputs[i]).parent().find('span');

      var message = 'Uděl ' + span.text().trim().toLowerCase() + '.';
      showFlashMessage(2, message);

      $form
        .find('button[type=submit]')
        .before('<p class="form-status error">' + message + '</p>');

      return false;
    }
  }

  return true;
}

function showFlashMessage(type, message) {
  var $message = $('#js-message');
  switch (type) {
    case 1:
      type = 'success';
      break;
    case 2:
      type = 'error';
      break;
    default:
      type = '';
      break;
  }
  $message
    .removeAttr('class')
    .addClass('window-message')
    .removeClass('success')
    .removeClass('error')
    .addClass(type)
    .stop()
    .slideDown(300);
  $message.find('p').text(message);
  setTimeout(function () {
    $message.stop().slideUp(300);
  }, 2000);
}

function trackChangeUrl() {
  try {
    ga('send', 'pageview', location.pathname);
  } catch (e) {}

  try {
    pp_gemius_hit(window.pp_gemius_identifier);
  } catch (e) {}

  console.log('tracking change url', location.pathname);
}
