<?php
/**
 * template-parts/lien-he/hero.php
 * Section: Page Hero — Liên hệ
 */
$hero_img = get_field('banner_lien_he_img', 'option');
$title    = cmb_get_option('banner_lien_he_title') ?: cmb_txt('LIÊN HỆ', 'CONTACT');
$subtitle = cmb_get_option('banner_lien_he_desc') ?: cmb_txt('Kết nối cùng CMB<br>Kiến tạo những công trình hàng hải bền vững', 'Connect with CMB<br>Building sustainable maritime works');
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="lien-he-hero" aria-label="<?php echo esc_attr(cmb_txt('Liên hệ CMB', 'Contact CMB')); ?>">

  <div class="p-page-hero__image-side">
    <?php if ($hero_img) : ?>
    <img src="<?php echo esc_url($hero_img['url']); ?>"
         alt="<?php echo esc_attr($hero_img['alt'] ?: strip_tags($title)); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php else : ?>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero_port.jpg"
         alt="<?php echo esc_attr(cmb_txt('Cảng biển Việt Nam – CMB', 'Vietnam Seaport – CMB')); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php endif; ?>
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="<?php echo esc_attr(cmb_txt('Đường dẫn', 'Breadcrumb')); ?>">
      <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo cmb_txt('Trang chủ', 'Home'); ?></a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page"><?php echo cmb_txt('Liên hệ', 'Contact'); ?></span>
    </nav>
    <div class="p-page-hero__content">
      <h1 class="p-page-hero__title"><?php echo wp_kses_post($title); ?></h1>
      <?php if ($subtitle) : ?>
      <p class="p-page-hero__subtitle"><?php echo wp_kses_post($subtitle); ?></p>
      <?php endif; ?>
    </div>
  </div>

</section>
<!-- ======= /PAGE HERO ======= -->
