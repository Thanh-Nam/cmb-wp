/**
 * modules/news-swiper.js - CMB Theme
 * News featured Swiper — lazy init khi vào viewport
 */

'use strict';

(function initNewsSwiper() {
  window.CMB_lazyInit('.p-news-columns__featured-swiper', function (el) {
    new Swiper(el, {
      loop: true,
      speed: 800,
      autoplay: { delay: 5000, disableOnInteraction: false },
      pagination: {
        el: '.p-news-columns__featured-pagination',
        clickable: true,
      },
      effect: 'fade',
      fadeEffect: { crossFade: true },
    });
  }, '150px');
})();
