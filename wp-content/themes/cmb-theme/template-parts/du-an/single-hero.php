<?php
/**
 * template-parts/du-an/single-hero.php
 * Section: Single Page Hero — Dự án chi tiết
 */
$hero_img  = get_field('project_hero_image');
$terms     = get_the_terms(get_the_ID(), 'du-an-category');
$term_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : cmb_txt( 'Dự án Tiêu biểu', 'Featured Project' );
?>
<!-- ======= PAGE HERO ======= -->
<section class="p-page-hero" id="project-hero" aria-label="<?php echo esc_attr(get_the_title()); ?>">

  <div class="p-page-hero__image-side" aria-hidden="true">
    <?php if ($hero_img) : ?>
    <img src="<?php echo $hero_img['url']; ?>"
         alt="<?php echo esc_attr($hero_img['alt'] ?: get_the_title()); ?>"
         class="p-page-hero__image" loading="eager" />
    <?php elseif (has_post_thumbnail()) : ?>
    <?php the_post_thumbnail('large', ['class' => 'p-page-hero__image', 'loading' => 'eager']); ?>
    <?php endif; ?>
  </div>

  <div class="p-page-hero__fade" aria-hidden="true"></div>

  <div class="l-container">
    <nav class="p-page-hero__breadcrumb" aria-label="<?php echo esc_attr( cmb_txt( 'Đường dẫn', 'Breadcrumb' ) ); ?>">
      <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo cmb_txt( 'Trang chủ', 'Home' ); ?></a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <a href="<?php echo esc_url(get_post_type_archive_link('du-an')); ?>"><?php echo cmb_txt( 'Dự án tiêu biểu', 'Featured Projects' ); ?></a>
      <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
      <span class="p-page-hero__breadcrumb-current" aria-current="page"><?php echo cmb_txt( 'Chi tiết dự án', 'Project Details' ); ?></span>
    </nav>
    <div class="p-page-hero__content">
      <span class="p-page-hero__label"><?php echo $term_name; ?></span>
      <?php the_title('<h1 class="p-page-hero__title">', '</h1>'); ?>
    </div>
  </div>

</section>
<!-- ======= /PAGE HERO ======= -->
