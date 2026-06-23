/**
 * modules/news-filter.js - CMB Theme
 * News AJAX filter với pagination (archive tin tức)
 */

'use strict';

(function initNewsFilter() {
  var filterWrap = document.getElementById('news-filters');
  if (!filterWrap) return;

  var catSelect = document.getElementById('filter-category');
  var sortSelect = document.getElementById('filter-sort');
  var list = document.getElementById('news-all-list');
  var pagination = document.getElementById('news-all-pagination');
  if (!catSelect || !sortSelect || !list) return;

  var nonce = filterWrap.dataset.nonce;
  var ajaxUrl = filterWrap.dataset.ajax;
  var isLoading = false;
  var curPage = 1;

  function fetchNews(page) {
    if (isLoading) return;
    isLoading = true;
    curPage = page || 1;

    list.style.opacity = '0.5';
    list.style.pointerEvents = 'none';

    var body = new FormData();
    body.append('action', 'cmb_filter_news');
    body.append('nonce', nonce);
    body.append('category', catSelect.value);
    body.append('sort', sortSelect.value);
    body.append('paged', curPage);

    fetch(ajaxUrl, { method: 'POST', body: body })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          list.innerHTML = data.data.html;
          if (pagination) {
            pagination.innerHTML = data.data.pagination;
          }
        }
      })
      .catch(function () { })
      .finally(function () {
        list.style.opacity = '';
        list.style.pointerEvents = '';
        isLoading = false;
      });
  }

  // Filter selects
  [catSelect, sortSelect].forEach(function (sel) {
    sel.addEventListener('change', function () { fetchNews(1); });
  });

  // Pagination clicks — handles both initial <a> links and AJAX <button data-paged>
  if (pagination) {
    pagination.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-paged]');
      if (btn) {
        e.preventDefault();
        var page = parseInt(btn.dataset.paged, 10);
        if (page) fetchNews(page);
        return;
      }
      // Initial PHP <a> pagination — intercept if filters are active
      var link = e.target.closest('a.p-news-all__page-btn');
      if (link && (catSelect.value || sortSelect.value !== 'newest')) {
        e.preventDefault();
        var match = link.href.match(/paged=(\d+)/);
        var page = match ? parseInt(match[1], 10) : 1;
        fetchNews(page);
      }
    });
  }
})();
