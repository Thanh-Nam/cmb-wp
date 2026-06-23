/**
 * modules/field-swiper.js - CMB Theme
 * Fields of Operation Swiper coverflow — lazy init khi vào viewport
 */

'use strict';

(function initFieldSwiper() {
  window.CMB_lazyInit('.p-field__swiper', function (el) {
    new Swiper(el, {
      effect: 'coverflow',
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: 'auto',
      initialSlide: 1,
      speed: 500,
      coverflowEffect: {
        rotate: 40,
        stretch: 0,
        depth: 280,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: '.p-field__pagination',
        clickable: true,
      },
      on: {
        afterInit: function (swiper) {
          requestAnimationFrame(function () { swiper.update(); });
        },
      },
      breakpoints: {
        0: {
          coverflowEffect: {
            rotate: 25,
            stretch: 0,
            depth: 140,
            modifier: 1,
            slideShadows: false,
          },
        },
        1024: {
          coverflowEffect: {
            rotate: 40,
            stretch: 0,
            depth: 280,
            modifier: 1,
            slideShadows: false,
          },
        },
      },
    });
  }, '150px');
})();
