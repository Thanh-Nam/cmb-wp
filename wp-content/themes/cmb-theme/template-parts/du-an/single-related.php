<?php
/**
 * template-parts/du-an/single-related.php
 * Section: Related Projects
 */
$terms    = get_the_terms(get_the_ID(), 'du-an-category');
$term_ids = ($terms && !is_wp_error($terms)) ? wp_list_pluck($terms, 'term_id') : [];

$related = new WP_Query([
    'post_type'      => 'du-an',
    'posts_per_page' => 4,
    'post__not_in'   => [get_the_ID()],
    'tax_query'      => $term_ids ? [[
        'taxonomy' => 'du-an-category',
        'field'    => 'term_id',
        'terms'    => $term_ids,
    ]] : [],
]);

if (!$related->have_posts()) {
    wp_reset_postdata();
    return;
}
?>
<!-- ======= RELATED PROJECTS ======= -->
<section class="p-related-projects" id="related-projects" aria-label="Dự án liên quan">
  <div class="l-container">

    <h2 class="p-related-projects__title">DỰ ÁN LIÊN QUAN</h2>

    <div class="p-related-projects__grid" id="related-projects-grid">
      <div class="p-related-projects__cards">
        <?php $ri = 0; while ($related->have_posts()) : $related->the_post(); $ri++; ?>
        <?php $rel_img = get_field('project_hero_image'); ?>
        <article class="p-related-projects__card" id="related-<?php echo $ri; ?>">
          <a href="<?php the_permalink(); ?>" class="p-related-projects__card-link" title="<?php the_title_attribute(); ?>">
            <div class="p-related-projects__card-img-wrap">
              <?php if ($rel_img) : ?>
              <img src="<?php echo esc_url($rel_img['url']); ?>"
                   alt="<?php echo esc_attr($rel_img['alt'] ?: get_the_title()); ?>"
                   class="p-related-projects__card-img" loading="lazy" />
              <?php elseif (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('medium_large', ['class' => 'p-related-projects__card-img', 'loading' => 'lazy']); ?>
              <?php endif; ?>
            </div>
            <div class="p-related-projects__card-body">
              <span class="p-related-projects__card-name"><?php the_title(); ?></span>
            </div>
          </a>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>

      <div class="p-related-projects__all-wrap">
        <a href="<?php echo esc_url(get_post_type_archive_link('du-an')); ?>" class="p-related-projects__all" id="btn-all-projects">
          Xem tất cả dự án
          <svg width="16" height="12" viewBox="0 0 16 12" fill="none" aria-hidden="true">
            <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
      </div>
    </div>

  </div>
</section>
<!-- ======= /RELATED PROJECTS ======= -->
