/**
 * modules/history.js - CMB Theme
 * History Milestones (sliding window) + Ship Activation (RAF loop)
 * Ship RAF loop chỉ start khi section .p-history vào viewport
 */

'use strict';

(function () {

  // ============================================
  // HISTORY MILESTONES: Sliding window navigation
  // ============================================
  (function initHistoryMilestones() {
    var MILESTONES = (window.CMB_History && window.CMB_History.length)
      ? window.CMB_History
      : [
        { year: '1966', desc: 'Thành lập Đội khảo sát thiết kế (tiền thân của Công ty).' },
        { year: '1977', desc: 'Chuyển đổi mô hình thành Công ty Khảo sát Thiết kế Đường biển.' },
        { year: '1995', desc: 'Đổi tên thành Công ty tư vấn xây dựng công trình Hàng hải.' },
        { year: '1999', desc: 'Lập Quy hoạch tổng thể phát triển hệ thống cảng biển Việt Nam đến năm 2010 — Quy hoạch cảng biển đầu tiên của Việt Nam.' },
        { year: '2004', desc: 'Cổ phần hóa, hoạt động theo mô hình công ty cổ phần.' },
        { year: '2011', desc: 'Được trao tặng Huân chương Độc lập Hạng Ba.' },
        { year: '2021', desc: 'Lập Quy hoạch tổng thể phát triển hệ thống cảng biển Việt Nam thời kỳ 2021-2030, tầm nhìn đến năm 2050. Kỷ niệm 55 năm thành lập và được tặng Huân chương Độc lập Hạng Nhì.' },
        { year: '2023', desc: 'Lập Quy hoạch chi tiết nhóm cảng biển, bến cảng, cầu cảng, bến phao, khu nước, vùng nước thời kỳ 2021-2030, tầm nhìn đến năm 2050. Lập Quy hoạch phát triển hệ thống cảng cạn thời kỳ 2021-2030, tầm nhìn đến năm 2050.' },
      ];

    var slots = Array.from(document.querySelectorAll('.p-history__item'));
    var mobileList = document.getElementById('history-mobile-list');
    var btnPrev = document.getElementById('history-nav-prev');
    var btnNext = document.getElementById('history-nav-next');
    if (!slots.length || !btnPrev || !btnNext) return;

    var mobileListInitialized = false;

    // Slot 0 (1966) cố định — chỉ 5 slot còn lại mới cuộn
    var SCROLL_VISIBLE = slots.length - 1;
    var scrollSlots = slots.slice(1);
    var startIndex = 1;
    var prevStartIndex = 1;

    // Gán cố định slot 0
    slots[0].querySelector('.p-history__year').textContent = MILESTONES[0].year;
    slots[0].querySelector('.p-history__desc').textContent = MILESTONES[0].desc;

    // Desktop: cập nhật 5 slot cuộn
    function render() {
      var slidDir = startIndex - prevStartIndex;

      scrollSlots.forEach(function (slot, i) {
        var m = MILESTONES[startIndex + i];
        slot.querySelector('.p-history__year').textContent = m.year;
        slot.querySelector('.p-history__desc').textContent = m.desc;
      });

      if (slidDir !== 0) {
        var dirClass = slidDir > 0 ? 'is-sliding-next' : 'is-sliding-prev';
        scrollSlots.forEach(function (slot) {
          slot.classList.remove('is-sliding-next', 'is-sliding-prev');
          void slot.offsetWidth;
          slot.classList.add(dirClass);
          slot.addEventListener('animationend', function () { slot.classList.remove(dirClass); }, { once: true });
        });
      }

      prevStartIndex = startIndex;
      btnPrev.disabled = startIndex === 1;
      btnNext.disabled = startIndex + SCROLL_VISIBLE >= MILESTONES.length;

      renderMobileList();
    }

    // Mobile: hiển thị toàn bộ milestones theo dạng timeline dọc
    function renderMobileList() {
      if (!mobileList) return;

      var startEl = '<li class="p-history__mobile-start" role="presentation"><img src="assets/images/icon-banh-lai.png" alt="" class="p-history__mobile-compass" aria-hidden="true" /></li>';

      var bgTopEl = '<li class="p-history__mobile-bg-top" role="presentation" aria-hidden="true"><img src="assets/images/history-2.png" alt="" /></li>';
      var bgMiddleEl = '<li class="p-history__mobile-bg-middle" role="presentation" aria-hidden="true"><img src="assets/images/history-1.png" alt="" /></li>';

      var itemsHtml = MILESTONES.map(function (m, i) {
        return '<li class="p-history__mobile-item' + (i === 0 ? ' p-history__mobile-item--first' : '') + '" data-index="' + i + '"' + (!mobileListInitialized ? ' data-reveal="fade-left"' : '') + '>' +
          '<div class="p-history__mobile-icon-col"><img src="assets/images/banh-lai.svg" alt="" class="p-history__mobile-wheel" aria-hidden="true" /></div>' +
          '<div class="p-history__mobile-content"><h3 class="p-history__year">' + m.year + '</h3><p class="p-history__desc">' + m.desc + '</p></div>' +
          '</li>';
      }).join('');

      var endEl = '<li class="p-history__mobile-end" role="presentation" aria-hidden="true"><img src="assets/images/history-3.png" alt="" class="p-history__mobile-bg-end" aria-hidden="true" /><img src="assets/images/icon-hai-dang.png" alt="" class="p-history__mobile-lighthouse" /></li>';

      mobileList.innerHTML = startEl + bgTopEl + bgMiddleEl + itemsHtml + endEl;

      if (!mobileListInitialized && window.CMB_revealObserver) {
        mobileList.querySelectorAll('[data-reveal]').forEach(function (el) { window.CMB_revealObserver.observe(el); });
        mobileListInitialized = true;
      }
    }

    btnNext.addEventListener('click', function () {
      if (startIndex + SCROLL_VISIBLE >= MILESTONES.length) return;
      var remaining = MILESTONES.length - (startIndex + SCROLL_VISIBLE);
      if (remaining >= SCROLL_VISIBLE) {
        startIndex += SCROLL_VISIBLE;
      } else {
        startIndex = MILESTONES.length - SCROLL_VISIBLE;
      }
      render();
    });

    btnPrev.addEventListener('click', function () {
      if (startIndex === 1) return;
      startIndex = Math.max(1, startIndex - SCROLL_VISIBLE);
      render();
    });

    render();
  })();


  // ============================================
  // HISTORY SHIP → MILESTONE ACTIVATION
  // Ship RAF loop chỉ start khi section vào viewport
  // ============================================
  (function initHistoryShipActivation() {
    var section = document.querySelector('.p-history');
    var pathEl = document.getElementById('ship-route');
    var svgEl = document.querySelector('.p-history__ship-svg');
    var slots = Array.from(document.querySelectorAll('.p-history__item'));
    if (!pathEl || !svgEl || !slots.length) return;

    var DUR_S = 22; // seconds — phải khớp với dur="22s" trong animateMotion

    var COORDS = [
      { x: 67.5, y: 690.0 },
      { x: 167.3, y: 418.1 },
      { x: 539.1, y: 418.1 },
      { x: 877.4, y: 400.0 },
      { x: 1031.7, y: 163.8 },
      { x: 1407.2, y: 163.8 },
    ];

    var total = pathEl.getTotalLength();
    var STEPS = 800;
    var fractions = COORDS.map(function (coord) {
      var best = 0, bestDist = Infinity;
      for (var i = 0; i <= STEPS; i++) {
        var len = (i / STEPS) * total;
        var p = pathEl.getPointAtLength(len);
        var d = Math.pow(p.x - coord.x, 2) + Math.pow(p.y - coord.y, 2);
        if (d < bestDist) { bestDist = d; best = len; }
      }
      return best / total;
    });

    var rafStarted = false;

    function tick() {
      // Dùng SVG clock thay vì performance.now() để luôn sync với SMIL animateMotion
      var progress = (svgEl.getCurrentTime() % DUR_S) / DUR_S;

      var activeIndex = 0;
      for (var i = 0; i < fractions.length; i++) {
        if (progress >= fractions[i]) activeIndex = i;
      }

      slots.forEach(function (slot, i) {
        slot.classList.toggle('is-active', i === activeIndex);
      });

      requestAnimationFrame(tick);
    }

    function startRaf() {
      if (rafStarted) return;
      rafStarted = true;
      requestAnimationFrame(tick);
    }

    if (section && 'IntersectionObserver' in window) {
      var obs = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) {
          obs.disconnect();
          startRaf();
        }
      }, { rootMargin: '150px' });
      obs.observe(section);
    } else {
      startRaf();
    }
  })();

})();
