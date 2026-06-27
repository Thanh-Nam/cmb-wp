<?php
/**
 * template-parts/home/field.php
 * Section: Fields of Operation (Lĩnh vực hoạt động)
 */

// Section header from Options Page
$field_subtitle    = cmb_get_option( 'homefield_subtitle' ) ?: 'Khám Phá';
$field_title_raw   = cmb_get_option( 'homefield_title' ) ?: '';
$field_title_lines = $field_title_raw
    ? array_values( array_filter( array_map( 'trim', explode( "\n", $field_title_raw ) ) ) )
    : [ 'Lĩnh Vực', 'Hoạt Động' ];
$field_content     = cmb_get_option( 'homefield_content' );

// Slides from linh-vuc CPT
$field_q = new WP_Query( [
    'post_type'      => 'linh-vuc',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );
?>
<!-- ======= FIELDS OF OPERATION ======= -->
<section class="p-field" id="field" aria-label="Lĩnh vực hoạt động">
  <div class="l-container">
    <div class="p-field__inner">

      <!-- Left: text content -->
      <div class="p-field__left" data-reveal="fade-left">

        <div class="p-field__label">
          <span class="c-section-label"><?php echo $field_subtitle; ?></span>
        </div>

        <h2 class="c-section-title p-field__title">
          <?php if ( isset( $field_title_lines[0] ) ) : ?>
          <span class="p-field__title-line p-field__title-line--blue"><?php echo $field_title_lines[0]; ?></span>
          <?php endif; ?>
          <?php if ( isset( $field_title_lines[1] ) ) : ?>
          <span class="p-field__title-line p-field__title-line--red"><?php echo $field_title_lines[1]; ?></span>
          <?php endif; ?>
        </h2>

        <?php if ( $field_content ) : ?>
        <div class="p-field__desc"><?php echo $field_content; ?></div>
        <?php endif; ?>

      </div>

      <!-- Right: Swiper coverflow -->
      <div class="p-field__right">
        <div class="swiper p-field__swiper" id="field-swiper">
          <div class="swiper-wrapper">

            <?php
            if ( $field_q->have_posts() ) :
              $slide_index = 0;
              while ( $field_q->have_posts() ) : $field_q->the_post();
                $slide_index++;
                $slide_num = sprintf( '%02d', $slide_index );
                $thumb_url = get_the_post_thumbnail_url( null, 'large' )
                             ?: get_template_directory_uri() . '/assets/images/hero_port.jpg';
                $slide_excerpt = get_the_excerpt();
            ?>
            <div class="swiper-slide p-field__slide">
              <article class="p-field__card" aria-label="<?php the_title_attribute(); ?>">
                <img src="<?php echo $thumb_url; ?>"
                  alt="<?php the_title_attribute(); ?>"
                  class="p-field__card-img" loading="lazy" />
                <div class="p-field__card-overlay" aria-hidden="true"></div>
                <div class="p-field__card-content">
                  <span class="p-field__card-num" aria-hidden="true"><?php echo $slide_num; ?></span>
                  <h3 class="p-field__card-title"><?php the_title(); ?></h3>
                  <?php if ( $slide_excerpt ) : ?>
                  <p class="p-field__card-desc"><?php echo $slide_excerpt; ?></p>
                  <?php endif; ?>
                  <a href="<?php the_permalink(); ?>" class="p-field__card-link"
                    title="Xem thêm về <?php the_title_attribute(); ?>">
                    Khám phá thêm
                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </a>
                </div>
              </article>
            </div>
            <?php endwhile; wp_reset_postdata(); endif; ?>

          </div>
          <!-- Pagination -->
          <div class="swiper-pagination p-field__pagination" aria-label="Chọn lĩnh vực hoạt động"></div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- ======= /FIELDS OF OPERATION ======= -->
