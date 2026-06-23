/**
 * modules/ir-tabs.js - CMB Theme
 * Investor Relations tabs (quan-he-co-dong)
 */

'use strict';

(function initIRTabs() {
  var tabs = document.querySelectorAll('.p-ir-tabs__link');
  var panels = document.querySelectorAll('.p-ir-panel');
  if (!tabs.length) return;

  tabs.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var targetId = this.getAttribute('data-target');

      tabs.forEach(function (b) {
        b.classList.remove('is-active');
        b.setAttribute('aria-selected', 'false');
      });
      this.classList.add('is-active');
      this.setAttribute('aria-selected', 'true');

      panels.forEach(function (panel) {
        panel.classList.remove('is-active');
      });
      var target = document.getElementById(targetId);
      if (target) target.classList.add('is-active');
    });
  });
})();
