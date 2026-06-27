<?php
/**
 * template-parts/du-an/archive-hero.php
 * Section: Archive Page Hero — Dự án tiêu biểu
 */
$theme    = get_template_directory_uri();
$hero_img = function_exists('get_field') ? get_field('archive_du_an_hero_img', 'option') : null;
$subtitle = function_exists('get_field') ? get_field('archive_du_an_subtitle', 'option') : '';
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="projects-hero" aria-label="Dự án tiêu biểu CMB">

  <div class="p-page-hero__image-side">
    <?php if ($hero_img) : ?>
    <img src="<?php echo $hero_img['url']; ?>"
         alt="<?php echo $hero_img['alt']; ?>"
         class="p-page-hero__image" loading="eager" />
    <?php else : ?>
    <img src="<?php echo $theme; ?>/assets/images/hero_port.jpg"
         alt="Cảng container hiện đại - CMB tư vấn xây dựng công trình hàng hải"
         class="p-page-hero__image" loading="eager" />
    <?php endif; ?>
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page">Dự án tiêu biểu</span>
    </nav>
    <div class="p-page-hero__content">
      <h1 class="p-page-hero__title">DỰ ÁN TIÊU BIỂU</h1>
      <p class="p-page-hero__subtitle">
        <?php if ($subtitle) : ?>
        <?php echo $subtitle; ?>
        <?php else : ?>
        300+ dự án đa dạng lĩnh vực hàng hải, logistics,<br />
        khu công nghiệp và hạ tầng kỹ thuật trên toàn quốc.
        <?php endif; ?>
      </p>
    </div>
  </div>

</section>
<!-- ======= /PAGE HERO ======= -->
