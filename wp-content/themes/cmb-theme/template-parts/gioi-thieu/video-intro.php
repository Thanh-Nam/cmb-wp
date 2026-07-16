<?php
/**
 * template-parts/gioi-thieu/video-intro.php
 * Section: Video giới thiệu — upload MP4 (control mặc định) hoặc nhúng YouTube/Vimeo
 */
$v_fields = get_fields('option') ?: [];
$v_source = $v_fields['video_intro_source'] ?? 'upload';
$v_file   = get_field('video_intro_file', 'option');
$v_embed  = get_field('video_intro_embed_url', 'option');
$v_title  = cmb_arr($v_fields, 'video_intro_title') ?: cmb_txt('Giới thiệu về CMB', 'About CMB');
$v_desc   = cmb_arr($v_fields, 'video_intro_desc');

$has_upload = $v_source === 'upload' && !empty($v_file['url']);
$has_embed  = $v_source === 'embed' && !empty($v_embed);

if (!$has_upload && !$has_embed) return;
?>
<!-- ======= VIDEO GIỚI THIỆU ======= -->
<section class="p-video-intro" id="video-intro" aria-label="<?php echo esc_attr($v_title); ?>">
  <div class="l-container">
    <div class="p-video-intro__card" data-reveal="fade-up">
      <div class="p-video-intro__card-head">
        <span class="p-video-intro__icon" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M2 5.5C2 4.67157 2.67157 4 3.5 4H11.5C12.3284 4 13 4.67157 13 5.5V14.5C13 15.3284 12.3284 16 11.5 16H3.5C2.67157 16 2 15.3284 2 14.5V5.5Z" stroke="currentColor" stroke-width="1.4"/><path d="M13 8L17.1056 5.94721C17.4372 5.78145 17.8284 5.78145 18.16 5.94721C18.4916 6.11296 18.6944 6.44561 18.6944 6.80902V13.191C18.6944 13.5544 18.4916 13.887 18.16 14.0528C17.8284 14.2186 17.4372 14.2186 17.1056 14.0528L13 12V8Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
        </span>
        <div class="p-video-intro__card-text">
          <h3 class="p-video-intro__title"><?php echo esc_html($v_title); ?></h3>
          <?php if ($v_desc) : ?>
          <p class="p-video-intro__desc"><?php echo esc_html($v_desc); ?></p>
          <?php endif; ?>
        </div>
      </div>

      <?php if ($has_upload) : ?>
      <div class="p-video-player" data-reveal-delay="1">
        <video class="p-video-player__video" playsinline controls preload="metadata">
          <source src="<?php echo esc_url($v_file['url']); ?>" type="<?php echo esc_attr($v_file['mime_type'] ?: 'video/mp4'); ?>">
        </video>
      </div>
      <?php elseif ($has_embed) : ?>
      <div class="p-video-embed" data-reveal-delay="1">
        <?php echo $v_embed; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<!-- ======= /VIDEO GIỚI THIỆU ======= -->
