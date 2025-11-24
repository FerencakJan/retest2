$('.social-share-button').click(function (e) {
  e.preventDefault();
  let path = window.location.href;
  window.location.href = $(this).attr('href') + path;
});
