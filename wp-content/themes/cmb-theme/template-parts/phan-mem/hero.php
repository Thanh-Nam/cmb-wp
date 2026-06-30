<?php
/**
 * template-parts/phan-mem/hero.php
 * Section: Page Hero — Phần mềm
 */
$hero_img = get_field('banner_phan_mem_img', 'option');
$title    = cmb_get_option('banner_phan_mem_title') ?: 'PHẦN MỀM';
$subtitle = cmb_get_option('banner_phan_mem_desc') ?: 'Hệ thống phần mềm chuyên dụng, hiện đại phục vụ<br>khảo sát, thiết kế và quản lý công trình.';
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="software-hero" aria-label="Phần mềm CMB">

  <div class="p-page-hero__image-side">
    <?php if ($hero_img) : ?>
    <img src="<?php echo esc_url($hero_img['url']); ?>"
         alt="<?php echo esc_attr($hero_img['alt'] ?: strip_tags($title)); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php else : ?>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero_port.jpg"
         alt="Phần mềm chuyên dụng - CMB tư vấn xây dựng công trình hàng hải"
         class="p-page-hero__image" loading="eager" />
    <?php endif; ?>
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page">Phần mềm</span>
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
