/**
 * modules/partner-marquee.js - CMB Theme
 * Section "Khách hàng - Đối tác" (marquee logo chạy vô hạn) — PHP chỉ render
 * sẵn đủ số bộ logo để phủ kín màn hình desktop thông thường (~1920px), giữ
 * DOM gọn cho mobile/tablet/desktop thường. Trên màn hình rộng hơn (đặc biệt
 * >1920px khi .l-container bỏ giới hạn max-width — xem _info.scss/
 * _partner.scss), track có thể không đủ dài để phủ kín khung nhìn trong lúc
 * chạy loop, gây lộ khoảng trống. Module này đo chiều rộng thực tế lúc
 * runtime (chính xác hơn assumption cứng của PHP vì biết đúng viewport thật
 * của khách) và tự nhân bản thêm logo nếu thiếu — chỉ thêm, không bao giờ
 * bớt (thừa vài bộ khi resize nhỏ lại vẫn an toàn, chỉ là DOM dư, không lỗi
 * hiển thị).
 */

'use strict';

(function initPartnerMarquee() {
  var rowsList = document.querySelectorAll('.p-partner__rows');
  if (!rowsList.length) return;

  function ensureCoverage(rowsEl) {
    var copies = parseInt(getComputedStyle(rowsEl).getPropertyValue('--marquee-copies'), 10) || 2;

    rowsEl.querySelectorAll('.p-partner__track-wrap').forEach(function (wrap) {
      // Bỏ qua track đang ẩn (VD: track mobile khi đang ở desktop, và ngược
      // lại) — offsetParent null nghĩa là display:none, đo lúc này sẽ ra 0,
      // dẫn tới tính sai nhu cầu nhân bản.
      if (!wrap.offsetParent) return;

      var track = wrap.querySelector('.p-partner__track');
      if (!track) return;

      var items = Array.prototype.slice.call(track.children);
      var setLength = Math.round(items.length / copies);
      if (!setLength) return;

      var totalTrackWidth = track.scrollWidth;
      var perSetWidth = totalTrackWidth / copies;
      if (!perSetWidth) return;

      var viewportWidth = wrap.getBoundingClientRect().width;
      // +1 bộ dự phòng để chắc chắn phủ kín trong toàn bộ chu kỳ dịch chuyển.
      var neededCopies = Math.max(copies, Math.ceil(viewportWidth / perSetWidth) + 1);
      neededCopies = Math.min(neededCopies, 20); // chặn trên, tránh phình DOM bất thường

      if (neededCopies <= copies) return;

      var setNodes = items.slice(0, setLength);
      for (var c = copies; c < neededCopies; c++) {
        setNodes.forEach(function (node) {
          var clone = node.cloneNode(true);
          clone.setAttribute('aria-hidden', 'true');
          track.appendChild(clone);
        });
      }

      rowsEl.style.setProperty('--marquee-copies', neededCopies);
    });
  }

  function recalcAll() {
    rowsList.forEach(ensureCoverage);
  }

  recalcAll();

  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(recalcAll, 200);
  });
})();
