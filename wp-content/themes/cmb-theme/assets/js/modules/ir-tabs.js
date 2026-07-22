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

/**
 * Year filter — mỗi panel lọc riêng các nhóm .p-ir-timeline__group theo năm.
 */
(function initIRYearFilter() {
  var selects = document.querySelectorAll('[data-ir-year-filter]');
  if (!selects.length) return;

  selects.forEach(function (select) {
    var panel = select.closest('.p-ir-panel');
    if (!panel) return;
    var groups = panel.querySelectorAll('.p-ir-timeline__group');

    select.addEventListener('change', function () {
      var year = this.value;
      groups.forEach(function (group) {
        var match = !year || group.getAttribute('data-year') === year;
        group.style.display = match ? '' : 'none';
      });
    });
  });
})();
