/**
 * main.js - CMB Base Theme
 * Entry point cho toàn bộ JavaScript
 */

'use strict';

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
      // Đo lại trước khi fixed để lấy giá trị chính xác
      headerHeight = header.offsetHeight;
      // Thêm padding trước khi fixed để tránh page giật
      document.body.style.paddingTop = headerHeight + 'px';
      header.classList.add('is-fixed');
      isFixed = true;
    } else if (isFixed && scrollY <= 0) {
      header.classList.remove('is-fixed');
      document.body.style.paddingTop = '';
      isFixed = false;
      // Cập nhật lại chiều cao sau khi unfix
      headerHeight = header.offsetHeight;
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });

  // Cập nhật headerHeight khi resize (khi chưa fixed)
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
  // Dùng overlay đã có sẵn trong HTML, không tạo mới
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

  // Nếu có preloader, chờ nó xong (is-loaded) rồi mới observe
  // để phần tử trong viewport lúc trang hiện ra vẫn chạy animation
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

  // Selector đúng theo BEM: .l-nav__link
  document.querySelectorAll('.has-dropdown > .l-nav__link').forEach((link) => {
    link.addEventListener('click', (e) => {
      if (!isMobile()) return;
      e.preventDefault();
      const dropdown = link.nextElementSibling;
      if (!dropdown) return;

      const isOpen = dropdown.classList.contains('is-open');
      // Close all dropdowns
      document.querySelectorAll('.l-nav__dropdown').forEach((d) => {
        d.classList.remove('is-open');
      });
      if (!isOpen) dropdown.classList.add('is-open');
    });
  });
})();


// ============================================
// INFO STATS: Count-up animation khi scroll vào viewport
// ============================================
(function initStatCountUp() {
  const numbers = document.querySelectorAll('.p-info__stat-number');
  if (!numbers.length || !('IntersectionObserver' in window)) return;

  function countUp(el) {
    const raw = el.textContent.trim();
    const target = parseInt(raw, 10);
    const padLen = raw.length; // giữ leading zero (vd: "03" → length 2)
    const duration = 1800; // ms
    const startTime = performance.now();

    function tick(now) {
      const elapsed = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      // easeOutQuart — nhanh lúc đầu, chậm dần về cuối
      const ease = 1 - Math.pow(1 - progress, 4);
      const current = Math.round(ease * target);
      el.textContent = String(current).padStart(padLen, '0');
      if (progress < 1) requestAnimationFrame(tick);
    }

    requestAnimationFrame(tick);
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          countUp(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.5 }
  );

  numbers.forEach((el) => observer.observe(el));
})();


// ============================================
// FORM VALIDATION (Basic)
// ============================================
(function initFormValidation() {
  const forms = document.querySelectorAll('form[data-validate]');

  forms.forEach((form) => {
    form.addEventListener('submit', function (e) {
      let valid = true;

      this.querySelectorAll('[required]').forEach((field) => {
        const group = field.closest('.form__group');
        const errorEl = group?.querySelector('.form__error');

        if (!field.value.trim()) {
          valid = false;
          field.classList.add('has-error');
          if (errorEl) errorEl.textContent = 'Trường này là bắt buộc.';
        } else {
          field.classList.remove('has-error');
          if (errorEl) errorEl.textContent = '';
        }
      });

      if (!valid) e.preventDefault();
    });
  });
})();


// ============================================
// HERO BANNER SLIDER: Swiper Initialization
// ============================================
(function initHeroSlider() {
  const sliderEl = document.querySelector('.p-hero__slider');
  if (!sliderEl) return;

  new Swiper(sliderEl, {
    loop: true,
    speed: 1000,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.p-hero__pagination',
      clickable: true,
    },
    effect: 'fade',
    fadeEffect: {
      crossFade: true
    }
  });
})();


