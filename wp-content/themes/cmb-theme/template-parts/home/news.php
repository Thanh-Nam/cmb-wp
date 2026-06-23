<?php
/**
 * template-parts/home/news.php
 * Section: News (Featured + List)
 */
$theme = get_template_directory_uri();

// Featured: ưu tiên bài có is_featured=1, fallback bài mới nhất
$hp_featured_q = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [[
        'key'     => 'is_featured',
        'value'   => '1',
        'compare' => '=',
    ]],
]);
if ( ! $hp_featured_q->have_posts() ) {
    $hp_featured_q = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
}
$hp_featured_id = 0;

// Arrow SVG dùng lại nhiều lần
$arrow_svg = '<svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M1 5H13M9 1L13 5L9 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<!-- ======= NEWS ======= -->
<section class="p-news" id="news" aria-label="Tin tức">

  <div class="l-container">

    <!-- Header -->
    <div class="p-news__header" data-reveal="fade-up">
      <span class="c-section-label">Sự Kiện</span>
      <h2 class="c-section-title p-news__title">Tin Tức</h2>
    </div>

    <!-- Featured article -->
    <?php if ( $hp_featured_q->have_posts() ) : $hp_featured_q->the_post();
      $hp_featured_id = get_the_ID();
      $hp_f_cats      = get_the_category();
      $hp_f_cat       = $hp_f_cats ? $hp_f_cats[0] : null;
      $hp_f_cat_slug  = $hp_f_cat ? $hp_f_cat->slug : '';
      $hp_f_cat_name  = $hp_f_cat ? $hp_f_cat->name : '';
    ?>
    <article class="p-news__featured" id="news-featured-1" data-reveal="fade-left">

      <a href="<?php the_permalink(); ?>" class="p-news__featured-img-wrap" tabindex="-1" aria-hidden="true">
        <?php if ( has_post_thumbnail() ) :
          the_post_thumbnail( 'large', ['class' => 'p-news__featured-img', 'loading' => 'lazy'] );
        else : ?>
          <img src="<?php echo $theme; ?>/assets/images/hero_port.jpg"
            alt="<?php the_title_attribute(); ?>"
            class="p-news__featured-img" loading="lazy" />
        <?php endif; ?>
      </a>

      <div class="p-news__featured-content">

        <div class="p-news__meta">
          <time class="p-news__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
            <?php echo get_the_date( 'd - m - Y' ); ?>
          </time>
          <?php if ( $hp_f_cat_name ) : ?>
          <span class="p-news__cat p-news__cat--<?php echo esc_attr( $hp_f_cat_slug ); ?>">
            <?php echo esc_html( $hp_f_cat_name ); ?>
          </span>
          <?php endif; ?>
        </div>

        <?php the_title( '<h3 class="p-news__featured-title"><a href="' . esc_url( get_permalink() ) . '" class="p-news__featured-title-link">', '</a></h3>' ); ?>

        <p class="p-news__featured-excerpt">
          <?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?>
        </p>

        <a href="<?php the_permalink(); ?>" class="p-news__link" title="Xem chi tiết: <?php the_title_attribute(); ?>">
          Xem Chi Tiết <?php echo $arrow_svg; ?>
        </a>

      </div>
    </article>
    <?php wp_reset_postdata(); endif; ?>
    <!-- /Featured article -->


    <!-- Small articles list -->
    <?php
    $hp_list_q = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post__not_in'   => $hp_featured_id ? [ $hp_featured_id ] : [],
    ]);
    ?>
    <div class="p-news__list">
      <?php $hp_i = 0; while ( $hp_list_q->have_posts() ) : $hp_list_q->the_post();
        $hp_i++;
        $hp_cats     = get_the_category();
        $hp_cat      = $hp_cats ? $hp_cats[0] : null;
        $hp_cat_slug = $hp_cat ? $hp_cat->slug : '';
        $hp_cat_name = $hp_cat ? $hp_cat->name : '';
      ?>
      <article class="p-news__item" id="news-item-<?php echo $hp_i; ?>" data-reveal="fade-up" data-reveal-delay="<?php echo $hp_i; ?>">
        <a href="<?php the_permalink(); ?>" class="p-news__item-img-wrap">
          <?php if ( has_post_thumbnail() ) :
            the_post_thumbnail( 'medium', ['class' => 'p-news__item-img', 'loading' => 'lazy'] );
          else : ?>
            <img src="<?php echo $theme; ?>/assets/images/hero_port.jpg"
              alt="<?php the_title_attribute(); ?>"
              class="p-news__item-img" loading="lazy" />
          <?php endif; ?>
        </a>
        <div class="p-news__item-content">
          <div class="p-news__meta">
            <time class="p-news__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
              <?php echo get_the_date( 'd - m - Y' ); ?>
            </time>
            <?php if ( $hp_cat_name ) : ?>
            <span class="p-news__cat p-news__cat--<?php echo esc_attr( $hp_cat_slug ); ?>">
              <?php echo esc_html( $hp_cat_name ); ?>
            </span>
            <?php endif; ?>
          </div>
          <h3 class="p-news__item-title">
            <a href="<?php the_permalink(); ?>" class="p-news__item-title-link"><?php the_title(); ?></a>
          </h3>
          <a href="<?php the_permalink(); ?>" class="p-news__link" title="Xem chi tiết: <?php the_title_attribute(); ?>">
            Xem Chi Tiết <?php echo $arrow_svg; ?>
          </a>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <!-- /Small articles list -->

  </div>
</section>
<!-- ======= /NEWS ======= -->
