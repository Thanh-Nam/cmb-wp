/**
 * modules/video-profile-sync.js - CMB Theme
 * Đồng bộ chiều cao 2 khung "Video giới thiệu" và "Hồ sơ năng lực" (nằm ngang
 * cạnh nhau trên desktop) — lấy chiều cao lớn hơn trong 2 khung, set làm
 * min-height cho cả 2 để 2 box luôn ngang bằng nhau, nhìn cân đối.
 *
 * Chỉ chỉnh min-height của khung NGOÀI (card), không đụng vào nội dung bên
 * trong (video hoặc plugin DearFlip) — mỗi bên vẫn tự render kích thước thật
 * của nó, tránh lặp lại sự cố ép chiều cao vào chính khung PDF trước đây.
 */

'use strict';

(function initVideoProfileSync() {
  var videoCard = document.getElementById('video-intro-card');
  var bookCard = document.getElementById('profile-book-card');
  if (!videoCard || !bookCard) return;

  var MD_BREAKPOINT = 1024; // khớp @include md trong _mixins.scss

  function isRowLayout() {
    return window.innerWidth >= MD_BREAKPOINT;
  }

  function syncHeight() {
    videoCard.style.minHeight = '';
    bookCard.style.minHeight = '';

    if (!isRowLayout()) return;

    var h = Math.max(videoCard.offsetHeight, bookCard.offsetHeight);
    if (h > 0) {
      videoCard.style.minHeight = h + 'px';
      bookCard.style.minHeight = h + 'px';
    }
  }

  syncHeight();
  window.addEventListener('load', syncHeight);

  // DearFlip (plugin hiển thị PDF) load ảnh trang PDF không đồng bộ nên chiều
  // cao thật của khung có thể thay đổi sau khi trang đã load xong — đo lại vài
  // lần trong 3s đầu để bắt kịp, sau đó dừng hẳn.
  [500, 1000, 1800, 3000].forEach(function (delay) {
    setTimeout(syncHeight, delay);
  });

  var resizeTimer = null;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(syncHeight, 150);
  });
})();