// ============================================
// HISTORY MILESTONES: Sliding window navigation
// ============================================
(function initHistoryMilestones() {
  const MILESTONES = (window.CMB_History && window.CMB_History.length)
    ? window.CMB_History
    : [
      { year: '1966', desc: 'Thành lập Đội khảo sát thiết kế (tiền thân của Công ty).' },
      { year: '1977', desc: 'Chuyển đổi mô hình thành Công ty Khảo sát Thiết kế Đường biển.' },
      { year: '1995', desc: 'Đổi tên thành Công ty tư vấn xây dựng công trình Hàng hải.' },
      { year: '1999', desc: 'Lập Quy hoạch tổng thể phát triển hệ thống cảng biển Việt Nam đến năm 2010 — Quy hoạch cảng biển đầu tiên của Việt Nam.' },
      { year: '2004', desc: 'Cổ phần hóa, hoạt động theo mô hình công ty cổ phần.' },
      { year: '2011', desc: 'Được trao tặng Huân chương Độc lập Hạng Ba.' },
      { year: '2021', desc: 'Lập Quy hoạch tổng thể phát triển hệ thống cảng biển Việt Nam thời kỳ 2021-2030, tầm nhìn đến năm 2050. Kỷ niệm 55 năm thành lập và được tặng Huân chương Độc lập Hạng Nhì.' },
      { year: '2023', desc: 'Lập Quy hoạch chi tiết nhóm cảng biển, bến cảng, cầu cảng, bến phao, khu nước, vùng nước thời kỳ 2021-2030, tầm nhìn đến năm 2050. Lập Quy hoạch phát triển hệ thống cảng cạn thời kỳ 2021-2030, tầm nhìn đến năm 2050.' },
    ];

  const slots = Array.from(document.querySelectorAll('.p-history__item'));
  const mobileList = document.getElementById('history-mobile-list');
  const btnPrev = document.getElementById('history-nav-prev');
  const btnNext = document.getElementById('history-nav-next');
  if (!slots.length || !btnPrev || !btnNext) return;

  let mobileListInitialized = false;

  // Slot 0 (1966) cố định — chỉ 5 slot còn lại mới cuộn
  const SCROLL_VISIBLE = slots.length - 1;
  const scrollSlots = slots.slice(1);
  let startIndex = 1;   // index trong MILESTONES cho scrollSlots[0]
  let prevStartIndex = 1;

  // Gán cố định slot 0
  slots[0].querySelector('.p-history__year').textContent = MILESTONES[0].year;
  slots[0].querySelector('.p-history__desc').textContent = MILESTONES[0].desc;

  // Desktop: cập nhật 5 slot cuộn
  function render() {
    const slidDir = startIndex - prevStartIndex; // >0: next, <0: prev, 0: không slide

    scrollSlots.forEach((slot, i) => {
      const m = MILESTONES[startIndex + i];
      slot.querySelector('.p-history__year').textContent = m.year;
      slot.querySelector('.p-history__desc').textContent = m.desc;
    });

    // Animate chỉ các scrollSlots khi window slide
    if (slidDir !== 0) {
      const dirClass = slidDir > 0 ? 'is-sliding-next' : 'is-sliding-prev';
      scrollSlots.forEach((slot) => {
        slot.classList.remove('is-sliding-next', 'is-sliding-prev');
        void slot.offsetWidth;
        slot.classList.add(dirClass);
        slot.addEventListener('animationend', () => slot.classList.remove(dirClass), { once: true });
      });
    }

    prevStartIndex = startIndex;
    btnPrev.disabled = startIndex === 1;
    btnNext.disabled = startIndex + SCROLL_VISIBLE >= MILESTONES.length;

    renderMobileList();
  }

  // Mobile: hiển thị toàn bộ milestones theo dạng timeline dọc
  function renderMobileList() {
    if (!mobileList) return;

    // Compass riêng ở đầu timeline (không gắn với item nào)
    const startEl = `
      <li class="p-history__mobile-start" role="presentation">
        <img src="assets/images/icon-banh-lai.png" alt="" class="p-history__mobile-compass" aria-hidden="true" />
      </li>`;

    // Background trang trí — absolute, không ảnh hưởng layout
    const bgTopEl = `
      <li class="p-history__mobile-bg-top" role="presentation" aria-hidden="true">
        <img src="assets/images/history-2.png" alt="" />
      </li>`;
    const bgMiddleEl = `
      <li class="p-history__mobile-bg-middle" role="presentation" aria-hidden="true">
        <img src="assets/images/history-1.png" alt="" />
      </li>`;

    // Tất cả items đều dùng wheel nhỏ
    // WP loop: thêm class p-history__mobile-item--first vào item đầu tiên
    // CSS tự ẩn wheel — không cần conditional trong template PHP
    // data-reveal chỉ gắn lần đầu — tránh flash ẩn/hiện khi user click lại milestone
    const itemsHtml = MILESTONES.map((m, i) => `
      <li class="p-history__mobile-item${i === 0 ? ' p-history__mobile-item--first' : ''}" data-index="${i}"${!mobileListInitialized ? ' data-reveal="fade-left"' : ''}>
        <div class="p-history__mobile-icon-col">
          <img src="assets/images/banh-lai.svg" alt="" class="p-history__mobile-wheel" aria-hidden="true" />
        </div>
        <div class="p-history__mobile-content">
          <h3 class="p-history__year">${m.year}</h3>
          <p class="p-history__desc">${m.desc}</p>
        </div>
      </li>`).join('');

    // Footer: L-shape dashed + lighthouse + history-3.png bg
    const endEl = `
      <li class="p-history__mobile-end" role="presentation" aria-hidden="true">
        <img src="assets/images/history-3.png" alt="" class="p-history__mobile-bg-end" aria-hidden="true" />
        <img src="assets/images/icon-hai-dang.png" alt="" class="p-history__mobile-lighthouse" />
      </li>`;

    mobileList.innerHTML = startEl + bgTopEl + bgMiddleEl + itemsHtml + endEl;

    // Observe mobile items lần đầu tiên (các lần render sau thì bỏ qua)
    if (!mobileListInitialized && window.CMB_revealObserver) {
      mobileList.querySelectorAll('[data-reveal]').forEach((el) => window.CMB_revealObserver.observe(el));
      mobileListInitialized = true;
    }

  }

  btnNext.addEventListener('click', () => {
    if (startIndex + SCROLL_VISIBLE >= MILESTONES.length) return;
    const remaining = MILESTONES.length - (startIndex + SCROLL_VISIBLE);
    if (remaining >= SCROLL_VISIBLE) {
      startIndex += SCROLL_VISIBLE;
    } else {
      startIndex = MILESTONES.length - SCROLL_VISIBLE;
    }
    render();
  });

  btnPrev.addEventListener('click', () => {
    if (startIndex === 1) return;
    startIndex = Math.max(1, startIndex - SCROLL_VISIBLE);
    render();
  });


  render();
})();


