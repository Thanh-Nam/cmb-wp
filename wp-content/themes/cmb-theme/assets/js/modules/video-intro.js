/**
 * modules/video-intro.js - CMB Theme
 * Custom player cho video giới thiệu (upload MP4) — play/pause, tua, âm lượng,
 * tốc độ phát, toàn màn hình. Chỉ chạy khi có player (không áp dụng cho video nhúng).
 */

'use strict';

(function initVideoIntro() {
  var player = document.getElementById('video-intro-player');
  if (!player) return;

  var video = document.getElementById('video-intro-el');
  var overlay = document.getElementById('video-intro-overlay');
  var playBtn = document.getElementById('video-intro-playpause');
  var muteBtn = document.getElementById('video-intro-mute');
  var seek = document.getElementById('video-intro-seek');
  var curEl = document.getElementById('video-intro-current');
  var durEl = document.getElementById('video-intro-duration');
  var speedBtn = document.getElementById('video-intro-speed-btn');
  var speedMenu = document.getElementById('video-intro-speed-menu');
  var fsBtn = document.getElementById('video-intro-fullscreen');

  if (!video || !playBtn || !muteBtn || !seek) return;

  var isSeeking = false;

  function formatTime(sec) {
    if (!isFinite(sec) || sec < 0) sec = 0;
    var m = Math.floor(sec / 60);
    var s = Math.floor(sec % 60);
    return m + ':' + (s < 10 ? '0' : '') + s;
  }

  function setPlayIcon(isPlaying) {
    playBtn.querySelector('.icon-play').hidden = isPlaying;
    playBtn.querySelector('.icon-pause').hidden = !isPlaying;
    if (overlay) overlay.hidden = isPlaying;
  }

  function togglePlay() {
    if (video.paused || video.ended) video.play(); else video.pause();
  }

  if (overlay) overlay.addEventListener('click', togglePlay);
  playBtn.addEventListener('click', togglePlay);
  video.addEventListener('click', togglePlay);
  video.addEventListener('play', function () { setPlayIcon(true); });
  video.addEventListener('pause', function () { setPlayIcon(false); });
  video.addEventListener('ended', function () { setPlayIcon(false); });

  video.addEventListener('loadedmetadata', function () {
    if (durEl) durEl.textContent = formatTime(video.duration);
    seek.max = video.duration || 0;
  });

  video.addEventListener('timeupdate', function () {
    if (curEl) curEl.textContent = formatTime(video.currentTime);
    if (!isSeeking) seek.value = video.currentTime;
  });

  seek.addEventListener('mousedown', function () { isSeeking = true; });
  seek.addEventListener('touchstart', function () { isSeeking = true; }, { passive: true });
  seek.addEventListener('input', function () {
    video.currentTime = parseFloat(seek.value);
  });
  ['mouseup', 'touchend'].forEach(function (evt) {
    seek.addEventListener(evt, function () { isSeeking = false; });
  });

  player.querySelectorAll('[data-seek]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var delta = parseFloat(btn.getAttribute('data-seek'));
      var duration = isFinite(video.duration) ? video.duration : Infinity;
      video.currentTime = Math.min(Math.max(0, video.currentTime + delta), duration);
    });
  });

  function setMuteIcon(muted) {
    muteBtn.querySelector('.icon-vol-on').hidden = muted;
    muteBtn.querySelector('.icon-vol-off').hidden = !muted;
  }

  muteBtn.addEventListener('click', function () {
    video.muted = !video.muted;
    setMuteIcon(video.muted);
  });

  if (speedBtn && speedMenu) {
    speedBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var isOpen = !speedMenu.hidden;
      speedMenu.hidden = isOpen;
      speedBtn.setAttribute('aria-expanded', String(!isOpen));
    });

    speedMenu.querySelectorAll('[data-speed]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        video.playbackRate = parseFloat(btn.getAttribute('data-speed'));
        speedMenu.querySelectorAll('[data-speed]').forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
        speedMenu.hidden = true;
        speedBtn.setAttribute('aria-expanded', 'false');
      });
    });

    document.addEventListener('click', function (e) {
      if (!speedMenu.hidden && !player.contains(e.target)) {
        speedMenu.hidden = true;
        speedBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  if (fsBtn) {
    fsBtn.addEventListener('click', function () {
      if (document.fullscreenElement) {
        document.exitFullscreen();
      } else if (player.requestFullscreen) {
        player.requestFullscreen();
      }
    });
  }
})();
