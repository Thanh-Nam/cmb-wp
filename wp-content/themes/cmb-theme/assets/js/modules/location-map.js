/**
 * modules/location-map.js - CMB Theme
 * Inline SVG + Interactive city label boxes.
 * Toàn bộ SVG fetch được wrap trong lazyInit — chỉ fetch map.svg
 * khi #location-map-wrap vào viewport.
 *
 * Global vars trong cùng module scope:
 * _locPanel, _locCityEl, _locSliderWrapper, _locSwiper
 * _locPopup, _popupCityEl, _popupSliderWrapper, _popupSwiper
 * _themeUri, _locationData
 *
 * Mỗi tỉnh/thành có thể có NHIỀU dự án (data.projects — mảng), hiển thị
 * dạng slide (Swiper) trong panel desktop và popup mobile.
 *
 * Nhãn tỉnh trong map.svg là chữ đã vector hoá (path), không thể chèn
 * text mới theo đúng kiểu cũ. Vì vậy: Hải Phòng / TP. Hồ Chí Minh / Đồng Nai
 * (vị trí đã đúng) giữ nguyên nhãn gốc trong SVG; các tỉnh còn lại (mới thêm,
 * hoặc bị sai vị trí như Nghệ An / Tây Ninh) dùng nhãn HTML/CSS đè lên bản đồ,
 * định vị theo % toạ độ viewBox (980 × 981) để luôn khớp khi resize.
 */

'use strict';