// ============================================
// HISTORY SHIP → MILESTONE ACTIVATION
// ============================================
(function initHistoryShipActivation() {
  const pathEl = document.getElementById('ship-route');
  const slots = Array.from(document.querySelectorAll('.p-history__item'));
  if (!pathEl || !slots.length) return;

  const DUR = 22000; // ms — phải khớp với dur="22s" trong animateMotion

  // Toạ độ SVG (viewBox 1859×823) của từng mốc — lấy từ vị trí bánh lái trên path
  const COORDS = [
    { x: 67.5, y: 690.0 },   // 1966 — điểm bắt đầu path
    { x: 167.3, y: 418.1 },   // 1977 — wheel: 9%, 50.8%
    { x: 539.1, y: 418.1 },   // 1995 — wheel: 29%, 50.8%
    { x: 877.4, y: 400.0 },   // 1999 — wheel: 47.2%, 48.6%
    { x: 1031.7, y: 163.8 },   // 2004 — wheel: 55.5%, 19.9%
    { x: 1407.2, y: 163.8 },   // 2011 — wheel: 75.7%, 19.9%
  ];

  // Tính fraction (0–1) trên path gần nhất với mỗi toạ độ
  const total = pathEl.getTotalLength();
  const STEPS = 800;
  const fractions = COORDS.map(({ x, y }) => {
    let best = 0, bestDist = Infinity;
    for (let i = 0; i <= STEPS; i++) {
      const len = (i / STEPS) * total;
      const p = pathEl.getPointAtLength(len);
      const d = (p.x - x) ** 2 + (p.y - y) ** 2;
      if (d < bestDist) { bestDist = d; best = len; }
    }
    return best / total;
  });

  // RAF loop: tìm mốc cuối cùng ship vừa qua → chỉ active duy nhất mốc đó
  function tick(now) {
    const progress = (now % DUR) / DUR;

    let activeIndex = 0;
    for (let i = 0; i < fractions.length; i++) {
      if (progress >= fractions[i]) activeIndex = i;
    }

    slots.forEach((slot, i) => {
      slot.classList.toggle('is-active', i === activeIndex);
    });

    requestAnimationFrame(tick);
  }

  requestAnimationFrame(tick);
})();


// ============================================
// LOCATION MAP: Fetch + inline map.svg để animate đường nét đứt
// map.svg có sẵn stroke-dasharray="8.35 4.18" trên các path.
// Phải inline SVG (không dùng <img>) mới CSS-animate được nội bộ SVG.
// ============================================
// LOCATION MAP: Inline SVG + Interactive city label boxes
// ============================================

// Panel element references (assigned by initLocationMap, used by SVG click handlers)
var _locPanel, _locCityEl, _locProjectEl, _locDescEl, _locLinkEl, _locImgEl;
var _locPopup, _popupCityEl, _popupProjectEl, _popupDescEl, _popupImgEl;

