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

$pb_cover_url = $pb_cover['url'] ?? '';
$pb_cover_alt = $pb_cover['alt'] ?? '';
?>
<!-- ======= PROFILE BOOK (HỒ SƠ NĂNG LỰC) ======= -->
<section class="p-profile-book" id="profile-book" aria-label="<?php echo esc_attr(cmb_txt('Hồ sơ năng lực CMB', 'CMB Company Profile')); ?>">
  <div class="l-container">
    <div class="p-profile-book__header" data-reveal="fade-up">
      <span class="c-section-label"><?php echo cmb_txt('HỒ SƠ NĂNG LỰC', 'COMPANY PROFILE'); ?></span>
    </div>

    <div class="p-book-wrap" id="profile-book-wrap" data-reveal="fade-up" data-reveal-delay="1">
      <button type="button" class="p-book-cover" id="profile-book-cover-btn" aria-label="<?php echo esc_attr(cmb_txt('Bấm để mở hồ sơ năng lực', 'Click to open the company profile')); ?>">
        <?php if ($pb_cover_url) : ?>
        <img src="<?php echo esc_url($pb_cover_url); ?>" alt="<?php echo esc_attr($pb_cover_alt ?: $pb_title); ?>" class="p-book-cover__img" loading="lazy" />
        <?php endif; ?>
        <div class="p-book-cover__overlay"></div>
        <div class="p-book-cover__body">
          <p class="p-book-cover__eyebrow"><?php echo cmb_txt('TẬN TÂM · CHUYÊN NGHIỆP · HIỆU QUẢ', 'DEDICATED · PROFESSIONAL · EFFICIENT'); ?></p>
          <h3 class="p-book-cover__title"><?php echo esc_html($pb_title); ?></h3>
          <span class="p-book-cover__hint">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
              <path d="M2 4.5C2 4.5 4.5 3 7 3C9 3 10 4 10 4V16.5C10 16.5 9 15.5 7 15.5C4.5 15.5 2 17 2 17V4.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
              <path d="M18 4.5C18 4.5 15.5 3 13 3C11 3 10 4 10 4V16.5C10 16.5 11 15.5 13 15.5C15.5 15.5 18 17 18 17V4.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
            </svg>
            <?php echo cmb_txt('Bấm để mở', 'Click to open'); ?>
          </span>
        </div>
      </button>

      <div class="p-book"
           id="profile-book-viewer"
           data-pdf-url="<?php echo esc_attr($pb_pdf['url']); ?>"
           tabindex="0"
           role="group"
           aria-label="<?php echo esc_attr($pb_title); ?>">
        <div class="p-book__stage">
          <div class="p-book__flip" id="profile-book-flip"></div>
          <div class="p-book__loading" id="profile-book-loading">
            <span class="p-book__spinner"></span>
            <span class="p-book__loading-text" id="profile-book-loading-text"></span>
          </div>
        </div>
      </div>
    </div>

    <div class="p-profile-book__footer" id="profile-book-footer">
      <span class="p-book__pager" id="profile-book-pager"></span>
      <span class="p-book__error" id="profile-book-error" hidden><?php echo esc_html(cmb_txt('Không thể tải file PDF. Vui lòng thử lại.', 'Unable to load the PDF file. Please try again.')); ?></span>
    </div>
  </div>
</section>
<!-- ======= /PROFILE BOOK ======= -->
