<?php
/**
 * template-parts/du-an/project-card.php
 * Section: Single project card — dùng chung cho render lần đầu (archive-list.php)
 * và kết quả trả về từ AJAX filter (cmb_filter_projects_handler trong functions.php).
 * Cần post hiện tại đã được setup qua the_post() trước khi gọi.
 */
$ci = isset( $args['ci'] ) ? (int) $args['ci'] : 0;

$c_terms = get_the_terms( get_the_ID(), 'du-an-category' );
$c_slug  = ( $c_terms && ! is_wp_error( $c_terms ) ) ? $c_terms[0]->slug : '';
$c_name  = ( $c_terms && ! is_wp_error( $c_terms ) ) ? $c_terms[0]->name : '';
$c_loc   = get_field( 'project_location_detail' );
$c_svc   = get_field( 'project_services' );
?>
<article class="p-projects-card" id="project-card-<?php echo esc_attr( $ci ?: get_the_ID() ); ?>" role="listitem"
         data-category="<?php echo esc_attr( $c_slug ); ?>"
         data-reveal="fade-up" data-reveal-delay="<?php echo esc_attr( ( $ci % 3 ) + 1 ); ?>">

  <div class="p-projects-card__img-wrap">
    <?php if ( has_post_thumbnail() ) : ?>
    <?php the_post_thumbnail( 'medium_large', [ 'class' => 'p-projects-card__img', 'loading' => 'lazy' ] ); ?>
    <?php endif; ?>
    <?php if ( $c_name ) : ?>
    <span class="p-projects-card__tag p-projects-card__tag--<?php echo esc_attr( $c_slug ); ?>">
      <?php echo esc_html( mb_strtoupper( $c_name, 'UTF-8' ) ); ?>
    </span>
    <?php endif; ?>
  </div>

  <div class="p-projects-card__body">
    <h3 class="p-projects-card__name">
      <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( cmb_txt( 'Xem chi tiết ', 'View details ' ) . get_the_title() ); ?>">
        <?php the_title(); ?>
      </a>
    </h3>
    <?php if ( $c_loc ) : ?>
    <p class="p-projects-card__location">
      <svg width="12" height="14" viewBox="0 0 12 15" fill="none" aria-hidden="true">
        <path d="M6 1C3.24 1 1 3.24 1 6C1 9.75 6 14 6 14C6 14 11 9.75 11 6C11 3.24 8.76 1 6 1Z"
              stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
        <circle cx="6" cy="6" r="2" stroke="currentColor" stroke-width="1.4" />
      </svg>
      <?php echo $c_loc; ?>
    </p>
    <?php endif; ?>
    <?php if ( $c_svc ) : ?>
    <p class="p-projects-card__services"><?php echo $c_svc; ?></p>
    <?php endif; ?>
  </div>

  <div class="p-projects-card__footer">
    <a href="<?php the_permalink(); ?>" class="p-projects-card__cta" title="<?php echo esc_attr( cmb_txt( 'Xem chi tiết', 'View details' ) ); ?>">
      <?php echo cmb_txt( 'Xem chi tiết', 'View details' ); ?>
      <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
        <path d="M1 5H13M9 1L13 5L9 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </a>
  </div>

</article>
