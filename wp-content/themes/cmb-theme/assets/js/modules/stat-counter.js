/**
 * modules/stat-counter.js - CMB Theme
 * Count-up animation khi scroll vào viewport
 */

'use strict';

(function () {
  var numbers = document.querySelectorAll('.p-info__stat-number');
  if (!numbers.length || !('IntersectionObserver' in window)) return;

  function countUp(el) {
    var raw = el.textContent.trim();
    var target = parseInt(raw, 10);
    var padLen = raw.length;
    var duration = 1800;
    var startTime = performance.now();

    function tick(now) {
      var elapsed = now - startTime;
      var progress = Math.min(elapsed / duration, 1);
      // easeOutQuart
      var ease = 1 - Math.pow(1 - progress, 4);
      var current = Math.round(ease * target);
      el.textContent = String(current).padStart(padLen, '0');
      if (progress < 1) requestAnimationFrame(tick);
    }

    requestAnimationFrame(tick);
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          countUp(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 }
  );

  numbers.forEach(function (el) { observer.observe(el); });
})();