var _themeUri = (window.CMB_Theme && window.CMB_Theme.uri) ? window.CMB_Theme.uri.replace(/\/$/, '') : '';

var _locationData = {
  'nghe-an': {
    city: 'NGHỆ AN',
    project: 'Cảng tổng hợp Đông Hồi, Quỳnh Lưu',
    desc: 'Tư vấn lập dự án đầu tư và thiết kế cơ sở Cảng tổng hợp Đông Hồi tại huyện Quỳnh Lưu, Nghệ An, công suất 5 triệu tấn/năm.',
    link: '#',
    imgSrc: _themeUri + '/assets/images/cang-tong-hop-dong-hoi.png',
    imgAlt: 'Cảng tổng hợp Đông Hồi, Nghệ An'
  },
  'hai-phong': {
    city: 'HẢI PHÒNG',
    project: 'Cảng Đình Vũ',
    desc: 'Diện tích 73,56ha; chiều dài bến 1.610,6m, tiếp nhận tàu 20.000 – 50.000 DW T; công suất 15 triệu tấn/năm',
    link: '#',
    imgSrc: _themeUri + '/assets/images/cang-dinh-vu.png',
    imgAlt: 'Cảng Đình Vũ'
  },
  'tay-ninh': {
    city: 'TÂY NINH',
    project: 'Trung tâm Logistics, cảng Cạn cảng tổng hợp Tây Ninh',
    desc: 'Khu Cảng cạn 48,94 ha; Khu Trung tâm Logistics 159,70 ha; Khu Cảng tổng hợp 50,58 ha, đầu tư cơ sở hạ tầng san nền, đường giao thông, hạ tầng kỹ thuật, cảng thủy nội địa đồng bộ',
    link: '#',
    imgSrc: _themeUri + '/assets/images/cang-can-tay-ninh.jpg',
    imgAlt: 'Thị xã Trảng Bàng, tỉnh Tây Ninh'
  },
  'tp-hcm': {
    city: 'TP. HỒ CHÍ MINH',
    project: 'Cảng Contaner Cát Lái',
    desc: 'Diện tích 80ha; chiều dài bến 1.462m, tiếp nhận tảu Container đến 45.000DWT; công suất 2,5 triệu TEU/năm',
    link: '#',
    imgSrc: _themeUri + '/assets/images/cang-cat-lai.jpg',
    imgAlt: 'Cảng Contaner Cát Lái, TP. Hồ Chí Minh'
  },
  'dong-nai': {
    city: 'ĐỒNG NAI',
    project: 'ICD Tân Cảng Long Bình',
    desc: 'Tổng diện tích 235 ha, diện tích bãi container 15,6ha, diện tích kho 52,4ha',
    link: '#',
    imgSrc: _themeUri + '/assets/images/tan-cang-long-binh.jpg',
    imgAlt: 'Thành phố Biên Hòa, tỉnh Đồng Nai'
  }
};

// Override location data từ ACF Options (wp_localize_script → window.CMB_LocationData)
if (window.CMB_LocationData) {
  Object.keys(window.CMB_LocationData).forEach(function (key) {
    if (_locationData[key]) {
      Object.assign(_locationData[key], window.CMB_LocationData[key]);
    }
  });
}

function _updateLocPanel(locKey) {
  var data = _locationData[locKey];
  if (!data || !_locPanel) return;

  _locPanel.style.transition = 'opacity 0.18s ease, transform 0.18s ease';
  _locPanel.style.opacity = '0';
  _locPanel.style.transform = 'translateY(6px)';

  var imgReady = !data.imgSrc, timerDone = false;

  function tryShow() {
    if (!imgReady || !timerDone) return;
    if (_locCityEl) _locCityEl.textContent = data.city;
    if (_locProjectEl) _locProjectEl.textContent = data.project;
    if (_locDescEl) _locDescEl.textContent = data.desc;
    if (_locLinkEl) _locLinkEl.href = data.link;
    if (_locImgEl && data.imgSrc) _locImgEl.src = data.imgSrc;
    if (_locImgEl) _locImgEl.alt = data.imgAlt;
    _locPanel.style.opacity = '1';
    _locPanel.style.transform = 'translateY(0)';
  }

  setTimeout(function () { timerDone = true; tryShow(); }, 180);

  if (data.imgSrc) {
    var preload = new Image();
    preload.onload = preload.onerror = function () { imgReady = true; tryShow(); };
    preload.src = data.imgSrc;
  }
}

