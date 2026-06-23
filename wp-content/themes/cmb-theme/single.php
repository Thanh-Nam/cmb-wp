<?php
/**
 * Template: Single post — Tin tức chi tiết
 * Post type: post (standard)
 */
get_header();

while (have_posts()) : the_post();

  $cats          = get_the_category();
  $post_cat      = $cats ? $cats[0] : null;
  $pub_datetime  = get_the_date('Y-m-d\TH:i');
  $pub_time      = get_the_date('H:i');
  $pub_date_str  = get_the_date('d.m.Y');
  $gallery       = function_exists('get_field') ? get_field('event_gallery') : [];
  $share_url     = urlencode(get_permalink());

  // News archive URL
  $news_url = get_option('page_for_posts')
    ? get_permalink(get_option('page_for_posts'))
    : home_url('/tin-tuc/');

  // Related posts (same category, exclude current)
  $cat_ids = $cats ? wp_list_pluck($cats, 'term_id') : [];
  $related_args = [
      'post_type'      => 'post',
      'posts_per_page' => 3,
      'post__not_in'   => [get_the_ID()],
      'orderby'        => 'date',
      'order'          => 'DESC',
  ];
  if ($cat_ids) $related_args['category__in'] = $cat_ids;
  $related_q = new WP_Query($related_args);

  // Prev / Next post
  $prev_post = get_previous_post();
  $next_post = get_next_post();
