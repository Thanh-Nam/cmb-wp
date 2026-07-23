<?php
/**
 * Template Part: Khách hàng - Đối tác
 * Usage: get_template_part('template-parts/section-partner');
 * Data source: ACF Options — partner_list (Repeater > logo: Image)
 */
$partner_logos = get_field('partner_list', 'option');
$valid_logos   = array_values( array_filter( (array) $partner_logos, function ( $item ) {
    return ! empty( $item['logo'] );
} ) );
$logo_count = count( $valid_logos );

// Số bộ logo lặp lại trong track — đủ để 1 vòng lặp phủ kín màn hình desktop
// thông thường (tới ~1920px). Với màn hình rộng hơn (l-container full-width,
// xem _info.scss/_partner.scss), assets/js/modules/partner-marquee.js sẽ tự
// đo chiều rộng thực tế lúc runtime và nhân bản thêm logo nếu cần — tránh
// phải render sẵn thật nhiều bản sao cho MỌI màn hình trong khi phần lớn
// (mobile/tablet/desktop thường) không cần đến.
$item_w        = 240;
$gap           = 20;
$set_width     = $logo_count > 0 ? ( $logo_count * $item_w + ( $logo_count - 1 ) * $gap ) : 0;
$target_width  = 2000;
$marquee_copies = $set_width > 0 ? max( 3, (int) ceil( $target_width / $set_width ) + 1 ) : 3;
$marquee_copies = min( $marquee_copies, 20 ); // chặn trên, tránh phình DOM nếu admin chỉ nhập 1 logo
?>
<section class="p-partner" id="partner" aria-label="<?php echo esc_attr( cmb_txt( 'Khách hàng - Đối tác', 'Clients - Partners' ) ); ?>">

  <div class="l-container">
    <div class="p-partner__header" data-reveal="fade-up">
      <h2 class="c-section-title p-partner__title"><?php echo cmb_txt( 'KHÁCH HÀNG - ĐỐI TÁC', 'CLIENTS - PARTNERS' ); ?></h2>
    </div>
  </div>

  <?php if ($valid_logos) : ?>
  <div class="p-partner__rows" aria-label="<?php echo esc_attr( cmb_txt( 'Danh sách khách hàng và đối tác', 'List of clients and partners' ) ); ?>" style="--marquee-copies: <?php echo (int) $marquee_copies; ?>;">

    <!-- DESKTOP: 1 hàng chạy rtl -->
    <div class="p-partner__track-wrap p-partner__track-wrap--desktop">
      <div class="p-partner__track p-partner__track--rtl" role="list">
        <?php for ($c = 0; $c < $marquee_copies; $c++) : ?>
          <?php foreach ($valid_logos as $item) : ?>
          <div class="p-partner__item" role="listitem" <?php echo $c > 0 ? 'aria-hidden="true"' : ''; ?>>
            <img src="<?php echo $item['logo']['url']; ?>"
                 alt="<?php echo $c === 0 ? esc_attr($item['logo']['alt']) : ''; ?>"
                 class="p-partner__logo" loading="lazy" />
          </div>
          <?php endforeach; ?>
        <?php endfor; ?>
      </div>
    </div>

    <!-- MOBILE hàng 1: rtl -->
    <div class="p-partner__track-wrap p-partner__track-wrap--sp-r1" aria-hidden="true">
      <div class="p-partner__track p-partner__track--rtl">
        <?php for ($c = 0; $c < $marquee_copies; $c++) : ?>
          <?php foreach ($valid_logos as $item) : ?>
          <div class="p-partner__item">
            <img src="<?php echo $item['logo']['url']; ?>"
                 alt="" class="p-partner__logo" loading="lazy" />
          </div>
          <?php endforeach; ?>
        <?php endfor; ?>
      </div>
    </div>

    <!-- MOBILE hàng 2: ltr -->
    <div class="p-partner__track-wrap p-partner__track-wrap--sp-r2" aria-hidden="true">
      <div class="p-partner__track p-partner__track--ltr">
        <?php for ($c = 0; $c < $marquee_copies; $c++) : ?>
          <?php foreach ($valid_logos as $item) : ?>
          <div class="p-partner__item">
            <img src="<?php echo $item['logo']['url']; ?>"
                 alt="" class="p-partner__logo" loading="lazy" />
          </div>
          <?php endforeach; ?>
        <?php endfor; ?>
      </div>
    </div>

  </div>
  <?php endif; ?>

</section>
