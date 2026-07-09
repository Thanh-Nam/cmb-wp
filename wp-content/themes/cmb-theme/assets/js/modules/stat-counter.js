/**
 * modules/stat-counter.js - CMB Theme
 * Count-up animation khi scroll vào viewport
 */

'use strict';

(function () {
  if (!('IntersectionObserver' in window)) return;

  // Thêm dấu chấm phân cách hàng nghìn kiểu VN: 1000 -> "1.000"
  function formatThousands(n) {
    return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  // Tách prefix / số / suffix từ chuỗi như "35+", "100%", "300+", "1.000", "1.234,5"
  // Quy ước VN: "." là phân cách hàng nghìn, "," là phân cách thập phân
  function parseValue(raw) {
    var match = raw.match(/^([^\d]*)(\d[\d,.]*)([^\d]*)$/);
    if (!match) return null;

    var numStr = match[2];
    var decimals = 0;
    var useThousands = false;
    var num;

    if (numStr.indexOf(',') !== -1) {
      // Có dấu phẩy -> phần sau dấu phẩy cuối cùng là thập phân
      var commaParts = numStr.split(',');
      var decPart = commaParts.pop();
      decimals = decPart.length;
      num = parseFloat(commaParts.join('').replace(/\./g, '') + '.' + decPart);
      useThousands = true;
    } else if (numStr.indexOf('.') !== -1) {
      var dotParts = numStr.split('.');
      var allThreeDigits = true;
      for (var i = 1; i < dotParts.length; i++) {
        if (dotParts[i].length !== 3) { allThreeDigits = false; break; }
      }
      if (allThreeDigits) {
        // Các dấu chấm là phân cách hàng nghìn: "1.000" -> 1000
        num = parseFloat(dotParts.join(''));
        useThousands = true;
      } else if (dotParts.length === 2) {
        // Một dấu chấm, không phải nhóm 3 số -> số thập phân: "1.5" -> 1.5
        decimals = dotParts[1].length;
        num = parseFloat(numStr);
      } else {
        num = parseFloat(dotParts.join(''));
        useThousands = true;
      }
    } else {
      num = parseFloat(numStr);
    }

    return {
      prefix: match[1],
      num: num,
      decimals: decimals,
      useThousands: useThousands,
      suffix: match[3]
    };
  }

  function countUp(el, duration) {
    var raw    = el.textContent.trim();
    var parsed = parseValue(raw);
    if (!parsed || isNaN(parsed.num)) return;

    var target   = parsed.num;
    var decimals = parsed.decimals;
    duration = duration || 1600;
    var startTime = null;

    function tick(now) {
      if (!startTime) startTime = now;
      var elapsed  = now - startTime;
      var progress = Math.min(elapsed / duration, 1);
      var ease     = 1 - Math.pow(1 - progress, 4);
      var current  = ease * target;
      var display;
      if (decimals > 0) {
        display = current.toFixed(decimals).replace('.', ',');
        if (parsed.useThousands) {
          var parts = display.split(',');
          display = formatThousands(parseFloat(parts[0])) + ',' + parts[1];
        }
      } else {
        display = parsed.useThousands ? formatThousands(current) : Math.round(current);
      }
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
        var parsed = parseValue(raw);
        if (!parsed || isNaN(parsed.num)) return;
        var target    = parsed.num;
        var padLen    = raw.replace(/[^\d]/g, '').length;
        var duration  = 1800;
        var startTime = performance.now();

        function tick(now) {
          var elapsed  = now - startTime;
          var progress = Math.min(elapsed / duration, 1);
          var ease     = 1 - Math.pow(1 - progress, 4);
          var current  = Math.round(ease * target);
          var display  = parsed.useThousands ? formatThousands(current) : String(current).padStart(padLen, '0');
          el.textContent = parsed.prefix + display + parsed.suffix;
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
