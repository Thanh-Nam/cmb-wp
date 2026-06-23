<?php
get_header();

$paged  = get_query_var('paged') ?: 1;
$lab_q  = new WP_Query([
    'post_type'      => 'phong-thi-nghiem',
    'posts_per_page' => 6,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
?>

  <main class="site-main" id="main-content">

    <!-- ======= PAGE HERO ======= -->
    <section class="p-page-hero" id="lab-hero" aria-label="Phòng thí nghiệm CMB">

      <div class="p-page-hero__image-side">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
          alt="Phòng thí nghiệm chuyên ngành xây dựng CMB"
          class="p-page-hero__image"
          loading="eager" />
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">

        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page">Phòng thí nghiệm</span>
        </nav>

        <div class="p-page-hero__content">
          <h1 class="p-page-hero__title">PHÒNG THÍ NGHIỆM</h1>
          <p class="p-page-hero__subtitle">
            Công bố các báo cáo, chứng nhận và năng lực phòng thí nghiệm<br />
            chuyên ngành xây dựng theo quy định của Bộ Xây dựng.
          </p>
        </div>

      </div>

    </section>
    <!-- ======= /PAGE HERO ======= -->


    <!-- ======= DANH SÁCH BÁO CÁO ======= -->
    <section class="p-lab-list" id="lab-list" aria-label="Báo cáo và năng lực phòng thí nghiệm">
      <div class="l-container">

        <div class="p-lab-list__heading" data-reveal="fade-up">
          <h2 class="p-lab-list__section-title">Báo cáo / Năng lực mới nhất</h2>
        </div>

        <?php if ($lab_q->have_posts()) : ?>
        <div class="p-lab-list__items">

          <?php $delay = 0; while ($lab_q->have_posts()) : $lab_q->the_post(); $delay++;

            // Category
            $terms      = get_the_terms(get_the_ID(), 'phong-thi-nghiem-category');
            $term       = ($terms && !is_wp_error($terms)) ? $terms[0] : null;
            $cat_label  = $term ? $term->name : '';
            $cat_class  = 'p-lab-item__category' . ($term ? ' p-lab-item__category--' . $term->slug : '');

            // ACF fields
            $updated_raw  = get_field('document_updated', false, false);
            $updated_ts   = $updated_raw ? strtotime((string) $updated_raw) : 0;
            $updated_label = $updated_ts ? date('m/Y', $updated_ts) : '';
            $doc_pages    = get_field('document_pages');
            $doc_size     = get_field('document_size');
            $doc_pdf      = get_field('document_pdf');
            $pdf_url      = ($doc_pdf && !empty($doc_pdf['url'])) ? $doc_pdf['url'] : '';
          ?>

          <article class="p-lab-item" data-reveal="fade-up" data-reveal-delay="<?php echo $delay % 3 ?: 3; ?>">

            <div class="p-lab-item__thumb">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium', ['class' => 'p-lab-item__thumb-img', 'loading' => 'lazy']); ?>
              <?php else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/lab-doc-1.svg"
                  alt="<?php the_title_attribute(); ?>"
                  class="p-lab-item__thumb-img" loading="lazy" />
              <?php endif; ?>
            </div>

            <div class="p-lab-item__body">

              <?php if ($cat_label) : ?>
              <span class="<?php echo esc_attr($cat_class); ?>"><?php echo esc_html($cat_label); ?></span>
              <?php endif; ?>

              <h3 class="p-lab-item__title">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                  <?php the_title(); ?>
                </a>
              </h3>

              <p class="p-lab-item__meta" aria-label="Thông tin tài liệu">
                <?php if ($updated_label) : ?>
                <span>Cập nhật: <?php echo esc_html($updated_label); ?></span>
                <?php endif; ?>
                <?php if ($doc_pages) : ?>
                <span class="p-lab-item__meta-sep" aria-hidden="true">|</span>
                <span><?php echo esc_html($doc_pages); ?> trang</span>
                <?php endif; ?>
                <?php if ($doc_size) : ?>
                <span class="p-lab-item__meta-sep" aria-hidden="true">|</span>
                <span><?php echo esc_html($doc_size); ?></span>
                <?php endif; ?>
              </p>

              <div class="p-lab-item__actions">
                <a href="<?php the_permalink(); ?>" class="p-lab-item__btn p-lab-item__btn--primary" title="Xem trực tuyến">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M8 3C4.5 3 1.5 8 1.5 8C1.5 8 4.5 13 8 13C11.5 13 14.5 8 14.5 8C14.5 8 11.5 3 8 3Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                    <circle cx="8" cy="8" r="2.5" stroke="currentColor" stroke-width="1.4"/>
                  </svg>
                  Xem trực tuyến
                </a>
                <?php if ($pdf_url) : ?>
                <a href="<?php echo esc_url($pdf_url); ?>" class="p-lab-item__btn p-lab-item__btn--outline" title="Tải PDF" download>
                  Tải PDF
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M7 1V9M7 9L4 6M7 9L10 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 11H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  </svg>
                </a>
                <?php endif; ?>
              </div>

            </div>
          </article>

          <?php endwhile; wp_reset_postdata(); ?>

        </div>

        <?php if ($lab_q->max_num_pages > 1) :
          $pagination = paginate_links([
              'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
              'format'    => '?paged=%#%',
              'current'   => $paged,
              'total'     => $lab_q->max_num_pages,
              'type'      => 'array',
              'prev_text' => '&#8249;',
              'next_text' => '&#8250;',
          ]);
          if ($pagination) : ?>
        <nav class="p-lab-pagination" aria-label="Phân trang tài liệu">
          <?php foreach ($pagination as $link) :
            $link = str_replace('class="prev page-numbers"', 'class="p-lab-pagination__btn p-lab-pagination__btn--prev"', $link);
            $link = str_replace('class="next page-numbers"', 'class="p-lab-pagination__btn p-lab-pagination__btn--next"', $link);
            $link = str_replace('class="page-numbers current"', 'class="p-lab-pagination__btn is-active" aria-current="page"', $link);
            $link = str_replace('class="page-numbers dots"', 'class="p-lab-pagination__btn p-lab-pagination__btn--dots"', $link);
            $link = str_replace('class="page-numbers"', 'class="p-lab-pagination__btn"', $link);
            echo $link;
          endforeach; ?>
        </nav>
          <?php endif;
        endif; ?>

        <?php else : ?>
        <p style="padding:2rem 0;text-align:center;color:#888;">Chưa có tài liệu nào.</p>
        <?php endif; ?>

      </div>
    </section>
    <!-- ======= /DANH SÁCH BÁO CÁO ======= -->

  </main>

<?php get_footer(); ?>
