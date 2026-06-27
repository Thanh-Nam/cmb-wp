<?php
/**
 * template-parts/du-an/archive-featured.php
 * Section: Featured Project — Dự án nổi bật
 * Uses Transient caching (12h) to avoid repeated query on every page load
 */
$featured_id = get_transient('cmb_featured_du_an_id');
if ($featured_id === false) {
    $featured_q_tmp = new WP_Query([
        'post_type'      => 'du-an',
        'posts_per_page' => 1,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
        'fields'         => 'ids',
    ]);
    $featured_id = $featured_q_tmp->have_posts() ? $featured_q_tmp->posts[0] : 0;
    set_transient('cmb_featured_du_an_id', $featured_id, 12 * HOUR_IN_SECONDS);
}

$featured_q = new WP_Query([
    'post_type'      => 'du-an',
    'posts_per_page' => 1,
    'p'              => $featured_id ?: -1,
]);

if (!$featured_q->have_posts()) {
    wp_reset_postdata();
    return;
}
$featured_q->the_post();

$f_img   = get_field('project_img');
$f_terms = get_the_terms(get_the_ID(), 'du-an-category');
$f_cat   = ($f_terms && !is_wp_error($f_terms)) ? $f_terms[0]->name : '';
$f_loc   = get_field('project_location_detail');
$f_specs = get_field('project_tech_specs');
?>
<!-- ======= DỰ ÁN NỔI BẬT ======= -->
<section class="p-projects-featured" id="projects-featured" aria-label="Dự án nổi bật">
  <div class="l-container">

    <div class="p-projects-featured__header" data-reveal="fade-up">
      <h2 class="p-projects-featured__section-title">DỰ ÁN NỔI BẬT</h2>
    </div>

    <div class="p-projects-featured__body" id="featured-project-body">

      <?php if ($f_cat) : ?>
      <span class="p-projects-featured__tag" id="featured-tag"><?php echo esc_html(strtoupper($f_cat)); ?></span>
      <?php endif; ?>

      <div class="p-projects-featured__img-wrap" id="featured-img-wrap" data-reveal="fade-right">
        <?php if ($f_img) : ?>
        <img src="<?php echo $f_img['url']; ?>"
             alt="<?php echo esc_attr($f_img['alt'] ?: get_the_title()); ?>"
             class="p-projects-featured__img" id="featured-img" loading="eager" />
        <?php elseif (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('large', ['class' => 'p-projects-featured__img', 'id' => 'featured-img', 'loading' => 'eager']); ?>
        <?php endif; ?>
      </div>

      <div class="p-projects-featured__content" id="featured-content" data-reveal="fade-left">

        <?php the_title('<h2 class="p-projects-featured__title" id="featured-title">', '</h2>'); ?>

        <?php if ($f_loc) : ?>
        <p class="p-projects-featured__location" id="featured-location">
          <svg class="p-projects-featured__location-icon" width="14" height="16" viewBox="0 0 14 16" fill="none" aria-hidden="true">
            <path d="M7 1C4.24 1 2 3.24 2 6C2 9.75 7 14 7 14C7 14 12 9.75 12 6C12 3.24 9.76 1 7 1Z"
                  stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
            <circle cx="7" cy="6" r="2" stroke="currentColor" stroke-width="1.4" />
          </svg>
          <?php echo $f_loc; ?>
        </p>
        <?php endif; ?>

        <p class="p-projects-featured__desc" id="featured-desc">
          <?php echo wp_trim_words(get_the_excerpt() ?: strip_tags(get_the_content()), 40); ?>
        </p>

        <?php if ($f_specs) : ?>
        <div class="p-projects-featured__metrics" id="featured-metrics">
          <?php $shown = 0;
          foreach ($f_specs as $i => $spec) :
            if (empty($spec['label']) || $shown >= 3) continue;
            $shown++;
          ?>
          <div class="p-projects-featured__metric" id="featured-metric-<?php echo $shown; ?>">
            <span class="p-projects-featured__metric-value"><?php echo $spec['value']; ?></span>
            <span class="p-projects-featured__metric-label"><?php echo $spec['label']; ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <a href="<?php the_permalink(); ?>" class="p-projects-featured__cta" id="featured-cta"
           title="Xem chi tiết <?php the_title_attribute(); ?>">
          Xem chi tiết dự án
          <svg width="16" height="12" viewBox="0 0 16 12" fill="none" aria-hidden="true">
            <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>

      </div>

    </div>
  </div>
</section>
<?php wp_reset_postdata(); ?>
<!-- ======= /DỰ ÁN NỔI BẬT ======= -->