function _openLocPopup(locKey) {
  var data = _locationData[locKey];
  if (!data || !_locPopup) return;
  if (_popupCityEl) _popupCityEl.textContent = data.city;
  if (_popupProjectEl) _popupProjectEl.textContent = data.project;
  if (_popupDescEl) _popupDescEl.textContent = data.desc;
  if (_popupImgEl && data.imgSrc) _popupImgEl.src = data.imgSrc;
  if (_popupImgEl) _popupImgEl.alt = data.imgAlt;
  _locPopup.classList.add('is-open');
  _locPopup.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
}

function _closeLocPopup() {
  if (!_locPopup) return;
  _locPopup.classList.remove('is-open');
  _locPopup.setAttribute('aria-hidden', 'true');
  document.body.style.overflow = '';
}

(function inlineLocationMapSVG() {
  var wrap = document.getElementById('location-map-wrap');
  var imgEl = wrap && wrap.querySelector('.p-location__map-img');
  if (!imgEl) return;

  fetch(imgEl.src)
    .then(function (r) { return r.text(); })
    .then(function (svgText) {
      var parser = new DOMParser();
      var doc = parser.parseFromString(svgText, 'image/svg+xml');
      var svg = doc.querySelector('svg');
      if (!svg) return;

      svg.classList.add('p-location__map-svg');
      svg.removeAttribute('width');
      svg.removeAttribute('height');

      // 1. Extract golden wheel image from SVG pattern → apply to orbit element
      var wheelImgEl = svg.querySelector('#image0_57023_32');
      if (wheelImgEl) {
        var wheelSrc = wheelImgEl.getAttribute('href') ||
          wheelImgEl.getAttributeNS('http://www.w3.org/1999/xlink', 'href');
        if (wheelSrc) {
          var orbitWheel = document.querySelector('.p-location__wheel');
          if (orbitWheel) orbitWheel.src = wheelSrc;
        }
      }
      // Hide the static wheel from its original position in the map
      var staticWheel = svg.querySelector('circle[cx="173.375"]');
      if (staticWheel) staticWheel.style.display = 'none';

      // 2. Remove drop-shadow filters that cause white glow under map
      svg.querySelectorAll('[filter]').forEach(function (el) {
        var f = el.getAttribute('filter') || '';
        if (f.indexOf('filter0_d_57023_32') !== -1 || f.indexOf('filter1_d_57023_32') !== -1) {
          el.removeAttribute('filter');
        }
      });

      // 3. Animate dashed connector lines
      svg.querySelectorAll('[stroke-dasharray]').forEach(function (el, i) {
        el.classList.add('p-location__map-line');
        el.style.animationDelay = (-i * 0.2) + 's';
      });

      // 4. HẢI PHÒNG has blue decorative outer layers in SVG that look like shadow.
      //    Hide them by default so all 5 cities look consistent; show only when active.
      var hpBlueOuter = null, hpBlueMid = null;
      svg.querySelectorAll('path').forEach(function (p) {
        var d = p.getAttribute('d') || '';
        if (d.startsWith('M543.301')) hpBlueOuter = p;
        if (d.startsWith('M547.301')) hpBlueMid = p;
      });
      if (hpBlueOuter) hpBlueOuter.style.display = 'none';
      if (hpBlueMid) hpBlueMid.style.display = 'none';

      // Hide HP dot's static outer rings (r > 12 at the HP dot position)
      // Ripple animation replaces them — so inactive HP looks the same as other city dots
      svg.querySelectorAll('circle').forEach(function (c) {
        var ccx = parseFloat(c.getAttribute('cx') || '0');
        var ccy = parseFloat(c.getAttribute('cy') || '0');
        var r = parseFloat(c.getAttribute('r') || '0');
        if (Math.abs(ccx - 462.818) < 1 && Math.abs(ccy - 229.352) < 1 && r > 12) {
          c.style.display = 'none';
        }
      });

      // 5. Tag each city's white label box for shadow toggling
      var cityBoxes = [
        { id: 'hai-phong', x: 543, y: 156, w: 188, h: 55, bgStart: 'M551.301' },
        { id: 'nghe-an', x: 224, y: 202, w: 130, h: 39, bgStart: 'M224.207' },
        { id: 'tp-hcm', x: 210, y: 755, w: 191, h: 39, bgStart: 'M210.223' },
        { id: 'dong-nai', x: 677, y: 736, w: 138, h: 39, bgStart: 'M677.66' },
        { id: 'tay-ninh', x: 366, y: 679, w: 130, h: 39, bgStart: 'M366.98' }
      ];

      svg.querySelectorAll('path').forEach(function (path) {
        var d = path.getAttribute('d') || '';
        cityBoxes.forEach(function (box) {
          if (d.startsWith(box.bgStart)) path.dataset.locBg = box.id;
        });
      });

      imgEl.replaceWith(svg);

      // City dot positions (SVG viewBox 980×981) — inner white circle coordinates
      var cityDotCoords = {
        'hai-phong': { cx: 462.818, cy: 229.352 },
        'nghe-an': { cx: 428.541, cy: 251.448 },
        'tp-hcm': { cx: 521.286, cy: 806.690 },
        'dong-nai': { cx: 564.776, cy: 789.622 },
        'tay-ninh': { cx: 603.876, cy: 743.123 }
      };

      // Inject 2 ripple rings per city dot, inserted before the dot's outermost circle
      var dotRippleRings = {};
      Object.keys(cityDotCoords).forEach(function (locId) {
        var coords = cityDotCoords[locId];
        // Find all circles at this position (tolerance 1px)
        var group = [];
        svg.querySelectorAll('circle').forEach(function (c) {
          var ccx = parseFloat(c.getAttribute('cx') || '0');
          var ccy = parseFloat(c.getAttribute('cy') || '0');
          if (Math.abs(ccx - coords.cx) < 1 && Math.abs(ccy - coords.cy) < 1) {
            group.push(c);
          }
        });
        if (!group.length) return;
        // Sort by r ascending; first = inner dot (base radius for ripple)
        group.sort(function (a, b) {
          return parseFloat(a.getAttribute('r')) - parseFloat(b.getAttribute('r'));
        });
        var baseR = parseFloat(group[0].getAttribute('r')) || 9;
        var anchor = group[group.length - 1]; // insert before outermost → renders behind dot group

        var rings = [];
        [false, true].forEach(function (delayed) {
          var ring = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
          ring.setAttribute('cx', coords.cx);
          ring.setAttribute('cy', coords.cy);
          ring.setAttribute('r', baseR);
          ring.setAttribute('fill', 'none');
          ring.setAttribute('stroke', '#8BCBFF');
          ring.setAttribute('stroke-width', '1.5');
          ring.setAttribute('data-loc-ripple', locId);
          if (delayed) ring.setAttribute('data-ripple-delay', '1');
          anchor.parentNode.insertBefore(ring, anchor);
          rings.push(ring);
        });
        dotRippleRings[locId] = rings;
      });

      function setActiveLabel(locKey) {
        // Strong shadow on active box, light shadow on all others (via CSS)
        svg.querySelectorAll('path[data-loc-bg]').forEach(function (p) {
          p.classList.toggle('is-loc-active', p.dataset.locBg === locKey);
        });
        // Ripple rings: activate only the selected city's dot
        Object.keys(dotRippleRings).forEach(function (id) {
          dotRippleRings[id].forEach(function (ring) {
            ring.classList.toggle('is-loc-ripple-active', id === locKey);
          });
        });
      }

      // 5. Transparent clickable overlay rects positioned over each city label box
      cityBoxes.forEach(function (box) {
        var rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        rect.setAttribute('x', box.x);
        rect.setAttribute('y', box.y);
        rect.setAttribute('width', box.w);
        rect.setAttribute('height', box.h);
        rect.setAttribute('rx', '6');
        rect.dataset.loc = box.id;
        svg.appendChild(rect);

        rect.addEventListener('click', function () {
          if (window.innerWidth > 1024) {
            setActiveLabel(box.id);
            _updateLocPanel(box.id);
          } else {
            _openLocPopup(box.id);
          }
        });
      });

      // HẢI PHÒNG active by default — desktop only
      if (window.innerWidth > 1024) {
        setActiveLabel('hai-phong');
      }

      // Khi resize: đồng bộ active state với breakpoint
      window.addEventListener('resize', function () {
        if (window.innerWidth <= 1024) {
          // Mobile: bỏ toàn bộ active, ẩn HP blue layers
          svg.querySelectorAll('path[data-loc-bg]').forEach(function (p) {
            p.classList.remove('is-loc-active');
          });
          if (hpBlueOuter) hpBlueOuter.style.display = 'none';
          if (hpBlueMid) hpBlueMid.style.display = 'none';
          // Clear all ripple rings on mobile
          Object.keys(dotRippleRings).forEach(function (id) {
            dotRippleRings[id].forEach(function (ring) {
              ring.classList.remove('is-loc-ripple-active');
            });
          });
        } else {
          // Desktop: set HẢI PHÒNG active nếu chưa có city nào active
          var hasActive = svg.querySelector('path[data-loc-bg].is-loc-active');
          if (!hasActive) setActiveLabel('hai-phong');
        }
      });
    })
    .catch(function () { });
})();

