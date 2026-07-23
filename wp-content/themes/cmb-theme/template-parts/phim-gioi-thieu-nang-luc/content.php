<?php
/**
 * template-parts/phim-gioi-thieu-nang-luc/content.php
 * Section: Sidebar số liệu (trái) + Video giới thiệu (phải)
 * Tái dùng đúng dữ liệu đã có ở Options Page "Giới thiệu" (menu_slug
 * "gioi-thieu"): field "about_stat_list" (cùng số liệu với section Stats ở
 * trang Giới thiệu) và "video_intro_*" (cùng video/poster với section Video
 * giới thiệu ở trang Giới thiệu) — sửa nội dung tại wp-admin > Cấu hình
 * chung > Cấu hình trang giới thiệu.
 */
$is_en = function_exists('pll_current_language') && pll_current_language() === 'en';

// ---- Sidebar: 4 số liệu (dùng chung với section Stats trang Giới thiệu) ----
$stat_items = [];
if (have_rows('about_stat_list', 'option')) {
  while (have_rows('about_stat_list', 'option')) {
    the_row();
    $stat_items[] = [
      'icon'    => get_sub_field('icon'),
      'number'  => get_sub_field('number'),
      'content' => ($is_en && get_sub_field('content_en')) ? get_sub_field('content_en') : get_sub_field('content'),
    ];
  }
}

// ---- Video (dùng chung với section Video giới thiệu trang Giới thiệu) ----
$v_fields = get_fields('option') ?: [];
$v_source = $v_fields['video_intro_source'] ?? 'upload';
$v_file   = get_field('video_intro_file', 'option');
$v_poster = get_field('video_intro_poster', 'option');
$v_embed  = get_field('video_intro_embed_url', 'option');
$v_title  = cmb_arr($v_fields, 'video_intro_title') ?: cmb_txt('CMB - Hành trình kiến tạo giá trị bền vững', 'CMB - A Journey of Building Sustainable Value');
$v_desc   = cmb_arr($v_fields, 'video_intro_desc');

$has_upload = $v_source === 'upload' && !empty($v_file['url']);
$has_embed  = $v_source === 'embed' && !empty($v_embed);
?>
<!-- ======= PHIM GIỚI THIỆU NĂNG LỰC ======= -->
<section class="p-capability-video" id="capability-video-content">
  <div class="l-container">

    <div class="p-capability-video__layout">

      <!-- ---- Sidebar (trái) ---- -->
      <aside class="p-capability-video__sidebar">
        <?php foreach ($stat_items as $stat) : ?>
        <div class="p-capability-video__stat">
          <?php if ($stat['icon']) : ?>
          <div class="p-capability-video__stat-icon" aria-hidden="true" style="--icon-url: url('<?php echo esc_url($stat['icon']['url']); ?>');"></div>
          <?php endif; ?>
          <span class="p-capability-video__stat-text">
            <?php if ($stat['number'] !== '' && $stat['number'] !== null) : ?>
            <strong><?php echo cmb_txt('Hơn', 'Over'); ?> <?php echo esc_html($stat['number']); ?></strong>
            <?php endif; ?>
            <?php echo esc_html($stat['content']); ?>
          </span>
        </div>
        <?php endforeach; ?>
      </aside>

      <!-- ---- Video (phải) ---- -->
      <div class="p-capability-video__main">
        <?php if ($has_upload) : ?>
        <div class="p-video-player">
          <video class="p-video-player__video" playsinline controls preload="metadata">
            <source src="<?php echo esc_url($v_file['url']); ?>" type="<?php echo esc_attr($v_file['mime_type'] ?: 'video/mp4'); ?>">
          </video>
          <button type="button" class="p-video-player__poster<?php echo !empty($v_poster['url']) ? ' has-manual-poster' : ''; ?>"
                  aria-label="<?php echo esc_attr(cmb_txt('Phát video', 'Play video')); ?>"
                  <?php if (!empty($v_poster['url'])) : ?>style="background-image:url('<?php echo esc_url($v_poster['url']); ?>')"<?php endif; ?>>
            <span class="p-video-player__play-icon" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M6 4.5L19 12L6 19.5V4.5Z" fill="currentColor"/></svg>
            </span>
          </button>
        </div>
        <?php elseif ($has_embed) : ?>
        <div class="p-video-embed">
          <?php echo $v_embed; ?>
        </div>
        <?php endif; ?>

        <div class="p-capability-video__body">
          <h2 class="p-capability-video__title"><?php echo esc_html($v_title); ?></h2>
          <?php if ($v_desc) : ?>
          <p class="p-capability-video__desc"><?php echo esc_html($v_desc); ?></p>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- ======= /PHIM GIỚI THIỆU NĂNG LỰC ======= -->
