<?php
/**
 * template-parts/ho-so-nang-luc/content.php
 * Section: Thông tin hồ sơ PDF (trái) + PDF hiển thị trực tiếp (phải)
 * Toàn bộ field riêng cho trang này, khai báo trong Options Page "Cấu hình
 * trang năng lực" (menu_slug "nang-luc") — xem
 * acf-json/group_fdf91f4ccdb5.json. Không dùng chung dữ liệu với trang nào
 * khác.
 */
$is_en = function_exists('pll_current_language') && pll_current_language() === 'en';

$pdf = get_field('nangluc_profile_pdf', 'option');
if ($is_en) {
  $pdf_en = get_field('nangluc_profile_pdf_en', 'option');
  if (!empty($pdf_en['url'])) {
    $pdf = $pdf_en;
  }
}

$fields = get_fields('option') ?: [];
$title  = cmb_arr($fields, 'nangluc_profile_title') ?: cmb_txt('Hồ sơ năng lực CMB (PDF)', 'CMB Company Profile (PDF)');
$desc   = cmb_arr($fields, 'nangluc_profile_desc') ?: cmb_txt('Tìm hiểu chi tiết về năng lực, kinh nghiệm, các dự án tiêu biểu và đối tác của chúng tôi.', "Learn more about our capabilities, experience, notable projects and partners.");
$pages  = (!empty($pdf['ID'])) ? cmb_pdf_page_count($pdf['ID']) : null;
if (!$pages) {
  $pages = $fields['nangluc_profile_pages'] ?? '';
}

$size_mb = (!empty($pdf['filesize'])) ? round($pdf['filesize'] / 1048576, 1) : null;
$updated = (!empty($pdf['modified'])) ? date_i18n('m/Y', strtotime($pdf['modified'])) : null;
?>
<!-- ======= HỒ SƠ NĂNG LỰC ======= -->
<section class="p-profile-file" id="profile-file-content">
  <div class="l-container">

    <div class="p-profile-file__header" data-reveal="fade-up">
      <h2 class="c-section-title p-profile-file__title-main"><?php echo cmb_txt('Hồ sơ năng lực', 'Company Profile'); ?></h2>
    </div>

    <?php if (!empty($pdf['url'])) : ?>
    <div class="p-profile-file__layout">

      <!-- ---- Thông tin (trái) ---- -->
      <div class="p-profile-file__info">
        <h3 class="p-profile-file__title"><?php echo esc_html($title); ?></h3>
        <?php if ($desc) : ?>
        <p class="p-profile-file__desc"><?php echo esc_html($desc); ?></p>
        <?php endif; ?>

        <ul class="p-profile-file__meta">
          <li class="p-profile-file__meta-row">
            <span class="p-profile-file__meta-label"><?php echo cmb_txt('Định dạng', 'Format'); ?></span>
            <span class="p-profile-file__meta-value">PDF</span>
          </li>
          <?php if ($pages) : ?>
          <li class="p-profile-file__meta-row">
            <span class="p-profile-file__meta-label"><?php echo cmb_txt('Số trang', 'Pages'); ?></span>
            <span class="p-profile-file__meta-value"><?php echo esc_html($pages); ?> <?php echo cmb_txt('trang', 'pages'); ?></span>
          </li>
          <?php endif; ?>
          <?php if ($size_mb) : ?>
          <li class="p-profile-file__meta-row">
            <span class="p-profile-file__meta-label"><?php echo cmb_txt('Dung lượng', 'Size'); ?></span>
            <span class="p-profile-file__meta-value"><?php echo esc_html($size_mb); ?> MB</span>
          </li>
          <?php endif; ?>
          <?php if ($updated) : ?>
          <li class="p-profile-file__meta-row">
            <span class="p-profile-file__meta-label"><?php echo cmb_txt('Cập nhật', 'Updated'); ?></span>
            <span class="p-profile-file__meta-value"><?php echo esc_html($updated); ?></span>
          </li>
          <?php endif; ?>
        </ul>

        <a class="p-profile-file__download" href="<?php echo esc_url($pdf['url']); ?>" target="_blank" rel="noopener">
          <?php echo cmb_txt('Tải xuống', 'Download'); ?>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M12 15l-5-5M12 15l5-5"/><path d="M4 21h16"/></svg>
        </a>
      </div>

      <!-- ---- PDF hiển thị trực tiếp (phải) ---- -->
      <div class="p-profile-file__viewer">
        <iframe
          src="<?php echo esc_url($pdf['url']); ?>"
          title="<?php echo esc_attr($title); ?>"
          loading="lazy"
          aria-label="<?php echo esc_attr(cmb_txt('Xem hồ sơ năng lực PDF', 'View company profile PDF')); ?>">
        </iframe>
      </div>

    </div>
    <?php else : ?>
    <p class="p-profile-file__empty"><?php echo cmb_txt('Hồ sơ năng lực chưa được tải lên.', 'Company profile has not been uploaded yet.'); ?></p>
    <?php endif; ?>

  </div>
</section>
<!-- ======= /HỒ SƠ NĂNG LỰC ======= -->
