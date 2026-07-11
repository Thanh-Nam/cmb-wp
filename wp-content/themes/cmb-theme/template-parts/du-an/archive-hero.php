<?php
/**
 * template-parts/du-an/archive-hero.php
 * Section: Archive Page Hero — Dự án tiêu biểu
 */
$hero_img = get_field('banner_du_an_img', 'option');
$title    = cmb_get_option('banner_du_an_title') ?: cmb_txt( 'DỰ ÁN TIÊU BIỂU', 'FEATURED PROJECTS' );
$subtitle = cmb_get_option('banner_du_an_desc') ?: cmb_txt( '300+ dự án đa dạng lĩnh vực hàng hải, logistics,<br>khu công nghiệp và hạ tầng kỹ thuật trên toàn quốc.', '300+ projects across the maritime, logistics,<br>industrial park and technical infrastructure sectors nationwide.' );
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="projects-hero" aria-label="<?php echo esc_attr( cmb_txt( 'Dự án tiêu biểu CMB', 'CMB Featured Projects' ) ); ?>">

  <div class="p-page-hero__image-side">
    <?php if ($hero_img) : ?>
    <img src="<?php echo esc_url($hero_img['url']); ?>"
         alt="<?php echo esc_attr($hero_img['alt'] ?: strip_tags($title)); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php else : ?>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero_port.jpg"
         alt="<?php echo esc_attr( cmb_txt( 'Cảng container hiện đại - CMB tư vấn xây dựng công trình hàng hải', 'Modern container port - CMB marine construction consulting' ) ); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php endif; ?>
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="<?php echo esc_attr( cmb_txt( 'Đường dẫn', 'Breadcrumb' ) ); ?>">
      <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo cmb_txt( 'Trang chủ', 'Home' ); ?></a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page"><?php echo cmb_txt( 'Dự án tiêu biểu', 'Featured Projects' ); ?></span>
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
