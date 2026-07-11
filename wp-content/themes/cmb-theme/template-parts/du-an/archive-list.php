<?php
/**
 * template-parts/du-an/archive-list.php
 * Section: Projects Grid/List with Pagination
 * Excludes the featured project (cached transient ID) to avoid duplicate
 */
$featured_id = (int) get_transient('cmb_featured_du_an_id');
$paged       = get_query_var('paged') ?: 1;

$projects_q = new WP_Query([
    'post_type'      => 'du-an',
    'posts_per_page' => 6,
    'paged'          => $paged,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
    'post__not_in'   => $featured_id ? [$featured_id] : [],
]);
?>
<!-- ======= DANH SÁCH DỰ ÁN ======= -->
<section class="p-projects-list" id="projects-list" aria-label="<?php echo esc_attr( cmb_txt( 'Danh sách dự án tiêu biểu', 'List of featured projects' ) ); ?>">
  <div class="l-container">

    <div class="p-projects-list__header" data-reveal="fade-up">
      <h2 class="p-projects-list__section-title"><?php echo cmb_txt( 'DANH SÁCH DỰ ÁN', 'PROJECT LIST' ); ?></h2>

      <div class="p-projects-list__view-toggle" role="group" aria-label="<?php echo esc_attr( cmb_txt( 'Chế độ hiển thị', 'Display mode' ) ); ?>">
        <button class="p-projects-list__view-btn is-active" id="view-grid-btn"
                aria-label="<?php echo esc_attr( cmb_txt( 'Xem dạng lưới', 'Grid view' ) ); ?>" aria-pressed="true" title="<?php echo esc_attr( cmb_txt( 'Lưới 3 cột', '3-column grid' ) ); ?>">
          <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
            <rect x="1" y="1" width="6" height="6" rx="1" fill="currentColor" />
            <rect x="9" y="1" width="6" height="6" rx="1" fill="currentColor" />
            <rect x="1" y="9" width="6" height="6" rx="1" fill="currentColor" />
            <rect x="9" y="9" width="6" height="6" rx="1" fill="currentColor" />
          </svg>
        </button>
        <button class="p-projects-list__view-btn" id="view-list-btn"
                aria-label="<?php echo esc_attr( cmb_txt( 'Xem dạng danh sách', 'List view' ) ); ?>" aria-pressed="false" title="<?php echo esc_attr( cmb_txt( 'Danh sách', 'List' ) ); ?>">
          <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
            <rect x="1" y="2" width="14" height="3" rx="1" fill="currentColor" />
            <rect x="1" y="7" width="14" height="3" rx="1" fill="currentColor" />
            <rect x="1" y="12" width="14" height="3" rx="1" fill="currentColor" />
          </svg>
        </button>
      </div>
    </div>

    <div class="p-projects-list__grid" id="projects-grid" role="list">
      <?php if ($projects_q->have_posts()) : $ci = 0; ?>
      <?php while ($projects_q->have_posts()) : $projects_q->the_post(); $ci++; ?>
      <?php
        $c_img   = get_field('project_img');
        $c_terms = get_the_terms(get_the_ID(), 'du-an-category');
        $c_slug  = ($c_terms && !is_wp_error($c_terms)) ? $c_terms[0]->slug : '';
        $c_name  = ($c_terms && !is_wp_error($c_terms)) ? $c_terms[0]->name : '';
        $c_loc   = get_field('project_location_detail');
        $c_svc   = get_field('project_services');
      ?>
      <article class="p-projects-card" id="project-card-<?php echo $ci; ?>" role="listitem"
               data-category="<?php echo $c_slug; ?>"
               data-reveal="fade-up" data-reveal-delay="<?php echo ($ci % 3) + 1; ?>">

        <div class="p-projects-card__img-wrap">
          <?php if ($c_img) : ?>
          <img src="<?php echo $c_img['url']; ?>"
               alt="<?php echo esc_attr($c_img['alt'] ?: get_the_title()); ?>"
               class="p-projects-card__img" loading="lazy" />
          <?php elseif (has_post_thumbnail()) : ?>
          <?php the_post_thumbnail('medium_large', ['class' => 'p-projects-card__img', 'loading' => 'lazy']); ?>
          <?php endif; ?>
          <?php if ($c_name) : ?>
          <span class="p-projects-card__tag p-projects-card__tag--<?php echo $c_slug; ?>">
            <?php echo esc_html(strtoupper($c_name)); ?>
          </span>
          <?php endif; ?>
        </div>

        <div class="p-projects-card__body">
          <h3 class="p-projects-card__name">
            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( cmb_txt( 'Xem chi tiết ', 'View details ' ) . get_the_title() ); ?>">
              <?php the_title(); ?>
            </a>
          </h3>
          <?php if ($c_loc) : ?>
          <p class="p-projects-card__location">
            <svg width="12" height="14" viewBox="0 0 12 15" fill="none" aria-hidden="true">
              <path d="M6 1C3.24 1 1 3.24 1 6C1 9.75 6 14 6 14C6 14 11 9.75 11 6C11 3.24 8.76 1 6 1Z"
                    stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
              <circle cx="6" cy="6" r="2" stroke="currentColor" stroke-width="1.4" />
            </svg>
            <?php echo $c_loc; ?>
          </p>
          <?php endif; ?>
          <?php if ($c_svc) : ?>
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
      <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
      <p class="p-projects-list__empty"><?php echo cmb_txt( 'Chưa có dự án nào.', 'No projects available yet.' ); ?></p>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($projects_q->max_num_pages > 1) : ?>
    <?php
    $pagination = paginate_links([
        'current'   => $paged,
        'total'     => $projects_q->max_num_pages,
        'type'      => 'array',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ]);
    ?>
    <nav class="p-projects-list__pagination" aria-label="<?php echo esc_attr( cmb_txt( 'Phân trang dự án', 'Project pagination' ) ); ?>">
      <?php foreach ($pagination as $link) :
        $link = str_replace('class="page-numbers current"', 'class="p-projects-list__page-btn is-active" aria-current="page"', $link);
        $link = str_replace('class="page-numbers dots"',    'class="p-projects-list__page-btn p-projects-list__page-btn--dots"', $link);
        $link = str_replace('class="page-numbers',          'class="p-projects-list__page-btn', $link);
        echo $link;
      endforeach; ?>
    </nav>
    <?php endif; ?>

  </div>
</section>
<!-- ======= /DANH SÁCH DỰ ÁN ======= -->