(function initLocationMap() {
  _locPanel = document.getElementById('location-panel');
  _locCityEl = document.getElementById('location-city-name');
  _locProjectEl = document.getElementById('location-project');
  _locDescEl = document.getElementById('location-desc');
  _locLinkEl = document.getElementById('location-link');
  _locImgEl = document.getElementById('location-img');

  // Fill panel with default city data immediately (no animation)
  var def = _locationData['hai-phong'];
  if (def) {
    if (_locCityEl) _locCityEl.textContent = def.city;
    if (_locProjectEl) _locProjectEl.textContent = def.project;
    if (_locDescEl) _locDescEl.textContent = def.desc;
    if (_locImgEl && def.imgSrc) _locImgEl.src = def.imgSrc;
    if (_locImgEl) _locImgEl.alt = def.imgAlt;
  }

  // Mobile popup
  _locPopup = document.getElementById('location-popup');
  _popupCityEl = document.getElementById('popup-city-name');
  _popupProjectEl = document.getElementById('popup-project');
  _popupDescEl = document.getElementById('popup-desc');
  _popupImgEl = document.getElementById('popup-img');

  var closeBtn = document.getElementById('location-popup-close');
  if (closeBtn) closeBtn.addEventListener('click', _closeLocPopup);
  if (_locPopup) {
    _locPopup.addEventListener('click', function (e) {
      if (e.target === _locPopup) _closeLocPopup();
    });
  }

  // Escape key closes popup
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') _closeLocPopup();
  });
})();


