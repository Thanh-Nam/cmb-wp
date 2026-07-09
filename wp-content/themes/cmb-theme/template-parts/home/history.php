<?php
/**
 * template-parts/home/history.php
 * Section: History Milestones + Ship Animation
 */
$theme            = get_template_directory_uri();
$history_subtitle = cmb_get_option( 'history_subtitle' );
$history_title    = cmb_get_option( 'history_title' );
$history_items    = get_field( 'history_item', 'option' );
?>
<!-- ======= HISTORY ======= -->
<section class="p-history" id="history" aria-label="Lịch sử CMB">

  <!-- Background: phủ toàn section -->
  <div class="p-history__bg" aria-hidden="true">
    <img src="<?php echo $theme; ?>/assets/images/bg-history.png" alt="" role="presentation" class="p-history__bg-img" loading="lazy" />
  </div>

  <!-- Tiêu đề section -->
  <div class="p-history__header" data-reveal="fade-up">
    <?php if ( $history_subtitle ) : ?>
      <div class="p-history__label">
        <span class="c-section-label"><?php echo $history_subtitle; ?></span>
      </div>
    <?php endif; ?>
    <?php if ( $history_title ) : ?>
      <h2 class="c-section-title p-history__title"><?php echo $history_title; ?></h2>
    <?php endif; ?>
  </div>

  <!-- Navigation arrows -->
  <nav class="p-history__nav" aria-label="Điều hướng mốc lịch sử">
    <button class="p-history__nav-btn p-history__nav-btn--prev" id="history-nav-prev" aria-label="Mốc trước"
      type="button" disabled>
      <img src="<?php echo $theme; ?>/assets/images/arrow-history.svg" alt="" role="presentation" class="p-history__nav-arrow" loading="lazy" />
    </button>
    <button class="p-history__nav-btn p-history__nav-btn--next" id="history-nav-next" aria-label="Mốc tiếp theo"
      type="button">
      <img src="<?php echo $theme; ?>/assets/images/arrow-history.svg" alt="" role="presentation"
        class="p-history__nav-arrow p-history__nav-arrow--flip" loading="lazy" />
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
      <image id="ship-icon" href="<?php echo $theme; ?>/assets/images/tau.gif" xlink:href="<?php echo $theme; ?>/assets/images/tau.gif" x="-43.33" y="-38.67"
        width="60" height="60" transform="rotate(0)" />
      <animateMotion href="#ship-icon" dur="44s" begin="0s" repeatCount="indefinite" rotate="auto"
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
        <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" loading="lazy" />
      </span>
      <span class="p-history__wheel p-history__wheel--1995">
        <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" loading="lazy" />
      </span>
      <span class="p-history__wheel p-history__wheel--1999">
        <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" loading="lazy" />
      </span>
      <span class="p-history__wheel p-history__wheel--2004">
        <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" loading="lazy" />
      </span>
      <span class="p-history__wheel p-history__wheel--2011">
        <img src="<?php echo $theme; ?>/assets/images/banh-lai.svg" alt="" loading="lazy" />
      </span>
    </div>

  </div>

  <!-- Mobile timeline list: populated by JS -->
  <ul class="p-history__mobile-list" id="history-mobile-list" role="list" aria-label="Lịch sử CMB"></ul>

</section>
<!-- ======= /HISTORY ======= -->
