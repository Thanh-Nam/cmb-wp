<?php get_header();
$theme = get_template_directory_uri();
?>

  <!-- ======= MAIN ======= -->
  <main class="site-main" id="main-content">

    <!-- ======= HERO BANNER ======= -->
    <section class="p-hero" id="hero">
      <div class="p-hero__slider swiper">
        <div class="swiper-wrapper">
          <?php if ( have_rows( 'slide_banner', 'option' ) ) : ?>
            <?php while ( have_rows( 'slide_banner', 'option' ) ) : the_row(); ?>
              <?php
                $img   = get_sub_field( 'img' );
                $title = get_sub_field( 'title' );
              ?>
              <div class="swiper-slide p-hero__slide">
                <?php if ( $img ) : ?>
                  <img src="<?php echo esc_url( $img['url'] ); ?>"
                       alt="<?php echo esc_attr( $img['alt'] ?: $title ); ?>"
                       class="p-hero__bg" />
                <?php endif; ?>
                <div class="p-hero__overlay"></div>
                <div class="l-container">
                  <div class="p-hero__content">
                    <?php if ( $title ) : ?>
                      <h1 class="p-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
        <!-- Pagination -->
        <div class="p-hero__pagination swiper-pagination"></div>
      </div>
    </section>
    <!-- ======= /HERO BANNER ======= -->

    <!-- ======= ABOUT ======= -->
    <?php
      $about_sub_title = get_field( 'about_sub_title', 'option' );
      $about_title     = get_field( 'about_title', 'option' );
      $about_content   = get_field( 'about_content', 'option' );
      $about_link      = get_field( 'about_link', 'option' );
      $about_img       = get_field( 'about_img', 'option' );
      $about_name      = get_field( 'about_name', 'option' );
      $about_position  = get_field( 'about_position', 'option' );
    ?>
    <section class="p-about" id="about">
      <div class="l-container">

        <!-- Header -->
        <div class="p-about__header" data-reveal="fade-up">
          <?php if ( $about_sub_title ) : ?>
            <span class="c-section-label c-section-label--center"><?php echo esc_html( $about_sub_title ); ?></span>
          <?php endif; ?>
          <?php if ( $about_title ) : ?>
            <h2 class="c-section-title p-about__title"><?php echo esc_html( $about_title ); ?></h2>
          <?php endif; ?>
        </div>

        <!-- Body -->
        <div class="p-about__body">

          <!-- Content Left -->
          <div class="p-about__content">
            <div class="p-about__quote-icon" data-reveal="fade-left">
              <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g opacity="0.5">
                  <path
                    d="M14.4594 9.29652C14.4594 10.0443 13.7384 11.4063 12.3229 13.4093C10.9075 15.4123 10.1864 16.4539 10.1864 16.5874C10.1864 16.8812 10.4535 17.1215 10.9609 17.3619C11.4683 17.6023 12.1093 17.8426 12.8571 18.1364C13.6315 18.4302 14.406 18.804 15.1271 19.2314C15.9283 19.7388 16.596 20.4331 17.05 21.2878C17.5841 22.3026 17.8512 23.4243 17.8245 24.5727C17.8245 26.7626 17.0767 28.5519 15.5544 29.914C14.0321 31.3027 12.0826 31.9971 9.70568 31.9971C7.54245 32.0505 5.43264 31.2226 3.85695 29.727C2.30797 28.3116 1.42666 26.2819 1.45337 24.1721C1.48007 21.5815 1.9875 19.0177 2.97564 16.6408C3.88366 14.2906 5.2724 12.1808 7.06173 10.4182C8.77095 8.7891 10.5336 7.96119 12.3496 7.96119C13.7651 7.96119 14.4594 8.41521 14.4594 9.29652ZM34.3558 9.29652C34.3558 10.0443 33.6347 11.4063 32.2193 13.4093C30.8038 15.4123 30.0828 16.4539 30.0828 16.5874C30.0828 16.8812 30.3498 17.1215 30.8573 17.3619C31.3647 17.6023 32.0056 17.8426 32.7534 18.1364C33.5279 18.4302 34.3024 18.804 35.0235 19.2314C35.8247 19.7388 36.4923 20.4331 36.9463 21.2878C37.4805 22.3026 37.7475 23.4243 37.7208 24.5727C37.7208 26.7626 36.973 28.5519 35.4508 29.914C33.9552 31.3027 32.0056 31.9971 29.602 31.9971C27.4388 32.0505 25.329 31.2226 23.7533 29.727C22.2043 28.3116 21.323 26.2819 21.3497 24.1721C21.3764 21.5815 21.8839 19.0177 22.872 16.6408C23.78 14.2906 25.1688 12.1808 26.9848 10.4182C28.694 8.7891 30.4567 7.96119 32.2727 7.96119C33.6614 7.96119 34.3558 8.41521 34.3558 9.29652Z"
                    fill="url(#paint0_linear_55321_2025)" />
                </g>
                <defs>
                  <linearGradient id="paint0_linear_55321_2025" x1="37.7227" y1="7.96119" x2="-1.14505" y2="26.575"
                    gradientUnits="userSpaceOnUse">
                    <stop stop-color="#257AD6" />
                    <stop offset="1" stop-color="#76C7F7" />
                  </linearGradient>
                </defs>
              </svg>
            </div>

            <?php if ( $about_content ) : ?>
              <blockquote class="p-about__quote" data-reveal="fade-left" data-reveal-delay="1">
                <?php echo wp_kses_post( $about_content ); ?>
              </blockquote>
            <?php endif; ?>

            <!-- Author info -->
            <?php if ( $about_name || $about_position ) : ?>
              <div class="p-about__author">
                <span class="p-about__author-line" aria-hidden="true"></span>
                <div class="p-about__author-info">
                  <?php if ( $about_name ) : ?>
                    <cite class="p-about__author-name"><?php echo esc_html( $about_name ); ?></cite>
                  <?php endif; ?>
                  <?php if ( $about_position ) : ?>
                    <span class="p-about__author-title"><?php echo esc_html( $about_position ); ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- Button -->
            <?php if ( $about_link ) : ?>
              <div class="p-about__action">
                <a href="<?php echo esc_url( $about_link ); ?>" class="c-btn p-about__btn" id="btn-about-more">
                  <span class="p-about__btn-text">Về CMB</span>
                  <span class="p-about__btn-arrow">
                    <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M4.6 6L0 1.4L1.4 0L7.4 6L1.4 12L0 10.6L4.6 6Z" fill="white" />
                    </svg>
                  </span>
                </a>
              </div>
            <?php endif; ?>
          </div>

          <!-- CEO Right -->
          <?php if ( $about_img ) : ?>
            <div class="p-about__ceo" data-reveal="fade-right">
              <div class="p-about__ceo-wrapper">
                <img src="<?php echo esc_url( $about_img['url'] ); ?>"
                     alt="<?php echo esc_attr( $about_img['alt'] ?: $about_name ); ?>"
                     class="p-about__ceo-img" loading="lazy" />
              </div>
            </div>
          <?php endif; ?>

        </div>

      </div>
    </section>
    <!-- ======= /ABOUT ======= -->

    <!-- ======= HISTORY ======= -->
    <?php
      $history_subtitle = get_field( 'history_subtitle', 'option' );
      $history_title    = get_field( 'history_title', 'option' );
      $history_items    = get_field( 'history_item', 'option' );
    ?>
    <section class="p-history" id="history" aria-label="Lịch sử CMB">

      <!-- Background: phủ toàn section -->
      <div class="p-history__bg" aria-hidden="true">
        <img src="<?php echo $theme; ?>/assets/images/bg-history.png" alt="" role="presentation" class="p-history__bg-img" loading="lazy" />
      </div>

      <!-- Tiêu đề section -->
      <div class="p-history__header" data-reveal="fade-up">
        <?php if ( $history_subtitle ) : ?>
          <div class="p-history__label">
            <span class="c-section-label"><?php echo esc_html( $history_subtitle ); ?></span>
          </div>
        <?php endif; ?>
        <?php if ( $history_title ) : ?>
          <h2 class="c-section-title p-history__title"><?php echo esc_html( $history_title ); ?></h2>
        <?php endif; ?>
      </div>

      <!-- Navigation arrows -->
      <nav class="p-history__nav" aria-label="Điều hướng mốc lịch sử">
        <button class="p-history__nav-btn p-history__nav-btn--prev" id="history-nav-prev" aria-label="Mốc trước"
          type="button" disabled>
          <img src="<?php echo $theme; ?>/assets/images/arrow-history.svg" alt="" role="presentation" class="p-history__nav-arrow" />
        </button>
        <button class="p-history__nav-btn p-history__nav-btn--next" id="history-nav-next" aria-label="Mốc tiếp theo"
          type="button">
          <img src="<?php echo $theme; ?>/assets/images/arrow-history.svg" alt="" role="presentation"
            class="p-history__nav-arrow p-history__nav-arrow--flip" />
        </button>
      </nav>

      <!-- Canvas: chỉ chứa SVG line + ship + wheels + milestone cards (định vị theo % canvas) -->
      <div class="p-history__canvas">

        <!-- Đường kẻ dashed + trang trí (la bàn, hải đăng) -->
        <img src="<?php echo $theme; ?>/assets/images/line-history.svg" class="p-history__line" alt="" role="presentation" loading="lazy" />

        <!-- SVG overlay: ship chạy theo path lặp vô tận -->
        <svg class="p-history__ship-svg" viewBox="0 0 1859 823" xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true">
          <defs>
            <path id="ship-route"
              d="M67.5 690.103V442.103C67.5 428.848 78.2452 418.103 91.5 418.103H854.667C867.922 418.103 878.667 407.357 878.667 394.103V188.103C878.667 174.848 889.412 164.103 902.667 164.103H1676" />
          </defs>
          <image id="ship-icon" href="<?php echo $theme; ?>/assets/images/con-tau.svg" xlink:href="<?php echo $theme; ?>/assets/images/con-tau.svg" x="-65" y="-45"
            width="90" height="90" transform="rotate(90)" />
          <animateMotion href="#ship-icon" dur="22s" begin="0s" repeatCount="indefinite" rotate="auto"
            calcMode="linear">
            <mpath href="#ship-route" />
          </animateMotion>
        </svg>

        <!-- Milestone cards (positioned absolute theo % của canvas) -->
        <div class="p-history__milestones">

          <article class="p-history__item p-history__item--1966 is-active" id="milestone-1966" data-reveal="fade-up"
            data-reveal-delay="1">
            <h3 class="p-history__year">1966</h3>
            <p class="p-history__desc">Thành lập Đội khảo sát thiết kế (tiền thân của Công ty).</p>
          </article>

          <article class="p-history__item p-history__item--1977" id="milestone-1977" data-reveal="fade-up"
            data-reveal-delay="2">
            <h3 class="p-history__year">1977</h3>
            <p class="p-history__desc">Chuyển đổi mô hình thành Công ty Khảo sát Thiết kế Đường biển.</p>
          </article>

          <article class="p-history__item p-history__item--1995" id="milestone-1995" data-reveal="fade-up"
            data-reveal-delay="3">
            <h3 class="p-history__year">1995</h3>
            <p class="p-history__desc">Đổi tên thành Công ty tư vấn xây dựng công trình Hàng hải</p>
          </article>

          <article class="p-history__item p-history__item--1999" id="milestone-1999" data-reveal="fade-up"
            data-reveal-delay="4">
            <h3 class="p-history__year">1999</h3>
            <p class="p-history__desc">Lập Quy hoạch tổng thể phát triển hệ thống cảng biển Việt Nam đến năm 2010 — Quy
              hoạch cảng biển đầu tiên của Việt Nam</p>
          </article>

          <article class="p-history__item p-history__item--2004" id="milestone-2004" data-reveal="fade-up"
            data-reveal-delay="5">
            <h3 class="p-history__year">2004</h3>
            <p class="p-history__desc">Cổ phần hóa, hoạt động theo mô hình công ty cổ phần.</p>
          </article>

          <article class="p-history__item p-history__item--2011" id="milestone-2011" data-reveal="fade-up">
            <h3 class="p-history__year">2011</h3>
            <p class="p-history__desc">Được trao tặng Huân chương Độc lập Hạng Ba</p>
          </article>

        </div>

        <!-- Bánh lái tại mỗi mốc lịch sử, đặt ON path (% theo canvas 1859×823) -->
        <div class="p-history__wheels" aria-hidden="true">
          <span class="p-history__wheel p-history__wheel--1977">
            <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" />
          </span>
          <span class="p-history__wheel p-history__wheel--1995">
            <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" />
          </span>
          <span class="p-history__wheel p-history__wheel--1999">
            <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" />
          </span>
          <span class="p-history__wheel p-history__wheel--2004">
            <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" />
          </span>
          <span class="p-history__wheel p-history__wheel--2011">
            <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" />
          </span>
        </div>

      </div>

      <!-- Mobile timeline list: populated by JS -->
      <ul class="p-history__mobile-list" id="history-mobile-list" role="list" aria-label="Lịch sử CMB"></ul>

    </section>
    <!-- ======= /HISTORY ======= -->


    <!-- ======= INFO ======= -->
    <?php
      $info_title   = get_field( 'info_title', 'option' );
      $info_slogan  = get_field( 'info_slogan', 'option' );
      $info_content = get_field( 'info_content', 'option' );
      $info_items   = get_field( 'info_item', 'option' );
    ?>
    <section class="p-info" id="info" aria-label="Giới thiệu chung">

      <!-- Background: cùng cấp container, absolute full-width, không bị giới hạn bởi container -->
      <div class="p-info__bg" aria-hidden="true"></div>

      <div class="l-container">
        <div class="p-info__card" data-reveal="fade-up">
          <div class="p-info__inner">

            <!-- Left: content -->
            <div class="p-info__left">
              <?php if ( $info_slogan ) : ?>
                <span class="c-section-label c-section-label--white p-info__label"><?php echo esc_html( $info_title ); ?></span>
              <?php endif; ?>
              <?php if ( $info_title ) : ?>
                <h2 class="c-section-title c-section-title--white p-info__title"><?php echo esc_html( $info_slogan ); ?></h2>
              <?php endif; ?>
              <?php if ( $info_content ) : ?>
                <p class="p-info__desc"><?php echo wp_kses_post( $info_content ); ?></p>
              <?php endif; ?>
              <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'gioi-thieu' ) ) ?: '#' ); ?>" class="p-info__btn" id="btn-info-more">
                <span>Xem Tất Cả</span>
                <span class="p-info__btn-arrow" aria-hidden="true">
                  <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 5H13M9 1L13 5L9 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                      stroke-linejoin="round" />
                  </svg>
                </span>
              </a>
            </div>

            <!-- Right: stats -->
            <?php if ( $info_items ) : ?>
              <div class="p-info__stats">
                <?php $info_total = count( $info_items ); ?>
                <?php foreach ( $info_items as $i => $stat ) : ?>
                  <div class="p-info__stat">
                    <?php if ( ! empty( $stat['icon'] ) ) : ?>
                      <img src="<?php echo esc_url( $stat['icon']['url'] ); ?>"
                           alt=""
                           role="presentation"
                           class="p-info__stat-icon" />
                    <?php endif; ?>
                    <div class="p-info__stat-body">
                      <?php if ( isset( $stat['number'] ) && $stat['number'] !== '' ) : ?>
                        <span class="p-info__stat-number"><?php echo esc_html( $stat['number'] ); ?></span>
                      <?php endif; ?>
                      <?php if ( ! empty( $stat['content'] ) ) : ?>
                        <span class="p-info__stat-label"><?php echo wp_kses_post( $stat['content'] ); ?></span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php if ( $i < $info_total - 1 ) : ?>
                    <div class="p-info__stat-divider" aria-hidden="true"></div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>

    </section>
    <!-- ======= /INFO ======= -->


    <!-- ======= FIELDS OF OPERATION ======= -->
    <?php
    // ---- Section header from Options Page ----
    $field_subtitle     = get_field( 'homefield_subtitle', 'option' ) ?: 'Khám Phá';
    $field_title_raw    = get_field( 'homefield_title', 'option' ) ?: '';
    $field_title_lines  = $field_title_raw
        ? array_values( array_filter( array_map( 'trim', explode( "\n", $field_title_raw ) ) ) )
        : [ 'Lĩnh Vực', 'Hoạt Động' ];
    $field_content      = get_field( 'homefield_content', 'option' );

    // ---- Slides from linh-vuc CPT ----
    $field_q    = new WP_Query( [
        'post_type'      => 'linh-vuc',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );
    ?>
    <section class="p-field" id="field" aria-label="Lĩnh vực hoạt động">
      <div class="l-container">
        <div class="p-field__inner">

          <!-- Left: text content -->
          <div class="p-field__left" data-reveal="fade-left">

            <div class="p-field__label">
              <span class="c-section-label"><?php echo esc_html( $field_subtitle ); ?></span>
            </div>

            <h2 class="c-section-title p-field__title">
              <?php if ( isset( $field_title_lines[0] ) ) : ?>
              <span class="p-field__title-line p-field__title-line--blue"><?php echo esc_html( $field_title_lines[0] ); ?></span>
              <?php endif; ?>
              <?php if ( isset( $field_title_lines[1] ) ) : ?>
              <span class="p-field__title-line p-field__title-line--red"><?php echo esc_html( $field_title_lines[1] ); ?></span>
              <?php endif; ?>
            </h2>

            <?php if ( $field_content ) : ?>
            <div class="p-field__desc"><?php echo wp_kses_post( $field_content ); ?></div>
            <?php endif; ?>

          </div>

          <!-- Right: Swiper coverflow -->
          <div class="p-field__right">
            <div class="swiper p-field__swiper" id="field-swiper">
              <div class="swiper-wrapper">

                <?php
                if ( $field_q->have_posts() ) :
                  $slide_index = 0;
                  while ( $field_q->have_posts() ) : $field_q->the_post();
                    $slide_index++;
                    $slide_num = sprintf( '%02d', $slide_index );
                    $thumb_url = get_the_post_thumbnail_url( null, 'large' )
                                 ?: get_template_directory_uri() . '/assets/images/hero_port.jpg';
                    $slide_excerpt = get_the_excerpt();
                ?>
                <div class="swiper-slide p-field__slide">
                  <article class="p-field__card" aria-label="<?php the_title_attribute(); ?>">
                    <img src="<?php echo esc_url( $thumb_url ); ?>"
                      alt="<?php the_title_attribute(); ?>"
                      class="p-field__card-img" loading="lazy" />
                    <div class="p-field__card-overlay" aria-hidden="true"></div>
                    <div class="p-field__card-content">
                      <span class="p-field__card-num" aria-hidden="true"><?php echo esc_html( $slide_num ); ?></span>
                      <h3 class="p-field__card-title"><?php the_title(); ?></h3>
                      <?php if ( $slide_excerpt ) : ?>
                      <p class="p-field__card-desc"><?php echo esc_html( $slide_excerpt ); ?></p>
                      <?php endif; ?>
                      <a href="<?php the_permalink(); ?>" class="p-field__card-link"
                        title="Xem thêm về <?php the_title_attribute(); ?>">
                        Khám phá thêm
                        <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                      </a>
                    </div>
                  </article>
                </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>

              </div>
              <!-- Pagination -->
              <div class="swiper-pagination p-field__pagination" aria-label="Chọn lĩnh vực hoạt động"></div>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- ======= /FIELDS OF OPERATION ======= -->


    <!-- ======= LOCATION ======= -->
    <section class="p-location" id="location" aria-label="Vị trí dự án">
      <div class="l-container">

        <!-- Tiêu đề section — luôn hiển thị cả desktop lẫn mobile -->
        <div class="p-location__header" data-reveal="fade-up">
          <div class="p-location__label">
            <span class="c-section-label">DẤU ẤN</span>
          </div>
          <h2 class="p-location__heading">VỊ TRÍ DỰ ÁN</h2>
        </div>

        <div class="p-location__inner">

          <!-- Left: project panel -->
          <div class="p-location__left" data-reveal="fade-left">

            <!-- Info panel — cập nhật khi click marker -->
            <div class="p-location__panel" id="location-panel">

              <div class="p-location__city-row">
                <span class="p-location__city-line" aria-hidden="true"></span>
                <h3 class="p-location__city-name" id="location-city-name">HẢI PHÒNG</h3>
              </div>

              <div class="p-location__details">
                <div class="p-location__detail-row">
                  <span class="p-location__detail-label">Dự án:</span>
                  <p class="p-location__detail-text" id="location-project">Bến số 1,2 Cảng cửa ngõ Quốc tế Hải Phòng</p>
                </div>
                <div class="p-location__detail-row">
                  <span class="p-location__detail-label">Mô tả:</span>
                  <p class="p-location__detail-text" id="location-desc">Diện tích 45ha; chiều dài bến 750m; tiếp nhận
                    tàu Container đến 100.000DWT đầy tải, 160.000DWT giảm tải; công suất 1,1 triệu TEU/năm.</p>
                </div>
              </div>

              <div class="p-location__img-wrap">
                <!-- WP: <?php the_post_thumbnail('large', ['class' => 'p-location__img', 'id' => 'location-img']); ?> -->
                <img src="<?php echo $theme; ?>/assets/images/hero_port.jpg" alt="Bến số 1,2 Cảng cửa ngõ Quốc tế Hải Phòng"
                  class="p-location__img" id="location-img" loading="lazy" />
              </div>

              <!-- WP: <?php the_field('location_link'); ?> -->
              <a href="#" class="p-location__link" id="location-link" title="Xem chi tiết dự án">
                Xem dự án
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true">
                  <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </a>

            </div>
            <!-- /panel -->

          </div>
          <!-- /left -->

          <!-- Right: Interactive Vietnam map -->
          <div class="p-location__right" data-reveal="fade-right">
            <div class="p-location__map-wrap" id="location-map-wrap">

              <!-- Base map SVG -->
              <img src="<?php echo $theme; ?>/assets/images/map.svg" alt="Bản đồ Việt Nam — vị trí các dự án CMB"
                class="p-location__map-img" />

              <!-- SVG map.svg được JS fetch + inline, các ô label trong SVG tự clickable -->

              <!-- Bánh lái quay quanh bản đồ liên tục -->
              <!-- Kỹ thuật: transform-origin đặt tại tâm bản đồ → element orbit xung quanh
                   .wheel-pivot : xác định quỹ đạo (orbit) — 3 o'clock ban đầu
                   .wheel       : quay quanh trục riêng (self-spin) -->
              <div class="p-location__wheel-pivot" aria-hidden="true">
                <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" class="p-location__wheel" />
              </div>

            </div>
            <!-- /map-wrap -->
          </div>
          <!-- /right -->

        </div>
      </div>

      <!-- Mobile popup: hiện khi click city trên màn hình <= 1024px -->
      <div class="p-location__popup" id="location-popup" role="dialog" aria-modal="true" aria-hidden="true"
        aria-label="Thông tin vị trí dự án">
        <div class="p-location__popup-card">
          <button class="p-location__popup-close" id="location-popup-close" aria-label="Đóng">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true">
              <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
          </button>
          <div class="p-location__popup-city-row">
            <span class="p-location__city-line" aria-hidden="true"></span>
            <h3 class="p-location__popup-city-name" id="popup-city-name"></h3>
          </div>
          <div class="p-location__details">
            <div class="p-location__detail-row">
              <span class="p-location__detail-label">Dự án:</span>
              <p class="p-location__detail-text" id="popup-project"></p>
            </div>
            <div class="p-location__detail-row">
              <span class="p-location__detail-label">Mô tả:</span>
              <p class="p-location__detail-text" id="popup-desc"></p>
            </div>
          </div>
          <div class="p-location__img-wrap">
            <img src="<?php echo $theme; ?>/assets/images/demo-du-an.png" alt="" class="p-location__img" id="popup-img" loading="lazy" />
          </div>
        </div>
      </div>

    </section>
    <!-- ======= /LOCATION ======= -->


    <!-- ======= PROJECT ======= -->
    <section class="p-project" id="project" aria-label="Dự án tiêu biểu">
      <div class="l-container">

        <!-- Header -->
        <div class="p-project__header" data-reveal="fade-up">
          <span class="c-section-label c-section-label--center">Nổi Bật</span>
          <h2 class="c-section-title p-project__title">Dự Án Tiêu Biểu</h2>
        </div>

        <!-- Filter tabs -->
        <?php $project_cats = get_terms(['taxonomy' => 'du-an-category', 'hide_empty' => false]); ?>
        <div class="p-project__filter-wrap" data-reveal="fade-up" data-reveal-delay="2">
          <nav class="p-project__filter" role="tablist" aria-label="Lọc dự án theo danh mục">
            <button class="p-project__tab is-active" role="tab" aria-selected="true" data-filter="all" id="tab-all">
              <span>Tất Cả</span>
            </button>
            <?php if ($project_cats && !is_wp_error($project_cats)) : ?>
            <?php foreach ($project_cats as $cat) : ?>
            <button class="p-project__tab" role="tab" aria-selected="false"
                    data-filter="<?php echo esc_attr($cat->slug); ?>"
                    id="tab-<?php echo esc_attr($cat->slug); ?>">
              <span><?php echo esc_html($cat->name); ?></span>
            </button>
            <?php endforeach; ?>
            <?php endif; ?>
          </nav>
        </div>

        <!-- Grid -->
        <?php
        $projects_q = new WP_Query([
            'post_type'      => 'du-an',
            'posts_per_page' => 5,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
        ]);
        ?>
        <div class="p-project__grid" id="project-grid" role="list">

          <?php if ($projects_q->have_posts()) : $ci = 0; ?>
          <?php while ($projects_q->have_posts()) : $projects_q->the_post(); $ci++; ?>
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
                   data-category="<?php echo esc_attr($p_cat_slug); ?>"
                   data-reveal="fade-up" data-reveal-delay="<?php echo $ci; ?>">

            <?php if ($p_img) : ?>
            <img src="<?php echo esc_url($p_img['url']); ?>"
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
                  <span class="p-project__card-cat"><?php echo esc_html($p_cat_name); ?></span>
                </div>
                <h3 class="p-project__card-title"><?php the_title(); ?></h3>
                <div class="p-project__card-extra">
                  <?php if ($is_feat && ($p_owner || $p_loc || $p_svc)) : ?>
                  <ul class="p-project__card-info" aria-label="Thông tin dự án">
                    <?php if ($p_owner) : ?><li>Chủ đầu tư: <?php echo esc_html($p_owner); ?></li><?php endif; ?>
                    <?php if ($p_loc) : ?><li>Địa điểm/vị trí: <?php echo esc_html($p_loc); ?></li><?php endif; ?>
                    <?php if ($p_svc) : ?><li>Dịch vụ tư vấn chính: <?php echo esc_html($p_svc); ?></li><?php endif; ?>
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
          <?php endif; ?>

        </div>
        <!-- /Grid -->

      </div>
    </section>
    <!-- ======= /PROJECT ======= -->


    <!-- ======= NEWS ======= -->
    <?php
    // Featured: ưu tiên bài có is_featured=1, fallback bài mới nhất
    $hp_featured_q = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [[
            'key'     => 'is_featured',
            'value'   => '1',
            'compare' => '=',
        ]],
    ]);
    if ( ! $hp_featured_q->have_posts() ) {
        $hp_featured_q = new WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
    }
    $hp_featured_id = 0;

    // Arrow SVG dùng lại nhiều lần
    $arrow_svg = '<svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M1 5H13M9 1L13 5L9 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    ?>
    <section class="p-news" id="news" aria-label="Tin tức">

      <div class="l-container">

        <!-- Header -->
        <div class="p-news__header" data-reveal="fade-up">
          <span class="c-section-label">Sự Kiện</span>
          <h2 class="c-section-title p-news__title">Tin Tức</h2>
        </div>

        <!-- Featured article -->
        <?php if ( $hp_featured_q->have_posts() ) : $hp_featured_q->the_post();
          $hp_featured_id = get_the_ID();
          $hp_f_cats      = get_the_category();
          $hp_f_cat       = $hp_f_cats ? $hp_f_cats[0] : null;
          $hp_f_cat_slug  = $hp_f_cat ? $hp_f_cat->slug : '';
          $hp_f_cat_name  = $hp_f_cat ? $hp_f_cat->name : '';
        ?>
        <article class="p-news__featured" id="news-featured-1" data-reveal="fade-left">

          <a href="<?php the_permalink(); ?>" class="p-news__featured-img-wrap" tabindex="-1" aria-hidden="true">
            <?php if ( has_post_thumbnail() ) :
              the_post_thumbnail( 'large', ['class' => 'p-news__featured-img', 'loading' => 'lazy'] );
            else : ?>
              <img src="<?php echo $theme; ?>/assets/images/hero_port.jpg"
                alt="<?php the_title_attribute(); ?>"
                class="p-news__featured-img" loading="lazy" />
            <?php endif; ?>
          </a>

          <div class="p-news__featured-content">

            <div class="p-news__meta">
              <time class="p-news__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                <?php echo get_the_date( 'd - m - Y' ); ?>
              </time>
              <?php if ( $hp_f_cat_name ) : ?>
              <span class="p-news__cat p-news__cat--<?php echo esc_attr( $hp_f_cat_slug ); ?>">
                <?php echo esc_html( $hp_f_cat_name ); ?>
              </span>
              <?php endif; ?>
            </div>

            <?php the_title( '<h3 class="p-news__featured-title"><a href="' . esc_url( get_permalink() ) . '" class="p-news__featured-title-link">', '</a></h3>' ); ?>

            <p class="p-news__featured-excerpt">
              <?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?>
            </p>

            <a href="<?php the_permalink(); ?>" class="p-news__link" title="Xem chi tiết: <?php the_title_attribute(); ?>">
              Xem Chi Tiết <?php echo $arrow_svg; ?>
            </a>

          </div>
        </article>
        <?php wp_reset_postdata(); endif; ?>
        <!-- /Featured article -->


        <!-- Small articles list -->
        <?php
        $hp_list_q = new WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post__not_in'   => $hp_featured_id ? [ $hp_featured_id ] : [],
        ]);
        ?>
        <div class="p-news__list">
          <?php $hp_i = 0; while ( $hp_list_q->have_posts() ) : $hp_list_q->the_post();
            $hp_i++;
            $hp_cats     = get_the_category();
            $hp_cat      = $hp_cats ? $hp_cats[0] : null;
            $hp_cat_slug = $hp_cat ? $hp_cat->slug : '';
            $hp_cat_name = $hp_cat ? $hp_cat->name : '';
          ?>
          <article class="p-news__item" id="news-item-<?php echo $hp_i; ?>" data-reveal="fade-up" data-reveal-delay="<?php echo $hp_i; ?>">
            <a href="<?php the_permalink(); ?>" class="p-news__item-img-wrap">
              <?php if ( has_post_thumbnail() ) :
                the_post_thumbnail( 'medium', ['class' => 'p-news__item-img', 'loading' => 'lazy'] );
              else : ?>
                <img src="<?php echo $theme; ?>/assets/images/hero_port.jpg"
                  alt="<?php the_title_attribute(); ?>"
                  class="p-news__item-img" loading="lazy" />
              <?php endif; ?>
            </a>
            <div class="p-news__item-content">
              <div class="p-news__meta">
                <time class="p-news__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                  <?php echo get_the_date( 'd - m - Y' ); ?>
                </time>
                <?php if ( $hp_cat_name ) : ?>
                <span class="p-news__cat p-news__cat--<?php echo esc_attr( $hp_cat_slug ); ?>">
                  <?php echo esc_html( $hp_cat_name ); ?>
                </span>
                <?php endif; ?>
              </div>
              <h3 class="p-news__item-title">
                <a href="<?php the_permalink(); ?>" class="p-news__item-title-link"><?php the_title(); ?></a>
              </h3>
              <a href="<?php the_permalink(); ?>" class="p-news__link" title="Xem chi tiết: <?php the_title_attribute(); ?>">
                Xem Chi Tiết <?php echo $arrow_svg; ?>
              </a>
            </div>
          </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <!-- /Small articles list -->

      </div>
    </section>
    <!-- ======= /NEWS ======= -->


    <!-- ======= PARTNER ======= -->
    <?php get_template_part('template-parts/section-partner'); ?>
    <!-- ======= /PARTNER ======= -->
  </main>
  <!-- ======= /MAIN ======= -->

<?php get_footer();
