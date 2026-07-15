/**
 * modules/profile-book.js - CMB Theme
 * Hồ sơ năng lực — flipbook hiển thị & lật trực tiếp trên trang (không popup).
 * Render trực tiếp từ file PDF (pdf.js) — chỉ cần upload 1 file PDF trong ACF.
 */

'use strict';

(function initProfileBook() {
  var viewer = document.getElementById('profile-book-viewer');
  if (!viewer) return;

  var prevBtn = document.getElementById('profile-book-prev');
  var nextBtn = document.getElementById('profile-book-next');
  var stage = viewer.querySelector('.p-book__stage');
  var imgLeft = document.getElementById('profile-book-img-left');
  var imgRight = document.getElementById('profile-book-img-right');
  var leaf = document.getElementById('profile-book-leaf');
  var leafFront = document.getElementById('profile-book-leaf-front');
  var leafBack = document.getElementById('profile-book-leaf-back');
  var pager = document.getElementById('profile-book-pager');
  var loadingEl = document.getElementById('profile-book-loading');
  var errorEl = document.getElementById('profile-book-error');
  var introEl = document.getElementById('profile-book-intro');
  var introImg = document.getElementById('profile-book-intro-img');

  if (!stage || !leaf) return;

  var THEME_URI = (window.CMB_Theme && window.CMB_Theme.uri) || '';
  var PDFJS_URL = THEME_URI + '/assets/js/vendors/pdfjs/pdf.min.js';
  var PDFJS_WORKER_URL = THEME_URI + '/assets/js/vendors/pdfjs/pdf.worker.min.js';
  var MOBILE_BP = 900;
  var MIN_INTRO_MS = 500;

  var pdfjsLib = null;
  var pdfjsReady = null;
  var pdfDoc = null;
  var pageCache = new Map(); // pageNum(1-based) -> Promise<dataURL>

  var total = 0;
  var curSpread = 0; // desktop: spread index (0-based)
  var curPage = 0;   // mobile: single page index (0-based)
  var isBusy = false;
  var isMobile = window.innerWidth < MOBILE_BP;
  var started = false;

  function isMobileMode() {
    return window.innerWidth < MOBILE_BP;
  }

  function lastSpread() {
    return Math.floor(total / 2);
  }

  function leftIndexOf(k) { return 2 * k - 1; }
  function rightIndexOf(k) { return 2 * k; }

  function showLoading(show) {
    if (loadingEl) loadingEl.classList.toggle('is-visible', !!show);
  }

  function showError(show) {
    if (errorEl) errorEl.hidden = !show;
    if (pager) pager.hidden = !!show;
  }

  function ensurePdfJs() {
    if (pdfjsReady) return pdfjsReady;
    pdfjsReady = import(PDFJS_URL).then(function (mod) {
      pdfjsLib = mod;
      pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS_WORKER_URL;
      return mod;
    });
    return pdfjsReady;
  }

  function computeScaleFor(page) {
    var slot = stage.querySelector('.p-book__slot--right');
    var cssWidth = (slot && slot.clientWidth) || 500;
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    var base = page.getViewport({ scale: 1 });
    var scale = (cssWidth * dpr) / base.width;
    return Math.max(0.5, Math.min(scale, 3));
  }

  function renderPageDataUrl(pageNum) {
    if (pageCache.has(pageNum)) return pageCache.get(pageNum);
    var p = pdfDoc.getPage(pageNum).then(function (page) {
      var scale = computeScaleFor(page);
      var viewport = page.getViewport({ scale: scale });
      var canvas = document.createElement('canvas');
      canvas.width = Math.round(viewport.width);
      canvas.height = Math.round(viewport.height);
      var ctx = canvas.getContext('2d');
      return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function () {
        return canvas.toDataURL('image/jpeg', 0.85);
      });
    });
    pageCache.set(pageNum, p);
    return p;
  }

  function setImgBlank(el) {
    el.src = '';
    el.alt = '';
    var holder = el.closest('.p-book__slot, .p-book__face');
    if (holder) holder.classList.add('is-blank');
  }

  function setImg(el, idx) {
    if (idx === null || idx < 0 || idx >= total) {
      setImgBlank(el);
      return Promise.resolve();
    }
    var holder = el.closest('.p-book__slot, .p-book__face');
    if (holder) holder.classList.remove('is-blank');
    return renderPageDataUrl(idx + 1).then(function (url) {
      el.src = url;
      el.alt = 'Page ' + (idx + 1);
    });
  }

  function prefetchAround(indexes) {
    indexes.forEach(function (idx) {
      if (idx >= 0 && idx < total) renderPageDataUrl(idx + 1);
    });
  }

  function updatePager() {
    if (!pager) return;
    if (isMobileMode()) {
      pager.textContent = (curPage + 1) + ' / ' + total;
    } else {
      var l = leftIndexOf(curSpread);
      var r = rightIndexOf(curSpread);
      var parts = [];
      if (l >= 0 && l < total) parts.push(l + 1);
      if (r >= 0 && r < total) parts.push(r + 1);
      pager.textContent = parts.join('-') + ' / ' + total;
    }
  }

  function updateNavState() {
    var atStart, atEnd;
    if (isMobileMode()) {
      atStart = curPage <= 0;
      atEnd = curPage >= total - 1;
    } else {
      atStart = curSpread <= 0;
      atEnd = curSpread >= lastSpread();
    }
    prevBtn.disabled = isBusy || atStart;
    nextBtn.disabled = isBusy || atEnd;
  }

  function renderStatic() {
    leaf.classList.remove('is-flipping-next', 'is-flipping-prev', 'is-leaf-next', 'is-leaf-prev');
    leaf.style.visibility = 'hidden';

    var tasks;
    if (isMobileMode()) {
      stage.classList.add('is-single');
      setImgBlank(imgLeft);
      tasks = [setImg(imgRight, curPage)];
    } else {
      stage.classList.remove('is-single');
      tasks = [setImg(imgLeft, leftIndexOf(curSpread)), setImg(imgRight, rightIndexOf(curSpread))];
    }

    isBusy = true;
    showLoading(true);
    updateNavState();
    return Promise.all(tasks).then(function () {
      isBusy = false;
      showLoading(false);
      updatePager();
      updateNavState();
      if (isMobileMode()) {
        prefetchAround([curPage - 1, curPage + 1]);
      } else {
        prefetchAround([leftIndexOf(curSpread - 1), rightIndexOf(curSpread - 1), leftIndexOf(curSpread + 1), rightIndexOf(curSpread + 1)]);
      }
    });
  }

  function flipNext() {
    if (isBusy) return;

    if (isMobileMode()) {
      if (curPage >= total - 1) return;
      curPage++;
      crossfadeTo(curPage);
      return;
    }

    var rightIdx = rightIndexOf(curSpread);
    if (curSpread >= lastSpread() || rightIdx >= total) return;

    var nextLeftIdx = rightIdx + 1;
    var nextRightIdx = rightIdx + 2;

    isBusy = true;
    showLoading(true);
    updateNavState();

    Promise.all([setImg(leafFront, rightIdx), setImg(leafBack, nextLeftIdx), setImg(imgRight, nextRightIdx)]).then(function () {
      showLoading(false);
      leaf.classList.remove('is-leaf-prev');
      leaf.classList.add('is-leaf-next');
      leaf.style.visibility = 'visible';

      void leaf.offsetWidth;

      var onEnd = function () {
        leaf.removeEventListener('animationend', onEnd);
        leaf.classList.remove('is-flipping-next', 'is-leaf-next');
        leaf.style.visibility = 'hidden';
        setImg(imgLeft, nextLeftIdx);
        curSpread++;
        updatePager();
        isBusy = false;
        updateNavState();
        prefetchAround([leftIndexOf(curSpread + 1), rightIndexOf(curSpread + 1)]);
      };
      leaf.addEventListener('animationend', onEnd);
      requestAnimationFrame(function () {
        leaf.classList.add('is-flipping-next');
      });
    });
  }

  function flipPrev() {
    if (isBusy) return;

    if (isMobileMode()) {
      if (curPage <= 0) return;
      curPage--;
      crossfadeTo(curPage);
      return;
    }

    var leftIdx = leftIndexOf(curSpread);
    if (curSpread <= 0 || leftIdx < 0) return;

    var prevRightIdx = leftIdx - 1;
    var prevLeftIdx = leftIdx - 2;

    isBusy = true;
    showLoading(true);
    updateNavState();

    Promise.all([setImg(leafFront, leftIdx), setImg(leafBack, prevRightIdx), setImg(imgLeft, prevLeftIdx)]).then(function () {
      showLoading(false);
      leaf.classList.remove('is-leaf-next');
      leaf.classList.add('is-leaf-prev');
      leaf.style.visibility = 'visible';

      void leaf.offsetWidth;

      var onEnd = function () {
        leaf.removeEventListener('animationend', onEnd);
        leaf.classList.remove('is-flipping-prev', 'is-leaf-prev');
        leaf.style.visibility = 'hidden';
        setImg(imgRight, prevRightIdx);
        curSpread--;
        updatePager();
        isBusy = false;
        updateNavState();
        prefetchAround([leftIndexOf(curSpread - 1), rightIndexOf(curSpread - 1)]);
      };
      leaf.addEventListener('animationend', onEnd);
      requestAnimationFrame(function () {
        leaf.classList.add('is-flipping-prev');
      });
    });
  }

  function crossfadeTo(pageIdx) {
    isBusy = true;
    updateNavState();
    imgRight.classList.add('is-fading');
    setImg(imgRight, pageIdx).then(function () {
      imgRight.classList.remove('is-fading');
      updatePager();
      isBusy = false;
      updateNavState();
      prefetchAround([pageIdx - 1, pageIdx + 1]);
    });
  }

  function playOpenIntro() {
    if (!introEl || !introImg.getAttribute('src')) return Promise.resolve();
    return new Promise(function (resolve) {
      void introEl.offsetWidth;
      var onEnd = function () {
        introEl.removeEventListener('transitionend', onEnd);
        introEl.classList.add('is-hidden');
        resolve();
      };
      introEl.addEventListener('transitionend', onEnd);
      requestAnimationFrame(function () {
        introEl.classList.add('is-opening');
      });
    });
  }

  function startBook() {
    if (started) return;
    started = true;

    var url = viewer.getAttribute('data-pdf-url') || '';
    if (!url) return;

    var coverUrl = viewer.getAttribute('data-cover-url') || '';
    if (introImg) introImg.src = coverUrl;
    if (introEl) introEl.classList.toggle('is-hidden', !coverUrl);

    showError(false);

    var startedAt = Date.now();
    function afterReady() {
      var elapsed = Date.now() - startedAt;
      var wait = Math.max(0, MIN_INTRO_MS - elapsed);
      setTimeout(playOpenIntro, wait);
    }

    isBusy = true;
    showLoading(true);
    updateNavState();

    ensurePdfJs()
      .then(function () { return pdfjsLib.getDocument(url).promise; })
      .then(function (doc) {
        pdfDoc = doc;
        total = doc.numPages;
        isBusy = false;
        return renderStatic();
      })
      .then(afterReady)
      .catch(function (err) {
        isBusy = false;
        showLoading(false);
        showError(true);
        if (introEl) introEl.classList.add('is-hidden');
        // eslint-disable-next-line no-console
        if (window.console) console.error('[profile-book] Không thể tải PDF:', err);
      });
  }

  nextBtn.addEventListener('click', flipNext);
  prevBtn.addEventListener('click', flipPrev);

  stage.addEventListener('click', function (e) {
    if (e.target.closest('.p-book__nav')) return;
    if (isBusy) return;
    var rect = stage.getBoundingClientRect();
    var x = e.clientX - rect.left;
    if (x < rect.width / 2) flipPrev(); else flipNext();
  });

  viewer.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowRight') { e.preventDefault(); flipNext(); }
    if (e.key === 'ArrowLeft') { e.preventDefault(); flipPrev(); }
  });

  var touchStartX = null;
  stage.addEventListener('touchstart', function (e) {
    touchStartX = e.touches[0].clientX;
  }, { passive: true });
  stage.addEventListener('touchend', function (e) {
    if (touchStartX === null || isBusy) return;
    var dx = e.changedTouches[0].clientX - touchStartX;
    if (Math.abs(dx) > 40) { dx < 0 ? flipNext() : flipPrev(); }
    touchStartX = null;
  }, { passive: true });

  var resizeTimer = null;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      if (!pdfDoc) return;
      var nowMobile = isMobileMode();
      if (nowMobile !== isMobile) {
        isMobile = nowMobile;
        if (nowMobile) {
          curPage = Math.max(0, rightIndexOf(curSpread));
        } else {
          curSpread = Math.floor((curPage + 1) / 2);
        }
      }
      renderStatic();
    }, 200);
  });

  window.CMB_lazyInit('#profile-book-viewer', startBook, '200px');
})();
