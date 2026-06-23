<?php
/**
 * template-parts/home/location.php
 * Section: Location Map (Vietnam SVG + desktop panel + mobile popup)
 */
$theme = get_template_directory_uri();
?>
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
            <img src="<?php echo $theme; ?>/assets/images/hero_port.jpg" alt="Bến số 1,2 Cảng cửa ngõ Quốc tế Hải Phòng"
              class="p-location__img" id="location-img" loading="lazy" />
          </div>

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

          <!-- Base map SVG — JS sẽ fetch + inline khi vào viewport -->
          <img src="<?php echo $theme; ?>/assets/images/map.svg" alt="Bản đồ Việt Nam — vị trí các dự án CMB"
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
