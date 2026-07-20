/**
 * modules/profile-book-loader.js - CMB Theme
 * Chèn markup cho "book loader" (thuần CSS, dựa theo
 * https://codepen.io/aaroniker/pen/zYOewEP) vào bên trong .df-loading-icon
 * — div này do plugin DearFlip tự tạo bằng jQuery lúc PDF bắt đầu tải (không
 * có sẵn trong HTML ban đầu), nên phải dùng MutationObserver để biết lúc nào
 * nó xuất hiện rồi mới chèn nội dung vào, thay vì chạy 1 lần lúc DOM ready.
 */

'use strict';

(function initProfileBookLoader() {
  var wrap = document.getElementById('profile-book-wrap');
  if (!wrap) return;

  var PAGE_COUNT = 18;

  function buildBookMarkup() {
    var pages = '';
    for (var i = 0; i < PAGE_COUNT; i++) {
      pages += '<li class="df-book__page"></li>';
    }
    return (
      '<div class="df-book">' +
        '<div class="df-book__inner">' +
          '<div class="df-book__left"></div>' +
          '<div class="df-book__middle"></div>' +
          '<div class="df-book__right"></div>' +
        '</div>' +
        '<ul class="df-book__pages">' + pages + '</ul>' +
      '</div>'
    );
  }

  function injectInto(icon) {
    if (!icon || icon.dataset.bookInjected) return;
    icon.dataset.bookInjected = '1';
    icon.innerHTML = buildBookMarkup();
  }

  var existing = wrap.querySelector('.df-loading-icon');
  if (existing) injectInto(existing);

  var observer = new MutationObserver(function (mutations) {
    for (var m = 0; m < mutations.length; m++) {
      var added = mutations[m].addedNodes;
      for (var i = 0; i < added.length; i++) {
        var node = added[i];
        if (node.nodeType !== 1) continue;
        if (node.classList && node.classList.contains('df-loading-icon')) {
          injectInto(node);
        }
      }
    }
  });
  observer.observe(wrap, { childList: true, subtree: true });
})();
