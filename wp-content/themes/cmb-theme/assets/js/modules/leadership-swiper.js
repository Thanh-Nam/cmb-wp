/**
 * modules/leadership-swiper.js - CMB Theme
 * Leadership Swiper — lazy init khi #leadership-swiper vào viewport
 */

'use strict';

(function initLeadershipSwiper() {
  window.CMB_lazyInit('#leadership-swiper', function (el) {
    // observer/observeParents: IntersectionObserver ('150px' rootMargin) có thể
    // init Swiper trước khi container đạt kích thước thật (ảnh/font chưa load
    // xong) — gây sai slidesPerView/height lúc đầu, chỉ "tự đúng" khi có resize
    // tình cờ xảy ra sau đó. Bật observer để Swiper tự update khi kích thước
    // DOM thay đổi (ảnh load xong, font load xong, v.v.) thay vì chỉ dựa vào
    // window resize.
    var swiper = new Swiper(el, {
      loop: true,
      speed: 600,
      slidesPerView: 1,
      spaceBetween: 24,
      observer: true,
      observeParents: true,
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

    // Ép update lại sau khi toàn bộ ảnh trong slider load xong, phòng trường
    // hợp observer không bắt kịp (ảnh cache/onload chạy trước khi observer gắn).
    el.querySelectorAll('img').forEach(function (img) {
      if (!img.complete) {
        img.addEventListener('load', function () { swiper.update(); }, { once: true });
      }
    });
  }, '150px');
})();
