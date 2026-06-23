<?php
/**
 * Template: Archive — Tin tức & Sự kiện
 * Post type: post (standard)
 */
get_header();

// ── 1. Featured posts (is_featured = 1)
$featured_q = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [[
        'key'     => 'is_featured',
        'value'   => '1',
        'compare' => '=',
    ]],
]);

// ── 2. Tin nội bộ (category slug: noi-bo)
$noi_bo_q = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'category_name'  => 'noi-bo',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

// ── 3. Tin chuyên ngành (category slug: chuyen-nganh)
$chuyen_nganh_q = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'category_name'  => 'chuyen-nganh',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

// ── 4. All news — initial render (5/page)
$paged = get_query_var('paged') ?: 1;
$all_q = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 5,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

// ── 5. Categories for filter dropdown
$news_cats = get_categories(['hide_empty' => true, 'orderby' => 'name', 'order' => 'ASC']);
?>

  <!-- ======= MAIN ======= -->
  <main class="site-main" id="main-content">

    <!-- ======= PAGE HERO ======= -->
    <section class="p-page-hero" id="news-hero" aria-label="Tin tức & Sự kiện CMB">

      <div class="p-page-hero__image-side">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
          alt="Cảng container hiện đại - CMB tư vấn xây dựng công trình hàng hải"
          class="p-page-hero__image"
          loading="eager" />
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">
        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page">Tin tức &amp; Sự kiện</span>
        </nav>

        <div class="p-page-hero__content">
          <h1 class="p-page-hero__title">TIN TỨC &amp; SỰ KIỆN</h1>
          <p class="p-page-hero__subtitle">
            Cập nhật hoạt động, thông tin chuyên ngành<br />
            và các sự kiện nổi bật của CMB.
          </p>
        </div>
      </div>

    </section>
    <!-- ======= /PAGE HERO ======= -->


    <!-- ======= FEATURED 3-COLUMN ======= -->
    <section class="p-news-columns" id="news-columns" aria-label="Tin tức nổi bật">
      <div class="l-container">
        <div class="p-news-columns__grid">

          <!-- Left: TIN NỔI BẬT — Swiper slider -->
          <div class="p-news-columns__featured-wrap" id="news-featured-card" aria-label="Tin nổi bật" data-reveal="fade-up">

            <div class="p-news-columns__col-header">
              <h2 class="p-news-columns__col-title">TIN NỔI BẬT</h2>
            </div>

            <div class="p-news-columns__featured-card">
              <?php if ($featured_q->have_posts()) : ?>
              <div class="swiper p-news-columns__featured-swiper">
                <div class="swiper-wrapper">
                  <?php while ($featured_q->have_posts()) : $featured_q->the_post(); ?>
                  <div class="swiper-slide p-news-columns__featured-slide">
                    <?php if (has_post_thumbnail()) :
                      the_post_thumbnail('large', ['class' => 'p-news-columns__featured-img', 'loading' => 'eager']);
                    else : ?>
                      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
                        alt="<?php the_title_attribute(); ?>"
                        class="p-news-columns__featured-img" loading="eager" />
                    <?php endif; ?>
                    <div class="p-news-columns__featured-overlay" aria-hidden="true"></div>
                    <div class="p-news-columns__featured-body">
                      <p class="p-news-columns__featured-col-title">TIN NỔI BẬT</p>
                      <time class="p-news-columns__featured-date"
                        datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
                        <?php echo get_the_date('d/m/Y'); ?>
                      </time>
                      <h2 class="p-news-columns__featured-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                      </h2>
                      <p class="p-news-columns__featured-excerpt">
                        <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?>
                      </p>
                      <a href="<?php the_permalink(); ?>"
                        class="p-news-columns__featured-link"
                        title="Xem chi tiết: <?php the_title_attribute(); ?>">
                        XEM CHI TIẾT
                        <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
                          <path d="M1 5H13M9 1L13 5L9 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </a>
                    </div>
                  </div>
                  <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <div class="swiper-pagination p-news-columns__featured-pagination"></div>
              </div>
              <?php else : wp_reset_postdata(); ?>
              <p style="padding:2rem;color:#888;">Chưa có tin nổi bật.</p>
              <?php endif; ?>
            </div>

          </div>
          <!-- /Left Featured -->


          <!-- Center: TIN NỘI BỘ -->
          <div class="p-news-columns__col" id="news-col-noi-bo" data-reveal="fade-up">
            <div class="p-news-columns__col-header">
              <h2 class="p-news-columns__col-title">TIN NỘI BỘ</h2>
            </div>

            <?php if ($noi_bo_q->have_posts()) :
              while ($noi_bo_q->have_posts()) : $noi_bo_q->the_post(); ?>
            <article class="p-news-columns__item">
              <a href="<?php the_permalink(); ?>" class="p-news-columns__item-img-wrap" tabindex="-1" aria-hidden="true">
                <?php if (has_post_thumbnail()) :
                  the_post_thumbnail('medium', ['class' => 'p-news-columns__item-img', 'loading' => 'lazy']);
                else : ?>
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
                    alt="<?php the_title_attribute(); ?>"
                    class="p-news-columns__item-img" loading="lazy" />
                <?php endif; ?>
              </a>
              <div class="p-news-columns__item-content">
                <time class="p-news-columns__item-date"
                  datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
                  <?php echo get_the_date('d/m/Y'); ?>
                </time>
                <h3 class="p-news-columns__item-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
              </div>
            </article>
            <?php endwhile; wp_reset_postdata();
            else : wp_reset_postdata(); endif; ?>
          </div>
          <!-- /Center: Nội Bộ -->


          <!-- Right: TIN CHUYÊN NGÀNH -->
          <div class="p-news-columns__col p-news-columns__col--industry" id="news-col-chuyen-nganh" data-reveal="fade-up">
            <div class="p-news-columns__col-header">
              <h2 class="p-news-columns__col-title">TIN CHUYÊN NGÀNH</h2>
            </div>

            <?php if ($chuyen_nganh_q->have_posts()) :
              while ($chuyen_nganh_q->have_posts()) : $chuyen_nganh_q->the_post();
                $cs_cats  = get_the_category();
                $cs_term  = $cs_cats ? $cs_cats[0] : null;
                $cs_slug  = $cs_term ? $cs_term->slug : '';
                $cs_label = $cs_term ? strtoupper($cs_term->name) : '';
            ?>
            <article class="p-news-columns__item">
              <a href="<?php the_permalink(); ?>" class="p-news-columns__item-img-wrap" tabindex="-1" aria-hidden="true">
                <?php if (has_post_thumbnail()) :
                  the_post_thumbnail('medium', ['class' => 'p-news-columns__item-img', 'loading' => 'lazy']);
                else : ?>
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
                    alt="<?php the_title_attribute(); ?>"
                    class="p-news-columns__item-img" loading="lazy" />
                <?php endif; ?>
              </a>
              <div class="p-news-columns__item-content">
                <?php if ($cs_label) : ?>
                <span class="p-news-columns__item-badge p-news-columns__item-badge--<?php echo esc_attr($cs_slug); ?>">
                  <?php echo esc_html($cs_label); ?>
                </span>
                <?php endif; ?>
                <h3 class="p-news-columns__item-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
              </div>
            </article>
            <?php endwhile; wp_reset_postdata();
            else : wp_reset_postdata(); endif; ?>
          </div>
          <!-- /Right: Chuyên Ngành -->

        </div>
      </div>
    </section>
    <!-- ======= /FEATURED 3-COLUMN ======= -->


    <!-- ======= ALL NEWS ======= -->
    <section class="p-news-all" id="news-all" aria-label="Tất cả tin tức">
      <div class="l-container">

        <div class="p-news-all__header" data-reveal="fade-up">
          <h2 class="p-news-all__title">TẤT CẢ TIN TỨC</h2>

          <div class="p-news-all__filters"
            id="news-filters"
            data-nonce="<?php echo esc_attr(wp_create_nonce('cmb_news_filter')); ?>"
            data-ajax="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">

            <select class="p-news-all__select" id="filter-category" aria-label="Lọc theo chủ đề">
              <option value="">Tất cả chủ đề</option>
              <?php foreach ($news_cats as $cat) : ?>
              <option value="<?php echo esc_attr($cat->slug); ?>">
                <?php echo esc_html($cat->name); ?>
              </option>
              <?php endforeach; ?>
            </select>

            <select class="p-news-all__select" id="filter-sort" aria-label="Sắp xếp theo">
              <option value="newest">Mới nhất</option>
              <option value="oldest">Cũ nhất</option>
              <option value="popular">Phổ biến</option>
            </select>

          </div>
        </div>

        <div class="p-news-all__list" id="news-all-list">
          <?php if ($all_q->have_posts()) :
            while ($all_q->have_posts()) : $all_q->the_post();
              get_template_part('template-parts/news-item');
            endwhile;
            wp_reset_postdata();
          else : wp_reset_postdata(); ?>
          <p style="padding:2rem 0;text-align:center;color:#888;">Chưa có tin tức nào.</p>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <nav class="p-news-all__pagination" id="news-all-pagination" aria-label="Phân trang tin tức">
          <?php
          if ($all_q->max_num_pages > 1) {
              $links = paginate_links([
                  'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                  'format'    => '?paged=%#%',
                  'current'   => $paged,
                  'total'     => $all_q->max_num_pages,
                  'type'      => 'array',
                  'prev_text' => '&laquo;',
                  'next_text' => '&raquo;',
              ]);
              if ($links) {
                  foreach ($links as $link) {
                      $link = str_replace('class="prev page-numbers"',  'class="p-news-all__page-btn" aria-label="Trang trước"', $link);
                      $link = str_replace('class="next page-numbers"',  'class="p-news-all__page-btn" aria-label="Trang tiếp"', $link);
                      $link = str_replace('class="page-numbers current"', 'class="p-news-all__page-btn is-active" aria-current="page"', $link);
                      $link = str_replace('class="page-numbers dots"',  'class="p-news-all__page-btn p-news-all__page-btn--dots"', $link);
                      $link = str_replace('class="page-numbers"',       'class="p-news-all__page-btn"', $link);
                      echo $link;
                  }
              }
          }
          ?>
        </nav>

      </div>
    </section>
    <!-- ======= /ALL NEWS ======= -->

  </main>
  <!-- ======= /MAIN ======= -->

<?php get_footer(); ?>
