/**
 * modules/video-poster.js - CMB Theme
 * Video upload (MP4) mặc định hiển thị nền đen cho tới khi bấm play (do trình
 * duyệt không tự vẽ khung hình đầu ra màn hình khi chỉ preload="metadata").
 * Module này che 1 overlay có icon play phía trên video — bấm vào thì ẩn đi và
 * play video. Ảnh nền overlay: nếu admin đã nhập ảnh riêng trong CMS (field
 * video_intro_poster, đánh dấu bằng class "has-manual-poster") thì giữ nguyên
 * ảnh đó; ngược lại tự chụp khung hình đầu tiên của video (canvas) làm ảnh nền.
 */

'use strict';

(function initVideoPoster() {
  var players = document.querySelectorAll('.p-video-player');
  if (!players.length) return;

  players.forEach(function (wrap) {
    var video = wrap.querySelector('.p-video-player__video');
    var overlay = wrap.querySelector('.p-video-player__poster');
    if (!video || !overlay) return;

    if (overlay.classList.contains('has-manual-poster')) {
      overlay.addEventListener('click', function () {
        overlay.classList.add('is-hidden');
        video.play();
      });
      video.addEventListener('play', function () {
        overlay.classList.add('is-hidden');
      });
      return;
    }

    function hideOverlay() {
      overlay.classList.add('is-hidden');
    }

    function captureFirstFrame() {
      try {
        var canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        if (!canvas.width || !canvas.height) return;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        overlay.style.backgroundImage = 'url(' + canvas.toDataURL('image/jpeg', 0.85) + ')';
      } catch (e) {
        // Lỗi CORS/canvas tainted (video khác domain) — bỏ qua, overlay vẫn còn
        // icon play trên nền đen mặc định, không chặn play.
      }
    }

    // "seeked" đảm bảo trình duyệt đã thực sự decode xong khung hình tại
    // currentTime mới set, tránh chụp lúc frame chưa sẵn sàng (canvas trắng/đen).
    video.addEventListener('loadeddata', function () {
      if (video.currentTime === 0) {
        video.currentTime = 0.01;
      } else {
        captureFirstFrame();
      }
    });
    video.addEventListener('seeked', captureFirstFrame);

    overlay.addEventListener('click', function () {
      hideOverlay();
      video.play();
    });

    video.addEventListener('play', hideOverlay);
  });
})();
