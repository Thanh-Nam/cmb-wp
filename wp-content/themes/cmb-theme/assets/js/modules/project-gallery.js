/**
 * modules/project-gallery.js - CMB Theme
 * Slider ảnh dự án (single-du-an) dùng Swiper (Thumbs + Navigation).
 * Bấm vào ảnh chính vẫn mở lightbox riêng (xem gallery-lightbox.js).
 */

'use strict';

(function initProjectGallerySlider() {
  var mainEl = document.getElementById('project-gallery');
  var thumbsEl = document.getElementById('project-gallery-thumbs');
  var prevBtn = document.getElementById('project-gallery-prev');
  var nextBtn = document.getElementById('project-gallery-next');
  if (!mainEl || typeof Swiper === 'undefined') return;

  var thumbsSwiper = thumbsEl ? new Swiper(thumbsEl, {
    spaceBetween: 10,
    watchSlidesProgress: true,
    breakpoints: {
      0: { slidesPerView: 3 },
      600: { slidesPerView: 4 },
      1024: { slidesPerView: 5 },
    },
  }) : null;

  new Swiper(mainEl, {
    spaceBetween: 12,
    navigation: {
      prevEl: prevBtn,
      nextEl: nextBtn,
    },
    thumbs: thumbsSwiper ? { swiper: thumbsSwiper } : undefined,
  });
})();
