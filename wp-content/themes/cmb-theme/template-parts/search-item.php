<?php
/**
 * Template Part: Single search result item
 * Used by search.php — works across all post types
 */

$post_type = get_post_type();
$type_labels = [
    'post'                => 'Tin tức',
    'du-an'               => 'Dự án',
    'thiet-bi'            => 'Thiết bị',
    'phong-thi-nghiem'    => 'Phòng thí nghiệm',
    'quan-he-co-dong'     => 'Quan hệ cổ đông',
];
$type_label = isset( $type_labels[ $post_type ] ) ? $type_labels[ $post_type ] : ucfirst( $post_type );
?>
<article class="p-search-results__item" data-reveal="fade-up">

  <a href="<?php the_permalink(); ?>" class="p-search-results__item-img-wrap" tabindex="-1" aria-hidden="true">
    <?php if ( has_post_thumbnail() ) :
      the_post_thumbnail( 'medium', [ 'class' => 'p-search-results__item-img', 'loading' => 'lazy' ] );
    else : ?>
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero_port.jpg"
        alt="<?php the_title_attribute(); ?>"
        class="p-search-results__item-img" loading="lazy" />
    <?php endif; ?>
  </a>

  <div class="p-search-results__item-body">
    <div class="p-search-results__item-meta">
      <time class="p-search-results__item-date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
        <?php echo get_the_date( 'd/m/Y' ); ?>
      </time>
      <span class="p-search-results__item-type p-search-results__item-type--<?php echo esc_attr( $post_type ); ?>">
        <?php echo esc_html( $type_label ); ?>
      </span>
    </div>
    <h3 class="p-search-results__item-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
    <p class="p-search-results__item-excerpt">
      <?php echo esc_html( wp_trim_words( get_the_excerpt(), 25 ) ); ?>
    </p>
  </div>

  <span class="p-search-results__item-arrow" aria-hidden="true">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
      <path d="M5 12H19M14 7L19 12L14 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </span>

</article>
