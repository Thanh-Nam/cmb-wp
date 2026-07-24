/**
 * modules/ir-tabs-height-sync.js - CMB Theme
 * Trang Quan hệ cổ đông (archive-quan-he-co-dong.php) — ở mobile, 2 hàng tab
 * (4 tab trên + 4 pill dưới, không tính ô "Công bố thông tin") xếp lưới 2x2
 * (xem _quan-he-co-dong.scss) nên box nào label dài hơn (xuống 2 dòng) sẽ
 * cao hơn box còn lại trong cặp — ở desktop không bị vì cùng 1 hàng flex tự
 * stretch bằng nhau, chỉ mobile mới lệch vì tách thành nhiều hàng lưới độc lập.
 *
 * Đồng bộ bằng JS (đo chiều cao thật, lấy max) thay vì hardcode 1 con số cố
 * định — để mọi box chỉ to bằng đúng box cao nhất, không bị "phồng" thêm so
 * với nội dung thật.
 */

'use strict';

(function initIrTabsHeightSync() {
  var tabsNav = document.getElementById('ir-tabs');
  if (!tabsNav) return;

  var SP_BREAKPOINT = 767; // khớp @include sp trong _mixins.scss

  function isMobile() {
    return window.innerWidth <= SP_BREAKPOINT;
  }

  function syncGroup(items) {
    items.forEach(function (el) { el.style.minHeight = ''; });
    if (!isMobile() || items.length < 2) return;

    var max = 0;
    items.forEach(function (el) {
      if (el.offsetHeight > max) max = el.offsetHeight;
    });
    if (max > 0) {
      items.forEach(function (el) { el.style.minHeight = max + 'px'; });
    }
  }

  function syncAll() {
    syncGroup(Array.prototype.slice.call(
      tabsNav.querySelectorAll('.p-ir-tabs__list:not(.p-ir-tabs__list--pill) .p-ir-tabs__link')
    ));
    syncGroup(Array.prototype.slice.call(
      tabsNav.querySelectorAll('.p-ir-tabs__link--pill:not(.p-ir-tabs__link--header)')
    ));
  }

  syncAll();
  window.addEventListener('load', syncAll);

  var resizeTimer = null;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(syncAll, 150);
  });
})();
