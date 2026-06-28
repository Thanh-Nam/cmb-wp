/**
 * modules/software-modal.js - CMB Theme
 * Software popup modal cho archive phần mềm
 */

'use strict';

(function initSoftwareModal() {
  var modal = document.getElementById('software-modal');
  var overlay = document.getElementById('sw-modal-overlay');
  var closeBtn = document.getElementById('sw-modal-close');
  var images = document.getElementById('sw-modal-images');
  var desc = document.getElementById('sw-modal-desc');
  if (!modal || !overlay || !closeBtn) return;

  function openModal(card) {
    var title = card.getAttribute('data-title');
    var text = card.getAttribute('data-desc');
    var imageUrls = [];
    try { imageUrls = JSON.parse(card.getAttribute('data-images') || '[]'); } catch (e) { }

    images.innerHTML = imageUrls.map(function (url) {
      return '<div class="p-software-modal__img-wrap"><img src="' + url + '" alt="' + title + '" class="p-software-modal__img" loading="lazy"/></div>';
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

  document.querySelectorAll('.js-software-card').forEach(function (card) {
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
