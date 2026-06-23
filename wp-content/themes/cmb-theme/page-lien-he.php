<?php
/**
 * Template Name: Liên hệ
 */
get_header();

while (have_posts()) : the_post();

// ── Contact info từ ACF Options (fallback về giá trị tĩnh)
$address       = function_exists('get_field') ? get_field('company_address',       'option') : '';
$phones_raw    = function_exists('get_field') ? get_field('company_phone',         'option') : '';
$emails_raw    = function_exists('get_field') ? get_field('company_email',         'option') : '';
$working_hours = function_exists('get_field') ? get_field('company_working_hours', 'option') : '';

if ( ! $address )       $address    = "Tầng 11, Tòa nhà CMB, 512 Tôn Thất Thuyết,\nCầu Giấy, Hà Nội, Việt Nam";
if ( ! $working_hours ) $working_hours = "Thứ 2 – Thứ 6\n08:00 – 17:30";

// Tách từng dòng thành mảng (Textarea — mỗi dòng 1 số/email)
$phones = $phones_raw
    ? array_values( array_filter( array_map( 'trim', explode( "\n", $phones_raw ) ) ) )
    : [ '(84) 24 3786 6291', '(84) 225 3 760 629' ];
$emails = $emails_raw
    ? array_values( array_filter( array_map( 'trim', explode( "\n", $emails_raw ) ) ) )
    : [ 'info@cmb.com.vn', 'ir@cmb.com.vn' ];

// ── CF7 form — tìm theo post_title, không hardcode ID
$cf7_html = '';
$cf7_post = get_page_by_title( 'Liên hệ CMB', OBJECT, 'wpcf7_contact_form' );
if ( $cf7_post && function_exists( 'do_shortcode' ) ) {
    $cf7_html = do_shortcode( '[contact-form-7 id="' . $cf7_post->ID . '"]' );
}

// ── Privacy policy URL
$privacy_url = function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url()
    ? get_privacy_policy_url()
    : '#';
