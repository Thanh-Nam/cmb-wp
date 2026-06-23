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
 */

'use strict';

(function () {

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

  // ============================================
  // LOCATION MAP: Inline SVG (lazy — chỉ fetch khi vào viewport)
  // ============================================
  window.CMB_lazyInit('#location-map-wrap', function (wrap) {
    var imgEl = wrap.querySelector('.p-location__map-img');
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

        // City dot positions (SVG viewBox 980×981)
        var cityDotCoords = {
          'hai-phong': { cx: 462.818, cy: 229.352 },
          'nghe-an': { cx: 428.541, cy: 251.448 },
          'tp-hcm': { cx: 521.286, cy: 806.690 },
          'dong-nai': { cx: 564.776, cy: 789.622 },
          'tay-ninh': { cx: 603.876, cy: 743.123 }
        };

        // Inject 2 ripple rings per city dot
        var dotRippleRings = {};
        Object.keys(cityDotCoords).forEach(function (locId) {
          var coords = cityDotCoords[locId];
          var group = [];
          svg.querySelectorAll('circle').forEach(function (c) {
            var ccx = parseFloat(c.getAttribute('cx') || '0');
            var ccy = parseFloat(c.getAttribute('cy') || '0');
            if (Math.abs(ccx - coords.cx) < 1 && Math.abs(ccy - coords.cy) < 1) {
              group.push(c);
            }
          });
          if (!group.length) return;
          group.sort(function (a, b) {
            return parseFloat(a.getAttribute('r')) - parseFloat(b.getAttribute('r'));
          });
          var baseR = parseFloat(group[0].getAttribute('r')) || 9;
          var anchor = group[group.length - 1];

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
          svg.querySelectorAll('path[data-loc-bg]').forEach(function (p) {
            p.classList.toggle('is-loc-active', p.dataset.locBg === locKey);
          });
          Object.keys(dotRippleRings).forEach(function (id) {
            dotRippleRings[id].forEach(function (ring) {
              ring.classList.toggle('is-loc-ripple-active', id === locKey);
            });
          });
        }

        // Transparent clickable overlay rects positioned over each city label box
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
            svg.querySelectorAll('path[data-loc-bg]').forEach(function (p) {
              p.classList.remove('is-loc-active');
            });
            if (hpBlueOuter) hpBlueOuter.style.display = 'none';
            if (hpBlueMid) hpBlueMid.style.display = 'none';
            Object.keys(dotRippleRings).forEach(function (id) {
              dotRippleRings[id].forEach(function (ring) {
                ring.classList.remove('is-loc-ripple-active');
              });
            });
          } else {
            var hasActive = svg.querySelector('path[data-loc-bg].is-loc-active');
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
