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

    <div class="p-projects-list__grid" id="projects-grid" role="list"
         data-nonce="<?php echo esc_attr(wp_create_nonce('cmb_projects_filter')); ?>">
      <?php if ($projects_q->have_posts()) : $ci = 0; ?>
      <?php while ($projects_q->have_posts()) : $projects_q->the_post(); $ci++; ?>
        <?php get_template_part('template-parts/du-an/project-card', null, ['ci' => $ci]); ?>
      <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
      <p class="p-projects-list__empty"><?php echo cmb_txt( 'Chưa có dự án nào.', 'No projects available yet.' ); ?></p>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <nav class="p-projects-list__pagination" id="projects-pagination" aria-label="<?php echo esc_attr( cmb_txt( 'Phân trang dự án', 'Project pagination' ) ); ?>">
      <?php if ($projects_q->max_num_pages > 1) :
      $pagination = paginate_links([
          'current'   => $paged,
          'total'     => $projects_q->max_num_pages,
          'type'      => 'array',
          'prev_text' => '&laquo;',
          'next_text' => '&raquo;',
      ]);
      foreach ($pagination as $link) :
        $link = str_replace('class="page-numbers current"', 'class="p-projects-list__page-btn is-active" aria-current="page"', $link);
        $link = str_replace('class="page-numbers dots"',    'class="p-projects-list__page-btn p-projects-list__page-btn--dots"', $link);
        $link = str_replace('class="page-numbers',          'class="p-projects-list__page-btn', $link);
        echo $link;
      endforeach;
      endif; ?>
    </nav>

  </div>
</section>
<!-- ======= /DANH SÁCH DỰ ÁN ======= -->
