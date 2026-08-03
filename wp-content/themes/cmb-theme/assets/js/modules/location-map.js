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
  var _lang = (window.CMB_Theme && window.CMB_Theme.lang) ? window.CMB_Theme.lang : 'vi';
  var _placeholderImg = _themeUri + '/assets/images/demo-du-an.png';

  function _t(vi, en) {
    return _lang === 'en' ? en : vi;
  }

  // vi/en đi theo cặp: project, desc, imgAlt đều có bản EN riêng
  function _proj(project, projectEn, desc, descEn, link, imgSrc, imgAlt, imgAltEn) {
    return {
      project: _t(project, projectEn),
      desc: _t(desc, descEn),
      link: link || '#',
      imgSrc: imgSrc,
      imgAlt: _t(imgAlt, imgAltEn)
    };
  }

  var _updating = _t('Đang cập nhật', 'Updating');
  var _updatingDesc = _t('Thông tin dự án đang được cập nhật.', 'Project information is being updated.');

  function _placeholderProj(cityVi, cityEn) {
    return _proj(_updating, _updating, _updatingDesc, _updatingDesc, '#', _placeholderImg, cityVi, cityEn);
  }

  var _locationData = {
    'nghe-an': { city: _t('NGHỆ AN', 'NGHE AN'), projects: [_placeholderProj('Nghệ An', 'Nghe An')] },
    'hai-phong': { city: _t('HẢI PHÒNG', 'HAI PHONG'), projects: [_placeholderProj('Hải Phòng', 'Hai Phong')] },
    'tay-ninh': { city: _t('TÂY NINH', 'TAY NINH'), projects: [_placeholderProj('Tây Ninh', 'Tay Ninh')] },
    'tp-hcm': { city: _t('TP. HỒ CHÍ MINH', 'HO CHI MINH CITY'), projects: [_placeholderProj('TP. Hồ Chí Minh', 'Ho Chi Minh City')] },
    'dong-nai': { city: _t('ĐỒNG NAI', 'DONG NAI'), projects: [_placeholderProj('Đồng Nai', 'Dong Nai')] },
    'quang-ninh': { city: _t('QUẢNG NINH', 'QUANG NINH'), projects: [_placeholderProj('Quảng Ninh', 'Quang Ninh')] },
    'thanh-hoa': { city: _t('THANH HÓA', 'THANH HOA'), projects: [_placeholderProj('Thanh Hóa', 'Thanh Hoa')] },
    'quang-tri': { city: _t('QUẢNG TRỊ', 'QUANG TRI'), projects: [_placeholderProj('Quảng Trị', 'Quang Tri')] },
    'da-nang': { city: _t('ĐÀ NẴNG', 'DA NANG'), projects: [_placeholderProj('Đà Nẵng', 'Da Nang')] },
    'quang-ngai': { city: _t('QUẢNG NGÃI', 'QUANG NGAI'), projects: [_placeholderProj('Quảng Ngãi', 'Quang Ngai')] },
    'khanh-hoa': { city: _t('KHÁNH HÒA', 'KHANH HOA'), projects: [_placeholderProj('Khánh Hòa', 'Khanh Hoa')] },
    'lam-dong': { city: _t('LÂM ĐỒNG', 'LAM DONG'), projects: [_placeholderProj('Lâm Đồng', 'Lam Dong')] },
    'dong-thap': { city: _t('ĐỒNG THÁP', 'DONG THAP'), projects: [_placeholderProj('Đồng Tháp', 'Dong Thap')] },
    'vinh-long': { city: _t('VĨNH LONG', 'VINH LONG'), projects: [_placeholderProj('Vĩnh Long', 'Vinh Long')] },
    'can-tho': { city: _t('CẦN THƠ', 'CAN THO'), projects: [_placeholderProj('Cần Thơ', 'Can Tho')] }
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

  // Thứ tự cố định để chọn tỉnh mặc định (đầu tiên có dự án thật) khi cần —
  // trùng danh sách 17 id ở PROVINCES bên dưới, chỉ cần đúng tập id, thứ tự
  // ưu tiên bắc → nam.
  var _provinceOrder = [
    'quang-ninh', 'hai-phong', 'thanh-hoa', 'nghe-an', 'quang-tri', 'da-nang',
    'quang-ngai', 'khanh-hoa', 'lam-dong', 'dong-nai',
    'tay-ninh', 'tp-hcm', 'dong-thap', 'vinh-long', 'can-tho'
  ];

  // Tỉnh chỉ được coi là "đã gắn dự án" khi có dữ liệu thật từ CMB_LocationData
  // (không tính placeholder "Đang cập nhật") — dùng để ẩn dot/nhãn trên bản đồ.
  function _hasRealProjects(id) {
    return !!(window.CMB_LocationData && window.CMB_LocationData[id] &&
      window.CMB_LocationData[id].projects && window.CMB_LocationData[id].projects.length);
  }

  function _getDefaultCityKey() {
    for (var i = 0; i < _provinceOrder.length; i++) {
      if (_hasRealProjects(_provinceOrder[i])) return _provinceOrder[i];
    }
    return 'hai-phong'; // fallback nếu chưa tỉnh nào gắn dự án thật (tránh panel trống)
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
      '<span class="p-location__detail-label">' + _escapeHtml(_t('Dự án:', 'Project:')) + '</span>' +
      '<p class="p-location__detail-text p-location__detail-text--title" title="' + _escapeHtml(p.project) + '">' + _escapeHtml(p.project) + '</p>' +
      '</div>' +
      '<div class="p-location__detail-row">' +
      '<span class="p-location__detail-label">' + _escapeHtml(_t('Mô tả:', 'Description:')) + '</span>' +
      '<p class="p-location__detail-text p-location__detail-text--desc" title="' + _escapeHtml(p.desc) + '">' + _escapeHtml(p.desc) + '</p>' +
      '</div>' +
      '</div>' +
      '<div class="p-location__img-wrap">' +
      '<img src="' + _escapeHtml(img) + '" alt="' + _escapeHtml(p.imgAlt) + '" class="p-location__img" loading="lazy" />' +
      '</div>' +
      '<a href="' + _escapeHtml(p.link || '#') + '" class="p-location__link" title="' + _escapeHtml(_t('Xem chi tiết dự án', 'View project details')) + '">' +
      _escapeHtml(_t('Xem dự án', 'View project')) +
      '<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' +
      '<path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />' +
      '</svg>' +
      '</a>' +
      '</div>'
    );
  }

  // Khởi tạo/refresh Swiper cho slider dự án (dùng chung cho panel desktop + popup mobile)
  // Link "Xem dự án" nằm NGAY TRONG từng slide (mỗi dự án có link riêng của nó).
  // Nút mũi tên (điều hướng) nằm NGAY TRONG khung slider, đè lên góc trên-phải
  // để luôn ngang hàng với dòng "Dự án:" (dòng đầu của slide) — không cần JS đồng bộ.
  function _renderProjectSlider(containerEl, wrapperEl, existingSwiper, projects) {
    if (existingSwiper) {
      existingSwiper.destroy(true, true);
    }
    wrapperEl.innerHTML = projects.map(_slideHtml).join('');

    var multi = projects.length > 1;
    var navEl = containerEl.querySelector('.p-location__slider-nav');
    if (navEl) navEl.classList.toggle('is-hidden', !multi);

    if (!window.Swiper) return null;

    return new Swiper(containerEl, {
      slidesPerView: 1,
      spaceBetween: 0,
      speed: 450,
      allowTouchMove: multi,
      navigation: multi ? {
        nextEl: containerEl.querySelector('.p-location__slider-next'),
        prevEl: containerEl.querySelector('.p-location__slider-prev'),
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
    // Bỏ focus khỏi phần tử đang active TRƯỚC khi set aria-hidden="true" — nếu
    // không, khi đóng bằng bàn phím/nút X (đang giữ focus) mà phần tử đó nằm
    // trong popup, trình duyệt sẽ chặn aria-hidden và báo warning ra console
    // ("Blocked aria-hidden on an element because its descendant retained
    // focus"), vì phần tử ẩn không được phép chứa phần tử đang có focus.
    if (_locPopup.contains(document.activeElement)) {
      document.activeElement.blur();
    }
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

    // Bánh lái orbit + kích thước nhãn tỉnh trước dùng đơn vị Container Query
    // (cqw) để tỉ lệ theo bề rộng map-wrap — nhưng 1 số bản Safari/iOS không hỗ
    // trợ đúng cqw khiến khai báo bị invalid (bánh lái lệch tâm/không quay, box
    // nhãn co về vừa khít text, mất dấu do overflow:hidden bó sát chữ). Việc
    // đoán ngưỡng theo breakpoint (tablet, rồi 1024px) đều không khớp đúng với
    // thiết bị thật (không debug trực tiếp được) — nên bỏ hẳn breakpoint, tính
    // bằng JS (đáng tin cậy, không phụ thuộc đơn vị CSS đặc biệt nào) cho MỌI
    // kích thước màn hình luôn, không phân biệt mobile/tablet/desktop nữa.
    //
    // Set thẳng inline style lên TỪNG phần tử (left/top/transform-origin của
    // wheel-pivot, width/height/font-size của từng tag) thay vì set 1 custom
    // property trên map-wrap rồi để con kế thừa qua var() — vì 1 số bản WebKit
    // cũ có bug không recompute lại style của phần tử con khi custom property
    // của cha bị đổi qua JS (CSSOM), dù giá trị đã đúng trong DevTools. Set
    // trực tiếp trên chính phần tử tránh hoàn toàn phụ thuộc đó.
    var wheelPivotEl = wrap.querySelector('.p-location__wheel-pivot');

    function clampNum(min, val, max) {
      return Math.max(min, Math.min(max, val));
    }

    function applyTagSizes(w) {
      var tags = wrap.querySelectorAll('.p-location__tag');
      // Sàn (min) trước đây 92px/26px/9px là kích thước tính cho map cỡ tablet
      // trở lên — ở mobile map-wrap chỉ còn ~350-400px, nhiều tỉnh nằm khá gần
      // nhau (vd Thanh Hóa/Nghệ An, Khánh Hòa/Đà Nẵng) nên box giữ nguyên sàn
      // cũ sẽ chạm/đè nhau theo chiều DỌC là chính (khoảng cách giữa các dot
      // hẹp theo trục y hơn trục x). Giảm HEIGHT là chính, giữ nguyên WIDTH —
      // giảm width sẽ làm tên dài như "BÀ RỊA - VŨNG TÀU" bị bó chật/khó đọc.
      var tw = clampNum(92, w * 0.175, 172);
      var th = clampNum(18, w * 0.04, 38);
      var tfs = clampNum(8, w * 0.0135, 14);
      var tr = clampNum(3, w * 0.006, 6);
      var tp = clampNum(4, w * 0.006, 8);
      tags.forEach(function (tag) {
        tag.style.width = tw + 'px';
        tag.style.height = th + 'px';
        tag.style.fontSize = tfs + 'px';
        tag.style.borderRadius = tr + 'px';
        tag.style.padding = '0 ' + tp + 'px';
      });
    }

    function syncMapScale() {
      var w = wrap.getBoundingClientRect().width;
      if (!w) return;
      var orbitR = w * 0.499;
      if (wheelPivotEl) {
        // KHÔNG dùng getBoundingClientRect() ở đây: wheel-pivot đang chạy
        // animation rotate() liên tục (loc-orbit), nên bounding box đo được
        // thay đổi theo góc xoay tại đúng thời điểm gọi hàm (bounding box của
        // 1 hình vuông xoay lệch luôn lớn hơn cạnh gốc, sai lệch tuỳ góc).
        // getComputedStyle().width lấy đúng layout width, không bị transform
        // ảnh hưởng.
        var halfW = (parseFloat(getComputedStyle(wheelPivotEl).width) || 44) / 2;
        wheelPivotEl.style.left = 'calc(50% + ' + orbitR + 'px - ' + halfW + 'px)';
        wheelPivotEl.style.top = 'calc(50% - ' + halfW + 'px)';
        wheelPivotEl.style.transformOrigin = (-orbitR + halfW) + 'px ' + halfW + 'px';
      }
      applyTagSizes(w);
    }

    syncMapScale();
    window.addEventListener('resize', function () {
      clearTimeout(window._cmbLocScaleTimer);
      window._cmbLocScaleTimer = setTimeout(syncMapScale, 150);
    });

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
        // map.svg trước đây nhúng base64 4096x4096 trực tiếp (~8MB) cho ảnh này —
        // đã tách ra file riêng assets/images/wheel-icon.png (đã resize + nén) để
        // map.svg nhẹ hơn nhiều. href giờ là đường dẫn tương đối theo map.svg,
        // không phải theo URL trang hiện tại, nên phải tự ghép với _themeUri.
        var wheelImgEl = svg.querySelector('#image0_57023_32');
        if (wheelImgEl) {
          var wheelSrc = wheelImgEl.getAttribute('href') ||
            wheelImgEl.getAttributeNS('http://www.w3.org/1999/xlink', 'href');
          if (wheelSrc) {
            var isAbsolute = /^(https?:)?\/\//.test(wheelSrc) || wheelSrc.indexOf('data:') === 0;
            var resolvedWheelSrc = isAbsolute ? wheelSrc : (_themeUri + '/assets/images/' + wheelSrc);
            var orbitWheel = document.querySelector('.p-location__wheel');
            if (orbitWheel) orbitWheel.src = resolvedWheelSrc;
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

        // 3. Animate dashed connector lines (bao gồm cả line trang trí gốc).
        // 7 line nét đứt này được vẽ SẴN trong map.svg (không phải tạo động
        // trong PROVINCES.forEach ở dưới), mỗi line toả từ Hà Nội tới đúng toạ
        // độ dot của 1 tỉnh — nên tỉnh nào chưa gắn dự án thật thì ẩn luôn line
        // tương ứng theo toạ độ điểm cuối, tránh còn sót line trỏ tới dot đã ẩn.
        var STATIC_LINE_ENDPOINTS = [
          { x: 595, y: 780, id: 'lam-dong' },
          { x: 530, y: 830, id: 'tp-hcm' },
          { x: 470, y: 770, id: 'tay-ninh' },
          { x: 530, y: 170, id: 'quang-ninh' },
          { x: 490, y: 190, id: 'hai-phong' },
          { x: 430, y: 250, id: 'thanh-hoa' },
          { x: 410, y: 300, id: 'nghe-an' }
        ];
        svg.querySelectorAll('[stroke-dasharray]').forEach(function (el, i) {
          var d = el.getAttribute('d') || '';
          var nums = d.match(/-?\d+(\.\d+)?/g);
          var endX = nums ? parseFloat(nums[nums.length - 2]) : null;
          var endY = nums ? parseFloat(nums[nums.length - 1]) : null;
          var match = null;
          if (endX !== null) {
            STATIC_LINE_ENDPOINTS.some(function (pt) {
              if (Math.abs(endX - pt.x) < 1 && Math.abs(endY - pt.y) < 1) {
                match = pt;
                return true;
              }
              return false;
            });
          }
          if (match && !_hasRealProjects(match.id)) {
            el.style.display = 'none';
            return;
          }
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
          { id: 'quang-ninh', name: _t('QUẢNG NINH', 'QUANG NINH'), dot: { x: 530, y: 170 }, box: { x: 600, y: 80 } },
          { id: 'hai-phong', name: _t('HẢI PHÒNG', 'HAI PHONG'), dot: { x: 490, y: 190 }, box: { x: 660, y: 183 } },
          { id: 'thanh-hoa', name: _t('THANH HÓA', 'THANH HOA'), dot: { x: 430, y: 250 }, box: { x: 300, y: 180 } },
          { id: 'nghe-an', name: _t('NGHỆ AN', 'NGHE AN'), dot: { x: 410, y: 300 }, box: { x: 255, y: 260 } },
          { id: 'quang-tri', name: _t('QUẢNG TRỊ', 'QUANG TRI'), dot: { x: 485, y: 405 }, box: { x: 350, y: 360 } },
          { id: 'da-nang', name: _t('ĐÀ NẴNG', 'DA NANG'), dot: { x: 580, y: 505 }, box: { x: 770, y: 480 } },
          { id: 'quang-ngai', name: _t('QUẢNG NGÃI', 'QUANG NGAI'), dot: { x: 580, y: 570 }, box: { x: 400, y: 505 } },
          { id: 'khanh-hoa', name: _t('KHÁNH HÒA', 'KHANH HOA'), dot: { x: 643.9, y: 720 }, box: { x: 790, y: 620 } },
          { id: 'lam-dong', name: _t('LÂM ĐỒNG', 'LAM DONG'), dot: { x: 595, y: 780 }, box: { x: 790, y: 720 } },
          { id: 'dong-nai', name: _t('ĐỒNG NAI', 'DONG NAI'), dot: { x: 520, y: 770 }, box: { x: 280, y: 630 } },
          { id: 'tay-ninh', name: _t('TÂY NINH', 'TAY NINH'), dot: { x: 470, y: 770 }, box: { x: 300, y: 720 } },
          { id: 'tp-hcm', name: _t('TP. HỒ CHÍ MINH', 'HOCHI MINH CITY'), dot: { x: 530, y: 830 }, box: { x: 760, y: 800 } },
          { id: 'dong-thap', name: _t('ĐỒNG THÁP', 'DONG THAP'), dot: { x: 445, y: 835 }, box: { x: 250, y: 800 } },
          { id: 'vinh-long', name: _t('VĨNH LONG', 'VINH LONG'), dot: { x: 480, y: 860 }, box: { x: 620, y: 865 } },
          { id: 'can-tho', name: _t('CẦN THƠ', 'CAN THO'), dot: { x: 452, y: 878 }, box: { x: 470, y: 955 } }
        ];

        var DOT_R = 9;

        imgEl.replaceWith(svg);

        var tagEls = {};
        var dotRippleRings = {};

        PROVINCES.forEach(function (p) {
          // Tỉnh chưa gắn dự án thật (không có trong CMB_LocationData) — ẩn
          // hẳn dot/nhãn/connector, không render lên bản đồ.
          if (!_hasRealProjects(p.id)) return;

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
          // Đã thử 3 cách khác nhau đều không chạy ổn định trên 1 số bản
          // Safari: CSS transform+transform-box:fill-box, CSS animate thuộc
          // tính r, rồi SMIL (<animate> + beginElement()/endElement() — chính
          // các PHƯƠNG THỨC JS điều khiển SMIL này cũng có lịch sử hỗ trợ chập
          // chờn trên WebKit dù bản thân SMIL declaration thì được). Giờ tự
          // chạy animation hoàn toàn bằng JS thuần (rAF, xem tickRipples() bên
          // dưới) — chỉ set attribute r/opacity trực tiếp mỗi khung hình,
          // không phụ thuộc bất kỳ animation API (CSS hay SMIL) nào nữa nên
          // không thể bị vấn đề hỗ trợ trình duyệt.
          var rings = [];
          [false, true].forEach(function (delayed) {
            var ring = document.createElementNS(SVG_NS, 'circle');
            ring.setAttribute('cx', p.dot.x);
            ring.setAttribute('cy', p.dot.y);
            ring.setAttribute('r', DOT_R);
            ring.setAttribute('fill', 'none');
            ring.setAttribute('stroke', '#8BCBFF');
            ring.setAttribute('stroke-width', '1.5');
            ring.setAttribute('opacity', '0');
            ring.setAttribute('data-loc-ripple', p.id);
            if (delayed) ring.setAttribute('data-ripple-delay', '1');
            ring._rippleDelay = delayed ? 750 : 0;
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
            // CSS coi từ 1024px trở lên là desktop (@include md chỉ áp dụng
            // đến max-width: 1023px) — phải dùng >= 1024 khớp đúng mốc đó.
            // Lệch 1px (> 1024) khiến đúng lúc 1024px, CSS hiện layout desktop
            // nhưng JS lại tưởng là mobile và gọi mở popup — mà popup cũng chỉ
            // hiện từ ≤1023px nên không hiện gì cả, bấm vào không thấy active.
            if (window.innerWidth >= 1024) {
              setActiveLabel(p.id);
              _updateLocPanel(p.id);
            } else {
              _openLocPopup(p.id);
            }
          });
        });

        // Các tag vừa tạo xong ở trên chưa tồn tại lúc syncMapScale() chạy lần
        // đầu (gọi ngay khi lazyInit, trước khi fetch xong) nên phải áp lại.
        syncMapScale();

        // --- Ripple engine: 1 vòng lặp rAF dùng chung cho MỌI ring đang active,
        // tự tính r/opacity theo thời gian trôi qua rồi set attribute trực
        // tiếp — không dùng CSS animation hay SMIL, nên không phụ thuộc hỗ trợ
        // trình duyệt (xem lý do ở comment tạo ring phía trên). ---
        var RIPPLE_DUR = 2000; // ms, khớp @keyframes loc-ripple cũ (2s)
        var activeRipples = []; // { ring, startTime }
        var rippleRafId = null;

        function rippleTick(now) {
          activeRipples.forEach(function (item) {
            var elapsed = now - item.startTime;
            if (elapsed < 0) return; // chưa tới lúc bắt đầu (còn đang delay)
            var progress = (elapsed % RIPPLE_DUR) / RIPPLE_DUR; // 0..1 lặp lại
            var r = DOT_R + progress * (DOT_R * 2.5); // 9 → 31.5, khớp cũ
            var opacity = progress < 0.35
              ? 0.7 - (progress / 0.35) * (0.7 - 0.25)
              : 0.25 - ((progress - 0.35) / 0.65) * 0.25;
            item.ring.setAttribute('r', r);
            item.ring.setAttribute('opacity', opacity);
          });
          rippleRafId = activeRipples.length ? requestAnimationFrame(rippleTick) : null;
        }

        function startRipple(ring, delayMs) {
          if (ring._rippleActive) return;
          ring._rippleActive = true;
          activeRipples.push({ ring: ring, startTime: performance.now() + delayMs });
          if (!rippleRafId) rippleRafId = requestAnimationFrame(rippleTick);
        }

        function stopRipple(ring) {
          ring._rippleActive = false;
          activeRipples = activeRipples.filter(function (item) { return item.ring !== ring; });
          ring.setAttribute('r', DOT_R);
          ring.setAttribute('opacity', '0');
        }

        function setActiveLabel(locKey) {
          Object.keys(tagEls).forEach(function (id) {
            tagEls[id].classList.toggle('is-active', id === locKey);
          });
          Object.keys(dotRippleRings).forEach(function (id) {
            dotRippleRings[id].forEach(function (ring) {
              if (id === locKey) {
                startRipple(ring, ring._rippleDelay || 0);
              } else {
                stopRipple(ring);
              }
            });
          });
        }

        // Tỉnh đầu tiên có dự án thật active by default — desktop only (>=1024, khớp @include md)
        if (window.innerWidth >= 1024) {
          setActiveLabel(_getDefaultCityKey());
        }

        // Khi resize: đồng bộ active state với breakpoint
        window.addEventListener('resize', function () {
          if (window.innerWidth < 1024) {
            Object.keys(tagEls).forEach(function (id) {
              tagEls[id].classList.remove('is-active');
            });
            if (hpBlueOuter) hpBlueOuter.style.display = 'none';
            if (hpBlueMid) hpBlueMid.style.display = 'none';
            Object.keys(dotRippleRings).forEach(function (id) {
              dotRippleRings[id].forEach(stopRipple);
            });
          } else {
            var hasActive = Object.keys(tagEls).some(function (id) { return tagEls[id].classList.contains('is-active'); });
            if (!hasActive) setActiveLabel(_getDefaultCityKey());
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
    var def = _locationData[_getDefaultCityKey()];
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
