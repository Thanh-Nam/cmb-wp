/**
 * modules/equipment-modal.js - CMB Theme
 * Equipment popup modal cho archive thiết bị
 */

'use strict';

(function initEquipmentModal() {
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