// ============================================
// FIELDS OF OPERATION: Swiper coverflow
// ============================================
(function initFieldSwiper() {
  const el = document.querySelector('.p-field__swiper');
  if (!el) return;

  new Swiper(el, {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    initialSlide: 1,
    speed: 500,
    coverflowEffect: {
      rotate: 40,
      stretch: 0,
      depth: 280,
      modifier: 1,
      slideShadows: false,
    },
    pagination: {
      el: '.p-field__pagination',
      clickable: true,
    },
    on: {
      afterInit: function (swiper) {
        requestAnimationFrame(() => swiper.update());
      },
    },
    breakpoints: {
      0: {
        coverflowEffect: {
          rotate: 25,
          stretch: 0,
          depth: 140,
          modifier: 1,
          slideShadows: false,
        },
      },
      1024: {
        coverflowEffect: {
          rotate: 40,
          stretch: 0,
          depth: 280,
          modifier: 1,
          slideShadows: false,
        },
      },
    },
  });
})();




// ============================================
// PROJECT FILTER TABS
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
// PRELOADER
// ============================================
(function initPreloader() {
  const preloader = document.getElementById('page-preloader');
  if (!preloader) return;

  const line = preloader.querySelector('.preloader__line');
  const logo = preloader.querySelector('.preloader__logo');

  document.body.classList.add('has-preloader');

  // Thời gian animation fill chạy xong (delay 0.2s + duration 1.4s)
  const FILL_END = 1600;
  const startAt = performance.now();
  var pageLoaded = false;
  var fillDone = false;

  function runComplete() {
    if (!pageLoaded || !fillDone) return;
    if (line) {
      // Đọc width thực tế lúc này (animation đã chạy xong nên đang ở 70%)
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

  // Đợi animation fill chạy xong (1.6s từ lúc init)
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

  // Chỉ hiện overlay trắng ngay lập tức, không chạy animation bar
  // Destination page sẽ tự chạy animation của nó
  function showOverlay() {
    // Tắt transition để overlay hiện tức thì (không fade-in chậm)
    preloader.style.transition = 'none';
    preloader.classList.remove('is-done');
    document.body.classList.remove('is-loaded');
    void preloader.offsetWidth;
    preloader.style.transition = '';
    // Giữ bar và logo ở trạng thái tĩnh (không chạy animation)
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
// LEADERSHIP SWIPER INIT
// ============================================
(function () {
  var el = document.querySelector('#leadership-swiper');
  if (!el) return;
  new Swiper(el, {
    loop: true,
    speed: 600,
    slidesPerView: 1,
    spaceBetween: 24,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    breakpoints: {
      600: { slidesPerView: 2, spaceBetween: 28 },
      900: { slidesPerView: 3, spaceBetween: 32 },
      1024: { slidesPerView: 4, spaceBetween: 36 },
    }
  });
})();

// ============================================
// EQUIP POPUP SWIPER INIT
// ============================================
(function () {
  var modal = document.getElementById('equipment-modal');
  var overlay = document.getElementById('modal-overlay');
  var closeBtn = document.getElementById('modal-close');
  var images = document.getElementById('modal-images');
  var desc = document.getElementById('modal-desc');
  if (!modal || !overlay || !closeBtn) return;

  function openModal(card) {
    var title = card.getAttribute('data-title');
    var text = card.getAttribute('data-desc');
    var imageUrls = [];
    try { imageUrls = JSON.parse(card.getAttribute('data-images') || '[]'); } catch (e) { }

    images.innerHTML = imageUrls.map(function (url) {
      return '<div class="p-equipment-modal__img-wrap"><img src="' + url + '" alt="' + title + '" class="p-equipment-modal__img" loading="lazy"/></div>';
    }).join('');

    desc.textContent = text;

    modal.setAttribute('aria-label', title);
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  document.querySelectorAll('.js-equip-card').forEach(function (card) {
    card.addEventListener('click', function (e) {
      e.preventDefault();
      openModal(card);
    });
  });

  overlay.addEventListener('click', closeModal);
  closeBtn.addEventListener('click', closeModal);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
  });
})();

// ============================================
// INDUSTRY NEWS TABS
// ============================================
(function () {
  var tabs = document.querySelectorAll('.p-ir-tabs__link');
  var panels = document.querySelectorAll('.p-ir-panel');

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

// ============================================
// NEWS FEATUERD SWIPER
// ============================================
(function () {
  var el = document.querySelector('.p-news-columns__featured-swiper');
  if (!el) return;
  new Swiper(el, {
    loop: true,
    speed: 800,
    autoplay: { delay: 5000, disableOnInteraction: false },
    pagination: {
      el: '.p-news-columns__featured-pagination',
      clickable: true,
    },
    effect: 'fade',
    fadeEffect: { crossFade: true },
  });
})();

// ============================================
// NEWS FILTER — AJAX (archive.php)
// ============================================
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


// ============================================
// EVENT GALLERY LIGHTBOX (single.php)
// ============================================
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

  // Close on backdrop click (not on image)
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

// ============================================
// GOOGLE MAP (single.php)
// ============================================
(function () {
  var offices = document.querySelectorAll('.p-lh-office[data-map-src]');
  var mapIframe = document.querySelector('#google-map iframe');
  if (!offices.length || !mapIframe) return;

  function activateOffice(el) {
    offices.forEach(function (o) {
      o.classList.remove('p-lh-office--active');
      o.setAttribute('aria-pressed', 'false');
      // swap pin icon to outline
      var path = o.querySelector('.p-lh-office__pin path');
      if (path) {
        path.removeAttribute('fill');
        path.setAttribute('stroke', 'currentColor');
        path.setAttribute('stroke-width', '1.5');
        path.setAttribute('stroke-linejoin', 'round');
      }
      var circle = o.querySelector('.p-lh-office__pin circle');
      if (circle) {
        circle.removeAttribute('fill');
        circle.setAttribute('stroke', 'currentColor');
        circle.setAttribute('stroke-width', '1.5');
      }
    });

    el.classList.add('p-lh-office--active');
    el.setAttribute('aria-pressed', 'true');
    // swap pin icon to filled
    var path = el.querySelector('.p-lh-office__pin path');
    if (path) {
      path.setAttribute('fill', 'currentColor');
      path.removeAttribute('stroke');
      path.removeAttribute('stroke-width');
      path.removeAttribute('stroke-linejoin');
    }
    var circle = el.querySelector('.p-lh-office__pin circle');
    if (circle) {
      circle.setAttribute('fill', 'white');
      circle.removeAttribute('stroke');
      circle.removeAttribute('stroke-width');
    }

    mapIframe.src = el.dataset.mapSrc;
  }

  offices.forEach(function (office) {
    office.addEventListener('click', function (e) {
      // don't hijack tel: link clicks
      if (e.target.closest('a')) return;
      activateOffice(office);
    });
    office.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        activateOffice(office);
      }
    });
  });
})();

// ============================================
// SEARCH OVERLAY
// ============================================
(function () {
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

// ============================================
// PROJECTS FILTER & VIEW TOGGLE
// ============================================
(function () {
  // Filter tabs
  var tabs = document.querySelectorAll('.p-projects-filter__tab');
  var cards = document.querySelectorAll('.p-projects-card[data-category]');

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

  // View toggle
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

// ============================================
// UTILS: Expose to global scope nếu cần
// ============================================
window.CMB = {
  version: '1.0.0',
};
