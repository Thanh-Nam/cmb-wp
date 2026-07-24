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

    function applyFilter(filter) {
      var visibleIndex = 0;

      cards.forEach(function (card) {
        var show = filter === 'all' || card.dataset.category === filter;

        if (show) {
          visibleIndex++;
          card.classList.remove('is-hidden');

          // First visible card gets the featured (large) slot
          if (visibleIndex === 1) {
            card.classList.add('p-project__card--featured');
          } else {
            card.classList.remove('p-project__card--featured');
          }

          // Re-number
          var numEl = card.querySelector('.p-project__card-num');
          if (numEl) {
            var pad = visibleIndex < 10 ? '0' + visibleIndex : '' + visibleIndex;
            numEl.innerHTML = pad + '<span class="p-project__card-num-dot">.</span>';
          }
        } else {
          card.classList.add('is-hidden');
          card.classList.remove('p-project__card--featured');
        }
      });
    }

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        this.classList.add('is-active');
        this.setAttribute('aria-selected', 'true');
        applyFilter(this.dataset.filter);
      });
    });
  })();


  // ============================================
  // ARCHIVE version: .p-projects-filter__tab + #projects-grid (AJAX) + view toggle
  // Lọc bằng AJAX (không phải ẩn/hiện client-side) vì mỗi tab cần tính lại
  // đúng số dự án/số trang thực tế theo lĩnh vực để phân trang không bị sai
  // (VD: tab ít/không có dự án vẫn hiện phân trang như lúc xem "Tất cả").
  // ============================================
  (function initProjectsArchiveFilter() {
    var tabs = document.querySelectorAll('.p-projects-filter__tab');
    var grid = document.getElementById('projects-grid');
    var pagination = document.getElementById('projects-pagination');

    if (tabs.length && grid) {
      var nonce = grid.dataset.nonce;
      var ajaxUrl = (window.CMB_Ajax && window.CMB_Ajax.url) || '/wp-admin/admin-ajax.php';
      var isLoading = false;
      var currentFilter = 'all';

      function fetchProjects(filter, page) {
        if (isLoading) return;
        isLoading = true;
        currentFilter = filter;

        grid.style.opacity = '0.5';
        grid.style.pointerEvents = 'none';

        var body = new FormData();
        body.append('action', 'cmb_filter_projects');
        body.append('nonce', nonce);
        body.append('category', filter);
        body.append('paged', page || 1);

        fetch(ajaxUrl, { method: 'POST', body: body })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.success) {
              grid.innerHTML = data.data.html;
              if (pagination) {
                pagination.innerHTML = data.data.pagination;
              }
              if (window.CMB_revealObserver) {
                grid.querySelectorAll('[data-reveal]').forEach(function (el) {
                  window.CMB_revealObserver.observe(el);
                });
              }
            }
          })
          .catch(function () { })
          .finally(function () {
            grid.style.opacity = '';
            grid.style.pointerEvents = '';
            isLoading = false;
          });
      }

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          tabs.forEach(function (t) {
            t.classList.remove('is-active');
            t.setAttribute('aria-selected', 'false');
          });
          tab.classList.add('is-active');
          tab.setAttribute('aria-selected', 'true');

          fetchProjects(tab.getAttribute('data-filter'), 1);
        });
      });

      // Pagination clicks — AJAX <button data-paged> (kết quả trả về từ filter)
      // hoặc <a> phân trang gốc lúc PHP render lần đầu (chỉ khi đang lọc theo
      // 1 lĩnh vực cụ thể, để tính đúng lại số dự án/số trang theo lĩnh vực đó).
      if (pagination) {
        pagination.addEventListener('click', function (e) {
          var btn = e.target.closest('[data-paged]');
          if (btn) {
            e.preventDefault();
            var page = parseInt(btn.dataset.paged, 10);
            if (page) fetchProjects(currentFilter, page);
            return;
          }
          var link = e.target.closest('a.p-projects-list__page-btn');
          if (link && currentFilter !== 'all') {
            e.preventDefault();
            var match = link.href.match(/paged=(\d+)/);
            var page2 = match ? parseInt(match[1], 10) : 1;
            fetchProjects(currentFilter, page2);
          }
        });
      }
    }

    // View toggle (grid / list)
    var gridBtn = document.getElementById('view-grid-btn');
    var listBtn = document.getElementById('view-list-btn');

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
