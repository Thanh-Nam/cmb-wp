/**
 * modules/about-author-fit.js - CMB Theme
 * Section "Về CMB" (trang chủ) — tên/chức danh CEO đặt đè lên góc ảnh (> 1400px),
 * giữ nguyên 1 dòng (không xuống dòng) nhưng KHÔNG được đè vào cột quote ở giữa.
 * Độ dài chữ khác nhau theo ngôn ngữ (tiếng Anh thường dài hơn tiếng Việt) nên
 * không thể tính trước 1 kích thước cố định — script này đo trực tiếp khoảng
 * cách còn lại tới mép cột quote lúc runtime rồi tự co font-size vừa đủ để
 * luôn vừa 1 dòng trong khoảng đó.
 */

'use strict';

(function () {
  var MQ = window.matchMedia('(min-width: 1400.02px)');
  var GAP = 12; // khoảng đệm an toàn (px) giữa tên/chức danh và cột quote
  var MIN_FONT_RATIO = 0.6; // không co font nhỏ hơn 60% kích thước gốc

  function fitOne(el, boundaryX, fromRight) {
    if (!el || !el.textContent.trim()) return;
    var baseSize = parseFloat(el.dataset.baseFontSize || getComputedStyle(el).fontSize);
    if (!el.dataset.baseFontSize) el.dataset.baseFontSize = baseSize;
    el.style.fontSize = baseSize + 'px';

    var minSize = baseSize * MIN_FONT_RATIO;
    var size = baseSize;
    var guard = 0;

    while (guard < 30) {
      var rect = el.getBoundingClientRect();
      var overflow = fromRight ? (rect.right - boundaryX) : (boundaryX - rect.left);
      if (overflow <= 0 || size <= minSize) break;
      size -= 0.5;
      el.style.fontSize = size + 'px';
      guard++;
    }
  }

  function reset(el) {
    if (!el) return;
    el.style.fontSize = '';
  }

  function run() {
    var content = document.querySelector('.p-about__content');
    if (!content) return;

    var persons = document.querySelectorAll('.p-about__person');
    if (!persons.length) return;

    if (!MQ.matches) {
      persons.forEach(function (person) {
        reset(person.querySelector('.p-about__author-name'));
        reset(person.querySelector('.p-about__author-title'));
      });
      return;
    }

    var contentRect = content.getBoundingClientRect();

    persons.forEach(function (person) {
      var isLeft = person.classList.contains('p-about__person--left');
      var boundaryX = isLeft ? contentRect.left - GAP : contentRect.right + GAP;
      fitOne(person.querySelector('.p-about__author-name'), boundaryX, isLeft);
      fitOne(person.querySelector('.p-about__author-title'), boundaryX, isLeft);
    });
  }

  var scheduled = false;
  function scheduleRun() {
    if (scheduled) return;
    scheduled = true;
    requestAnimationFrame(function () {
      scheduled = false;
      run();
    });
  }

  function init() {
    run();

    window.addEventListener('resize', scheduleRun);

    // Ảnh/font load xong có thể đổi lại chiều rộng cột quote — chạy lại cho chắc.
    document.querySelectorAll('.p-about__person-img').forEach(function (img) {
      if (!img.complete) img.addEventListener('load', scheduleRun, { once: true });
    });
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(scheduleRun);
    }
  }

  if (document.getElementById('page-preloader')) {
    window.addEventListener('preloader:done', init, { once: true });
  } else if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
