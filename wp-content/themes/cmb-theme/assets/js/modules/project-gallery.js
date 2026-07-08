/**
 * modules/project-gallery.js - CMB Theme
 * Nút "Xem tất cả hình ảnh" cho gallery dự án (single-du-an)
 */

'use strict';

(function initProjectGallery() {
  var gallery = document.getElementById('project-gallery');
  var btn = document.getElementById('btn-all-photos');
  if (!gallery || !btn) return;

  btn.addEventListener('click', function (e) {
    e.preventDefault();
    gallery.querySelectorAll('.is-hidden-extra').forEach(function (item) {
      item.classList.remove('is-hidden-extra');
    });
    btn.closest('.p-project-gallery__footer').remove();
  });
})();
