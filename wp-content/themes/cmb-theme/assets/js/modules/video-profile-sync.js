/**
 * modules/video-profile-sync.js - CMB Theme
 * Đồng bộ chiều cao 2 khung "Video giới thiệu" và "Hồ sơ năng lực" (nằm ngang
 * cạnh nhau trên desktop) — lấy chiều cao lớn hơn trong 2 khung, set làm
 * min-height cho cả 2 để 2 box luôn ngang bằng nhau, nhìn cân đối.
 *
 * Chỉ chỉnh min-height của khung NGOÀI (card), không đụng vào nội dung bên
 * trong (video hoặc plugin DearFlip) — mỗi bên vẫn tự render kích thước thật
 * của nó, tránh lặp lại sự cố ép chiều cao vào chính khung PDF trước đây.
 *
 * DearFlip (plugin PDF) đổi chiều cao thật của nó không đồng bộ — với file PDF
 * lớn, việc "chốt" kích thước cuối cùng có thể xảy ra bất kỳ lúc nào (không có
 * mốc thời gian cố định), nên dùng ResizeObserver theo dõi trực tiếp khung PDF
 * thay vì đoán vài mốc thời gian cụ thể — bắt được thay đổi dù xảy ra muộn.
 */

'use strict';

(function initVideoProfileSync() {
  var videoCard = document.getElementById('video-intro-card');
  var bookCard = document.getElementById('profile-book-card');
  var bookWrap = document.getElementById('profile-book-wrap');
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

  var syncTimer = null;
  function scheduleSync() {
    clearTimeout(syncTimer);
    syncTimer = setTimeout(syncHeight, 150);
  }

  window.addEventListener('resize', scheduleSync);

  // Theo dõi trực tiếp khung PDF — bắt được mọi thay đổi kích thước bất kể xảy
  // ra lúc nào (DearFlip render xong trang, load thêm trang, đổi chế độ xem...).
  if (bookWrap && window.ResizeObserver) {
    var ro = new ResizeObserver(scheduleSync);
    ro.observe(bookWrap);
  } else {
    // Trình duyệt cũ không có ResizeObserver — dự phòng bằng vài mốc thời gian.
    [500, 1000, 1800, 3000, 5000, 8000].forEach(function (delay) {
      setTimeout(syncHeight, delay);
    });
  }
})();
