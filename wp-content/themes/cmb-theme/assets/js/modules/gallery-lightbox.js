/**
 * modules/gallery-lightbox.js - CMB Theme
 * Gallery lightbox dùng chung cho:
 * - #event-gallery  (single tin tức — ảnh/video sự kiện)
 * - #project-gallery (single dự án — ảnh dự án)
 */

'use strict';

(function () {
  var _lang = (window.CMB_Theme && window.CMB_Theme.lang) ? window.CMB_Theme.lang : 'vi';
  function _t(vi, en) { return _lang === 'en' ? en : vi; }

  var GALLERY_IDS = ['event-gallery', 'project-gallery'];
  var galleries = GALLERY_IDS
    .map(function (id) { return document.getElementById(id); })
    .filter(Boolean);
  if (!galleries.length) return;

  // Inject CSS 1 lần cho tất cả gallery trên trang
  var style = document.createElement('style');
  style.textContent = [
    '#gallery-lb{display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,.88)}',
    '#gallery-lb.is-open{display:flex}',
    '#gallery-lb .lb-img-wrap{max-width:90vw;max-height:90vh;display:flex;align-items:center;justify-content:center}',
    '#gallery-lb .lb-img-wrap img,#gallery-lb .lb-img-wrap video{max-width:100%;max-height:90vh;object-fit:contain;border-radius:4px}',
    '#gallery-lb .lb-close,#gallery-lb .lb-prev,#gallery-lb .lb-next{position:fixed;background:rgba(255,255,255,.15);border:none;color:#fff;cursor:pointer;font-size:1.5rem;line-height:1;border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;transition:background .2s}',
    '#gallery-lb .lb-close:hover,#gallery-lb .lb-prev:hover,#gallery-lb .lb-next:hover{background:rgba(255,255,255,.3)}',
    '#gallery-lb .lb-close{top:1rem;right:1rem}',
    '#gallery-lb .lb-prev{left:1rem;top:50%;transform:translateY(-50%)}',
    '#gallery-lb .lb-next{right:1rem;top:50%;transform:translateY(-50%)}',
    '#gallery-lb .lb-counter{position:fixed;bottom:1.25rem;left:50%;transform:translateX(-50%);color:#fff;font-size:.875rem;opacity:.7}',
    '.p-news-detail__gallery-grid figure,.p-project-gallery__item{cursor:zoom-in}',
    '.p-news-detail__gallery-grid figure:focus-visible,.p-project-gallery__item:focus-visible{outline:2px solid #0379CC;outline-offset:2px}',
  ].join('');
  document.head.appendChild(style);

  // Build DOM lightbox dùng chung
  var lb = document.createElement('div');
  lb.id = 'gallery-lb';
  lb.setAttribute('role', 'dialog');
  lb.setAttribute('aria-modal', 'true');
  lb.setAttribute('aria-label', _t('Xem ảnh', 'View photos'));
  lb.innerHTML = [
    '<div class="lb-img-wrap">',
    '<img class="lb-img" src="" alt="" />',
    '<video class="lb-video" controls playsinline></video>',
    '</div>',
    '<button class="lb-close" aria-label="' + _t('Đóng', 'Close') + '">&#215;</button>',
    '<button class="lb-prev"  aria-label="' + _t('Ảnh trước', 'Previous image') + '">&#8249;</button>',
    '<button class="lb-next"  aria-label="' + _t('Ảnh tiếp', 'Next image') + '">&#8250;</button>',
    '<div class="lb-counter"></div>',
  ].join('');
  document.body.appendChild(lb);

  var imgEl = lb.querySelector('.lb-img');
  var videoEl = lb.querySelector('.lb-video');
  var counter = lb.querySelector('.lb-counter');
  var prevBtn = lb.querySelector('.lb-prev');
  var nextBtn = lb.querySelector('.lb-next');

  var figures = [];
  var cur = 0;

  function show(index) {
    cur = index;
    videoEl.pause();
    videoEl.removeAttribute('src');
    videoEl.style.display = 'none';
    imgEl.style.display = 'none';

    var fig = figures[cur];
    var isVideo = fig && fig.dataset.type === 'video';

    if (isVideo) {
      var figVideo = fig.querySelector('video');
      videoEl.src = figVideo ? figVideo.src : '';
      videoEl.style.display = '';
    } else {
      var figImg = fig ? fig.querySelector('img') : null;
      if (figImg) { imgEl.src = figImg.src; imgEl.alt = figImg.alt; }
      imgEl.style.display = '';
    }

    counter.textContent = (cur + 1) + ' / ' + figures.length;
    prevBtn.disabled = cur === 0;
    nextBtn.disabled = cur === figures.length - 1;
  }

  function open(index) {
    show(index);
    lb.classList.add('is-open');
    window.CMB.lockScroll();
    lb.querySelector('.lb-close').focus();
  }

  function close() {
    videoEl.pause();
    lb.classList.remove('is-open');
    window.CMB.unlockScroll();
  }

  lb.querySelector('.lb-close').addEventListener('click', close);
  prevBtn.addEventListener('click', function () { if (cur > 0) show(cur - 1); });
  nextBtn.addEventListener('click', function () { if (cur < figures.length - 1) show(cur + 1); });

  lb.addEventListener('click', function (e) {
    if (e.target === lb) close();
  });

  document.addEventListener('keydown', function (e) {
    if (!lb.classList.contains('is-open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') { if (cur > 0) show(cur - 1); }
    if (e.key === 'ArrowRight') { if (cur < figures.length - 1) show(cur + 1); }
  });

  // Mỗi gallery trên trang dùng riêng mảng figures của nó — bấm vào ảnh nào,
  // dựng lại mảng "figures" từ đúng gallery chứa ảnh đó rồi mới mở lightbox,
  // để prev/next chỉ chạy trong phạm vi gallery đang xem (không lẫn 2 gallery).
  galleries.forEach(function (gallery) {
    var galleryFigures = Array.from(gallery.querySelectorAll('figure'));
    galleryFigures.forEach(function (fig, i) {
      fig.setAttribute('tabindex', '0');
      fig.addEventListener('click', function () {
        figures = galleryFigures;
        open(i);
      });
      fig.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          figures = galleryFigures;
          open(i);
        }
      });
    });
  });
})();
