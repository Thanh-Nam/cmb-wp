/**
 * modules/location-map.js - CMB Theme
 * Inline SVG + Interactive city label boxes.
 * Toàn bộ SVG fetch được wrap trong lazyInit — chỉ fetch map.svg
 * khi #location-map-wrap vào viewport.
 *
 * Global vars trong cùng module scope:
 * _locPanel, _locCityEl, _locProjectEl, _locDescEl, _locLinkEl, _locImgEl
 * _locPopup, _popupCityEl, _popupProjectEl, _popupDescEl, _popupImgEl
 * _themeUri, _locationData
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
  var _locPanel, _locCityEl, _locProjectEl, _locDescEl, _locLinkEl, _locImgEl;
  var _locPopup, _popupCityEl, _popupProjectEl, _popupDescEl, _popupImgEl;

  var _themeUri = (window.CMB_Theme && window.CMB_Theme.uri) ? window.CMB_Theme.uri.replace(/\/$/, '') : '';
  var _placeholderImg = _themeUri + '/assets/images/demo-du-an.png';

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
    },
    'quang-ninh': { city: 'QUẢNG NINH', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Quảng Ninh' },
    'thanh-hoa': { city: 'THANH HÓA', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Thanh Hóa' },
    'quang-tri': { city: 'QUẢNG TRỊ', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Quảng Trị' },
    'da-nang': { city: 'ĐÀ NẴNG', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Đà Nẵng' },
    'quang-ngai': { city: 'QUẢNG NGÃI', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Quảng Ngãi' },
    'khanh-hoa': { city: 'KHÁNH HÒA', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Khánh Hòa' },
    'ninh-thuan': { city: 'NINH THUẬN', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Ninh Thuận' },
    'binh-thuan': { city: 'BÌNH THUẬN', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Bình Thuận' },
    'ba-ria-vung-tau': { city: 'BÀ RỊA - VŨNG TÀU', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Bà Rịa - Vũng Tàu' },
    'tien-giang': { city: 'TIỀN GIANG', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Tiền Giang' },
    'ben-tre': { city: 'BẾN TRE', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Bến Tre' },
    'can-tho': { city: 'CẦN THƠ', project: 'Đang cập nhật', desc: 'Thông tin dự án đang được cập nhật.', link: '#', imgSrc: _placeholderImg, imgAlt: 'Cần Thơ' }
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
          { id: 'quang-ninh', name: 'QUẢNG NINH', dot: { x: 493.7, y: 182.7 }, box: { x: 550, y: 80 } },
          { id: 'hai-phong', name: 'HẢI PHÒNG', dot: { x: 462.8, y: 229.4 }, box: { x: 660, y: 183 } },
          { id: 'thanh-hoa', name: 'THANH HÓA', dot: { x: 428.5, y: 251.4 }, box: { x: 288, y: 222 } },
          { id: 'nghe-an', name: 'NGHỆ AN', dot: { x: 455, y: 350 }, box: { x: 255, y: 320 } },
          { id: 'quang-tri', name: 'QUẢNG TRỊ', dot: { x: 555, y: 460 }, box: { x: 390, y: 465 } },
          { id: 'da-nang', name: 'ĐÀ NẴNG', dot: { x: 607.5, y: 517.9 }, box: { x: 790, y: 455 } },
          { id: 'quang-ngai', name: 'QUẢNG NGÃI', dot: { x: 600, y: 554.3 }, box: { x: 400, y: 565 } },
          { id: 'khanh-hoa', name: 'KHÁNH HÒA', dot: { x: 643.9, y: 683.3 }, box: { x: 790, y: 595 } },
          { id: 'ninh-thuan', name: 'NINH THUẬN', dot: { x: 650, y: 745 }, box: { x: 830, y: 660 } },
          { id: 'binh-thuan', name: 'BÌNH THUẬN', dot: { x: 595, y: 780 }, box: { x: 790, y: 720 } },
          { id: 'dong-nai', name: 'ĐỒNG NAI', dot: { x: 564.8, y: 789.6 }, box: { x: 280, y: 630 } },
          { id: 'ba-ria-vung-tau', name: 'BÀ RỊA - VŨNG TÀU', dot: { x: 570, y: 815 }, box: { x: 800, y: 780 } },
          { id: 'tay-ninh', name: 'TÂY NINH', dot: { x: 410, y: 775 }, box: { x: 300, y: 690 } },
          { id: 'tp-hcm', name: 'TP. HỒ CHÍ MINH', dot: { x: 521.3, y: 806.7 }, box: { x: 260, y: 758 } },
          { id: 'tien-giang', name: 'TIỀN GIANG', dot: { x: 445, y: 825 }, box: { x: 250, y: 820 } },
          { id: 'ben-tre', name: 'BẾN TRE', dot: { x: 500, y: 850 }, box: { x: 620, y: 895 } },
          { id: 'can-tho', name: 'CẦN THƠ', dot: { x: 486.1, y: 860.8 }, box: { x: 470, y: 955 } }
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
          // giống style tỉnh gốc: đoạn 1 ngang từ box, đoạn 2 chéo tới dot.
          var elbowX = p.box.x + (p.dot.x - p.box.x) * 0.5;
          var elbowY = p.box.y;
          var pts = p.box.x + ',' + p.box.y + ' ' + elbowX + ',' + elbowY + ' ' + p.dot.x + ',' + p.dot.y;
          var line = document.createElementNS(SVG_NS, 'polyline');
          line.setAttribute('points', pts);
          line.setAttribute('class', 'p-location__tag-line');
          svg.appendChild(line);

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

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') _closeLocPopup();
    });
  })();

})();
