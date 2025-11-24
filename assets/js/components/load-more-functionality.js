function initializeLoadMoreEstates() {
  const wrapper = document.querySelector('#estate-wrapper');
  const loadMoreButton = document.querySelector('#load-estates');

  if (wrapper && loadMoreButton) {
    loadMoreButton.addEventListener('click', function (e) {
      e.preventDefault();

      const type = wrapper.getAttribute('data-type');
      const id = Number(wrapper.getAttribute('data-id'));
      const page = Number(wrapper.getAttribute('data-page'));

      fetch(`/api/estates?id=${id}&type=${type}&page=${page}`)
        .then((res) => res.json())
        .then((res) => {
          wrapper.setAttribute('data-page', page + 1);
          wrapper.insertAdjacentHTML('beforeend', res.rows);

          $('.estate__next__gallery:not(.slick-initialized)').slick({
            infinite: true,
            slidesToScroll: 1,
            lazyLoad: 'ondemand',
            dots: false,
            arrows: false,
            fade: false,
            autoplay: true,
          });

          if (!res.haveNext) {
            loadMoreButton.classList.add('js-hidden');
          }
        });
    });
  }
}

function initializeLoadMoreBrokers() {
  const wrapper = document.querySelector('#broker-wrapper');
  const loadMoreButton = document.querySelector('#load-brokers');

  if (wrapper && loadMoreButton) {
    loadMoreButton.addEventListener('click', function (e) {
      e.preventDefault();

      const id = Number(wrapper.getAttribute('data-id'));
      const page = Number(wrapper.getAttribute('data-page'));

      fetch(`/api/brokers?id=${id}&page=${page}`)
        .then((res) => res.json())
        .then((res) => {
          wrapper.setAttribute('data-page', page + 1);
          wrapper.insertAdjacentHTML('beforeend', res.rows);

          if (!res.haveNext) {
            loadMoreButton.classList.add('js-hidden');
          }
        });
    });
  }
}
