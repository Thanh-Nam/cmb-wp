<?php
/**
 * template-parts/du-an/single-detail.php
 * Section: Project Detail Content + Sidebar
 */
$services_list = get_field('project_services_list');
$gallery       = get_field('project_gallery');

// Thông tin dự án (sidebar) — trước đây nằm ở khối p-project-infobar riêng phía
// trên hero, giờ gộp thẳng vào sidebar "THÔNG TIN DỰ ÁN" cho gọn, dùng lại đúng
// CSS p-project-info-card__label/__value đã có sẵn.
$info_owner    = get_field('project_owner');
$info_location = get_field('project_location_detail');
$info_scale    = get_field('project_scale');
$info_timeline = get_field('project_timeline');
$info_services = get_field('project_services');

$info_items = [];

if ($info_owner) {
    $info_items[] = [
        'label' => cmb_txt( 'CHỦ ĐẦU TƯ', 'PROJECT OWNER' ),
        'value' => $info_owner,
        'icon'  => '<svg width="16" height="16" viewBox="0 0 22 22" fill="none"><circle cx="11" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M3 19C3 15.134 6.134 12 10 12H12C15.866 12 19 15.134 19 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
    ];
}

if ($info_location) {
    $info_items[] = [
        'label' => cmb_txt( 'ĐỊA ĐIỂM', 'LOCATION' ),
        'value' => $info_location,
        'icon'  => '<svg width="16" height="16" viewBox="0 0 22 22" fill="none"><path d="M11 2C7.96 2 5.5 4.46 5.5 7.5C5.5 11.88 11 18 11 18C11 18 16.5 11.88 16.5 7.5C16.5 4.46 14.04 2 11 2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><circle cx="11" cy="7.5" r="2.25" stroke="currentColor" stroke-width="1.5"/></svg>',
    ];
}

if ($info_scale) {
    $info_items[] = [
        'label' => cmb_txt( 'QUY MÔ', 'SCALE' ),
        'value' => $info_scale,
        'icon'  => '<svg width="16" height="16" viewBox="0 0 22 22" fill="none"><rect x="2" y="9" width="18" height="4" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M6 9V7M11 9V5M16 9V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
    ];
}

