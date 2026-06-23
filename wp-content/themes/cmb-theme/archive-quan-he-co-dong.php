<?php
get_header();

$ir_terms = get_terms([
    'taxonomy'   => 'quan-he-co-dong-category',
    'hide_empty' => true,
    'orderby'    => 'term_order',
    'order'      => 'ASC',
]);
if (is_wp_error($ir_terms)) $ir_terms = [];
?>

  <main class="site-main" id="main-content">

    <!-- ======= PAGE HERO ======= -->
    <section class="p-page-hero" id="ir-hero" aria-label="Quan hệ cổ đông CMB">

      <div class="p-page-hero__image-side">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
          alt="Quan hệ cổ đông Công ty Cổ phần Tư vấn Xây dựng Công trình Hàng hải"
          class="p-page-hero__image" loading="eager" />
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">
        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page">Quan hệ cổ đông</span>
        </nav>
        <div class="p-page-hero__content">
          <h1 class="p-page-hero__title">QUAN HỆ CỔ ĐÔNG</h1>
          <p class="p-page-hero__subtitle">
            Minh bạch thông tin, bảo vệ quyền lợi cổ đông<br />
            và cam kết phát triển bền vững cùng nhà đầu tư.
          </p>
        </div>
      </div>

    </section>
    <!-- ======= /PAGE HERO ======= -->


    <?php if (!empty($ir_terms)) : ?>

    <!-- ======= TAB NAVIGATION ======= -->
    <nav class="p-ir-tabs" id="ir-tabs" aria-label="Danh mục quan hệ cổ đông">
      <div class="l-container">
        <ul class="p-ir-tabs__list" role="tablist">
          <?php foreach ($ir_terms as $i => $term) : ?>
          <li class="p-ir-tabs__item" role="presentation">
            <button class="p-ir-tabs__link<?php echo ($i === 0) ? ' is-active' : ''; ?>"
              id="<?php echo esc_attr('tab-' . $term->slug); ?>"
              role="tab"
              aria-selected="<?php echo ($i === 0) ? 'true' : 'false'; ?>"
              aria-controls="<?php echo esc_attr('panel-' . $term->slug); ?>"
              data-target="<?php echo esc_attr('panel-' . $term->slug); ?>">
              <span class="p-ir-tabs__label"><?php echo esc_html($term->name); ?></span>
            </button>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </nav>
    <!-- ======= /TAB NAVIGATION ======= -->


    <!-- ======= NỘI DUNG CHÍNH ======= -->
    <section class="p-ir-body" id="ir-content">

      <?php foreach ($ir_terms as $i => $term) :

        // Query all posts in this term
        $term_q = new WP_Query([
            'post_type'      => 'quan-he-co-dong',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => [[
                'taxonomy' => 'quan-he-co-dong-category',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ]],
        ]);

        // Group posts by year using document_updated ACF field
        $grouped = [];
        while ($term_q->have_posts()) {
            $term_q->the_post();
            $raw  = get_field('document_updated', false, false);
            $ts   = $raw ? strtotime((string) $raw) : get_the_time('U');
            $year = date('Y', $ts);
            $pdf  = get_field('document_pdf');
            $grouped[$year][] = [
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'date_md'   => date('d/m', $ts),
                'pdf_url'   => ($pdf && !empty($pdf['url'])) ? $pdf['url'] : '',
            ];
        }
        wp_reset_postdata();
        krsort($grouped);

        // Featured: 3 most recent
        $feat_q = new WP_Query([
            'post_type'      => 'quan-he-co-dong',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => [[
                'taxonomy' => 'quan-he-co-dong-category',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ]],
        ]);
      ?>

      <div class="p-ir-panel<?php echo ($i === 0) ? ' is-active' : ''; ?>"
        id="<?php echo esc_attr('panel-' . $term->slug); ?>"
        role="tabpanel"
        aria-labelledby="<?php echo esc_attr('tab-' . $term->slug); ?>">
        <div class="p-ir-panel__inner">
          <div class="l-container">
            <div class="p-ir-panel__grid">

              <!-- ---- TIMELINE ---- -->
              <div class="p-ir-timeline" data-reveal="fade-right">
                <?php if (!empty($grouped)) :
                  $first_year = true;
                  foreach ($grouped as $year => $posts) :
                    $first_post = true;
                ?>
                <div class="p-ir-timeline__group">
                  <div class="p-ir-timeline__year">
                    <span class="p-ir-timeline__year-label"><?php echo esc_html($year); ?></span>
                    <span class="p-ir-timeline__year-line" aria-hidden="true"></span>
                  </div>
                  <div class="p-ir-timeline__items">
                    <?php foreach ($posts as $post_data) :
                      $highlight  = ($first_year && $first_post) ? ' p-ir-timeline__item--highlight' : '';
                      $first_post = false;
                      $href       = $post_data['pdf_url'] ?: $post_data['permalink'];
                      $dl_attr    = $post_data['pdf_url'] ? ' download' : '';
                    ?>
                    <a href="<?php echo esc_url($href); ?>" class="p-ir-timeline__item<?php echo $highlight; ?>"<?php echo $dl_attr; ?>>
                      <span class="p-ir-timeline__date"><?php echo esc_html($post_data['date_md']); ?></span>
                      <p class="p-ir-timeline__title"><?php echo esc_html($post_data['title']); ?></p>
                      <span class="p-ir-timeline__action" aria-label="Tải tài liệu">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path d="M8 2V10M8 10L5 7M8 10L11 7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M2 13H14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                      </span>
                    </a>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?php $first_year = false; endforeach;
                else : ?>
                <p style="color:#888;padding:1rem 0;">Chưa có tài liệu.</p>
                <?php endif; ?>
              </div>
              <!-- ---- /TIMELINE ---- -->


              <!-- ---- TÀI LIỆU NỔI BẬT ---- -->
              <aside class="p-ir-featured" aria-label="Tài liệu nổi bật" data-reveal="fade-left">
                <div class="p-ir-featured__heading">
                  <h2 class="p-ir-featured__title">Tài liệu nổi bật</h2>
                </div>
                <?php if ($feat_q->have_posts()) : ?>
                <ul class="p-ir-featured__list" role="list">
                  <?php while ($feat_q->have_posts()) : $feat_q->the_post();
                    $feat_pdf  = get_field('document_pdf');
                    $feat_url  = ($feat_pdf && !empty($feat_pdf['url'])) ? $feat_pdf['url'] : '';
                    $feat_size = get_field('document_size');
                    $href      = $feat_url ?: get_permalink();
                    $dl_attr   = $feat_url ? ' download' : '';
                  ?>
                  <li>
                    <a href="<?php echo esc_url($href); ?>" class="p-ir-feat-doc"<?php echo $dl_attr; ?>>
                      <div class="p-ir-feat-doc__thumb">
                        <?php if (has_post_thumbnail()) : ?>
                          <?php the_post_thumbnail('thumbnail', ['class' => 'p-ir-feat-doc__thumb-img']); ?>
                        <?php else : ?>
                          <svg width="48" height="62" viewBox="0 0 48 62" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="48" height="62" rx="4" fill="#0379CC"/>
                            <rect width="5" height="62" fill="#015A99"/>
                            <path d="M12 20H36M12 27H30M12 34H22" stroke="white" stroke-width="2" stroke-opacity="0.5" stroke-linecap="round"/>
                            <path d="M12 44H36M12 51H28" stroke="white" stroke-width="2" stroke-opacity="0.3" stroke-linecap="round"/>
                          </svg>
                        <?php endif; ?>
                      </div>
                      <div class="p-ir-feat-doc__body">
                        <h3 class="p-ir-feat-doc__title"><?php the_title(); ?></h3>
                        <div class="p-ir-feat-doc__meta">
                          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="1" y="0.5" width="10" height="11" rx="1.5" stroke="currentColor" stroke-width="1.2"/>
                            <path d="M3.5 4H8.5M3.5 6.5H6.5" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                          </svg>
                          <span>PDF<?php echo $feat_size ? ' · ' . esc_html($feat_size) : ''; ?></span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <?php endwhile; wp_reset_postdata(); ?>
                </ul>
                <?php else : wp_reset_postdata(); endif; ?>
              </aside>
              <!-- ---- /TÀI LIỆU NỔI BẬT ---- -->

            </div>
          </div>
        </div>
      </div>

      <?php endforeach; ?>

    </section>
    <!-- ======= /NỘI DUNG CHÍNH ======= -->

    <?php else : ?>
    <div class="l-container" style="padding:4rem 0;text-align:center;color:#888;">Chưa có tài liệu nào.</div>
    <?php endif; ?>

  </main>

<?php get_footer(); ?>
