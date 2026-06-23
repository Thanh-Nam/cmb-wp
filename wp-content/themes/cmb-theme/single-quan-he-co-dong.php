<?php
/**
 * Template: Single — Quan hệ cổ đông chi tiết
 * Post type: quan-he-co-dong
 */
get_header();

// ACF fields
$updated_raw  = get_field('document_updated', false, false);
$updated_ts   = $updated_raw ? strtotime((string) $updated_raw) : 0;
$updated_full = $updated_ts ? date('d/m/Y H:i', $updated_ts) : '';
$doc_pages    = get_field('document_pages');
$doc_size     = get_field('document_size');
$doc_pdf      = get_field('document_pdf');
$pdf_url      = ($doc_pdf && !empty($doc_pdf['url'])) ? $doc_pdf['url'] : '';

// Related docs (same taxonomy, exclude current)
$rel_terms    = get_the_terms(get_the_ID(), 'quan-he-co-dong-category');
$rel_term_ids = ($rel_terms && !is_wp_error($rel_terms)) ? wp_list_pluck($rel_terms, 'term_id') : [];
$related_q    = new WP_Query([
    'post_type'      => 'quan-he-co-dong',
    'posts_per_page' => 4,
    'post__not_in'   => [get_the_ID()],
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => $rel_term_ids ? [[
        'taxonomy' => 'quan-he-co-dong-category',
        'field'    => 'term_id',
        'terms'    => $rel_term_ids,
    ]] : [],
]);
?>

  <main class="site-main" id="main-content">

    <!-- ======= PAGE HERO ======= -->
    <section class="p-page-hero" id="ir-detail-hero" aria-label="Quan hệ cổ đông CMB">

      <div class="p-page-hero__image-side">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
          alt="Quan hệ cổ đông Công ty Cổ phần Tư vấn Xây dựng Công trình Hàng hải"
          class="p-page-hero__image"
          loading="eager" />
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">
        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <a href="<?php echo esc_url(get_post_type_archive_link('quan-he-co-dong')); ?>">Quan hệ cổ đông</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page">Xem tài liệu</span>
        </nav>

        <div class="p-page-hero__content">
          <h1 class="p-page-hero__title">QUAN HỆ CỔ ĐÔNG</h1>
        </div>
      </div>

    </section>
    <!-- ======= /PAGE HERO ======= -->


    <div class="p-lab-detail" id="ir-detail-content">
      <div class="l-container">

        <!-- ---- Header ---- -->
        <div class="p-lab-detail__header" data-reveal="fade-up">

          <?php the_title('<h2 class="p-lab-detail__title">', '</h2>'); ?>

          <div class="p-lab-detail__meta">
            <?php if ($updated_full) : ?>
            <span class="p-lab-detail__meta-item">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect x="1" y="2" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.3"/>
                <path d="M1 5.5H13" stroke="currentColor" stroke-width="1.3"/>
                <path d="M4 1V3M10 1V3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
              </svg>
              <?php echo esc_html($updated_full); ?>
            </span>
            <?php endif; ?>
            <?php if ($doc_pages) : ?>
            <span class="p-lab-detail__meta-sep" aria-hidden="true"></span>
            <span class="p-lab-detail__meta-item">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M3 2H9L12 5V12H3V2Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                <path d="M9 2V5H12" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>
                <path d="M5 7H9M5 9H8" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
              </svg>
              <?php echo esc_html($doc_pages); ?> trang
            </span>
            <?php endif; ?>
            <?php if ($doc_size) : ?>
            <span class="p-lab-detail__meta-sep" aria-hidden="true"></span>
            <span class="p-lab-detail__meta-item">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="7" cy="7" r="5.5" stroke="currentColor" stroke-width="1.3"/>
                <path d="M4.5 7C4.5 5.62 5.62 4.5 7 4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
              </svg>
              <?php echo esc_html($doc_size); ?>
            </span>
            <?php endif; ?>
          </div>

          <?php if ($pdf_url) : ?>
          <div class="p-lab-detail__actions">
            <a href="<?php echo esc_url($pdf_url); ?>" class="p-lab-detail__btn p-lab-detail__btn--primary" download title="Tải xuống PDF">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M7 1V9M7 9L4 6M7 9L10 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 11H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
              Tải xuống PDF
            </a>
          </div>
          <?php endif; ?>

        </div>
        <!-- ---- /Header ---- -->


        <div class="p-lab-detail__layout">
          <div class="p-lab-detail__main">

            <!-- ---- PDF Viewer ---- -->
            <div class="p-lab-detail__viewer" id="pdf-viewer-wrap">
              <?php if ($pdf_url) : ?>
              <iframe
                id="pdf-iframe"
                src="<?php echo esc_url($pdf_url); ?>"
                title="<?php the_title_attribute(); ?>"
                allowfullscreen
                loading="lazy"
                aria-label="Xem tài liệu PDF">
              </iframe>
              <div class="p-lab-detail__viewer-fallback" id="pdf-fallback" aria-live="polite">
                <svg width="48" height="60" viewBox="0 0 48 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M6 4H30L44 18V56H6V4Z" stroke="#0379CC" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M30 4V18H44" stroke="#0379CC" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M14 28H34M14 34H28M14 40H22" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <p>Trình duyệt của bạn không hỗ trợ xem PDF trực tiếp.</p>
                <a href="<?php echo esc_url($pdf_url); ?>" download>
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M7 1V9M7 9L4 6M7 9L10 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 11H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  </svg>
                  Tải xuống để xem
                </a>
              </div>
              <?php else : ?>
              <div class="p-lab-detail__viewer-fallback">
                <svg width="48" height="60" viewBox="0 0 48 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M6 4H30L44 18V56H6V4Z" stroke="#0379CC" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M30 4V18H44" stroke="#0379CC" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M14 28H34M14 34H28M14 40H22" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <p>Tài liệu chưa được tải lên.</p>
              </div>
              <?php endif; ?>
            </div>
            <!-- ---- /PDF Viewer ---- -->

          </div><!-- /.p-lab-detail__main -->


          <aside class="p-lab-detail__sidebar">

            <!-- THÔNG TIN TÀI LIỆU -->
            <div class="p-lab-detail__doc-info" data-reveal="fade-left">
              <h3 class="p-lab-detail__sidebar-title">THÔNG TIN TÀI LIỆU</h3>
              <ul class="p-lab-detail__info-list">

                <li class="p-lab-detail__info-row">
                  <span class="p-lab-detail__info-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                      <path d="M4 2H11L15 6V16H4V2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                      <path d="M11 2V6H15" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                    </svg>
                  </span>
                  <span class="p-lab-detail__info-text">
                    <span class="p-lab-detail__info-label">Định dạng</span>
                    <span class="p-lab-detail__info-value">PDF</span>
                  </span>
                </li>

                <?php if ($doc_pages) : ?>
                <li class="p-lab-detail__info-row">
                  <span class="p-lab-detail__info-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                      <path d="M4 2H11L15 6V16H4V2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                      <path d="M11 2V6H15" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                      <path d="M6 9H12M6 12H10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                  </span>
                  <span class="p-lab-detail__info-text">
                    <span class="p-lab-detail__info-label">Số trang</span>
                    <span class="p-lab-detail__info-value"><?php echo esc_html($doc_pages); ?> trang</span>
                  </span>
                </li>
                <?php endif; ?>

                <?php if ($doc_size) : ?>
                <li class="p-lab-detail__info-row">
                  <span class="p-lab-detail__info-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                      <path d="M9 3V9L12 12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                      <circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="1.4"/>
                    </svg>
                  </span>
                  <span class="p-lab-detail__info-text">
                    <span class="p-lab-detail__info-label">Dung lượng</span>
                    <span class="p-lab-detail__info-value"><?php echo esc_html($doc_size); ?></span>
                  </span>
                </li>
                <?php endif; ?>

                <?php if ($updated_full) : ?>
                <li class="p-lab-detail__info-row">
                  <span class="p-lab-detail__info-icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                      <rect x="2" y="3" width="14" height="13" rx="2" stroke="currentColor" stroke-width="1.4"/>
                      <path d="M2 7H16" stroke="currentColor" stroke-width="1.4"/>
                      <path d="M6 1V4M12 1V4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                  </span>
                  <span class="p-lab-detail__info-text">
                    <span class="p-lab-detail__info-label">Cập nhật</span>
                    <span class="p-lab-detail__info-value"><?php echo esc_html($updated_full); ?></span>
                  </span>
                </li>
                <?php endif; ?>

              </ul>
            </div>

            <!-- TÀI LIỆU LIÊN QUAN -->
            <?php if ($related_q->have_posts()) : ?>
            <div class="p-lab-detail__related" data-reveal="fade-left">
              <h3 class="p-lab-detail__sidebar-title">TÀI LIỆU LIÊN QUAN</h3>
              <ul class="p-lab-detail__related-list">
                <?php while ($related_q->have_posts()) : $related_q->the_post(); ?>
                <li>
                  <a href="<?php the_permalink(); ?>" class="p-lab-detail__related-item">
                    <span class="p-lab-detail__related-icon-box">
                      <svg width="18" height="22" viewBox="0 0 18 22" fill="none" aria-hidden="true">
                        <path d="M3 2H11L16 7V20H3V2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                        <path d="M11 2V7H16" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                        <path d="M6 11H12M6 14H10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                      </svg>
                    </span>
                    <span class="p-lab-detail__related-text"><?php the_title(); ?></span>
                  </a>
                </li>
                <?php endwhile; wp_reset_postdata(); ?>
              </ul>
            </div>
            <?php else : wp_reset_postdata(); endif; ?>

          </aside>

        </div><!-- /.p-lab-detail__layout -->

      </div>
    </div>

  </main>

<?php get_footer(); ?>
