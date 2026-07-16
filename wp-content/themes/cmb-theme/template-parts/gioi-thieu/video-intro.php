<?php
/**
 * template-parts/gioi-thieu/video-intro.php
 * Section: Video giới thiệu — upload MP4 (custom player) hoặc nhúng YouTube/Vimeo
 */
$v_fields = get_fields('option') ?: [];
$v_source = $v_fields['video_intro_source'] ?? 'upload';
$v_file   = get_field('video_intro_file', 'option');
$v_embed  = get_field('video_intro_embed_url', 'option');
$v_title  = cmb_arr($v_fields, 'video_intro_title') ?: cmb_txt('VIDEO GIỚI THIỆU', 'INTRODUCTION VIDEO');

$has_upload = $v_source === 'upload' && !empty($v_file['url']);
$has_embed  = $v_source === 'embed' && !empty($v_embed);

if (!$has_upload && !$has_embed) return;
?>
<!-- ======= VIDEO GIỚI THIỆU ======= -->
<section class="p-video-intro" id="video-intro" aria-label="<?php echo esc_attr($v_title); ?>">
  <div class="l-container">
    <div class="p-video-intro__header" data-reveal="fade-up">
      <span class="c-section-label"><?php echo esc_html($v_title); ?></span>
    </div>

    <?php if ($has_upload) : ?>
    <div class="p-video-player" id="video-intro-player" data-reveal="fade-up" data-reveal-delay="1">
      <video class="p-video-player__video" id="video-intro-el" playsinline preload="metadata">
        <source src="<?php echo esc_url($v_file['url']); ?>" type="<?php echo esc_attr($v_file['mime_type'] ?: 'video/mp4'); ?>">
      </video>

      <button type="button" class="p-video-player__overlay" id="video-intro-overlay" aria-label="<?php echo esc_attr(cmb_txt('Phát video', 'Play video')); ?>">
        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" aria-hidden="true">
          <circle cx="28" cy="28" r="28" fill="rgba(0,0,0,0.5)"/>
          <path d="M22 17L39 28L22 39V17Z" fill="#fff"/>
        </svg>
      </button>

      <div class="p-video-player__controls">
        <button type="button" class="p-video-player__btn" id="video-intro-playpause" aria-label="<?php echo esc_attr(cmb_txt('Phát/Tạm dừng', 'Play/Pause')); ?>">
          <svg class="icon-play" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 2.5L13 8L4 13.5V2.5Z" fill="currentColor"/></svg>
          <svg class="icon-pause" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" hidden><rect x="3" y="2" width="3.5" height="12" rx="1" fill="currentColor"/><rect x="9.5" y="2" width="3.5" height="12" rx="1" fill="currentColor"/></svg>
        </button>

        <button type="button" class="p-video-player__btn" data-seek="-10" aria-label="<?php echo esc_attr(cmb_txt('Lùi 10 giây', 'Rewind 10s')); ?>">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 3V0.5L4 3.5L8 6.5V4.5C10.2 4.5 12 6.3 12 8.5C12 10.7 10.2 12.5 8 12.5C5.8 12.5 4 10.7 4 8.5H2.5C2.5 11.5 5 14 8 14C11 14 13.5 11.5 13.5 8.5C13.5 5.5 11 3 8 3Z" fill="currentColor"/></svg>
        </button>

        <button type="button" class="p-video-player__btn" data-seek="10" aria-label="<?php echo esc_attr(cmb_txt('Tiến 10 giây', 'Forward 10s')); ?>">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" style="transform:scaleX(-1)"><path d="M8 3V0.5L4 3.5L8 6.5V4.5C10.2 4.5 12 6.3 12 8.5C12 10.7 10.2 12.5 8 12.5C5.8 12.5 4 10.7 4 8.5H2.5C2.5 11.5 5 14 8 14C11 14 13.5 11.5 13.5 8.5C13.5 5.5 11 3 8 3Z" fill="currentColor"/></svg>
        </button>

        <button type="button" class="p-video-player__btn" id="video-intro-mute" aria-label="<?php echo esc_attr(cmb_txt('Tắt tiếng', 'Mute')); ?>">
          <svg class="icon-vol-on" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M2 6H4.5L8 3V13L4.5 10H2V6Z" fill="currentColor"/><path d="M10.5 5.5C11.3 6.2 11.8 7.1 11.8 8C11.8 8.9 11.3 9.8 10.5 10.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
          <svg class="icon-vol-off" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" hidden><path d="M2 6H4.5L8 3V13L4.5 10H2V6Z" fill="currentColor"/><path d="M11 6L14 9M14 6L11 9" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        </button>

        <span class="p-video-player__time"><span id="video-intro-current">0:00</span> / <span id="video-intro-duration">0:00</span></span>

        <div class="p-video-player__seek-wrap">
          <input type="range" class="p-video-player__seek" id="video-intro-seek" min="0" max="100" value="0" step="0.1" aria-label="<?php echo esc_attr(cmb_txt('Tua video', 'Seek')); ?>">
        </div>

        <div class="p-video-player__speed">
          <button type="button" class="p-video-player__btn" id="video-intro-speed-btn" aria-haspopup="true" aria-expanded="false" aria-label="<?php echo esc_attr(cmb_txt('Tốc độ phát', 'Playback speed')); ?>">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1V2.5M8 13.5V15M15 8H13.5M2.5 8H1M12.5 3.5L11.5 4.5M4.5 11.5L3.5 12.5M12.5 12.5L11.5 11.5M4.5 4.5L3.5 3.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><circle cx="8" cy="8" r="3" stroke="currentColor" stroke-width="1.3"/></svg>
          </button>
          <ul class="p-video-player__speed-menu" id="video-intro-speed-menu" hidden>
            <li><button type="button" data-speed="0.5">0.5x</button></li>
            <li><button type="button" data-speed="1" class="is-active">1x</button></li>
            <li><button type="button" data-speed="1.25">1.25x</button></li>
            <li><button type="button" data-speed="1.5">1.5x</button></li>
            <li><button type="button" data-speed="2">2x</button></li>
          </ul>
        </div>

        <button type="button" class="p-video-player__btn" id="video-intro-fullscreen" aria-label="<?php echo esc_attr(cmb_txt('Toàn màn hình', 'Fullscreen')); ?>">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M2 6V2H6M10 2H14V6M14 10V14H10M6 14H2V10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
    </div>
    <?php elseif ($has_embed) : ?>
    <div class="p-video-embed" data-reveal="fade-up" data-reveal-delay="1">
      <?php echo $v_embed; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<!-- ======= /VIDEO GIỚI THIỆU ======= -->
