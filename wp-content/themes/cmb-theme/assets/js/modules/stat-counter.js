/**
 * modules/stat-counter.js - CMB Theme
 * Count-up animation khi scroll vào viewport
 */

'use strict';

(function () {
  if (!('IntersectionObserver' in window)) return;

  // Tách prefix / số / suffix từ chuỗi như "35+", "100%", "300+"
  function parseValue(raw) {
    var match = raw.match(/^([^\d]*)(\d[\d,.]*)([^\d]*)$/);
    if (!match) return null;
    return {
      prefix: match[1],
      num: parseFloat(match[2].replace(/,/g, '')),
      suffix: match[3]
    };
  }

  function countUp(el, duration) {
    var raw    = el.textContent.trim();
    var parsed = parseValue(raw);
    if (!parsed || isNaN(parsed.num)) return;

    var target   = parsed.num;
    var isFloat  = String(parsed.num).indexOf('.') !== -1;
    var decimals = isFloat ? (String(parsed.num).split('.')[1] || '').length : 0;
    duration = duration || 1600;
    var startTime = null;

    function tick(now) {
      if (!startTime) startTime = now;
      var elapsed  = now - startTime;
      var progress = Math.min(elapsed / duration, 1);
      var ease     = 1 - Math.pow(1 - progress, 4);
      var current  = ease * target;
      var display  = isFloat ? current.toFixed(decimals) : Math.round(current);
      el.textContent = parsed.prefix + display + parsed.suffix;
      if (progress < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  // ── Chung: observe item, thêm is-visible + chạy countup nếu có data-countup ──
  function observeItems(selector, threshold) {
    var items = document.querySelectorAll(selector);
    if (!items.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var item  = entry.target;
        var numEl = item.querySelector('[data-countup]');
        item.classList.add('is-visible');
        if (numEl) countUp(numEl, 1600);
        observer.unobserve(item);
      });
    }, { threshold: threshold || 0.25, rootMargin: '0px 0px -30px 0px' });

    items.forEach(function (el) { observer.observe(el); });
  }

  // ── p-info__stat-number (trang chủ) ───────────────────────────────────────
  function observeInfoNumbers() {
    var infoNumbers = document.querySelectorAll('.p-info__stat-number');
    if (!infoNumbers.length) return;

    var infoObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el     = entry.target;
        var raw    = el.textContent.trim();
        var target = parseInt(raw, 10);
        if (isNaN(target)) return;
        var padLen    = raw.length;
        var duration  = 1800;
        var startTime = performance.now();

        function tick(now) {
          var elapsed  = now - startTime;
          var progress = Math.min(elapsed / duration, 1);
          var ease     = 1 - Math.pow(1 - progress, 4);
          el.textContent = String(Math.round(ease * target)).padStart(padLen, '0');
          if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
        infoObserver.unobserve(el);
      });
    }, { threshold: 0.5 });

    infoNumbers.forEach(function (el) { infoObserver.observe(el); });
  }

  function startAll() {
    observeItems('.p-stats__item--anim', 0.25);
    observeItems('.p-projects-stats__item--anim', 0.25);
    observeItems('.p-project-infobar__item--anim', 0.2);
    observeInfoNumbers();
  }

  // Chờ preloader xong mới bắt đầu observe để tránh counter chạy trong lúc bị che
  if (document.getElementById('page-preloader')) {
    window.addEventListener('preloader:done', startAll, { once: true });
  } else {
    startAll();
  }
})();
