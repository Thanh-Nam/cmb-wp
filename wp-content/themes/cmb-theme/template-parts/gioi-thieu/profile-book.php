<?php
/**
 * template-parts/gioi-thieu/profile-book.php
 * Section: Hồ sơ năng lực (Company Profile flipbook) — nhúng bằng plugin
 * "3D FlipBook" (DearFlip, slug 3d-flipbook-dflip-lite), shortcode [dflip].
 */
$pb_pdf = get_field('profile_book_pdf', 'option');

if (empty($pb_pdf['url'])) return;

$pb_fields = get_fields('option') ?: [];
$pb_title  = cmb_arr($pb_fields, 'profile_book_title') ?: cmb_txt('Hồ sơ năng lực (PDF)', 'Company profile (PDF)');
$pb_desc   = cmb_arr($pb_fields, 'profile_book_desc');
?>
<!-- ======= PROFILE BOOK (HỒ SƠ NĂNG LỰC) ======= -->
<section class="p-profile-book" id="profile-book" aria-label="<?php echo esc_attr(cmb_txt('Hồ sơ năng lực CMB', 'CMB Company Profile')); ?>">
  <div class="l-container">
    <div class="p-profile-book__card" id="profile-book-card" data-reveal="fade-up">
      <div class="p-profile-book__card-head">
        <span class="p-profile-book__icon" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M5 2.5H11.5L15 6V16.5C15 17.0523 14.5523 17.5 14 17.5H5C4.44772 17.5 4 17.0523 4 16.5V3.5C4 2.94772 4.44772 2.5 5 2.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11.5 2.5V6H15" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M7 10.5H12M7 13H12M7 8H9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
        </span>
        <div class="p-profile-book__card-text">
          <h3 class="p-profile-book__title"><?php echo esc_html($pb_title); ?></h3>
          <?php if ($pb_desc) : ?>
          <p class="p-profile-book__desc"><?php echo esc_html($pb_desc); ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="p-book-wrap" id="profile-book-wrap" data-reveal-delay="1">
        <?php
        echo do_shortcode(
          '[dflip source="' . esc_url($pb_pdf['url']) . '" id="cmb-profile-book" class="cmb-profile-book"]' . esc_html($pb_title) . '[/dflip]'
        );
        ?>
      </div>
    </div>
  </div>
</section>
<!-- ======= /PROFILE BOOK ======= -->
