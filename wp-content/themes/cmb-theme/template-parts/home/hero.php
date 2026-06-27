<?php
/**
 * template-parts/home/hero.php
 * Section: Hero Banner Slider
 */
$theme = get_template_directory_uri();
?>
<!-- ======= HERO BANNER ======= -->
<section class="p-hero" id="hero">
  <div class="p-hero__slider swiper">
    <div class="swiper-wrapper">
      <?php if ( have_rows( 'slide_banner', 'option' ) ) : $slide_idx = 0; ?>
        <?php while ( have_rows( 'slide_banner', 'option' ) ) : the_row(); $slide_idx++; ?>
          <?php
            $img   = get_sub_field( 'img' );
            $title = cmb_sub( 'title' );
          ?>
          <div class="swiper-slide p-hero__slide">
            <?php if ( $img ) : ?>
              <img src="<?php echo esc_url( $img['url'] ); ?>"
                   alt="<?php echo esc_attr( $img['alt'] ?: $title ); ?>"
                   class="p-hero__bg"
                   <?php echo $slide_idx === 1 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?> />
            <?php endif; ?>
            <div class="p-hero__overlay"></div>
            <div class="l-container">
              <div class="p-hero__content">
                <?php if ( $title ) : ?>
                  <h1 class="p-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>
    <!-- Pagination -->
    <div class="p-hero__pagination swiper-pagination"></div>
  </div>
</section>
<!-- ======= /HERO BANNER ======= -->