if ($info_timeline) {
    $info_items[] = [
        'label' => cmb_txt( 'THỜI GIAN', 'TIMELINE' ),
        'value' => $info_timeline,
        'icon'  => '<svg width="16" height="16" viewBox="0 0 22 22" fill="none"><circle cx="11" cy="11" r="8.5" stroke="currentColor" stroke-width="1.5"/><path d="M11 6V11L14.5 13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ];
}

if ($info_services) {
    $info_items[] = [
        'label' => cmb_txt( 'DỊCH VỤ TƯ VẤN CHÍNH', 'KEY CONSULTING SERVICES' ),
        'value' => $info_services,
        'icon'  => '<svg width="16" height="16" viewBox="0 0 22 22" fill="none"><rect x="3" y="2" width="13" height="17" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 7H13M7 11H13M7 15H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="17" cy="16" r="3.5" fill="white" stroke="currentColor" stroke-width="1.5"/><path d="M15.5 16L16.5 17L18.5 15" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ];
}

// Thời gian đăng bài — có nhập ACF thì dùng, không thì lấy mặc định của WordPress
$proj_custom_date = get_field('project_publish_date');
if ($proj_custom_date) {
    $proj_date_obj  = DateTime::createFromFormat('d/m/Y g:i a', $proj_custom_date);
    $proj_datetime  = $proj_date_obj ? $proj_date_obj->format('Y-m-d\TH:i') : '';
    $proj_time      = $proj_date_obj ? $proj_date_obj->format('H:i') : '';
    $proj_date_str  = $proj_date_obj ? $proj_date_obj->format('d.m.Y') : $proj_custom_date;
} else {
    $proj_datetime  = get_the_date('Y-m-d\TH:i');
    $proj_time      = get_the_date('H:i');
    $proj_date_str  = get_the_date('d.m.Y');
}
?>
<!-- ======= PROJECT DETAIL CONTENT ======= -->
<section class="p-project-detail" id="project-detail">
  <div class="l-container">
    <div class="p-project-detail__layout">

      <?php
      $proj_content = apply_filters( 'the_content', get_the_content() );
      $has_intro    = trim( wp_strip_all_tags( $proj_content ) ) !== '';

      // Lọc trước các dịch vụ hợp lệ (có tên) để đếm ĐÚNG số phần tử sẽ hiển thị
      // -> chia số cột grid theo đúng số đó, tránh bị dư cột trống khi khách nhập
      // ít hơn 6 dịch vụ. Giới hạn tối đa 6 cột (desktop) / 3 cột (tablet, mobile)
      // để không bị quá hẹp khi nhập nhiều dịch vụ (giữ đúng thiết kế gốc).
      $services_valid = array_values( array_filter( (array) $services_list, function ( $s ) {
          return ! empty( $s['name'] );
      } ) );
      $services_count   = count( $services_valid );
      $services_cols_lg = max( 1, min( $services_count, 6 ) );
      $services_cols_md = max( 1, min( $services_count, 3 ) );
      ?>

      <!-- ROW 1: Giới thiệu dự án (kèm thông tin dự án) + Hình ảnh dự án (slider) -->
      <?php if ( $has_intro || $info_items || $gallery ) : ?>
      <div class="p-project-detail__top" id="project-top">

        <?php if ( $has_intro || $info_items ) : ?>
        <div class="p-project-detail__intro p-project-section" id="section-intro">
          <?php if ( $has_intro ) : ?>
          <h2 class="p-project-section__title"><?php echo cmb_txt( 'GIỚI THIỆU DỰ ÁN', 'PROJECT INTRODUCTION' ); ?></h2>
          <div class="p-project-section__content">
            <?php echo $proj_content; ?>
          </div>
          <?php endif; ?>

          <?php if ($info_items) : ?>
          <ul class="p-project-info-list" role="list" id="project-info-list">
            <?php foreach ($info_items as $item) : ?>
            <li class="p-project-info-list__item">
              <div class="p-project-info-list__icon" aria-hidden="true">
                <?php echo $item['icon']; ?>
              </div>
              <div class="p-project-info-list__text">
                <span class="p-project-info-list__label"><?php echo $item['label']; ?></span>
                <span class="p-project-info-list__value"><?php echo wp_kses($item['value'] ?? '', ['br' => []]); ?></span>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($gallery) : ?>
        <div class="p-project-detail__media" id="section-gallery">
          <h2 class="p-project-section__title"><?php echo cmb_txt( 'HÌNH ẢNH DỰ ÁN', 'PROJECT IMAGES' ); ?></h2>

          <div class="p-project-gallery-slider" id="project-gallery-slider">
            <div class="swiper p-project-gallery-slider__main" id="project-gallery">
              <div class="swiper-wrapper">
                <?php foreach ($gallery as $i => $img) : ?>
                <div class="swiper-slide">
                  <figure class="p-project-gallery-slider__slide">
                    <img src="<?php echo $img['url']; ?>"
                         alt="<?php echo $img['alt']; ?>"
                         class="p-project-gallery-slider__img" loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>" />
                  </figure>
                </div>
                <?php endforeach; ?>
              </div>
            </div>

            <?php if (count($gallery) > 1) : ?>
            <div class="p-project-gallery-slider__thumbs-row">
              <button type="button" class="p-project-gallery-slider__prev" id="project-gallery-prev" aria-label="<?php echo esc_attr( cmb_txt( 'Ảnh trước', 'Previous image' ) ); ?>">
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none" aria-hidden="true">
                  <path d="M15 6H1M6 1L1 6L6 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </button>

              <div class="swiper p-project-gallery-slider__thumbs" id="project-gallery-thumbs">
                <div class="swiper-wrapper">
                  <?php foreach ($gallery as $i => $img) : ?>
                  <div class="swiper-slide">
                    <img src="<?php echo $img['url']; ?>" alt="" loading="lazy" />
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <button type="button" class="p-project-gallery-slider__next" id="project-gallery-next" aria-label="<?php echo esc_attr( cmb_txt( 'Ảnh tiếp theo', 'Next image' ) ); ?>">
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none" aria-hidden="true">
                  <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </button>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>
      <?php endif; ?>

      <!-- ROW 2: Dịch vụ CMB đảm nhận -->
      <?php if ($services_valid) : ?>
      <div class="p-project-section" id="section-services">
        <h2 class="p-project-section__title"><?php echo cmb_txt( 'DỊCH VỤ CMB ĐẢM NHẬN', 'CMB SERVICES PROVIDED' ); ?></h2>
        <div class="p-project-services" id="project-services-grid" style="--services-cols: <?php echo $services_cols_lg; ?>; --services-cols-md: <?php echo $services_cols_md; ?>;">
          <?php foreach ($services_valid as $i => $service) :
            $icon_url = '';
            if (!empty($service['icon'])) {
              $icon_src = wp_get_attachment_image_src($service['icon'], 'full');
              if ($icon_src) $icon_url = $icon_src[0];
            }
          ?>
          <div class="p-project-services__item" id="service-<?php echo $i + 1; ?>">
            <div class="p-project-services__icon" aria-hidden="true">
              <?php if ($icon_url) : ?>
              <img src="<?php echo esc_url($icon_url); ?>" alt="" width="28" height="28" loading="lazy" />
              <?php else : ?>
              <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                <rect x="5" y="3" width="15" height="20" rx="2" stroke="#0379CC" stroke-width="1.5"/>
                <path d="M9 9H17M9 13H17M9 17H13" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
              <?php endif; ?>
            </div>
            <span class="p-project-services__label"><?php echo esc_html($service['name']); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Published meta -->
      <div class="p-project-detail__meta">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <rect x="1" y="2" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.3"/>
          <path d="M1 5.5H13" stroke="currentColor" stroke-width="1.3"/>
          <path d="M4 1V3M10 1V3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
        </svg>
        <time datetime="<?php echo esc_attr($proj_datetime); ?>">
          <?php echo cmb_txt( 'Đăng lúc', 'Posted at' ); ?> <strong><?php echo esc_html($proj_time); ?></strong>
          <?php echo cmb_txt( 'ngày', 'on' ); ?> <strong><?php echo esc_html($proj_date_str); ?></strong>
        </time>
      </div>

    </div>
  </div>
</section>
<!-- ======= /PROJECT DETAIL CONTENT ======= -->
