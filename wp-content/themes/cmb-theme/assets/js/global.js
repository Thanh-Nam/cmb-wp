/**
 * global.js - CMB Theme
 * Code chạy trên MỌI trang: Back to Top, Sticky Header, Mobile Nav,
 * Smooth Scroll, Scroll Reveal, Dropdowns, Preloader, Search Overlay.
 */

'use strict';

// ============================================
// NAMESPACE
// ============================================
window.CMB = window.CMB || { version: '1.0.0' };

// ============================================
// LAZY INIT HELPER
// ============================================
window.CMB_lazyInit = function(selector, initFn, rootMargin) {
  var el = document.querySelector(selector);
  if (!el) return;
  if (!('IntersectionObserver' in window)) { initFn(el); return; }
  var obs = new IntersectionObserver(function(entries) {
    if (entries[0].isIntersecting) { obs.disconnect(); initFn(el); }
  }, { rootMargin: rootMargin || '150px' });
  obs.observe(el);
};

// ============================================
// BACK TO TOP
// ============================================
(function initBackToTop() {
  const btn = document.getElementById('back-to-top');
  if (!btn) return;
  window.addEventListener('scroll', () => {
    btn.classList.toggle('is-visible', window.scrollY >= 400);
  }, { passive: true });
  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();

// ============================================
// HEADER: Scroll-triggered fixed + slide-down
// ============================================
(function initStickyHeader() {
  const header = document.getElementById('site-header');
  if (!header) return;

  let isFixed = false;
  let headerHeight = header.offsetHeight;

  const onScroll = () => {
    const scrollY = window.scrollY;

    header.classList.toggle('is-scrolled', scrollY > 20);

    if (!isFixed && scrollY > headerHeight) {
      headerHeight = header.offsetHeight;
      document.body.style.paddingTop = headerHeight + 'px';
      header.classList.add('is-fixed');
      isFixed = true;
    } else if (isFixed && scrollY <= 0) {
      header.classList.remove('is-fixed');
      document.body.style.paddingTop = '';
      isFixed = false;
      headerHeight = header.offsetHeight;
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });

  window.addEventListener('resize', () => {
    if (!isFixed) headerHeight = header.offsetHeight;
  });

  onScroll();
})();


// ============================================
// MOBILE NAVIGATION: Hamburger toggle
// ============================================
(function initMobileNav() {
  const hamburger = document.getElementById('hamburger-btn');
  const nav = document.getElementById('site-nav');
  const overlay = document.getElementById('nav-overlay');

  if (!hamburger || !nav) return;

  const openNav = () => {
    hamburger.classList.add('is-active');
    hamburger.setAttribute('aria-expanded', 'true');
    nav.classList.add('is-open');
    if (overlay) {
      overlay.classList.add('is-open');
      overlay.removeAttribute('aria-hidden');
    }
    document.body.style.overflow = 'hidden';
  };

  const closeNav = () => {
    hamburger.classList.remove('is-active');
    hamburger.setAttribute('aria-expanded', 'false');
    nav.classList.remove('is-open');
    if (overlay) {
      overlay.classList.remove('is-open');
      overlay.setAttribute('aria-hidden', 'true');
    }
    document.body.style.overflow = '';
  };

  hamburger.addEventListener('click', () => {
    const isOpen = hamburger.classList.contains('is-active');
    isOpen ? closeNav() : openNav();
  });

  if (overlay) overlay.addEventListener('click', closeNav);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeNav();
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 1023) closeNav();
  });
})();


// ============================================
// SMOOTH SCROLL: Anchor links
// ============================================
(function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (!target) return;
      e.preventDefault();

      const headerHeight = document.getElementById('site-header')?.offsetHeight ?? 0;
      const top = target.getBoundingClientRect().top + window.scrollY - headerHeight - 16;

      window.scrollTo({ top, behavior: 'smooth' });
    });
  });
})();


// ============================================
// SCROLL REVEAL: Hiệu ứng khi scroll vào viewport
// ============================================
(function initScrollReveal() {
  if (!('IntersectionObserver' in window)) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 }
  );

  function startObserving() {
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        document.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));
      });
    });
  }

  if (document.getElementById('page-preloader')) {
    window.addEventListener('preloader:done', startObserving, { once: true });
  } else {
    startObserving();
  }

  // Expose để các module khác (dynamic content) có thể observe thêm phần tử
  window.CMB_revealObserver = observer;
})();


