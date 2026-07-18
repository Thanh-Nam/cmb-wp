<!-- ======= CONTACT CTA ======= -->
  <?php
  $cta_title       = cmb_get_option( 'footer_cta_title' ) ?: cmb_txt( 'Dịch Vụ Tư Vấn Tận Tâm, Nâng Tầm Giá Trị', 'Dedicated Consulting Services, Elevating Value' );
  $cta_link        = get_field( 'footer_cta_link', 'option' ) ?: home_url( '/lien-he' );
  $acf_logo_field  = get_field( 'logo', 'option' );
  $acf_logo_url    = is_array( $acf_logo_field ) ? ( $acf_logo_field['url'] ?? '' ) : $acf_logo_field;
  $social_fb       = get_field( 'social_facebook', 'option' );
  $social_yt       = get_field( 'social_youtube', 'option' );
  $social_li       = get_field( 'social_linkedin', 'option' );
  $branch_hn_addr  = get_field( 'branch_hn_address', 'option' ) ?: 'Tầng 12 Tháp Tây Hancorp Plaza, 72 đường Trần Đăng Ninh, Phường Nghĩa Đô, Thành phố Hà Nội';
  $branch_hn_phone = get_field( 'branch_hn_phone', 'option' ) ?: '(+84) 24.37545.293';
  $branch_hn_email = get_field( 'branch_hn_email', 'option' ) ?: 'cmbsince1966@cmbvn.com.vn';
  $branch_hp_addr  = get_field( 'branch_hp_address', 'option' ) ?: '112 Lê Thành Tông, Phường Đông Hải, Thành phố Hải Phòng';
  $branch_hp_phone = get_field( 'branch_hp_phone', 'option' ) ?: '(+84) 225.3826817';
  $branch_hp_email = get_field( 'branch_hp_email', 'option' ) ?: 'cmbhp@cmbvn.com.vn';
  $branch_hcm_addr  = get_field( 'branch_hcm_address', 'option' ) ?: '123 Tôn Thất Thuyết, Phường Xóm Chiếu, Thành phố Hồ Chí Minh';
  $branch_hcm_phone = get_field( 'branch_hcm_phone', 'option' ) ?: '(+84) 28.628.74840';
  $branch_hcm_email = get_field( 'branch_hcm_email', 'option' ) ?: 'cmbhcm@cmbvn.com.vn';
  $float_zalo      = get_field( 'float_zalo_url', 'option' ) ?: '#';
  $float_messenger = get_field( 'float_messenger_url', 'option' ) ?: '#';
  $float_phone     = get_field( 'float_phone', 'option' );
  $footer_copy     = cmb_get_option( 'footer_copy' ) ?: "© " . date( 'Y' ) . " CMB. All Rights Reserved.\nCreated by CMB Center for Information Technology & AI.";
  ?>
  <section class="p-contact" id="contact" aria-label="<?php echo esc_attr( cmb_txt( 'Liên hệ tư vấn', 'Contact for consulting' ) ); ?>">
    <div class="l-container">
      <div class="p-contact__inner" data-reveal="fade-up">

        <h2 class="p-contact__title"><?php echo $cta_title; ?></h2>

        <a href="<?php echo $cta_link; ?>" class="p-contact__btn" id="btn-contact-cta">
          <?php echo cmb_txt( 'Liên hệ tư vấn', 'Contact for consulting' ); ?>
          <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true">
            <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </a>

      </div>
    </div>
  </section>
  <!-- ======= /CONTACT CTA ======= -->

  <!-- ======= FOOTER ======= -->
  <footer class="l-footer" id="site-footer">
    <div class="l-footer__body">
      <div class="l-container">

        <!-- TOP ROW: Logo -->
        <div class="l-footer__top">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="l-footer__logo" id="footer-logo" title="<?php echo esc_attr( cmb_txt( 'CMB - Trang chủ', 'CMB - Home' ) ); ?>">
            <img src="<?php echo esc_url( $acf_logo_url ?: get_template_directory_uri() . '/assets/images/Company Logo.svg' ); ?>"
              alt="<?php echo esc_attr( cmb_txt( 'Logo CMB - Công ty Cổ phần Tư vấn Xây dựng Công trình Hàng hải', 'CMB Logo - Marine Construction Consulting Joint Stock Company' ) ); ?>" class="l-footer__logo-img" loading="lazy" />
            <span class="l-footer__logo-name"><?php echo cmb_txt( 'CÔNG TY CỔ PHẦN TƯ VẤN XÂY DỰNG CÔNG TRÌNH HÀNG HẢI', 'MARINE CONSTRUCTION CONSULTING JOINT STOCK COMPANY' ); ?></span>
          </a>
        </div>

        <!-- BOTTOM ROW -->
        <div class="l-footer__inner">

          <!-- Column 1: Social + Copyright -->
          <div class="l-footer__brand">
            <nav class="l-footer__social" aria-label="<?php echo esc_attr( cmb_txt( 'Mạng xã hội CMB', 'CMB Social Media' ) ); ?>">
              <?php if ( $social_fb ) : ?>
              <a href="<?php echo $social_fb; ?>" class="l-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( cmb_txt( 'Theo dõi CMB trên Facebook', 'Follow CMB on Facebook' ) ); ?>" title="CMB Facebook">
                <svg width="9" height="17" viewBox="0 0 9 17" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M6 9.5H8.5L9.5 6.5H6V4.5C6 3.5 6.5 3 7.5 3H9.5V0C9.17 0 7.84 0 6.84 0C4.33 0 3 1.5 3 4V6.5H0.5V9.5H3V17H6V9.5Z" fill="currentColor" />
                </svg>
              </a>
              <?php endif; ?>
              <?php if ( $social_yt ) : ?>
              <a href="<?php echo $social_yt; ?>" class="l-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( cmb_txt( 'Theo dõi CMB trên YouTube', 'Follow CMB on YouTube' ) ); ?>" title="CMB YouTube">
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M15.54 1.87C15.37 1.21 14.86 0.69 14.2 0.52C12.96 0.19 8 0.19 8 0.19C8 0.19 3.04 0.19 1.8 0.52C1.14 0.69 0.63 1.21 0.46 1.87C0.13 3.12 0.13 5.73 0.13 5.73C0.13 5.73 0.13 8.34 0.46 9.59C0.63 10.25 1.14 10.77 1.8 10.94C3.04 11.27 8 11.27 8 11.27C8 11.27 12.96 11.27 14.2 10.94C14.86 10.77 15.37 10.25 15.54 9.59C15.87 8.34 15.87 5.73 15.87 5.73C15.87 5.73 15.87 3.12 15.54 1.87ZM6.33 8.2V3.26L10.67 5.73L6.33 8.2Z" fill="currentColor" />
                </svg>
              </a>
              <?php endif; ?>
              <?php if ( $social_li ) : ?>
              <a href="<?php echo $social_li; ?>" class="l-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( cmb_txt( 'Theo dõi CMB trên LinkedIn', 'Follow CMB on LinkedIn' ) ); ?>" title="CMB LinkedIn">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M3.13 14H0.23V4.65H3.13V14ZM1.67 3.38C0.75 3.38 0 2.62 0 1.7C0 0.76 0.75 0 1.67 0C2.59 0 3.34 0.76 3.34 1.7C3.34 2.62 2.59 3.38 1.67 3.38ZM14 14H11.11V9.45C11.11 8.38 11.09 7 9.61 7C8.11 7 7.87 8.18 7.87 9.37V14H4.97V4.65H7.75V5.93H7.79C8.17 5.19 9.13 4.41 10.56 4.41C13.49 4.41 14.04 6.35 14.04 8.89V14H14Z" fill="currentColor" />
                </svg>
              </a>
              <?php endif; ?>
            </nav>

            <div class="l-footer__copy">
              <?php foreach ( explode( "\n", trim( $footer_copy ) ) as $footer_copy_line ) : ?>
              <p><?php echo esc_html( trim( $footer_copy_line ) ); ?></p>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Column 2: Trụ sở chính -->
          <address class="l-footer__branch" id="footer-branch-hn">
            <h3 class="l-footer__branch-title"><?php echo cmb_txt( 'TRỤ SỞ CHÍNH', 'HEAD OFFICE' ); ?></h3>
            <ul class="l-footer__branch-list" role="list">
              <li class="l-footer__branch-item">
                <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M6 1C3.24 1 1 3.24 1 6C1 9.75 6 14 6 14C6 14 11 9.75 11 6C11 3.24 8.76 1 6 1Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
                  <circle cx="6" cy="6" r="2" stroke="currentColor" stroke-width="1.4" />
                </svg>
                <span><?php echo $branch_hn_addr; ?></span>
              </li>
              <li class="l-footer__branch-item">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M2 2.5H4.5L5.5 5L4 6.5C4.67 7.83 6.17 9.33 7.5 10L9 8.5L11.5 9.5V12C11.5 12.28 11.28 12.5 11 12.5C5.75 12.5 1.5 8.25 1.5 3C1.5 2.72 1.72 2.5 2 2.5Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round" stroke-linecap="round" />
                </svg>
                <span><?php echo $branch_hn_phone; ?></span>
              </li>
              <li class="l-footer__branch-item">
                <svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <rect x="0.7" y="0.7" width="12.6" height="9.6" rx="0.7" stroke="currentColor" stroke-width="1.3" />
                  <path d="M1 1.5L7 6.5L13 1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <a href="mailto:<?php echo $branch_hn_email; ?>" aria-label="<?php echo esc_attr( cmb_txt( 'Email trụ sở chính CMB', 'CMB headquarters email' ) ); ?>"><?php echo $branch_hn_email; ?></a>
              </li>
            </ul>
          </address>

          <!-- Column 3: Chi nhánh Hải Phòng -->
          <address class="l-footer__branch" id="footer-branch-hp">
            <h3 class="l-footer__branch-title"><?php echo cmb_txt( 'CHI NHÁNH HẢI PHÒNG', 'HAI PHONG BRANCH' ); ?></h3>
            <ul class="l-footer__branch-list" role="list">
              <li class="l-footer__branch-item">
                <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M6 1C3.24 1 1 3.24 1 6C1 9.75 6 14 6 14C6 14 11 9.75 11 6C11 3.24 8.76 1 6 1Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
                  <circle cx="6" cy="6" r="2" stroke="currentColor" stroke-width="1.4" />
                </svg>
                <span><?php echo $branch_hp_addr; ?></span>
              </li>
              <li class="l-footer__branch-item">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M2 2.5H4.5L5.5 5L4 6.5C4.67 7.83 6.17 9.33 7.5 10L9 8.5L11.5 9.5V12C11.5 12.28 11.28 12.5 11 12.5C5.75 12.5 1.5 8.25 1.5 3C1.5 2.72 1.72 2.5 2 2.5Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round" stroke-linecap="round" />
                </svg>
                <span><?php echo $branch_hp_phone; ?></span>
              </li>
              <li class="l-footer__branch-item">
                <svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <rect x="0.7" y="0.7" width="12.6" height="9.6" rx="0.7" stroke="currentColor" stroke-width="1.3" />
                  <path d="M1 1.5L7 6.5L13 1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <a href="mailto:<?php echo $branch_hp_email; ?>" aria-label="<?php echo esc_attr( cmb_txt( 'Email chi nhánh Hải Phòng CMB', 'CMB Hai Phong branch email' ) ); ?>"><?php echo $branch_hp_email; ?></a>
              </li>
            </ul>
          </address>

          <!-- Column 4: Chi nhánh TP. HCM -->
          <address class="l-footer__branch" id="footer-branch-hcm">
            <h3 class="l-footer__branch-title"><?php echo cmb_txt( 'CHI NHÁNH TP. HỒ CHÍ MINH', 'HO CHI MINH CITY BRANCH' ); ?></h3>
            <ul class="l-footer__branch-list" role="list">
              <li class="l-footer__branch-item">
                <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M6 1C3.24 1 1 3.24 1 6C1 9.75 6 14 6 14C6 14 11 9.75 11 6C11 3.24 8.76 1 6 1Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
                  <circle cx="6" cy="6" r="2" stroke="currentColor" stroke-width="1.4" />
                </svg>
                <span><?php echo $branch_hcm_addr; ?></span>
              </li>
              <li class="l-footer__branch-item">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M2 2.5H4.5L5.5 5L4 6.5C4.67 7.83 6.17 9.33 7.5 10L9 8.5L11.5 9.5V12C11.5 12.28 11.28 12.5 11 12.5C5.75 12.5 1.5 8.25 1.5 3C1.5 2.72 1.72 2.5 2 2.5Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round" stroke-linecap="round" />
                </svg>
                <span><?php echo $branch_hcm_phone; ?></span>
              </li>
              <li class="l-footer__branch-item">
                <svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <rect x="0.7" y="0.7" width="12.6" height="9.6" rx="0.7" stroke="currentColor" stroke-width="1.3" />
                  <path d="M1 1.5L7 6.5L13 1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <a href="mailto:<?php echo $branch_hcm_email; ?>" aria-label="<?php echo esc_attr( cmb_txt( 'Email chi nhánh TP. HCM CMB', 'CMB Ho Chi Minh City branch email' ) ); ?>"><?php echo $branch_hcm_email; ?></a>
              </li>
            </ul>
          </address>

        </div>
      </div>
    </div>
  </footer>
  <!-- ======= /FOOTER ======= -->

  <!-- ======= FLOATING ACTIONS ======= -->
  <div class="c-float-actions" aria-label="<?php echo esc_attr( cmb_txt( 'Liên hệ nhanh', 'Quick Contact' ) ); ?>">
    <?php if ( $float_zalo && $float_zalo !== '#' ) : ?>
    <a href="<?php echo $float_zalo; ?>" class="c-float-actions__btn" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( cmb_txt( 'Liên hệ Zalo', 'Contact via Zalo' ) ); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/zalo.svg" alt="Zalo" width="32" height="32" />
    </a>
    <?php endif; ?>
    <?php if ( $float_messenger && $float_messenger !== '#' ) : ?>
    <a href="<?php echo $float_messenger; ?>" class="c-float-actions__btn" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( cmb_txt( 'Chat Messenger', 'Chat via Messenger' ) ); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/messenger.svg" alt="Messenger" width="32" height="32" />
    </a>
    <?php endif; ?>
    <?php if ( $float_phone ) : ?>
    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $float_phone ) ); ?>" class="c-float-actions__btn" aria-label="<?php echo esc_attr( cmb_txt( 'Gọi điện', 'Call' ) ); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/phone.svg" alt="Phone" width="32" height="32" />
    </a>
    <?php endif; ?>
    <button class="c-float-actions__btn c-float-actions__btn--top" id="back-to-top" aria-label="<?php echo esc_attr( cmb_txt( 'Lên đầu trang', 'Back to top' ) ); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/back-to-top.svg" alt="" width="20" height="20" />
    </button>
  </div>

<?php wp_footer(); ?>
</body>
</html>
