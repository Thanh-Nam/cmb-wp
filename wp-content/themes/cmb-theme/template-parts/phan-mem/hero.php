<?php
/**
 * template-parts/phan-mem/hero.php
 * Section: Page Hero — Phần mềm
 */
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="software-hero" aria-label="Phần mềm CMB">

  <div class="p-page-hero__image-side">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
         alt="Phần mềm chuyên dụng - CMB tư vấn xây dựng công trình hàng hải"
         class="p-page-hero__image"
         loading="eager" />
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page">Phần mềm</span>
    </nav>
    <div class="p-page-hero__content">
      <h1 class="p-page-hero__title">PHẦN MỀM</h1>
      <p class="p-page-hero__subtitle">
        Hệ thống phần mềm chuyên dụng, hiện đại phục vụ<br />
        khảo sát, thiết kế và quản lý công trình.
      </p>
    </div>
  </div>

</section>
<!-- ======= /PAGE HERO ======= -->
