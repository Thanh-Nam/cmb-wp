<?php
/**
 * Template: Archive — Dự án (CPT archive)
 * Post type: du-an
 */
get_header();

$theme = get_template_directory_uri();

// ACF Options — hero & subtitle
$hero_img = function_exists('get_field') ? get_field('archive_du_an_hero_img', 'option') : null;
$subtitle = function_exists('get_field') ? get_field('archive_du_an_subtitle', 'option') : '';

// ACF Options — stats bar (Repeater: number + label)
$stats = function_exists('get_field') ? get_field('archive_du_an_stats', 'option') : [];

// Taxonomy filter tabs
$project_cats = get_terms(['taxonomy' => 'du-an-category', 'hide_empty' => false]);

// Featured: bài đầu tiên (sắp theo menu_order)
$featured_q = new WP_Query([
    'post_type'      => 'du-an',
    'posts_per_page' => 1,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);
$featured_id = $featured_q->have_posts() ? $featured_q->posts[0]->ID : 0;

// Grid: tất cả trừ featured, có phân trang
$paged = get_query_var('paged') ?: 1;
$projects_q = new WP_Query([
    'post_type'      => 'du-an',
    'posts_per_page' => 6,
    'paged'          => $paged,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
    'post__not_in'   => $featured_id ? [$featured_id] : [],
]);
?>

  <main class="site-main" id="main-content">

    <!-- ======= PAGE HERO ======= -->
    <section class="p-page-hero" id="projects-hero" aria-label="Dự án tiêu biểu CMB">

      <div class="p-page-hero__image-side">
        <?php if ($hero_img) : ?>
        <img src="<?php echo esc_url($hero_img['url']); ?>"
             alt="<?php echo esc_attr($hero_img['alt']); ?>"
             class="p-page-hero__image" loading="eager" />
        <?php else : ?>
        <img src="<?php echo $theme; ?>/assets/images/hero_port.jpg"
             alt="Cảng container hiện đại - CMB tư vấn xây dựng công trình hàng hải"
             class="p-page-hero__image" loading="eager" />
        <?php endif; ?>
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">
        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page">Dự án tiêu biểu</span>
        </nav>

        <div class="p-page-hero__content">
          <h1 class="p-page-hero__title">DỰ ÁN TIÊU BIỂU</h1>
          <p class="p-page-hero__subtitle">
            <?php if ($subtitle) : ?>
            <?php echo wp_kses_post($subtitle); ?>
            <?php else : ?>
            300+ dự án đa dạng lĩnh vực hàng hải, logistics,<br />
            khu công nghiệp và hạ tầng kỹ thuật trên toàn quốc.
            <?php endif; ?>
          </p>
        </div>
      </div>

    </section>
    <!-- ======= /PAGE HERO ======= -->


    <!-- ======= STATS BAR ======= -->
    <div class="p-projects-stats" id="projects-stats" aria-label="Thống kê dự án">
      <div class="l-container">
        <div class="p-projects-stats__inner">
          <?php if ($stats) : ?>
          <?php foreach ($stats as $i => $stat) : ?>
          <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="<?php echo $i + 1; ?>">
            <span class="p-projects-stats__number"><?php echo esc_html($stat['number']); ?></span>
            <span class="p-projects-stats__label"><?php echo esc_html($stat['label']); ?></span>
          </div>
          <?php endforeach; ?>
          <?php else : ?>
          <div class="p-projects-stats__item" id="stat-du-an" data-reveal="fade-up" data-reveal-delay="1">
            <span class="p-projects-stats__number">300+</span>
            <span class="p-projects-stats__label">Dự án đã thực hiện</span>
          </div>
          <div class="p-projects-stats__item" id="stat-tinh-thanh" data-reveal="fade-up" data-reveal-delay="2">
            <span class="p-projects-stats__number">15+</span>
            <span class="p-projects-stats__label">Tỉnh thành hoạt động</span>
          </div>
          <div class="p-projects-stats__item" id="stat-nam-kinh-nghiem" data-reveal="fade-up" data-reveal-delay="3">
            <span class="p-projects-stats__number">20+</span>
            <span class="p-projects-stats__label">Năm kinh nghiệm</span>
          </div>
          <div class="p-projects-stats__item" id="stat-chat-luong" data-reveal="fade-up" data-reveal-delay="4">
            <span class="p-projects-stats__number">100%</span>
            <span class="p-projects-stats__label">Cam kết chất lượng</span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- ======= /STATS BAR ======= -->


    <!-- ======= DỰ ÁN NỔI BẬT ======= -->
    <?php if ($featured_q->have_posts()) : $featured_q->the_post(); ?>
    <?php
      $f_img    = get_field('project_img');
      $f_terms  = get_the_terms(get_the_ID(), 'du-an-category');
      $f_cat    = ($f_terms && !is_wp_error($f_terms)) ? $f_terms[0]->name : '';
      $f_loc    = get_field('project_location_detail');
      $f_specs  = get_field('project_tech_specs');
    ?>
    <section class="p-projects-featured" id="projects-featured" aria-label="Dự án nổi bật">
      <div class="l-container">

        <div class="p-projects-featured__header" data-reveal="fade-up">
          <h2 class="p-projects-featured__section-title">DỰ ÁN NỔI BẬT</h2>
        </div>

        <div class="p-projects-featured__body" id="featured-project-body">

          <?php if ($f_cat) : ?>
          <span class="p-projects-featured__tag" id="featured-tag"><?php echo esc_html(strtoupper($f_cat)); ?></span>
          <?php endif; ?>

          <!-- Ảnh -->
          <div class="p-projects-featured__img-wrap" id="featured-img-wrap" data-reveal="fade-right">
            <?php if ($f_img) : ?>
            <img src="<?php echo esc_url($f_img['url']); ?>"
                 alt="<?php echo esc_attr($f_img['alt'] ?: get_the_title()); ?>"
                 class="p-projects-featured__img" id="featured-img" loading="eager" />
            <?php elseif (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large', ['class' => 'p-projects-featured__img', 'id' => 'featured-img', 'loading' => 'eager']); ?>
            <?php endif; ?>
          </div>

          <!-- Nội dung -->
          <div class="p-projects-featured__content" id="featured-content" data-reveal="fade-left">

            <?php the_title('<h2 class="p-projects-featured__title" id="featured-title">', '</h2>'); ?>

            <?php if ($f_loc) : ?>
            <p class="p-projects-featured__location" id="featured-location">
              <svg class="p-projects-featured__location-icon" width="14" height="16" viewBox="0 0 14 16" fill="none" aria-hidden="true">
                <path d="M7 1C4.24 1 2 3.24 2 6C2 9.75 7 14 7 14C7 14 12 9.75 12 6C12 3.24 9.76 1 7 1Z"
                  stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
                <circle cx="7" cy="6" r="2" stroke="currentColor" stroke-width="1.4" />
              </svg>
              <?php echo esc_html($f_loc); ?>
            </p>
            <?php endif; ?>

            <p class="p-projects-featured__desc" id="featured-desc">
              <?php echo wp_trim_words(get_the_excerpt() ?: strip_tags(get_the_content()), 40); ?>
            </p>

            <!-- Metrics: 3 thông số đầu từ project_tech_specs -->
            <?php if ($f_specs) : ?>
            <div class="p-projects-featured__metrics" id="featured-metrics">
              <?php
              $shown = 0;
              foreach ($f_specs as $i => $spec) :
                if (empty($spec['label']) || $shown >= 3) continue;
                $shown++;
              ?>
              <div class="p-projects-featured__metric" id="featured-metric-<?php echo $shown; ?>">
                <span class="p-projects-featured__metric-value"><?php echo esc_html($spec['value']); ?></span>
                <span class="p-projects-featured__metric-label"><?php echo esc_html($spec['label']); ?></span>
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
    <?php endif; ?>
    <!-- ======= /DỰ ÁN NỔI BẬT ======= -->


    <!-- ======= FILTER TABS ======= -->
    <div class="p-projects-filter" id="projects-filter" role="navigation" aria-label="Lọc dự án theo lĩnh vực">
      <div class="l-container">
        <div class="p-projects-filter__inner">
          <div class="p-projects-filter__tabs" role="tablist" aria-label="Lĩnh vực dự án">
            <button class="p-projects-filter__tab is-active" role="tab" aria-selected="true"
                    data-filter="all" id="filter-tab-all">Tất cả</button>
            <?php if ($project_cats && !is_wp_error($project_cats)) : ?>
            <?php foreach ($project_cats as $cat) : ?>
            <button class="p-projects-filter__tab" role="tab" aria-selected="false"
                    data-filter="<?php echo esc_attr($cat->slug); ?>"
                    id="filter-tab-<?php echo esc_attr($cat->slug); ?>">
              <?php echo esc_html($cat->name); ?>
            </button>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <!-- ======= /FILTER TABS ======= -->


    <!-- ======= DANH SÁCH DỰ ÁN ======= -->
    <section class="p-projects-list" id="projects-list" aria-label="Danh sách dự án tiêu biểu">
      <div class="l-container">

        <div class="p-projects-list__header" data-reveal="fade-up">
          <h2 class="p-projects-list__section-title">DANH SÁCH DỰ ÁN</h2>

          <div class="p-projects-list__view-toggle" role="group" aria-label="Chế độ hiển thị">
            <button class="p-projects-list__view-btn is-active" id="view-grid-btn"
                    aria-label="Xem dạng lưới" aria-pressed="true" title="Lưới 3 cột">
              <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <rect x="1" y="1" width="6" height="6" rx="1" fill="currentColor" />
                <rect x="9" y="1" width="6" height="6" rx="1" fill="currentColor" />
                <rect x="1" y="9" width="6" height="6" rx="1" fill="currentColor" />
                <rect x="9" y="9" width="6" height="6" rx="1" fill="currentColor" />
              </svg>
            </button>
            <button class="p-projects-list__view-btn" id="view-list-btn"
                    aria-label="Xem dạng danh sách" aria-pressed="false" title="Danh sách">
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
                   data-category="<?php echo esc_attr($c_slug); ?>"
                   data-reveal="fade-up" data-reveal-delay="<?php echo ($ci % 3) + 1; ?>">

            <div class="p-projects-card__img-wrap">
              <?php if ($c_img) : ?>
              <img src="<?php echo esc_url($c_img['url']); ?>"
                   alt="<?php echo esc_attr($c_img['alt'] ?: get_the_title()); ?>"
                   class="p-projects-card__img" loading="lazy" />
              <?php elseif (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('medium_large', ['class' => 'p-projects-card__img', 'loading' => 'lazy']); ?>
              <?php endif; ?>
              <?php if ($c_name) : ?>
              <span class="p-projects-card__tag p-projects-card__tag--<?php echo esc_attr($c_slug); ?>">
                <?php echo esc_html(strtoupper($c_name)); ?>
              </span>
              <?php endif; ?>
            </div>

            <div class="p-projects-card__body">
              <h3 class="p-projects-card__name">
                <a href="<?php the_permalink(); ?>" title="Xem chi tiết <?php the_title_attribute(); ?>">
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
                <?php echo esc_html($c_loc); ?>
              </p>
              <?php endif; ?>
              <?php if ($c_svc) : ?>
              <p class="p-projects-card__services"><?php echo esc_html($c_svc); ?></p>
              <?php endif; ?>
            </div>

            <div class="p-projects-card__footer">
              <a href="<?php the_permalink(); ?>" class="p-projects-card__cta" title="Xem chi tiết">
                Xem chi tiết
                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
                  <path d="M1 5H13M9 1L13 5L9 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </a>
            </div>

          </article>
          <?php endwhile; wp_reset_postdata(); ?>
          <?php else : ?>
          <p class="p-projects-list__empty">Chưa có dự án nào.</p>
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
        <nav class="p-projects-list__pagination" aria-label="Phân trang dự án">
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

  </main>

<?php get_footer();
