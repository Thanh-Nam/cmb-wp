/**
 * modules/gallery-lightbox.js - CMB Theme
 * Event gallery lightbox cho single post
 */

'use strict';

(function initGalleryLightbox() {
  var gallery = document.getElementById('event-gallery');
  if (!gallery) return;

  var figures = Array.from(gallery.querySelectorAll('figure'));
  if (!figures.length) return;

  // Inject CSS
  var style = document.createElement('style');
  style.textContent = [
    '#gallery-lb{display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,.88)}',
    '#gallery-lb.is-open{display:flex}',
    '#gallery-lb .lb-img-wrap{max-width:90vw;max-height:90vh;display:flex;align-items:center;justify-content:center}',
    '#gallery-lb .lb-img-wrap img{max-width:100%;max-height:90vh;object-fit:contain;border-radius:4px}',
    '#gallery-lb .lb-close,#gallery-lb .lb-prev,#gallery-lb .lb-next{position:fixed;background:rgba(255,255,255,.15);border:none;color:#fff;cursor:pointer;font-size:1.5rem;line-height:1;border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;transition:background .2s}',
    '#gallery-lb .lb-close:hover,#gallery-lb .lb-prev:hover,#gallery-lb .lb-next:hover{background:rgba(255,255,255,.3)}',
    '#gallery-lb .lb-close{top:1rem;right:1rem}',
    '#gallery-lb .lb-prev{left:1rem;top:50%;transform:translateY(-50%)}',
    '#gallery-lb .lb-next{right:1rem;top:50%;transform:translateY(-50%)}',
    '#gallery-lb .lb-counter{position:fixed;bottom:1.25rem;left:50%;transform:translateX(-50%);color:#fff;font-size:.875rem;opacity:.7}',
    '.p-news-detail__gallery-grid figure{cursor:pointer;overflow:hidden;border-radius:4px}',
    '.p-news-detail__gallery-grid figure:focus-visible{outline:2px solid #0379CC;outline-offset:2px}',
  ].join('');
  document.head.appendChild(style);

  // Build DOM
  var lb = document.createElement('div');
  lb.id = 'gallery-lb';
  lb.setAttribute('role', 'dialog');
  lb.setAttribute('aria-modal', 'true');
  lb.setAttribute('aria-label', 'Xem ảnh sự kiện');
  lb.innerHTML = [
    '<div class="lb-img-wrap"><img class="lb-img" src="" alt="" /></div>',
    '<button class="lb-close" aria-label="Đóng">&#215;</button>',
    '<button class="lb-prev"  aria-label="Ảnh trước">&#8249;</button>',
    '<button class="lb-next"  aria-label="Ảnh tiếp">&#8250;</button>',
    '<div class="lb-counter"></div>',
  ].join('');
  document.body.appendChild(lb);

  var imgEl = lb.querySelector('.lb-img');
  var counter = lb.querySelector('.lb-counter');
  var prevBtn = lb.querySelector('.lb-prev');
  var nextBtn = lb.querySelector('.lb-next');
  var cur = 0;

  function show(index) {
    cur = index;
    var figImg = figures[cur] ? figures[cur].querySelector('img') : null;
    if (figImg) { imgEl.src = figImg.src; imgEl.alt = figImg.alt; }
    counter.textContent = (cur + 1) + ' / ' + figures.length;
    prevBtn.disabled = cur === 0;
    nextBtn.disabled = cur === figures.length - 1;
  }

  function open(index) {
    show(index);
    lb.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    lb.querySelector('.lb-close').focus();
  }

  function close() {
    lb.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  figures.forEach(function (fig, i) {
    fig.setAttribute('tabindex', '0');
    fig.addEventListener('click', function () { open(i); });
    fig.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(i); }
    });
  });

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
})();
