/**
 * modules/form-validation.js - CMB Theme
 * Basic form validation cho các form có data-validate
 */

'use strict';

(function initFormValidation() {
  var _lang = (window.CMB_Theme && window.CMB_Theme.lang) ? window.CMB_Theme.lang : 'vi';
  function _t(vi, en) { return _lang === 'en' ? en : vi; }

  var forms = document.querySelectorAll('form[data-validate]');

  forms.forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var valid = true;

      this.querySelectorAll('[required]').forEach(function (field) {
        var group = field.closest('.form__group');
        var errorEl = group ? group.querySelector('.form__error') : null;

        if (!field.value.trim()) {
          valid = false;
          field.classList.add('has-error');
          if (errorEl) errorEl.textContent = _t('Trường này là bắt buộc.', 'This field is required.');
        } else {
          field.classList.remove('has-error');
          if (errorEl) errorEl.textContent = '';
        }
      });

      if (!valid) e.preventDefault();
    });
  });
})();
