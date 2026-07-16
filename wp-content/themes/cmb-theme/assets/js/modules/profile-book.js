/**
 * modules/profile-book.js - CMB Theme
 * Hồ sơ năng lực — flipbook thật (StPageFlip) render từ file PDF (pdf.js).
 * Kéo chuột/chạm để lật trang, chỉ cần upload 1 file PDF trong ACF.
 */

'use strict';

(function initProfileBook() {
  var wrap = document.getElementById('profile-book-wrap');
  var coverBtn = document.getElementById('profile-book-cover-btn');
  var root = document.getElementById('profile-book-viewer');
  if (!wrap || !coverBtn || !root) return;

  var flipEl = document.getElementById('profile-book-flip');
  var pager = document.getElementById('profile-book-pager');
  var footerEl = document.getElementById('profile-book-footer');
  var loadingEl = document.getElementById('profile-book-loading');
  var loadingText = document.getElementById('profile-book-loading-text');
  var errorEl = document.getElementById('profile-book-error');

  if (!flipEl || !window.St || !window.St.PageFlip) return;

  var THEME_URI = (window.CMB_Theme && window.CMB_Theme.uri) || '';
  var PDFJS_URL = THEME_URI + '/assets/js/vendors/pdfjs/pdf.min.js';
  var PDFJS_WORKER_URL = THEME_URI + '/assets/js/vendors/pdfjs/pdf.worker.min.js';
  var RENDER_WIDTH = 760; // độ phân giải render mỗi trang (px) — đủ nét ở kích thước hiển thị, không quá nặng
  var PRIORITY_PAGES = 4; // số trang render trước (đủ để mở sách ngay), phần còn lại tải ngầm phía sau
  var MAX_BOOK_WIDTH = 720;
  var FALLBACK_MAX_HEIGHT = window.innerWidth <= 767 ? 440 : 480;

  // Chiều cao sách bị chặn theo chiều cao THẬT của .p-book-wrap tại thời điểm dựng
  // sách (đo bằng clientHeight) — khung này giãn theo flex để khớp với cột video
  // bên cạnh (xem .p-video-profile-row trong _video-intro.scss), nên đo trực tiếp
  // thay vì đoán 1 con số cố định, tránh sách cao/thấp hơn video.
  function getMaxBookHeight() {
    var h = wrap.clientHeight;
    return h > 100 ? h - 8 : FALLBACK_MAX_HEIGHT;
  }

  var pdfjsLib = null;
  var pdfjsReady = null;
  var pageFlip = null;
  var totalPages = 0;

  function ensurePdfJs() {
    if (pdfjsReady) return pdfjsReady;
    pdfjsReady = import(PDFJS_URL).then(function (mod) {
      pdfjsLib = mod;
      pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS_WORKER_URL;
      return mod;
    });
    return pdfjsReady;
  }

  function renderPage(pdfDoc, pageNum) {
    return pdfDoc.getPage(pageNum).then(function (page) {
      var base = page.getViewport({ scale: 1 });
      var scale = RENDER_WIDTH / base.width;
      var viewport = page.getViewport({ scale: scale });
      var canvas = document.createElement('canvas');
      canvas.width = Math.round(viewport.width);
      canvas.height = Math.round(viewport.height);
      var ctx = canvas.getContext('2d');
      return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function () {
        return { url: canvas.toDataURL('image/jpeg', 0.85), width: base.width, height: base.height };
      });
    });
  }

  function setLoadingProgress(done, total) {
    if (loadingText) loadingText.textContent = done + ' / ' + total;
  }

  // Render đồng thời một khoảng trang liên tiếp (dùng cho lô ưu tiên — số lượng nhỏ nên an toàn khi chạy song song)
  function renderRange(pdfDoc, startPage, endPage, onEach) {
    var tasks = [];
    for (var p = startPage; p <= endPage; p++) {
      tasks.push(renderPage(pdfDoc, p).then(function (result) {
        if (onEach) onEach(result);
        return result;
      }));
    }
    return Promise.all(tasks);
  }

  // Render tuần tự (không song song) cho phần còn lại ở nền, tránh giật khi người dùng
  // đã bắt đầu thao tác với sách trong lúc các trang sau vẫn đang được xử lý.
  function renderSequential(pdfDoc, startPage, endPage, onEach) {
    var results = [];
    function next(p) {
      if (p > endPage) return Promise.resolve(results);
      return renderPage(pdfDoc, p).then(function (result) {
        results.push(result);
        if (onEach) onEach(result);
        return next(p + 1);
      });
    }
    return next(startPage);
  }

  function makePlaceholder(aspect) {
    var w = 60;
    var h = Math.round(w / aspect);
    var canvas = document.createElement('canvas');
    canvas.width = w;
    canvas.height = h;
    var ctx = canvas.getContext('2d');
    ctx.fillStyle = '#f4f5f7';
    ctx.fillRect(0, 0, w, h);
    return canvas.toDataURL('image/png');
  }

  function showLoading(show) {
    if (loadingEl) loadingEl.classList.toggle('is-visible', !!show);
  }

  function showError(show) {
    if (errorEl) errorEl.hidden = !show;
    if (pager) pager.hidden = !!show;
  }

  function updatePager(currentIndex) {
    if (!pager || !totalPages) return;
    pager.textContent = (currentIndex + 1) + ' / ' + totalPages;
  }

  // Chuẩn bị sẵn (tải pdf.js + render vài trang đầu) ngay khi trang web load xong,
  // trước cả khi người dùng bấm vào bìa — để lúc bấm mở ra gần như tức thì.
  // Idempotent: gọi nhiều lần chỉ chạy 1 lần, cache lại promise.
  var preparePromise = null;
  function prepare() {
    if (preparePromise) return preparePromise;

    var url = root.getAttribute('data-pdf-url') || '';
    if (!url) return Promise.reject(new Error('missing pdf url'));

    preparePromise = ensurePdfJs()
      .then(function () { return pdfjsLib.getDocument(url).promise; })
      .then(function (doc) {
        var priorityCount = Math.min(PRIORITY_PAGES, doc.numPages);
        var done = 0;
        setLoadingProgress(0, doc.numPages);
        return renderRange(doc, 1, priorityCount, function () {
          done++;
          setLoadingProgress(done, doc.numPages);
        }).then(function (firstPages) {
          return { doc: doc, firstPages: firstPages };
        });
      });

    return preparePromise;
  }

  function buildBook(ctx) {
    var pdfDoc = ctx.doc;
    var firstPages = ctx.firstPages;
    totalPages = pdfDoc.numPages;
    var first = firstPages[0];
    var aspect = first.width / first.height;

    // Chiều cao luôn bị chặn theo chiều cao thật của .p-book-wrap — chiều rộng suy ra
    // từ đó theo tỉ lệ trang, đảm bảo sách không bao giờ cao hơn khung đã có.
    var maxBookHeight = getMaxBookHeight();
    var maxWidth = Math.min(MAX_BOOK_WIDTH, Math.round(maxBookHeight * aspect));
    var maxHeight = Math.round(maxWidth / aspect);
    var baseWidth = 420;
    var baseHeight = Math.round(baseWidth / aspect);

    // StPageFlip chỉ chuyển sang chế độ "portrait" (hiện 1 trang/lần) khi chiều rộng
    // khung nhỏ hơn 2 * minWidth — với minWidth mặc định 200, màn hình điện thoại vẫn
    // có thể đủ rộng để rơi vào chế độ "landscape" (mở 2 nửa trang song song), không
    // phù hợp vì mỗi trang PDF gốc vốn đã là 1 trang đơn (không phải sách 2 mặt).
    // Trên điện thoại (<=767px, khớp @include sp), ép minWidth > maxWidth/2 để luôn
    // hiển thị đúng 1 trang/lần, vẫn giữ nguyên thao tác kéo/chạm để lật.
    var isPhone = window.innerWidth <= 767;
    var minWidth = isPhone ? Math.ceil(maxWidth / 2) + 1 : 200;

    var urls = firstPages.map(function (p) { return p.url; });
    if (firstPages.length < totalPages) {
      var placeholder = makePlaceholder(aspect);
      for (var i = firstPages.length; i < totalPages; i++) urls.push(placeholder);
    }

    pageFlip = new window.St.PageFlip(flipEl, {
      width: baseWidth,
      height: baseHeight,
      size: 'stretch',
      minWidth: minWidth,
      maxWidth: maxWidth,
      minHeight: Math.round(minWidth / aspect),
      maxHeight: maxHeight,
      maxShadowOpacity: 0.5,
      showCover: true,
      mobileScrollSupport: false,
      useMouseEvents: true,
      flippingTime: 700,
    });

    pageFlip.on('flip', function (e) {
      updatePager(e.data);
    });

    pageFlip.loadFromImages(urls);
    updatePager(0);
    showLoading(false);

    // Render phần trang còn lại ở nền — không chặn thao tác của người dùng.
    if (firstPages.length < totalPages) {
      renderSequential(pdfDoc, firstPages.length + 1, totalPages).then(function (restPages) {
        var allPages = firstPages.concat(restPages);
        pageFlip.updateFromImages(allPages.map(function (p) { return p.url; }));
      });
    }
  }

  // .p-book-cover và .p-book luôn nằm trong DOM, chồng lên nhau (absolute) trong
  // .p-book-wrap có kích thước cố định — mở/đóng chỉ là fade + xoay bìa trong cùng
  // 1 khung, không bao giờ đẩy layout của các phần tử khác trên trang.
  function openBook() {
    wrap.classList.add('is-opening');
    root.classList.add('is-visible');
    if (footerEl) footerEl.classList.add('is-visible');

    if (!pageFlip) {
      // Preload ngầm chưa kịp xong (người dùng bấm quá nhanh) — hiện loading rồi build luôn.
      showError(false);
      showLoading(true);
      prepare()
        .then(buildBook)
        .catch(function (err) {
          showLoading(false);
          showError(true);
          // eslint-disable-next-line no-console
          if (window.console) console.error('[profile-book] Không thể tải PDF:', err);
        });
    }

    var onEnd = function (e) {
      if (e.propertyName !== 'transform') return;
      coverBtn.removeEventListener('transitionend', onEnd);
      coverBtn.hidden = true;
      root.focus();
    };
    coverBtn.addEventListener('transitionend', onEnd);
  }

  function closeBook() {
    root.classList.remove('is-visible');
    if (footerEl) footerEl.classList.remove('is-visible');
    coverBtn.hidden = false;
    void coverBtn.offsetWidth; // ép reflow để transition mở->đóng chạy đúng chiều
    wrap.classList.remove('is-opening');

    var onEnd = function (e) {
      if (e.propertyName !== 'transform') return;
      coverBtn.removeEventListener('transitionend', onEnd);
      coverBtn.focus();
    };
    coverBtn.addEventListener('transitionend', onEnd);
  }

  coverBtn.addEventListener('click', openBook);

  // Bấm/kéo về bên trái khi đang ở trang đầu tiên → gấp sách lại về bìa
  flipEl.addEventListener('click', function (e) {
    if (!pageFlip || pageFlip.getCurrentPageIndex() > 0) return;
    var rect = flipEl.getBoundingClientRect();
    var x = e.clientX - rect.left;
    if (x < rect.width / 2) closeBook();
  });

  root.addEventListener('keydown', function (e) {
    if (!pageFlip) return;
    if (e.key === 'ArrowRight') { e.preventDefault(); pageFlip.flipNext(); }
    if (e.key === 'ArrowLeft') {
      e.preventDefault();
      if (pageFlip.getCurrentPageIndex() <= 0) closeBook();
      else pageFlip.flipPrev();
    }
    if (e.key === 'Escape' && pageFlip.getCurrentPageIndex() <= 0) closeBook();
  });

  // Tự chuẩn bị sẵn + dựng luôn cuốn sách (ẩn phía sau bìa, opacity:0) ngay sau khi
  // trang web load xong — không cần đợi người dùng bấm vào bìa. Nhờ vậy khi bấm mở,
  // sách đã có sẵn kích thước ổn định từ trước, không bị giật layout.
  function kickoffPreload() {
    prepare().then(buildBook).catch(function (err) {
      // eslint-disable-next-line no-console
      if (window.console) console.error('[profile-book] Preload thất bại:', err);
    });
  }

  if (document.getElementById('page-preloader')) {
    window.addEventListener('preloader:done', kickoffPreload, { once: true });
  } else {
    kickoffPreload();
  }
})();