?>

  <!-- ======= MAIN ======= -->
  <main class="site-main" id="main-content">

    <!-- ======= PAGE HERO ======= -->
    <section class="p-page-hero" id="lien-he-hero" aria-label="Liên hệ CMB">

      <div class="p-page-hero__image-side">
        <?php if ( has_post_thumbnail() ) :
          the_post_thumbnail( 'full', [ 'class' => 'p-page-hero__image', 'loading' => 'eager' ] );
        else : ?>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero_port.jpg"
            alt="Cảng biển Việt Nam – CMB"
            class="p-page-hero__image"
            loading="eager" />
        <?php endif; ?>
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">
        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page">Liên hệ</span>
        </nav>
        <div class="p-page-hero__content">
          <h1 class="p-page-hero__title">LIÊN HỆ</h1>
          <p class="p-page-hero__subtitle">
            Kết nối cùng CMB<br>
            Kiến tạo những công trình hàng hải bền vững
          </p>
        </div>
      </div>

    </section>
    <!-- ======= /PAGE HERO ======= -->


    <!-- ======= INFO BAR ======= -->
    <div class="p-lh-infobar" id="lien-he-info">
      <div class="l-container">
        <div class="p-lh-infobar__card">

          <div class="p-lh-infobar__item" data-reveal="fade-up" data-reveal-delay="1">
            <span class="p-lh-infobar__icon" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                <path d="M14 3C9.58 3 6 6.58 6 11C6 16.5 14 25 14 25C14 25 22 16.5 22 11C22 6.58 18.42 3 14 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                <circle cx="14" cy="11" r="3" stroke="currentColor" stroke-width="1.6"/>
              </svg>
            </span>
            <div class="p-lh-infobar__text">
              <span class="p-lh-infobar__label">Địa chỉ</span>
              <span class="p-lh-infobar__value">
                <?php echo nl2br( esc_html( $address ) ); ?>
              </span>
            </div>
          </div>

          <div class="p-lh-infobar__divider" aria-hidden="true"></div>

          <div class="p-lh-infobar__item" data-reveal="fade-up" data-reveal-delay="2">
            <span class="p-lh-infobar__icon" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                <path d="M4.5 5H9L11 9.5L8.5 11.5C9.33 13.17 10.83 14.67 12.5 15.5L14.5 13L18.5 15V19.5C18.5 19.78 18.28 20 18 20C9.72 20 3 13.28 3 5C3 4.72 3.22 4.5 3.5 4.5L4.5 5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round"/>
              </svg>
            </span>
            <div class="p-lh-infobar__text">
              <span class="p-lh-infobar__label">Điện thoại</span>
              <span class="p-lh-infobar__value">
                <?php foreach ( $phones as $phone ) : ?>
                <a href="tel:+<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                <?php endforeach; ?>
              </span>
            </div>
          </div>

          <div class="p-lh-infobar__divider" aria-hidden="true"></div>

          <div class="p-lh-infobar__item" data-reveal="fade-up" data-reveal-delay="3">
            <span class="p-lh-infobar__icon" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                <rect x="3" y="6" width="22" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/>
                <path d="M3 10L14 17L25 10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
            <div class="p-lh-infobar__text">
              <span class="p-lh-infobar__label">Email</span>
              <span class="p-lh-infobar__value">
                <?php foreach ( $emails as $email ) : ?>
                <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                <?php endforeach; ?>
              </span>
            </div>
          </div>

          <div class="p-lh-infobar__divider" aria-hidden="true"></div>

          <div class="p-lh-infobar__item" data-reveal="fade-up" data-reveal-delay="4">
            <span class="p-lh-infobar__icon" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                <circle cx="14" cy="14" r="10.5" stroke="currentColor" stroke-width="1.6"/>
                <path d="M14 8V14L17.5 17.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
            <div class="p-lh-infobar__text">
              <span class="p-lh-infobar__label">Giờ làm việc</span>
              <span class="p-lh-infobar__value">
                <?php echo nl2br( esc_html( $working_hours ) ); ?>
              </span>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- ======= /INFO BAR ======= -->


    <!-- ======= CONTACT FORM ======= -->
    <section class="p-lh-body" id="gui-thong-tin" aria-labelledby="form-title">
      <div class="l-container">
        <div class="p-lh-body__grid">

          <!-- Left: Image -->
          <div class="p-lh-body__image" data-reveal="fade-right">
            <?php if ( has_post_thumbnail() ) :
              the_post_thumbnail( 'large', [ 'class' => 'p-lh-body__img', 'alt' => 'Liên hệ CMB', 'loading' => 'lazy' ] );
            else : ?>
              <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero_port.jpg"
                alt="Tàu hàng tại cảng biển Việt Nam"
                class="p-lh-body__img"
                loading="lazy" />
            <?php endif; ?>
          </div>

          <!-- Right: Form -->
          <div class="p-lh-body__form-wrap" data-reveal="fade-left">
            <h2 id="form-title" class="p-lh-body__form-title">GỬI THÔNG TIN LIÊN HỆ</h2>

            <?php if ( $cf7_html ) : ?>
              <?php echo $cf7_html; ?>
            <?php else : ?>
            <!-- Fallback form (khi chưa cài CF7 hoặc chưa tạo form "Liên hệ CMB") -->
            <form class="p-lh-form" id="contact-form" action="#" method="post" novalidate aria-label="Form liên hệ">

              <div class="p-lh-form__row">
                <div class="p-lh-form__group">
                  <label class="p-lh-form__label" for="contact-name">
                    Họ và tên <span class="p-lh-form__required" aria-label="bắt buộc">*</span>
                  </label>
                  <input
                    type="text"
                    id="contact-name"
                    name="name"
                    class="p-lh-form__input"
                    placeholder="Nhập họ và tên"
                    required
                    autocomplete="name"
                    aria-required="true" />
                </div>
                <div class="p-lh-form__group">
                  <label class="p-lh-form__label" for="contact-email">
                    Email <span class="p-lh-form__required" aria-label="bắt buộc">*</span>
                  </label>
                  <input
                    type="email"
                    id="contact-email"
                    name="email"
                    class="p-lh-form__input"
                    placeholder="Nhập email"
                    required
                    autocomplete="email"
                    aria-required="true" />
                </div>
              </div>

              <div class="p-lh-form__group">
                <label class="p-lh-form__label" for="contact-phone">
                  Số điện thoại <span class="p-lh-form__required" aria-label="bắt buộc">*</span>
                </label>
                <input
                  type="tel"
                  id="contact-phone"
                  name="phone"
                  class="p-lh-form__input"
                  placeholder="Nhập số điện thoại"
                  required
                  autocomplete="tel"
                  aria-required="true" />
              </div>

              <div class="p-lh-form__group">
                <label class="p-lh-form__label" for="contact-subject">Chủ đề</label>
                <select id="contact-subject" name="subject" class="p-lh-form__select">
                  <option value="" disabled selected>Chọn chủ đề</option>
                  <option value="tu-van-xay-dung">Tư vấn xây dựng</option>
                  <option value="hop-tac">Hợp tác kinh doanh</option>
                  <option value="quan-he-co-dong">Quan hệ cổ đông</option>
                  <option value="tuyen-dung">Tuyển dụng</option>
                  <option value="khac">Khác</option>
                </select>
              </div>

              <div class="p-lh-form__group">
                <label class="p-lh-form__label" for="contact-message">
                  Nội dung <span class="p-lh-form__required" aria-label="bắt buộc">*</span>
                </label>
                <textarea
                  id="contact-message"
                  name="message"
                  class="p-lh-form__textarea"
                  placeholder="Nhập nội dung liên hệ"
                  required
                  rows="5"
                  aria-required="true"></textarea>
              </div>

              <div class="p-lh-form__checkbox-wrap">
                <input
                  type="checkbox"
                  id="contact-agree"
                  name="agree"
                  class="p-lh-form__checkbox"
                  required
                  aria-required="true" />
                <label for="contact-agree" class="p-lh-form__checkbox-label">
                  Tôi đồng ý cho phép CMB lưu trữ và xử lý thông tin theo
                  <a href="<?php echo esc_url( $privacy_url ); ?>">Chính sách bảo mật</a>
                </label>
              </div>

              <button type="submit" class="p-lh-form__submit">GỬI THÔNG TIN</button>

            </form>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </section>
    <!-- ======= /CONTACT FORM ======= -->


    <!-- ======= OFFICES + MAP ======= -->
    <?php
    // Lấy danh sách văn phòng từ ACF Repeater, fallback về dữ liệu tĩnh
    $offices = function_exists('get_field') ? get_field('offices', 'option') : [];
    if (empty($offices)) {
        $offices = [
            [
                'office_name'    => 'Văn phòng Hà Nội',
                'office_address' => 'Tầng 11, Tòa nhà CMB, 512 Tôn Thất Thuyết, Cầu Giấy, Hà Nội',
                'office_phone'   => '(84) 24 3786 6291',
                'office_map_src' => 'https://maps.google.com/maps?q=512+Ton+That+Thuyet,+Cau+Giay,+Ha+Noi,+Viet+Nam&output=embed&hl=vi',
            ],
            [
                'office_name'    => 'VP Hải Phòng',
                'office_address' => 'Số 12 Lô 22 Lê Hồng Phong, Ngô Quyền, Hải Phòng',
                'office_phone'   => '(84) 225 3 768 629',
                'office_map_src' => 'https://maps.google.com/maps?q=Le+Hong+Phong,+Ngo+Quyen,+Hai+Phong,+Viet+Nam&output=embed&hl=vi',
            ],
            [
                'office_name'    => 'VP TP HCM',
                'office_address' => 'Tầng 6, Tòa nhà Sailing, 111A Pasteur, Quận 1, TP.HCM',
                'office_phone'   => '(84) 28 6287 4840',
                'office_map_src' => 'https://maps.google.com/maps?q=111A+Pasteur,+Quan+1,+Ho+Chi+Minh+City,+Viet+Nam&output=embed&hl=vi',
            ],
        ];
    }
    $first_map_src = ! empty( $offices[0]['office_map_src'] ) ? $offices[0]['office_map_src'] : '';
    ?>
    <section class="p-lh-map" id="van-phong" aria-label="Văn phòng và chi nhánh CMB">
      <div class="l-container">
        <div class="p-lh-map__wrapper">

          <!-- Sidebar: Offices -->
          <div class="p-lh-map__sidebar" data-reveal="fade-right">
            <h2 class="p-lh-map__sidebar-title">VĂN PHÒNG / CHI NHÁNH</h2>

            <ul class="p-lh-offices" role="list">
              <?php foreach ( $offices as $i => $office ) :
                $is_first   = ( $i === 0 );
                $map_src    = esc_attr( $office['office_map_src'] ?? '' );
                $phone_raw  = $office['office_phone'] ?? '';
                $phone_href = 'tel:+' . preg_replace( '/[^0-9]/', '', $phone_raw );
              ?>
              <li class="p-lh-office<?php echo $is_first ? ' p-lh-office--active' : ''; ?>"
                  id="office-<?php echo esc_attr( sanitize_title( $office['office_name'] ?? $i ) ); ?>"
                  data-map-src="<?php echo $map_src; ?>"
                  role="button" tabindex="0"
                  aria-pressed="<?php echo $is_first ? 'true' : 'false'; ?>">
                <div class="p-lh-office__name">
                  <?php if ( $is_first ) : ?>
                  <svg class="p-lh-office__pin" width="14" height="18" viewBox="0 0 14 18" fill="none" aria-hidden="true">
                    <path d="M7 1C4.24 1 2 3.24 2 6C2 9.75 7 17 7 17C7 17 12 9.75 12 6C12 3.24 9.76 1 7 1Z" fill="currentColor"/>
                    <circle cx="7" cy="6" r="2" fill="white"/>
                  </svg>
                  <?php else : ?>
                  <svg class="p-lh-office__pin" width="14" height="18" viewBox="0 0 14 18" fill="none" aria-hidden="true">
                    <path d="M7 1C4.24 1 2 3.24 2 6C2 9.75 7 17 7 17C7 17 12 9.75 12 6C12 3.24 9.76 1 7 1Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <circle cx="7" cy="6" r="2" stroke="currentColor" stroke-width="1.5"/>
                  </svg>
                  <?php endif; ?>
                  <?php echo esc_html( $office['office_name'] ?? '' ); ?>
                </div>
                <?php if ( ! empty( $office['office_address'] ) ) : ?>
                <p class="p-lh-office__address"><?php echo nl2br( esc_html( $office['office_address'] ) ); ?></p>
                <?php endif; ?>
                <?php if ( $phone_raw ) : ?>
                <p class="p-lh-office__phone">
                  <a href="<?php echo esc_attr( $phone_href ); ?>"><?php echo esc_html( $phone_raw ); ?></a>
                </p>
                <?php endif; ?>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <!-- Map -->
          <div class="p-lh-map__embed" id="google-map">
            <iframe
              src="<?php echo esc_url( $first_map_src ); ?>"
              title="Bản đồ vị trí văn phòng CMB"
              allowfullscreen
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              aria-label="Bản đồ Google Maps vị trí CMB">
            </iframe>
          </div>

        </div>
      </div>
    </section>
    <!-- ======= /OFFICES + MAP ======= -->

  </main>
  <!-- ======= /MAIN ======= -->

<?php endwhile; ?>

<?php get_footer();