// ============================================
// DROPDOWN: Click-based on mobile
// ============================================
(function initDropdowns() {
  const isMobile = () => window.innerWidth <= 1023;

  document.querySelectorAll('.has-dropdown > .l-nav__link').forEach((link) => {
    link.addEventListener('click', (e) => {
      if (!isMobile()) return;
      e.preventDefault();
      const dropdown = link.nextElementSibling;
      if (!dropdown) return;

      const isOpen = dropdown.classList.contains('is-open');
      document.querySelectorAll('.l-nav__dropdown').forEach((d) => {
        d.classList.remove('is-open');
      });
      if (!isOpen) dropdown.classList.add('is-open');
    });
  });
})();


// ============================================
// PRELOADER
// ============================================
(function initPreloader() {
  const preloader = document.getElementById('page-preloader');
  if (!preloader) return;

  const line = preloader.querySelector('.preloader__line');
  const logo = preloader.querySelector('.preloader__logo');

  document.body.classList.add('has-preloader');

  const FILL_END = 1600;
  const startAt = performance.now();
  var pageLoaded = false;
  var fillDone = false;

  function runComplete() {
    if (!pageLoaded || !fillDone) return;
    if (line) {
      var barW = line.parentElement ? line.parentElement.getBoundingClientRect().width : 0;
      var lineW = line.getBoundingClientRect().width;
      var currentPct = (barW > 0 ? (lineW / barW * 100) : 70).toFixed(2) + '%';
      line.style.animation = 'none';
      line.style.width = currentPct;
      void line.offsetWidth;
      line.style.transition = 'width 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
      line.style.width = '100%';
    }
    setTimeout(function () {
      preloader.classList.add('is-done');
      document.body.classList.add('is-loaded');
      window.dispatchEvent(new CustomEvent('preloader:done'));
    }, 900);
  }

  var elapsed = performance.now() - startAt;
  setTimeout(function () {
    fillDone = true;
    runComplete();
  }, Math.max(0, FILL_END - elapsed));

  function onPageLoad() {
    pageLoaded = true;
    runComplete();
  }

  if (document.readyState === 'complete') {
    onPageLoad();
  } else {
    window.addEventListener('load', onPageLoad);
  }

  function showOverlay() {
    preloader.style.transition = 'none';
    preloader.classList.remove('is-done');
    document.body.classList.remove('is-loaded');
    void preloader.offsetWidth;
    preloader.style.transition = '';
    if (line) {
      line.style.animation = 'none';
      line.style.transition = 'none';
      line.style.width = '0';
    }
  }

  document.addEventListener('click', function (e) {
    const link = e.target.closest('a[href]');
    if (!link) return;
    const href = link.getAttribute('href');
    if (
      !href ||
      href.startsWith('#') ||
      href.startsWith('mailto:') ||
      href.startsWith('tel:') ||
      href.startsWith('javascript:') ||
      link.target === '_blank'
    ) return;
    if (href.startsWith('http') || href.startsWith('//')) {
      try {
        const url = new URL(href, location.href);
        if (url.hostname !== location.hostname) return;
      } catch (err) {
        return;
      }
    }

    e.preventDefault();
    showOverlay();
    setTimeout(function () {
      window.location.href = href;
    }, 150);
  });
})();


// ============================================
// LANGUAGE SWITCHER
// ============================================
(function initLangSwitcher() {
  const wraps = document.querySelectorAll('.l-header__lang-wrap');
  if (!wraps.length) return;

  function closeAll() {
    wraps.forEach((w) => {
      w.classList.remove('is-open');
      const btn = w.querySelector('[aria-expanded]');
      if (btn) btn.setAttribute('aria-expanded', 'false');
    });
  }

  wraps.forEach((wrap) => {
    const btn = wrap.querySelector('[aria-haspopup="listbox"]');
    if (!btn) return;
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = wrap.classList.contains('is-open');
      closeAll();
      if (!isOpen) {
        wrap.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  document.addEventListener('click', closeAll);
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAll(); });
})();


// ============================================
// SEARCH OVERLAY
// ============================================
(function initSearchOverlay() {
  var overlay = document.getElementById('search-overlay');
  var input = document.getElementById('search-overlay-input');
  var closeBtn = document.getElementById('search-overlay-close');
  var backdrop = document.getElementById('search-overlay-backdrop');
  if (!overlay) return;

  var searchBtns = document.querySelectorAll('#header-search-btn, #header-search-btn-mobile');

  function openSearch() {
    overlay.classList.add('is-open');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.classList.add('search-overlay-open');
    if (input) { setTimeout(function () { input.focus(); }, 50); }
  }

  function closeSearch() {
    overlay.classList.remove('is-open');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('search-overlay-open');
  }

  searchBtns.forEach(function (btn) {
    btn.addEventListener('click', openSearch);
  });

  if (closeBtn) closeBtn.addEventListener('click', closeSearch);
  if (backdrop) backdrop.addEventListener('click', closeSearch);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeSearch();
  });
})();


