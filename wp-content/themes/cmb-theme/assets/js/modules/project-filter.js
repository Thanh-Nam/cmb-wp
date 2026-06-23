/**
 * modules/project-filter.js - CMB Theme
 * Project filter tabs — hoạt động cho cả homepage và archive/du-an.
 * Mỗi version check element tồn tại trước khi chạy.
 */

'use strict';

(function () {

  // ============================================
  // HOMEPAGE version: .p-project__tab + .p-project__card
  // ============================================
  (function initProjectFilter() {
    var tabs = document.querySelectorAll('.p-project__tab');
    var cards = document.querySelectorAll('.p-project__card');
    if (!tabs.length || !cards.length) return;

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var filter = this.dataset.filter;

        tabs.forEach(function (t) {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        this.classList.add('is-active');
        this.setAttribute('aria-selected', 'true');

        cards.forEach(function (card) {
          if (filter === 'all' || card.dataset.category === filter) {
            card.classList.remove('is-hidden');
          } else {
            card.classList.add('is-hidden');
          }
        });
      });
    });
  })();


  // ============================================
  // ARCHIVE version: .p-projects-filter__tab + .p-projects-card + view toggle
  // ============================================
  (function initProjectsArchiveFilter() {
    var tabs = document.querySelectorAll('.p-projects-filter__tab');
    var cards = document.querySelectorAll('.p-projects-card[data-category]');

    if (tabs.length) {
      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          tabs.forEach(function (t) {
            t.classList.remove('is-active');
            t.setAttribute('aria-selected', 'false');
          });
          tab.classList.add('is-active');
          tab.setAttribute('aria-selected', 'true');

          var filter = tab.getAttribute('data-filter');
          cards.forEach(function (card) {
            if (filter === 'all' || card.getAttribute('data-category') === filter) {
              card.style.display = '';
            } else {
              card.style.display = 'none';
            }
          });
        });
      });
    }

    // View toggle (grid / list)
    var gridBtn = document.getElementById('view-grid-btn');
    var listBtn = document.getElementById('view-list-btn');
    var grid = document.getElementById('projects-grid');

    if (gridBtn && listBtn && grid) {
      gridBtn.addEventListener('click', function () {
        gridBtn.classList.add('is-active');
        gridBtn.setAttribute('aria-pressed', 'true');
        listBtn.classList.remove('is-active');
        listBtn.setAttribute('aria-pressed', 'false');
        grid.classList.remove('is-row-view');
      });

      listBtn.addEventListener('click', function () {
        listBtn.classList.add('is-active');
        listBtn.setAttribute('aria-pressed', 'true');
        gridBtn.classList.remove('is-active');
        gridBtn.setAttribute('aria-pressed', 'false');
        grid.classList.add('is-row-view');
      });
    }
  })();

})();
