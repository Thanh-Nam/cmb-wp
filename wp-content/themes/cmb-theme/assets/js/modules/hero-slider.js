/**
 * modules/hero-slider.js - CMB Theme
 * Hero banner Swiper — khởi tạo lazy khi section vào viewport
 */

'use strict';

(function initHeroSlider() {
  window.CMB_lazyInit('.p-hero__slider', function (el) {
    new Swiper(el, {
      loop: true,
      speed: 1000,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.p-hero__pagination',
        clickable: true,
      },
      effect: 'fade',
      fadeEffect: {
        crossFade: true
      }
    });
  }, '0px');
})();
