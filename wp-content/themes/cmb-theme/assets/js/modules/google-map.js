/**
 * modules/google-map.js - CMB Theme
 * Google Maps office switcher cho trang liên hệ
 */

'use strict';

(function initGoogleMap() {
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
