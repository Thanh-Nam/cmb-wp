<?php
/**
 * template-parts/lien-he/hero.php
 * Section: Page Hero — Liên hệ
 */
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="lien-he-hero" aria-label="Liên hệ CMB">

  <div class="p-page-hero__image-side">
    <?php if (has_post_thumbnail()) :
      the_post_thumbnail('full', ['class' => 'p-page-hero__image', 'loading' => 'eager']);
    else : ?>
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
           alt="Cảng biển Việt Nam – CMB"
           class="p-page-hero__image"
           loading="eager" />
    <?php endif; ?>
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page">Liên hệ</span>
    </nav>
    <div class="p-page-hero__content">
      <h1 class="p-page-hero__title">LIÊN HỆ</h1>
      <p class="p-page-hero__subtitle">
        Kết nối cùng CMB<br>
        Kiến tạo những công trình hàng hải bền vững
      </p>
    </div>
  </div>

</section>
<!-- ======= /PAGE HERO ======= -->
