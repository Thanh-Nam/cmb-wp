/**
 * modules/video-profile-sync.js - CMB Theme
 * Đồng bộ chiều cao khung "Hồ sơ năng lực" (.p-book-wrap) khớp đúng với chiều
 * cao thật của video bên cạnh (.p-video-profile-row), bằng cách đo trực tiếp
 * rồi set inline style — tránh dùng chuỗi flex/stretch CSS nhiều tầng (không
 * ổn định, dễ khiến khung sập chiều cao về 0).
 */

'use strict';

(function initVideoProfileSync() {
  var row = document.querySelector('.p-video-profile-row');
  if (!row) return;

  var videoBox = row.querySelector('.p-video-player, .p-video-embed');
  var wrap = document.getElementById('profile-book-wrap');
  if (!videoBox || !wrap) return;

  var MD_BREAKPOINT = 1024; // khớp @include md trong _mixins.scss — dưới mốc này 2 cột xếp chồng dọc

  function isRowLayout() {
    return window.innerWidth >= MD_BREAKPOINT;
  }

  function syncHeight() {
    if (!isRowLayout()) {
      wrap.style.height = '';
      return;
    }
    var h = videoBox.getBoundingClientRect().height;
    if (h > 0) wrap.style.height = h + 'px';
  }

  syncHeight();
  window.addEventListener('load', syncHeight);

  var resizeTimer = null;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(syncHeight, 150);
  });
})();
