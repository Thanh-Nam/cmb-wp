<?php
/**
 * template-parts/home/location.php
 * Section: Location Map (Vietnam SVG + desktop panel + mobile popup)
 */
$theme = get_template_directory_uri();
?>
<!-- ======= LOCATION ======= -->
<section class="p-location" id="location" aria-label="<?php echo esc_attr( cmb_txt( 'Vị trí dự án', 'Project Locations' ) ); ?>">
  <div class="l-container">

    <!-- Tiêu đề section — luôn hiển thị cả desktop lẫn mobile -->
    <div class="p-location__header" data-reveal="fade-up">
      <div class="p-location__label">
        <span class="c-section-label"><?php echo cmb_txt( 'DẤU ẤN', 'HIGHLIGHTS' ); ?></span>
      </div>
      <h2 class="p-location__heading"><?php echo cmb_txt( 'VỊ TRÍ DỰ ÁN', 'PROJECT LOCATIONS' ); ?></h2>
    </div>

    <div class="p-location__inner">

      <!-- Left: project panel -->
      <div class="p-location__left" data-reveal="fade-left">

        <!-- Info panel — cập nhật khi click marker -->
        <div class="p-location__panel" id="location-panel">

          <div class="p-location__city-row">
            <span class="p-location__city-line" aria-hidden="true"></span>
            <h3 class="p-location__city-name" id="location-city-name"><?php echo cmb_txt( 'HẢI PHÒNG', 'HAI PHONG' ); ?></h3>
          </div>

          <!-- Slider dự án — 1 tỉnh/thành có thể có nhiều dự án, mỗi dự án là 1 slide -->
          <div class="p-location__slider swiper" id="location-slider">
            <div class="swiper-wrapper" id="location-slider-wrapper">
              <!-- Slides được render bằng JS từ dữ liệu ACF (xem location-map.js) -->
            </div>

            <div class="p-location__slider-nav">
              <div class="p-location__slider-arrows">
                <button type="button" class="p-location__slider-prev" aria-label="<?php echo esc_attr( cmb_txt( 'Dự án trước', 'Previous project' ) ); ?>">
                  <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M7 1L1 7L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>
                <button type="button" class="p-location__slider-next" aria-label="<?php echo esc_attr( cmb_txt( 'Dự án tiếp theo', 'Next project' ) ); ?>">
                  <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M1 1L7 7L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

        </div>
        <!-- /panel -->

      </div>
      <!-- /left -->

      <!-- Right: Interactive Vietnam map -->
      <div class="p-location__right" data-reveal="fade-right">
        <div class="p-location__map-wrap" id="location-map-wrap">

          <!-- Base map SVG — JS sẽ fetch + inline khi vào viewport -->
          <img src="<?php echo $theme; ?>/assets/images/map.svg" alt="<?php echo esc_attr( cmb_txt( 'Bản đồ Việt Nam — vị trí các dự án CMB', 'Vietnam map — CMB project locations' ) ); ?>"
            class="p-location__map-img" loading="lazy" />

          <!-- Bánh lái quay quanh bản đồ liên tục -->
          <div class="p-location__wheel-pivot" aria-hidden="true">
            <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" class="p-location__wheel" loading="lazy" />
          </div>

        </div>
        <!-- /map-wrap -->
      </div>
      <!-- /right -->

    </div>
  </div>

  <!-- Mobile popup: hiện khi click city trên màn hình <= 1024px -->
  <div class="p-location__popup" id="location-popup" role="dialog" aria-modal="true" aria-hidden="true"
    aria-label="<?php echo esc_attr( cmb_txt( 'Thông tin vị trí dự án', 'Project location information' ) ); ?>">
    <div class="p-location__popup-card">
      <button class="p-location__popup-close" id="location-popup-close" aria-label="<?php echo esc_attr( cmb_txt( 'Đóng', 'Close' ) ); ?>">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
          aria-hidden="true">
          <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
      </button>
      <div class="p-location__popup-city-row">
        <span class="p-location__city-line" aria-hidden="true"></span>
        <h3 class="p-location__popup-city-name" id="popup-city-name"></h3>
      </div>

      <!-- Slider dự án — bản mobile của panel bên trái -->
      <div class="p-location__slider swiper" id="popup-slider">
        <div class="swiper-wrapper" id="popup-slider-wrapper">
          <!-- Slides được render bằng JS -->
        </div>

        <div class="p-location__slider-nav">
          <div class="p-location__slider-arrows">
            <button type="button" class="p-location__slider-prev" aria-label="<?php echo esc_attr( cmb_txt( 'Dự án trước', 'Previous project' ) ); ?>">
              <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M7 1L1 7L7 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
            <button type="button" class="p-location__slider-next" aria-label="<?php echo esc_attr( cmb_txt( 'Dự án tiếp theo', 'Next project' ) ); ?>">
              <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M1 1L7 7L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- ======= /LOCATION ======= -->
