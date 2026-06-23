/**
 * modules/leadership-swiper.js - CMB Theme
 * Leadership Swiper — lazy init khi #leadership-swiper vào viewport
 */

'use strict';

(function initLeadershipSwiper() {
  window.CMB_lazyInit('#leadership-swiper', function (el) {
    new Swiper(el, {
      loop: true,
      speed: 600,
      slidesPerView: 1,
      spaceBetween: 24,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      breakpoints: {
        600: { slidesPerView: 2, spaceBetween: 28 },
        900: { slidesPerView: 3, spaceBetween: 32 },
        1024: { slidesPerView: 4, spaceBetween: 36 },
      }
    });
  }, '150px');
})();
