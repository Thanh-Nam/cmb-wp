/**
 * modules/featured-swiper.js - CMB Theme
 * Featured projects Swiper for du-an archive — lazy init khi vào viewport
 */

'use strict';

(function initFeaturedSwiper() {
  window.CMB_lazyInit('#featured-swiper', function (el) {
    var section = el.closest('.p-projects-featured');
    new Swiper(el, {
      slidesPerView: 1,
      loop: true,
      speed: 600,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      grabCursor: true,
      navigation: {
        prevEl: section ? section.querySelector('.p-projects-featured__nav-btn--prev') : null,
        nextEl: section ? section.querySelector('.p-projects-featured__nav-btn--next') : null,
      },
    });
  }, '150px');
})();
