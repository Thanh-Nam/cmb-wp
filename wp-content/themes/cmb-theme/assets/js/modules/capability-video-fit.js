/**
 * modules/capability-video-fit.js - CMB Theme
 * Trang "Phim giới thiệu năng lực" (page-phim-gioi-thieu-nang-luc.php) — ép
 * section nội dung (#capability-video-content) vừa khít 1 màn hình: header +
 * hero + section này không được vượt quá chiều cao viewport, để lúc xem video
 * không thấy footer lấp ló bên dưới.
 *
 * 2 trường hợp:
 *  - Nội dung tự nhiên CAO HƠN 1 màn hình (video theo tỉ lệ 16:9 render ra quá
 *    cao) → co chiều cao khung video lại đúng bằng phần dư ra, video tự thu
 *    hẹp chiều rộng theo (giữ tỉ lệ), sidebar bên trái tự thấp theo (stretch).
 *  - Nội dung tự nhiên THẤP HƠN 1 màn hình → giãn min-height của section để
 *    lấp đầy phần còn thiếu, nội dung được căn giữa dọc (xem CSS
 *    justify-content: center), footer luôn nằm ngay dưới mép màn hình.
 *
 * Tính bằng JS (đo vị trí/kích thước thật) thay vì đoán số cố định — vì chiều
 * cao header/hero/video thay đổi tuỳ màn hình, tuỳ nội dung admin nhập.
 */

'use strict';

(function initCapabilityVideoFit() {
  var section = document.getElementById('capability-video-content');
  if (!section) return;

  var videoEl = section.querySelector('.p-video-player, .p-video-embed');
  var MIN_VIDEO_HEIGHT = 160;

  function fit() {
    section.style.minHeight = '';
    if (videoEl) {
      videoEl.style.height = '';
      videoEl.style.width = '';
    }

    var sectionRect = section.getBoundingClientRect();
    var overflow = sectionRect.bottom - window.innerHeight;

    if (overflow > 0 && videoEl) {
      var videoHeight = videoEl.getBoundingClientRect().height;
      var newHeight = Math.max(MIN_VIDEO_HEIGHT, videoHeight - overflow);
      videoEl.style.height = newHeight + 'px';
      videoEl.style.width = 'auto';
    } else {
      var target = window.innerHeight - sectionRect.top;
      if (target > 0) section.style.minHeight = target + 'px';
    }
  }

  fit();
  window.addEventListener('load', fit);

  var resizeTimer = null;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(fit, 150);
  });
})();
