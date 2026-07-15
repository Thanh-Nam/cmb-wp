<?php
/**
 * template-parts/gioi-thieu/profile-book.php
 * Section: Hồ sơ năng lực (Company Profile flipbook) — hiển thị & lật trực tiếp trên trang
 */
$pb_cover  = get_field('profile_book_cover', 'option');
$pb_pdf    = get_field('profile_book_pdf', 'option');
$pb_fields = get_fields('option') ?: [];

if (empty($pb_pdf['url'])) return;

$pb_title = cmb_arr($pb_fields, 'profile_book_title') ?: cmb_txt('HỒ SƠ NĂNG LỰC', 'COMPANY PROFILE');
$pb_desc  = cmb_arr($pb_fields, 'profile_book_desc');

// Ảnh bìa: dùng ảnh đã chọn, hoặc thumbnail PDF do WordPress tự sinh (nếu server có Imagick+Ghostscript)
$pb_cover_url = $pb_cover['url'] ?? '';
$pb_cover_alt = $pb_cover['alt'] ?? '';
if (!$pb_cover_url && !empty($pb_pdf['ID'])) {
    $pdf_thumb = wp_get_attachment_image_src($pb_pdf['ID'], 'large');
    if ($pdf_thumb) $pb_cover_url = $pdf_thumb[0];
}
?>
<!-- ======= PROFILE BOOK (HỒ SƠ NĂNG LỰC) ======= -->
<section class="p-profile-book" id="profile-book" aria-label="<?php echo esc_attr(cmb_txt('Hồ sơ năng lực CMB', 'CMB Company Profile')); ?>">
  <div class="l-container">
    <div class="p-profile-book__header" data-reveal="fade-up">
      <h2 class="c-section-title"><?php echo cmb_txt('HỒ SƠ NĂNG LỰC', 'COMPANY PROFILE'); ?></h2>
      <?php if ($pb_desc) : ?>
      <p class="p-profile-book__desc"><?php echo wp_kses_post($pb_desc); ?></p>
      <?php endif; ?>
      <a href="<?php echo esc_url($pb_pdf['url']); ?>" class="p-profile-book__download" download>
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
          <path d="M8 2V10M8 10L5 7M8 10L11 7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M2 13H14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        </svg>
        <?php echo cmb_txt('Tải PDF', 'Download PDF'); ?>
      </a>
    </div>

    <div class="p-book"
         id="profile-book-viewer"
         data-pdf-url="<?php echo esc_attr($pb_pdf['url']); ?>"
         data-cover-url="<?php echo esc_attr($pb_cover_url); ?>"
         tabindex="0"
         role="group"
         aria-label="<?php echo esc_attr($pb_title); ?>"
         data-reveal="fade-up" data-reveal-delay="1">
      <button type="button" class="p-book__nav p-book__nav--prev" id="profile-book-prev" aria-label="<?php echo esc_attr(cmb_txt('Trang trước', 'Previous page')); ?>">
        <svg width="14" height="24" viewBox="0 0 14 24" fill="none" aria-hidden="true"><path d="M12 2L2 12L12 22" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>

      <div class="p-book__stage">
        <div class="p-book__slot p-book__slot--left">
          <img class="p-book__img" id="profile-book-img-left" src="" alt="" />
        </div>
        <div class="p-book__slot p-book__slot--right">
          <img class="p-book__img" id="profile-book-img-right" src="" alt="" />
        </div>
        <div class="p-book__leaf" id="profile-book-leaf">
          <div class="p-book__face p-book__face--front">
            <img class="p-book__img" id="profile-book-leaf-front" src="" alt="" />
          </div>
          <div class="p-book__face p-book__face--back">
            <img class="p-book__img" id="profile-book-leaf-back" src="" alt="" />
          </div>
        </div>
        <div class="p-book__spine" aria-hidden="true"></div>
        <div class="p-book__loading" id="profile-book-loading" aria-hidden="true">
          <span class="p-book__spinner"></span>
        </div>
        <div class="p-book__intro" id="profile-book-intro" aria-hidden="true">
          <img class="p-book__intro-img" id="profile-book-intro-img" src="" alt="" />
        </div>
      </div>

      <button type="button" class="p-book__nav p-book__nav--next" id="profile-book-next" aria-label="<?php echo esc_attr(cmb_txt('Trang sau', 'Next page')); ?>">
        <svg width="14" height="24" viewBox="0 0 14 24" fill="none" aria-hidden="true"><path d="M2 2L12 12L2 22" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </div>

    <div class="p-profile-book__footer">
      <span class="p-book__pager" id="profile-book-pager"></span>
      <span class="p-book__error" id="profile-book-error" hidden><?php echo esc_html(cmb_txt('Không thể tải file PDF. Vui lòng thử lại.', 'Unable to load the PDF file. Please try again.')); ?></span>
    </div>
  </div>
</section>
<!-- ======= /PROFILE BOOK ======= -->
