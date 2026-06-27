<?php
/**
 * Template Part: Page Hero — dùng chung cho các trang nội dung
 * ACF fields: page_hero_title, page_hero_subtitle, page_hero_image
 */
$title    = function_exists('get_field') ? get_field('page_hero_title')    : get_the_title();
$subtitle = function_exists('get_field') ? get_field('page_hero_subtitle') : '';
$image    = function_exists('get_field') ? get_field('page_hero_image')    : '';
?>
<section class="c-page-hero">
  <?php if ( $image ) : ?>
    <img src="<?php echo esc_url( is_array($image) ? $image['url'] : $image ); ?>" alt="" class="c-page-hero__bg" loading="eager" />
  <?php endif; ?>
  <div class="c-page-hero__overlay"></div>
  <div class="l-container">
    <div class="c-page-hero__content">
      <h1 class="c-page-hero__title"><?php echo wp_kses_post( $title ?: get_the_title() ); ?></h1>
      <?php if ( $subtitle ) : ?>
        <p class="c-page-hero__subtitle"><?php echo $subtitle; ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>
