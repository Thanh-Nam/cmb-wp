<?php
/**
 * template-parts/ho-so-nang-luc/hero.php
 * Section: Page Hero — Hồ sơ năng lực
 */
$about_pages = get_posts([
  'post_type'      => 'page',
  'meta_key'       => '_wp_page_template',
  'meta_value'     => 'page-gioi-thieu.php',
  'posts_per_page' => 1,
  'fields'         => 'ids',
]);
$about_url = $about_pages ? get_permalink($about_pages[0]) : home_url('/gioi-thieu');
$hero_img = get_field('banner_ho_so_nang_luc_img', 'option');
$title    = cmb_get_option('banner_ho_so_nang_luc_title') ?: cmb_txt('HỒ SƠ NĂNG LỰC', 'COMPANY PROFILE');
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="profile-file-hero" aria-label="<?php echo esc_attr(cmb_txt('Hồ sơ năng lực CMB', 'CMB Company Profile')); ?>">

  <div class="p-page-hero__image-side">
    <?php if ($hero_img) : ?>
    <img src="<?php echo esc_url($hero_img['url']); ?>"
         alt="<?php echo esc_attr($hero_img['alt'] ?: strip_tags($title)); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php else : ?>
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
         alt="<?php echo esc_attr(cmb_txt('Hồ sơ năng lực CMB', 'CMB Company Profile')); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php endif; ?>
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="<?php echo esc_attr(cmb_txt('Đường dẫn', 'Breadcrumb')); ?>">
      <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo cmb_txt('Trang chủ', 'Home'); ?></a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <a href="<?php echo esc_url($about_url); ?>"><?php echo cmb_txt('Giới thiệu', 'About'); ?></a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page"><?php echo cmb_txt('Hồ sơ năng lực', 'Company Profile'); ?></span>
    </nav>
    <div class="p-page-hero__content">
      <h1 class="p-page-hero__title"><?php echo wp_kses_post($title); ?></h1>
    </div>
  </div>

</section>
<!-- ======= /PAGE HERO ======= -->
