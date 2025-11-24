let bannerDesktop = $('#banner-desktop');
if (bannerDesktop.length) {
  let bannerLinks = bannerDesktop.data('href').split(';');
  let bannerDesktopValues = bannerDesktop.data('values').split(';');
  let time = bannerDesktop.data('time');
  let desktopPoint = 0;

  let changeDesktopText = function () {
    $('.show-desktop').animate(
      {
        opacity: 0,
      },
      400,
      function () {
        bannerDesktop.text(bannerDesktopValues[desktopPoint]);
        bannerDesktop.attr('href', bannerLinks[desktopPoint]);
        $('.show-desktop').animate({ opacity: 1 }, 400);
      }
    );
    if (desktopPoint < bannerDesktopValues.length - 1) {
      desktopPoint++;
    } else {
      desktopPoint = 0;
    }
  };

  setInterval(changeDesktopText, time * 1000);
}

let bannerMobile = $('#banner-mobile');
if (bannerMobile.length) {
  let bannerLinks = bannerDesktop.data('href').split(';');
  let bannerMobileValues = bannerMobile.data('values').split(';');
  let time = bannerMobile.data('time');
  let mobilePoint = 0;

  let changeMobileText = function () {
    bannerMobile.animate(
      {
        opacity: 0,
      },
      400,
      function () {
        bannerMobile.attr('href', bannerLinks[mobilePoint]);
        bannerMobile
          .text(bannerMobileValues[mobilePoint])
          .animate({ opacity: 1 }, 400);
      }
    );
    if (mobilePoint < bannerMobileValues.length - 1) {
      mobilePoint++;
    } else {
      mobilePoint = 0;
    }
  };

  setInterval(changeMobileText, time * 1000);
}
