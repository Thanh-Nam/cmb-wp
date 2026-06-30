<?php
/**
 * template-parts/home/project.php
 * Section: Featured Projects (du-an CPT with category filter tabs)
 */
$projects_q = new WP_Query([
    'post_type'      => 'du-an',
    'posts_per_page' => 5,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
    'meta_query'     => [[
        'key'     => 'project_featured',
        'value'   => '1',
        'compare' => '=',
    ]],
]);

if ( ! $projects_q->have_posts() ) {
    wp_reset_postdata();
    return;
}
?>
<!-- ======= PROJECT ======= -->
<section class="p-project" id="project" aria-label="Dự án tiêu biểu">
  <div class="l-container">

    <!-- Header -->
    <div class="p-project__header" data-reveal="fade-up">
      <span class="c-section-label c-section-label--center">Nổi Bật</span>
      <h2 class="c-section-title p-project__title">Dự Án Tiêu Biểu</h2>
    </div>

    <!-- Filter tabs — chỉ lấy category có trong các dự án nổi bật -->
    <?php
    $project_cats = [];
    $seen_cat_ids = [];
    foreach ( $projects_q->posts as $p ) {
        $terms = get_the_terms( $p->ID, 'du-an-category' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $t ) {
                if ( ! in_array( $t->term_id, $seen_cat_ids, true ) ) {
                    $seen_cat_ids[]  = $t->term_id;
                    $project_cats[]  = $t;
                }
            }
        }
    }
    ?>
    <div class="p-project__filter-wrap" data-reveal="fade-up" data-reveal-delay="2">
      <nav class="p-project__filter" role="tablist" aria-label="Lọc dự án theo danh mục">
        <button class="p-project__tab is-active" role="tab" aria-selected="true" data-filter="all" id="tab-all">
          <span>Tất Cả</span>
        </button>
        <?php foreach ($project_cats as $cat) : ?>
        <button class="p-project__tab" role="tab" aria-selected="false"
                data-filter="<?php echo $cat->slug; ?>"
                id="tab-<?php echo $cat->slug; ?>">
          <span><?php echo $cat->name; ?></span>
        </button>
        <?php endforeach; ?>
      </nav>
    </div>

    <!-- Grid -->
    <div class="p-project__grid" id="project-grid" role="list">

      <?php $ci = 0; while ($projects_q->have_posts()) : $projects_q->the_post(); $ci++; ?>
      <?php
        $p_terms    = get_the_terms(get_the_ID(), 'du-an-category');
        $p_cat_slug = ($p_terms && !is_wp_error($p_terms)) ? $p_terms[0]->slug : '';
        $p_cat_name = ($p_terms && !is_wp_error($p_terms)) ? $p_terms[0]->name : '';
        $p_img      = get_field('project_hero_image');
        $p_owner    = get_field('project_owner');
        $p_loc      = get_field('project_location_detail');
        $p_svc      = get_field('project_services');
        $is_feat    = ($ci === 1);
      ?>
      <article class="p-project__card<?php echo $is_feat ? ' p-project__card--featured' : ''; ?>"
               id="project-card-<?php echo $ci; ?>" role="listitem"
               data-category="<?php echo $p_cat_slug; ?>"
               data-reveal="fade-up" data-reveal-delay="<?php echo $ci; ?>">

        <?php if ($p_img) : ?>
        <img src="<?php echo $p_img['url']; ?>"
             alt="<?php echo esc_attr($p_img['alt'] ?: get_the_title()); ?>"
             class="p-project__card-img" loading="lazy" />
        <?php elseif (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('large', ['class' => 'p-project__card-img', 'loading' => 'lazy']); ?>
        <?php endif; ?>

        <div class="p-project__card-overlay" aria-hidden="true"></div>
        <div class="p-project__card-body">
          <span class="p-project__card-num" aria-hidden="true">
            <?php echo str_pad($ci, 2, '0', STR_PAD_LEFT); ?><span class="p-project__card-num-dot">.</span>
          </span>
          <div class="p-project__card-content">
            <div class="p-project__card-meta">
              <span class="p-project__card-tag-dot" aria-hidden="true"></span>
              <span class="p-project__card-cat"><?php echo $p_cat_name; ?></span>
            </div>
            <h3 class="p-project__card-title"><?php the_title(); ?></h3>
            <div class="p-project__card-extra">
              <?php if ($is_feat && ($p_owner || $p_loc || $p_svc)) : ?>
              <ul class="p-project__card-info" aria-label="Thông tin dự án">
                <?php if ($p_owner) : ?><li>Chủ đầu tư: <?php echo $p_owner; ?></li><?php endif; ?>
                <?php if ($p_loc) : ?><li>Địa điểm/vị trí: <?php echo $p_loc; ?></li><?php endif; ?>
                <?php if ($p_svc) : ?><li>Dịch vụ tư vấn chính: <?php echo $p_svc; ?></li><?php endif; ?>
              </ul>
              <?php endif; ?>
              <a href="<?php the_permalink(); ?>" class="p-project__card-btn"
                 title="Xem chi tiết <?php the_title_attribute(); ?>">
                Chi Tiết
                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
                  <path d="M1 5H13M9 1L13 5L9 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>

    </div>
    <!-- /Grid -->

  </div>
</section>
<!-- ======= /PROJECT ======= -->