(function () {

  // Panel element references (assigned by initLocationMap, used by SVG click handlers)
  var _locPanel, _locCityEl, _locSliderWrapper, _locSwiper;
  var _locPopup, _popupCityEl, _popupSliderWrapper, _popupSwiper;

  var _themeUri = (window.CMB_Theme && window.CMB_Theme.uri) ? window.CMB_Theme.uri.replace(/\/$/, '') : '';
  var _placeholderImg = _themeUri + '/assets/images/demo-du-an.png';

  function _proj(project, desc, link, imgSrc, imgAlt) {
    return { project: project, desc: desc, link: link || '#', imgSrc: imgSrc, imgAlt: imgAlt };
  }

  var _locationData = {
    'nghe-an': {
      city: 'NGHỆ AN',
      projects: [_proj(
        'Cảng tổng hợp Đông Hồi, Quỳnh Lưu',
        'Tư vấn lập dự án đầu tư và thiết kế cơ sở Cảng tổng hợp Đông Hồi tại huyện Quỳnh Lưu, Nghệ An, công suất 5 triệu tấn/năm.',
        '#', _themeUri + '/assets/images/cang-tong-hop-dong-hoi.png', 'Cảng tổng hợp Đông Hồi, Nghệ An'
      )]
    },
    'hai-phong': {
      city: 'HẢI PHÒNG',
      projects: [_proj(
        'Cảng Đình Vũ',
        'Diện tích 73,56ha; chiều dài bến 1.610,6m, tiếp nhận tàu 20.000 – 50.000 DW T; công suất 15 triệu tấn/năm',
        '#', _themeUri + '/assets/images/cang-dinh-vu.png', 'Cảng Đình Vũ'
      )]
    },
    'tay-ninh': {
      city: 'TÂY NINH',
      projects: [_proj(
        'Trung tâm Logistics, cảng Cạn cảng tổng hợp Tây Ninh',
        'Khu Cảng cạn 48,94 ha; Khu Trung tâm Logistics 159,70 ha; Khu Cảng tổng hợp 50,58 ha, đầu tư cơ sở hạ tầng san nền, đường giao thông, hạ tầng kỹ thuật, cảng thủy nội địa đồng bộ',
        '#', _themeUri + '/assets/images/cang-can-tay-ninh.jpg', 'Thị xã Trảng Bàng, tỉnh Tây Ninh'
      )]
    },
    'tp-hcm': {
      city: 'TP. HỒ CHÍ MINH',
      projects: [_proj(
        'Cảng Contaner Cát Lái',
        'Diện tích 80ha; chiều dài bến 1.462m, tiếp nhận tảu Container đến 45.000DWT; công suất 2,5 triệu TEU/năm',
        '#', _themeUri + '/assets/images/cang-cat-lai.jpg', 'Cảng Contaner Cát Lái, TP. Hồ Chí Minh'
      )]
    },
    'dong-nai': {
      city: 'ĐỒNG NAI',
      projects: [_proj(
        'ICD Tân Cảng Long Bình',
        'Tổng diện tích 235 ha, diện tích bãi container 15,6ha, diện tích kho 52,4ha',
        '#', _themeUri + '/assets/images/tan-cang-long-binh.jpg', 'Thành phố Biên Hòa, tỉnh Đồng Nai'
      )]
    },
    'quang-ninh': { city: 'QUẢNG NINH', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Quảng Ninh')] },
    'thanh-hoa': { city: 'THANH HÓA', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Thanh Hóa')] },
    'quang-tri': { city: 'QUẢNG TRỊ', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Quảng Trị')] },
    'da-nang': { city: 'ĐÀ NẴNG', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Đà Nẵng')] },
    'quang-ngai': { city: 'QUẢNG NGÃI', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Quảng Ngãi')] },
    'khanh-hoa': { city: 'KHÁNH HÒA', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Khánh Hòa')] },
    'ninh-thuan': { city: 'NINH THUẬN', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Ninh Thuận')] },
    'binh-thuan': { city: 'BÌNH THUẬN', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Bình Thuận')] },
    'ba-ria-vung-tau': { city: 'BÀ RỊA - VŨNG TÀU', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Bà Rịa - Vũng Tàu')] },
    'tien-giang': { city: 'TIỀN GIANG', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Tiền Giang')] },
    'ben-tre': { city: 'BẾN TRE', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Bến Tre')] },
    'can-tho': { city: 'CẦN THƠ', projects: [_proj('Đang cập nhật', 'Thông tin dự án đang được cập nhật.', '#', _placeholderImg, 'Cần Thơ')] }
  };

  // Override location data từ ACF Options (wp_localize_script → window.CMB_LocationData)
  // Mỗi tỉnh có thể có nhiều dự án — nếu ACF có dữ liệu thì thay thế toàn bộ mảng projects mặc định.
  // Tên tỉnh/thành (city) không lấy từ ACF — cố định sẵn, trùng với nhãn trên bản đồ.
  if (window.CMB_LocationData) {
    Object.keys(window.CMB_LocationData).forEach(function (key) {
      var override = window.CMB_LocationData[key];
      if (!override) return;
      if (!_locationData[key]) return; // tỉnh không có trong danh sách cố định thì bỏ qua
      if (override.projects && override.projects.length) _locationData[key].projects = override.projects;
    });
  }

  function _escapeHtml(str) {
    return String(str == null ? '' : str)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function _slideHtml(p) {
    var img = p.imgSrc || _placeholderImg;
    return (
      '<div class="swiper-slide p-location__slide">' +
        '<div class="p-location__details">' +
          '<div class="p-location__detail-row">' +
            '<span class="p-location__detail-label">Dự án:</span>' +
            '<p class="p-location__detail-text">' + _escapeHtml(p.project) + '</p>' +
          '</div>' +
          '<div class="p-location__detail-row">' +
            '<span class="p-location__detail-label">Mô tả:</span>' +
            '<p class="p-location__detail-text">' + _escapeHtml(p.desc) + '</p>' +
          '</div>' +
        '</div>' +
        '<div class="p-location__img-wrap">' +
          '<img src="' + _escapeHtml(img) + '" alt="' + _escapeHtml(p.imgAlt) + '" class="p-location__img" loading="lazy" />' +
        '</div>' +
        '<a href="' + _escapeHtml(p.link || '#') + '" class="p-location__link" title="Xem chi tiết dự án">' +
          'Xem dự án' +
          '<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' +
            '<path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />' +
          '</svg>' +
        '</a>' +
      '</div>'
    );
  }

  // Khởi tạo/refresh Swiper cho slider dự án (dùng chung cho panel desktop + popup mobile)
  // Link "Xem dự án" nằm NGAY TRONG từng slide (mỗi dự án có link riêng của nó).
  // Nút mũi tên (điều hướng) là UI cố định, đặt đè lên góc dưới-phải bằng CSS
  // để nằm cùng dòng với link — không cần đồng bộ qua JS.
  function _renderProjectSlider(containerEl, wrapperEl, existingSwiper, projects) {
    if (existingSwiper) {
      existingSwiper.destroy(true, true);
    }
    wrapperEl.innerHTML = projects.map(_slideHtml).join('');

    var multi = projects.length > 1;
    var navEl = containerEl.parentNode.querySelector('.p-location__slider-nav');
    if (navEl) navEl.classList.toggle('is-hidden', !multi);

    if (!window.Swiper) return null;

    return new Swiper(containerEl, {
      slidesPerView: 1,
      spaceBetween: 0,
      speed: 450,
      allowTouchMove: multi,
      navigation: multi ? {
        nextEl: containerEl.parentNode.querySelector('.p-location__slider-next'),
        prevEl: containerEl.parentNode.querySelector('.p-location__slider-prev'),
      } : false,
    });
  }

  function _updateLocPanel(locKey) {
    var data = _locationData[locKey];
    if (!data || !_locPanel) return;

    _locPanel.style.transition = 'opacity 0.18s ease, transform 0.18s ease';
    _locPanel.style.opacity = '0';
    _locPanel.style.transform = 'translateY(6px)';

    setTimeout(function () {
      if (_locCityEl) _locCityEl.textContent = data.city;
      _locSwiper = _renderProjectSlider(
        document.getElementById('location-slider'),
        _locSliderWrapper,
        _locSwiper,
        data.projects || []
      );
      _locPanel.style.opacity = '1';
      _locPanel.style.transform = 'translateY(0)';
    }, 180);
  }

  function _openLocPopup(locKey) {
    var data = _locationData[locKey];
    if (!data || !_locPopup) return;
    if (_popupCityEl) _popupCityEl.textContent = data.city;
    _popupSwiper = _renderProjectSlider(
      document.getElementById('popup-slider'),
      _popupSliderWrapper,
      _popupSwiper,
      data.projects || []
    );
    _locPopup.classList.add('is-open');
    _locPopup.setAttribute('aria-hidden', 'false');
    window.CMB.lockScroll();
  }

  function _closeLocPopup() {
    if (!_locPopup) return;
    _locPopup.classList.remove('is-open');
    _locPopup.setAttribute('aria-hidden', 'true');
    window.CMB.unlockScroll();
  }

  // ============================================
  // LOCATION MAP: Inline SVG (lazy — chỉ fetch khi vào viewport)
  // ============================================
  window.CMB_lazyInit('#location-map-wrap', function (wrap) {
    var imgEl = wrap.querySelector('.p-location__map-img');
    if (!imgEl) return;

    var SVG_NS = 'http://www.w3.org/2000/svg';
    var VB_W = 980, VB_H = 981;

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

        // 3. Animate dashed connector lines (bao gồm cả line trang trí gốc)
        svg.querySelectorAll('[stroke-dasharray]').forEach(function (el, i) {
          el.classList.add('p-location__map-line');
          el.style.animationDelay = (-i * 0.2) + 's';
        });

        // 4. HẢI PHÒNG has blue decorative outer layers in SVG
        var hpBlueOuter = null, hpBlueMid = null;
        svg.querySelectorAll('path').forEach(function (p) {
          var d = p.getAttribute('d') || '';
          if (d.startsWith('M543.301')) hpBlueOuter = p;
          if (d.startsWith('M547.301')) hpBlueMid = p;
        });
        if (hpBlueOuter) hpBlueOuter.style.display = 'none';
        if (hpBlueMid) hpBlueMid.style.display = 'none';

        // Hide HP dot's static outer rings (r > 12 at the HP dot position)
        svg.querySelectorAll('circle').forEach(function (c) {
          var ccx = parseFloat(c.getAttribute('cx') || '0');
          var ccy = parseFloat(c.getAttribute('cy') || '0');
          var r = parseFloat(c.getAttribute('r') || '0');
          if (Math.abs(ccx - 462.818) < 1 && Math.abs(ccy - 229.352) < 1 && r > 12) {
            c.style.display = 'none';
          }
        });

        // 4b. Ẩn TOÀN BỘ nhãn gốc (box nền + chữ vector) và connector cũ của
        // map.svg — kể cả 3 tỉnh vị trí đúng (Hải Phòng / TP.HCM / Đồng Nai).
        // Toàn bộ 17 tỉnh dùng LẠI một hệ thống nhãn HTML/CSS + dot + line
        // thống nhất phía dưới để box, dot, line luôn đều nhau cho mọi tỉnh.
        var hidePrefixes = [
          'M551.301', 'M597.735',                      // hải phòng — box + chữ
          'M210.223', 'M239.055', 'M362.875',           // tp.hcm — box + chữ + connector
          'M677.66', 'M704.379', 'M717.695',            // đồng nai — box + chữ + connector
          'M224.207', 'M250.766',                       // nghệ an cũ — box + chữ (vị trí sai)
          'M366.98', 'M395.234', 'M450.57'              // tây ninh cũ — box + chữ + connector (vị trí sai)
        ];
        svg.querySelectorAll('path').forEach(function (p) {
          var d = p.getAttribute('d') || '';
          if (hidePrefixes.some(function (pre) { return d.startsWith(pre); })) {
            p.style.display = 'none';
          }
        });

        // Ẩn TOÀN BỘ dot/halo có sẵn trong artwork — mỗi tỉnh sẽ được vẽ dot
        // mới, đồng nhất kích thước, để tránh chồng dot cũ/mới hoặc sai vị trí.
        var legacyDotCoords = [
          [462.818, 229.352], [428.541, 251.448], [521.286, 806.69],
          [564.776, 789.622], [603.876, 743.123], [578.64, 484.569],
          [607.452, 517.94], [613.526, 536.143], [624.144, 537.66],
          [625.659, 554.345], [643.858, 683.281], [649.937, 652.943],
          [651.444, 674.179], [486.104, 860.756], [489.136, 197.878],
          [493.69, 182.709]
        ];
        svg.querySelectorAll('circle').forEach(function (c) {
          var ccx = parseFloat(c.getAttribute('cx') || '0');
          var ccy = parseFloat(c.getAttribute('cy') || '0');
          var r = parseFloat(c.getAttribute('r') || '0');
          if (r > 15) return; // giữ lại vòng viền lớn quanh bản đồ + halo trang trí riêng
          var isLegacyDot = legacyDotCoords.some(function (p) {
            return Math.abs(ccx - p[0]) < 1 && Math.abs(ccy - p[1]) < 1;
          });
          if (isLegacyDot) c.style.display = 'none';
        });

        // 5. Dữ liệu 17 tỉnh — toạ độ viewBox 980×981, đúng vị trí địa lý thực tế.
        // dot = chấm trên bản đồ; box = tâm nhãn tên tỉnh.
        var PROVINCES = [
          { id: 'quang-ninh', name: 'QUẢNG NINH', dot: { x: 540, y: 160 }, box: { x: 550, y: 80 } },
          { id: 'hai-phong', name: 'HẢI PHÒNG', dot: { x: 493.7, y: 182.7 }, box: { x: 660, y: 183 } },
          { id: 'thanh-hoa', name: 'THANH HÓA', dot: { x: 390, y: 300 }, box: { x: 288, y: 222 } },
          { id: 'nghe-an', name: 'NGHỆ AN', dot: { x: 455, y: 350 }, box: { x: 255, y: 320 } },
          { id: 'quang-tri', name: 'QUẢNG TRỊ', dot: { x: 555, y: 460 }, box: { x: 390, y: 465 } },
          { id: 'da-nang', name: 'ĐÀ NẴNG', dot: { x: 607.5, y: 517.9 }, box: { x: 790, y: 500 } },
          { id: 'quang-ngai', name: 'QUẢNG NGÃI', dot: { x: 600, y: 554.3 }, box: { x: 400, y: 565 } },
          { id: 'khanh-hoa', name: 'KHÁNH HÒA', dot: { x: 643.9, y: 683.3 }, box: { x: 790, y: 595 } },
          { id: 'ninh-thuan', name: 'NINH THUẬN', dot: { x: 650, y: 745 }, box: { x: 830, y: 660 } },
          { id: 'binh-thuan', name: 'BÌNH THUẬN', dot: { x: 595, y: 780 }, box: { x: 790, y: 720 } },
          { id: 'dong-nai', name: 'ĐỒNG NAI', dot: { x: 564.8, y: 789.6 }, box: { x: 280, y: 630 } },
          { id: 'ba-ria-vung-tau', name: 'BÀ RỊA - VŨNG TÀU', dot: { x: 570, y: 815 }, box: { x: 800, y: 780 } },
          { id: 'tay-ninh', name: 'TÂY NINH', dot: { x: 470, y: 770 }, box: { x: 300, y: 690 } },
          { id: 'tp-hcm', name: 'TP. HỒ CHÍ MINH', dot: { x: 521.3, y: 806.7 }, box: { x: 260, y: 758 } },
          { id: 'tien-giang', name: 'TIỀN GIANG', dot: { x: 445, y: 825 }, box: { x: 250, y: 820 } },
          { id: 'ben-tre', name: 'BẾN TRE', dot: { x: 545, y: 828 }, box: { x: 620, y: 895 } },
          { id: 'can-tho', name: 'CẦN THƠ', dot: { x: 452, y: 878 }, box: { x: 470, y: 955 } }
        ];

        var DOT_R = 9;

        imgEl.replaceWith(svg);

        var tagEls = {};
        var dotRippleRings = {};

        PROVINCES.forEach(function (p) {
          // --- Dot: halo mờ + chấm trắng, kích thước bằng nhau cho mọi tỉnh ---
          var halo = document.createElementNS(SVG_NS, 'circle');
          halo.setAttribute('cx', p.dot.x);
          halo.setAttribute('cy', p.dot.y);
          halo.setAttribute('r', DOT_R);
          halo.setAttribute('fill', '#8BCBFF');
          halo.setAttribute('opacity', '0.15');
          svg.appendChild(halo);

          var dot = document.createElementNS(SVG_NS, 'circle');
          dot.setAttribute('cx', p.dot.x);
          dot.setAttribute('cy', p.dot.y);
          dot.setAttribute('r', DOT_R);
          dot.setAttribute('fill', 'white');
          svg.appendChild(dot);

          // --- Ripple: 2 vòng lan toả quanh dot, chỉ hiện khi active ---
          var rings = [];
          [false, true].forEach(function (delayed) {
            var ring = document.createElementNS(SVG_NS, 'circle');
            ring.setAttribute('cx', p.dot.x);
            ring.setAttribute('cy', p.dot.y);
            ring.setAttribute('r', DOT_R);
            ring.setAttribute('fill', 'none');
            ring.setAttribute('stroke', '#8BCBFF');
            ring.setAttribute('stroke-width', '1.5');
            ring.setAttribute('data-loc-ripple', p.id);
            if (delayed) ring.setAttribute('data-ripple-delay', '1');
            svg.insertBefore(ring, dot);
            rings.push(ring);
          });
          dotRippleRings[p.id] = rings;

          // --- Connector: gấp khúc 2 đoạn (ngang rồi CHÉO — không vuông góc),
          // giống style tỉnh gốc: đoạn 1 ngang từ box, đoạn 2 chéo tới dot —
          // dừng lại đúng mép chấm tròn (không đâm xuyên vào tâm) cho gọn.
          var elbowX = p.box.x + (p.dot.x - p.box.x) * 0.5;
          var elbowY = p.box.y;
          var dx = p.dot.x - elbowX, dy = p.dot.y - elbowY;
          var dist = Math.sqrt(dx * dx + dy * dy) || 1;
          var endX = p.dot.x - (dx / dist) * DOT_R;
          var endY = p.dot.y - (dy / dist) * DOT_R;
          var pts = p.box.x + ',' + p.box.y + ' ' + elbowX + ',' + elbowY + ' ' + endX + ',' + endY;
          var line = document.createElementNS(SVG_NS, 'polyline');
          line.setAttribute('points', pts);
          line.setAttribute('class', 'p-location__tag-line');
          svg.insertBefore(line, halo);

          // --- Nhãn HTML (đều size, đè lên bản đồ) ---
          var el = document.createElement('button');
          el.type = 'button';
          el.className = 'p-location__tag';
          el.textContent = p.name;
          el.dataset.loc = p.id;
          el.style.left = (p.box.x / VB_W * 100) + '%';
          el.style.top = (p.box.y / VB_H * 100) + '%';
          wrap.appendChild(el);
          tagEls[p.id] = el;

          el.addEventListener('click', function () {
            if (window.innerWidth > 1024) {
              setActiveLabel(p.id);
              _updateLocPanel(p.id);
            } else {
              _openLocPopup(p.id);
            }
          });
        });

        function setActiveLabel(locKey) {
          Object.keys(tagEls).forEach(function (id) {
            tagEls[id].classList.toggle('is-active', id === locKey);
          });
          Object.keys(dotRippleRings).forEach(function (id) {
            dotRippleRings[id].forEach(function (ring) {
              ring.classList.toggle('is-loc-ripple-active', id === locKey);
            });
          });
        }

        // HẢI PHÒNG active by default — desktop only
        if (window.innerWidth > 1024) {
          setActiveLabel('hai-phong');
        }

        // Khi resize: đồng bộ active state với breakpoint
        window.addEventListener('resize', function () {
          if (window.innerWidth <= 1024) {
            Object.keys(tagEls).forEach(function (id) {
              tagEls[id].classList.remove('is-active');
            });
            if (hpBlueOuter) hpBlueOuter.style.display = 'none';
            if (hpBlueMid) hpBlueMid.style.display = 'none';
            Object.keys(dotRippleRings).forEach(function (id) {
              dotRippleRings[id].forEach(function (ring) {
                ring.classList.remove('is-loc-ripple-active');
              });
            });
          } else {
            var hasActive = Object.keys(tagEls).some(function (id) { return tagEls[id].classList.contains('is-active'); });
            if (!hasActive) setActiveLabel('hai-phong');
          }
        });
      })
      .catch(function () { });
  }, '150px');


  // ============================================
  // LOCATION MAP: Init panel + popup
  // ============================================
  (function initLocationMap() {
    _locPanel = document.getElementById('location-panel');
    _locCityEl = document.getElementById('location-city-name');
    _locSliderWrapper = document.getElementById('location-slider-wrapper');

    // Fill panel with default city data immediately (no animation)
    var def = _locationData['hai-phong'];
    if (def) {
      if (_locCityEl) _locCityEl.textContent = def.city;
      _locSwiper = _renderProjectSlider(
        document.getElementById('location-slider'),
        _locSliderWrapper,
        null,
        def.projects || []
      );
    }

    // Mobile popup
    _locPopup = document.getElementById('location-popup');
    _popupCityEl = document.getElementById('popup-city-name');
    _popupSliderWrapper = document.getElementById('popup-slider-wrapper');

    var closeBtn = document.getElementById('location-popup-close');
    if (closeBtn) closeBtn.addEventListener('click', _closeLocPopup);
    if (_locPopup) {
      _locPopup.addEventListener('click', function (e) {
        if (e.target === _locPopup) _closeLocPopup();
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') _closeLocPopup();
    });
  })();

})();