?>

  <!-- ======= MAIN ======= -->
  <main class="site-main" id="main-content">

    <!-- ======= NEWS ARTICLE HERO ======= -->
    <section class="p-page-hero p-news-article-hero" id="news-article-hero"
      aria-label="<?php the_title_attribute(); ?>">

      <div class="p-page-hero__image-side">
        <?php if (has_post_thumbnail()) :
          the_post_thumbnail('full', ['class' => 'p-page-hero__image', 'loading' => 'eager']);
        else : ?>
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
            alt="<?php the_title_attribute(); ?>"
            class="p-page-hero__image" loading="eager" />
        <?php endif; ?>
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">
        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <a href="<?php echo esc_url($news_url); ?>">Tin tức</a>
          <?php if ($post_cat) : ?>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <a href="<?php echo esc_url(get_category_link($post_cat->term_id)); ?>">
            <?php echo esc_html($post_cat->name); ?>
          </a>
          <?php endif; ?>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page"><?php the_title(); ?></span>
        </nav>

        <div class="p-page-hero__content">
          <?php the_title('<h1 class="p-page-hero__title">', '</h1>'); ?>
        </div>
      </div>

    </section>
    <!-- ======= /NEWS ARTICLE HERO ======= -->


    <!-- ======= NEWS DETAIL CONTENT ======= -->
    <div class="p-news-detail" id="news-detail-content">
      <div class="l-container">
        <div class="p-news-detail__layout">

          <!-- ---- ARTICLE CONTENT ---- -->
          <article class="p-news-detail__article" id="article-content" aria-label="Nội dung bài viết">

            <!-- Featured image -->
            <?php if (has_post_thumbnail()) : ?>
            <figure class="p-news-detail__featured-img">
              <?php the_post_thumbnail('large', ['loading' => 'eager']); ?>
            </figure>
            <?php endif; ?>

            <!-- Published meta -->
            <div class="p-news-detail__meta">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                <rect x="1" y="2" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.3"/>
                <path d="M1 5.5H13" stroke="currentColor" stroke-width="1.3"/>
                <path d="M4 1V3M10 1V3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
              </svg>
              <time datetime="<?php echo esc_attr($pub_datetime); ?>">
                Đăng lúc <strong><?php echo esc_html($pub_time); ?></strong>
                ngày <strong><?php echo esc_html($pub_date_str); ?></strong>
              </time>
            </div>

            <!-- Body -->
            <div class="p-news-detail__body">
              <?php the_content(); ?>
            </div>


            <!-- Event gallery -->
            <?php if (!empty($gallery)) : ?>
            <div class="p-news-detail__gallery">
              <h2 class="p-news-detail__gallery-title">HÌNH ẢNH SỰ KIỆN</h2>
              <div class="p-news-detail__gallery-grid" id="event-gallery">
                <?php foreach ($gallery as $i => $img) : ?>
                <figure data-lightbox-index="<?php echo $i; ?>" tabindex="0">
                  <img src="<?php echo esc_url($img['url']); ?>"
                    alt="<?php echo esc_attr($img['alt'] ?: get_the_title()); ?>"
                    loading="lazy" />
                </figure>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>


            <!-- POST NAVIGATION -->
            <?php if ($prev_post || $next_post) : ?>
            <nav class="p-news-detail__postnav" aria-label="Điều hướng giữa các bài viết">

              <?php if ($prev_post) : ?>
              <a href="<?php echo esc_url(get_permalink($prev_post)); ?>"
                class="p-news-detail__postnav-item p-news-detail__postnav-prev">
                <span class="p-news-detail__postnav-label">
                  <svg width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true">
                    <path d="M11 5H1M5 1L1 5L5 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Bài viết trước
                </span>
                <span class="p-news-detail__postnav-title">
                  <?php echo esc_html(get_the_title($prev_post)); ?>
                </span>
              </a>
              <?php endif; ?>

              <?php if ($next_post) : ?>
              <a href="<?php echo esc_url(get_permalink($next_post)); ?>"
                class="p-news-detail__postnav-item p-news-detail__postnav-next">
                <span class="p-news-detail__postnav-label">
                  Bài viết tiếp theo
                  <svg width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true">
                    <path d="M1 5H11M7 1L11 5L7 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
                <span class="p-news-detail__postnav-title">
                  <?php echo esc_html(get_the_title($next_post)); ?>
                </span>
              </a>
              <?php endif; ?>

            </nav>
            <?php endif; ?>
            <!-- /POST NAVIGATION -->

          </article>
          <!-- ---- /ARTICLE CONTENT ---- -->


          <!-- ---- SIDEBAR ---- -->
          <aside class="p-news-detail__sidebar" id="news-detail-sidebar" aria-label="Thông tin liên quan">

            <!-- TIN LIÊN QUAN -->
            <?php if ($related_q->have_posts()) : ?>
            <div class="p-news-detail__sidebar-widget" id="related-news-widget">
              <h3 class="p-news-detail__sidebar-title">TIN LIÊN QUAN</h3>
              <ul class="p-news-detail__related-news-list">
                <?php while ($related_q->have_posts()) : $related_q->the_post(); ?>
                <li>
                  <a href="<?php the_permalink(); ?>" class="p-news-detail__related-news-item">
                    <div class="p-news-detail__related-news-thumb">
                      <?php if (has_post_thumbnail()) :
                        the_post_thumbnail('thumbnail', ['loading' => 'lazy']);
                      else : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
                          alt="<?php the_title_attribute(); ?>" loading="lazy" />
                      <?php endif; ?>
                    </div>
                    <div class="p-news-detail__related-news-body">
                      <time class="p-news-detail__related-news-date">
                        <?php echo get_the_date('d.m.Y'); ?>
                      </time>
                      <span class="p-news-detail__related-news-title"><?php the_title(); ?></span>
                    </div>
                  </a>
                </li>
                <?php endwhile; wp_reset_postdata(); ?>
              </ul>
              <a href="<?php echo esc_url($news_url); ?>" class="p-news-detail__sidebar-cta" id="btn-all-news">
                Xem tất cả tin tức
                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
                  <path d="M1 5H13M9 1L13 5L9 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
            </div>
            <?php else : wp_reset_postdata(); endif; ?>


            <!-- CHIA SẺ -->
            <div class="p-news-detail__share-bar" id="share-widget">
              <span class="p-news-detail__share-label">Chia sẻ:</span>
              <div class="p-news-detail__share-icons">

                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>"
                  class="p-news-detail__share-btn"
                  target="_blank" rel="noopener noreferrer"
                  aria-label="Chia sẻ lên Facebook">
                  <svg width="9" height="17" viewBox="0 0 9 17" fill="none" aria-hidden="true">
                    <path d="M6 9.5H8.5L9.5 6.5H6V4.5C6 3.5 6.5 3 7.5 3H9.5V0C9.17 0 7.84 0 6.84 0C4.33 0 3 1.5 3 4V6.5H0.5V9.5H3V17H6V9.5Z" fill="currentColor"/>
                  </svg>
                </a>

                <a href="https://zalo.me/share?url=<?php echo $share_url; ?>"
                  class="p-news-detail__share-btn"
                  target="_blank" rel="noopener noreferrer"
                  aria-label="Chia sẻ lên Zalo">
                  <svg width="18" height="18" viewBox="0 0 40 40" fill="currentColor" aria-hidden="true">
                    <path d="M20 2C10.059 2 2 10.059 2 20s8.059 18 18 18 18-8.059 18-18S29.941 2 20 2zm-4.5 25.5H13v-11h2.5v11zm-1.25-12.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm14.25 12.5h-2.4l-4.6-7v7H19v-11h2.5l4.5 6.9V16.5H28.5v11z"/>
                  </svg>
                </a>

                <a href="https://www.instagram.com/"
                  class="p-news-detail__share-btn"
                  target="_blank" rel="noopener noreferrer"
                  aria-label="Chia sẻ lên Instagram">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="1.6"/>
                    <circle cx="12" cy="12" r="4.5" stroke="currentColor" stroke-width="1.6"/>
                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
                  </svg>
                </a>

              </div>
            </div>

          </aside>
          <!-- ---- /SIDEBAR ---- -->

        </div><!-- /.p-news-detail__layout -->
      </div>
    </div>
    <!-- ======= /NEWS DETAIL CONTENT ======= -->

  </main>
  <!-- ======= /MAIN ======= -->

<?php endwhile; ?>

<?php get_footer(); ?>
