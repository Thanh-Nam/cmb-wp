<?php
/**
 * Template: Single — Lĩnh vực hoạt động chi tiết
 * Post type: linh-vuc
 */
get_header();

while ( have_posts() ) : the_post();

$pub_datetime = get_the_date( 'Y-m-d\TH:i' );
$pub_time     = get_the_date( 'H:i' );
$pub_date_str = get_the_date( 'd.m.Y' );

$prev_post = get_previous_post( false, '', 'post_type' );
$next_post = get_next_post( false, '', 'post_type' );
?>

  <main class="site-main" id="main-content">

    <!-- ======= HERO ======= -->
    <section class="p-page-hero" id="linh-vuc-hero" aria-label="<?php the_title_attribute(); ?>">

      <div class="p-page-hero__image-side">
        <?php if ( has_post_thumbnail() ) :
          the_post_thumbnail( 'full', ['class' => 'p-page-hero__image', 'loading' => 'eager'] );
        else : ?>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero_port.jpg"
            alt="<?php the_title_attribute(); ?>"
            class="p-page-hero__image" loading="eager" />
        <?php endif; ?>
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">
        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true"></span>
          <a href="<?php echo esc_url( home_url( '/#field' ) ); ?>">Lĩnh vực hoạt động</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page"><?php the_title(); ?></span>
        </nav>

        <div class="p-page-hero__content">
          <?php the_title( '<h1 class="p-page-hero__title">', '</h1>' ); ?>
        </div>
      </div>

    </section>
    <!-- ======= /HERO ======= -->


    <!-- ======= NỘI DUNG ======= -->
    <div class="p-news-detail" id="linh-vuc-detail-content">
      <div class="l-container">

        <article class="p-news-detail__article p-news-detail__article--full" id="article-content" aria-label="Nội dung">

          <div class="p-news-detail__meta">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <rect x="1" y="2" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.3"/>
              <path d="M1 5.5H13" stroke="currentColor" stroke-width="1.3"/>
              <path d="M4 1V3M10 1V3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
            <time datetime="<?php echo $pub_datetime; ?>">
              Đăng lúc <strong><?php echo $pub_time; ?></strong>
              ngày <strong><?php echo $pub_date_str; ?></strong>
            </time>
          </div>

          <div class="p-news-detail__body">
            <?php the_content(); ?>
          </div>

          <!-- POST NAVIGATION -->
          <?php if ( $prev_post || $next_post ) : ?>
          <nav class="p-news-detail__postnav" aria-label="Điều hướng giữa các lĩnh vực">

            <?php if ( $prev_post ) : ?>
            <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>"
              class="p-news-detail__postnav-item p-news-detail__postnav-prev">
              <span class="p-news-detail__postnav-label">
                <svg width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true">
                  <path d="M11 5H1M5 1L1 5L5 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Lĩnh vực trước
              </span>
              <span class="p-news-detail__postnav-title">
                <?php echo esc_html( get_the_title( $prev_post ) ); ?>
              </span>
            </a>
            <?php endif; ?>

            <?php if ( $next_post ) : ?>
            <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>"
              class="p-news-detail__postnav-item p-news-detail__postnav-next">
              <span class="p-news-detail__postnav-label">
                Lĩnh vực tiếp theo
                <svg width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true">
                  <path d="M1 5H11M7 1L11 5L7 9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              <span class="p-news-detail__postnav-title">
                <?php echo esc_html( get_the_title( $next_post ) ); ?>
              </span>
            </a>
            <?php endif; ?>

          </nav>
          <?php endif; ?>
          <!-- /POST NAVIGATION -->

        </article>

      </div>
    </div>
    <!-- ======= /NỘI DUNG ======= -->

  </main>

<?php endwhile; ?>

<?php get_footer(); ?>
