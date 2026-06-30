<?php
/**
 * template-parts/thiet-bi/hero.php
 * Section: Page Hero — Thiết bị khảo sát
 */
$hero_img = get_field('banner_thiet_bi_img', 'option');
$title    = cmb_get_option('banner_thiet_bi_title');
$subtitle = cmb_get_option('banner_thiet_bi_desc');
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="equipment-hero" aria-label="Thiết bị khảo sát CMB">

  <div class="p-page-hero__image-side">
    <?php if ($hero_img) : ?>
    <img src="<?php echo esc_url($hero_img['url']); ?>"
         alt="<?php echo esc_attr($hero_img['alt'] ?: strip_tags($title)); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php else : ?>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero_port.jpg"
         alt="Thiết bị khảo sát hiện đại - CMB tư vấn xây dựng công trình hàng hải"
         class="p-page-hero__image" loading="eager" />
    <?php endif; ?>
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page">Thiết bị khảo sát</span>
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
