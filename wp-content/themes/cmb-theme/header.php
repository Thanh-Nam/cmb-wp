<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

  <!-- ======= PRELOADER ======= -->
  <div id="page-preloader" class="preloader" aria-hidden="true">
    <div class="preloader__inner">
      <svg class="preloader__compass" viewBox="0 0 126 126" role="img" aria-label="CMB">
        <circle class="preloader__ring" cx="63" cy="63" r="54" />
        <circle class="preloader__ring preloader__ring--inner" cx="63" cy="63" r="42" />

        <!-- tick chéo góc NE / SE / SW / NW -->
        <g class="preloader__tick preloader__tick--diag">
          <line x1="25" y1="25" x2="31" y2="31" />
          <line x1="101" y1="25" x2="95" y2="31" />
          <line x1="25" y1="101" x2="31" y2="95" />
          <line x1="101" y1="101" x2="95" y2="95" />
        </g>

        <!-- tick nhỏ chia độ, bỏ trống tại 4 hướng chính để nhường chỗ cho chữ -->
        <g class="preloader__tick preloader__tick--fine">
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(11.25 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(22.5 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(33.75 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(56.25 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(67.5 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(78.75 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(101.25 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(112.5 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(123.75 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(146.25 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(157.5 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(168.75 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(191.25 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(202.5 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(213.75 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(236.25 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(247.5 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(258.75 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(281.25 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(292.5 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(303.75 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(326.25 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(337.5 63 63)" />
          <line x1="63" y1="10" x2="63" y2="14" transform="rotate(348.75 63 63)" />
        </g>

        <!-- chữ hướng -->
        <g class="preloader__label">
          <text x="63" y="21" text-anchor="middle">N</text>
          <text x="63" y="110" text-anchor="middle">S</text>
          <text x="17" y="66" text-anchor="middle">W</text>
          <text x="109" y="66" text-anchor="middle">E</text>
        </g>

        <!-- kim mũi tàu, lắc dò hướng -->
        <g class="preloader__needle">
          <path class="preloader__needle-tip" d="M63 26 L70 63 L63 56 L56 63 Z" />
          <circle class="preloader__needle-core" cx="63" cy="63" r="7" />
          <path class="preloader__needle-tail" d="M63 100 L56 66 L63 73 L70 66 Z" />
        </g>

        <circle class="preloader__needle-dot" cx="63" cy="63" r="2.2" />
      </svg>
      <div class="preloader__bar">
        <div class="preloader__line"></div>
      </div>
    </div>
  </div>

  <!-- ======= HEADER ======= -->
  <header class="l-header" id="site-header">
    <div class="l-container">
      <div class="l-header__wrapper">

        <!-- Logo -->
        <div class="l-header__left">
          <?php
          $acf_logo_field = get_field( 'logo', 'option' );
          $acf_logo_url   = is_array( $acf_logo_field ) ? ( $acf_logo_field['url'] ?? '' ) : $acf_logo_field;
          ?>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="l-logo" id="site-logo" title="CMB - Trang chủ">
            <?php if ( $acf_logo_url ) : ?>
            <img src="<?php echo $acf_logo_url; ?>"
              alt="Logo CMB - Công ty Cổ phần Tư vấn Xây dựng Công trình Hàng hải" class="l-logo__image" />
            <?php elseif ( has_custom_logo() ) :
                the_custom_logo();
            else : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Company Logo.svg"
              alt="Logo CMB - Công ty Cổ phần Tư vấn Xây dựng Công trình Hàng hải" class="l-logo__image" />
            <div class="l-logo__text-wrap">
              <span class="l-logo__company">CÔNG TY CỔ PHẦN TƯ VẤN</span>
              <span class="l-logo__slogan">XÂY DỰNG CÔNG TRÌNH HÀNG HẢI</span>
            </div>
            <?php endif; ?>
          </a>
        </div>

        <!-- Right: Topbar + Nav -->
        <div class="l-header__right">

          <!-- Topbar -->
          <div class="l-header__topbar" id="header-topbar">
            <?php
            $hdr_email      = get_field( 'header_email', 'option' ) ?: 'cmbhcm@cmbvn.com.vn';
            $hdr_eoffice    = get_field( 'header_eoffice_url', 'option' ) ?: '#';
            $hdr_eoffice_v1mc = get_field( 'header_eoffice_v1mc_url', 'option' ) ?: '#';
            ?>
            <ul class="l-header__topbar-list" role="list" aria-label="Thông tin liên hệ và ngôn ngữ">
              <li class="l-header__topbar-item" id="topbar-item-email">
                <a href="mailto:<?php echo $hdr_email; ?>" class="l-header__topbar-link" id="topbar-email" aria-label="Gửi email tới <?php echo $hdr_email; ?>">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Email Icon.svg" alt="" role="presentation" class="l-header__topbar-icon" />
                  <span><?php echo $hdr_email; ?></span>
                </a>
              </li>
              <li class="l-header__topbar-item" id="topbar-item-eoffice">
                <a href="<?php echo $hdr_eoffice; ?>" class="l-header__topbar-link" id="topbar-eoffice" title="Truy cập E-Office CMB"<?php echo ( $hdr_eoffice !== '#' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Document Icon.svg" alt="" role="presentation" class="l-header__topbar-icon" />
                  <span>E-Office CMB</span>
                </a>
              </li>
              <li class="l-header__topbar-item" id="topbar-item-eoffice-v1mc">
                <a href="<?php echo $hdr_eoffice_v1mc; ?>" class="l-header__topbar-link" id="topbar-eoffice-v1mc" title="Truy cập E-Office V1MC"<?php echo ( $hdr_eoffice_v1mc !== '#' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Document Icon.svg" alt="" role="presentation" class="l-header__topbar-icon" />
                  <span>E-Office V1MC</span>
                </a>
              </li>
              <li class="l-header__topbar-item" id="topbar-item-lang">
                <?php
                $pll_langs = function_exists( 'pll_the_languages' ) ? pll_the_languages( [ 'raw' => 1, 'show_flags' => 1 ] ) : [];
                $cur_lang  = current( array_filter( $pll_langs, fn( $l ) => $l['current_lang'] ) ) ?: null;
                $cur_slug  = $cur_lang ? strtoupper( $cur_lang['slug'] ) : 'VI';
                $cur_flag  = $cur_lang['flag'] ?? '';
                $alt_langs = array_filter( $pll_langs, fn( $l ) => ! $l['current_lang'] );
                ?>
                <div class="l-header__lang-wrap" id="lang-wrap">
                  <button class="l-header__lang-btn" id="topbar-lang-btn" aria-expanded="false" aria-haspopup="listbox" aria-label="Chuyển đổi ngôn ngữ">
                    <?php if ( $cur_flag ) : ?>
                      <span class="l-header__lang-flag"><?php echo $cur_flag; ?></span>
                    <?php else : ?>
                      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Language Icon.svg" alt="" role="presentation" class="l-header__topbar-icon" />
                    <?php endif; ?>
                    <span><?php echo $cur_slug; ?></span>
                    <svg class="l-header__lang-arrow" width="10" height="6" viewBox="0 0 10 6" fill="none" aria-hidden="true">
                      <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </button>
                  <?php if ( $alt_langs ) : ?>
                  <ul class="l-header__lang-dropdown" role="listbox" aria-label="Chọn ngôn ngữ">
                    <?php foreach ( $alt_langs as $lang ) : ?>
                    <li role="option">
                      <a href="<?php echo $lang['url']; ?>" class="l-header__lang-option" hreflang="<?php echo $lang['slug']; ?>">
                        <?php if ( ! empty( $lang['flag'] ) ) : ?>
                          <span class="l-header__lang-flag"><?php echo $lang['flag']; ?></span>
                        <?php endif; ?>
                        <?php echo esc_html( strtoupper( $lang['slug'] ) ); ?>
                      </a>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                  <?php endif; ?>
                </div>
              </li>
            </ul>
          </div>

          <!-- Nav -->
          <div class="l-header__main" id="header-main">
            <nav class="l-nav" id="site-nav" aria-label="Menu chính">
              <?php
              wp_nav_menu( [
                  'theme_location' => 'primary',
                  'menu_class'     => 'l-nav__list',
                  'container'      => false,
                  'fallback_cb'    => 'cmb_fallback_nav',
                  'walker'         => new CMB_Nav_Walker(),
              ] );
              ?>

              <!-- Mobile info -->
              <div class="l-nav__mobile-info" id="nav-mobile-info" aria-label="Thông tin liên hệ">
                <div class="l-header__lang-wrap l-header__lang-wrap--mobile" id="lang-wrap-mobile">
                  <button class="l-header__lang-btn l-nav__mobile-info-item" id="topbar-lang-btn-mobile" aria-expanded="false" aria-haspopup="listbox" aria-label="Chuyển đổi ngôn ngữ">
                    <?php if ( $cur_flag ) : ?>
                      <span class="l-header__lang-flag"><?php echo $cur_flag; ?></span>
                    <?php else : ?>
                      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Language Icon.svg" alt="" role="presentation" />
                    <?php endif; ?>
                    <span><?php echo $cur_slug; ?></span>
                    <svg class="l-header__lang-arrow l-nav__mobile-info-arrow" width="10" height="6" viewBox="0 0 10 6" fill="none" aria-hidden="true">
                      <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </button>
                  <?php if ( $alt_langs ) : ?>
                  <ul class="l-header__lang-dropdown l-header__lang-dropdown--mobile" role="listbox">
                    <?php foreach ( $alt_langs as $lang ) : ?>
                    <li role="option">
                      <a href="<?php echo $lang['url']; ?>" class="l-header__lang-option" hreflang="<?php echo $lang['slug']; ?>">
                        <?php if ( ! empty( $lang['flag'] ) ) : ?>
                          <span class="l-header__lang-flag"><?php echo $lang['flag']; ?></span>
                        <?php endif; ?>
                        <?php echo esc_html( strtoupper( $lang['slug'] ) ); ?>
                      </a>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                  <?php endif; ?>
                </div>
                <a href="mailto:<?php echo $hdr_email; ?>" class="l-nav__mobile-info-item" aria-label="Email CMB">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Email Icon.svg" alt="" role="presentation" />
                  <?php echo $hdr_email; ?>
                </a>
                <a href="<?php echo $hdr_eoffice; ?>" class="l-nav__mobile-info-item" title="E-Office CMB"<?php echo ( $hdr_eoffice !== '#' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Document Icon.svg" alt="" role="presentation" />
                  E-Office CMB
                </a>
                <a href="<?php echo $hdr_eoffice_v1mc; ?>" class="l-nav__mobile-info-item" title="E-Office V1MC"<?php echo ( $hdr_eoffice_v1mc !== '#' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Document Icon.svg" alt="" role="presentation" />
                  E-Office V1MC
                </a>
              </div>
            </nav>

            <div class="l-nav-overlay" id="nav-overlay" aria-hidden="true"></div>

            <!-- Search (desktop) -->
            <button class="l-header__search-btn" id="header-search-btn" aria-label="Tìm kiếm">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-24px.svg" alt="" role="presentation" />
            </button>

            <!-- Mobile actions -->
            <div class="l-header__mobile-actions">
              <button class="l-header__search-btn" id="header-search-btn-mobile" aria-label="Tìm kiếm">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14.9714 14.9714L19 19M17 11C17 14.3137 14.3137 17 11 17C7.68629 17 5 14.3137 5 11C5 7.68629 7.68629 5 11 5C14.3137 5 17 7.68629 17 11Z" stroke="#ED202E" />
                </svg>
              </button>
              <button class="l-hamburger" id="hamburger-btn" aria-label="Mở menu" aria-expanded="false" aria-controls="site-nav">
                <span></span><span></span><span></span>
              </button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </header>
  <!-- ======= /HEADER ======= -->

  <!-- ======= SEARCH OVERLAY ======= -->
  <div class="l-search-overlay" id="search-overlay" aria-hidden="true" role="search" aria-label="Tìm kiếm">
    <div class="l-search-overlay__backdrop" id="search-overlay-backdrop" aria-hidden="true"></div>

    <button class="l-search-overlay__close" id="search-overlay-close" type="button" aria-label="Đóng tìm kiếm">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>

    <div class="l-search-overlay__panel">
      <div class="l-search-overlay__box">
        <form class="l-search-overlay__form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
          <input class="l-search-overlay__input" type="search" name="s" id="search-overlay-input"
            placeholder="Nhập từ khóa tìm kiếm..." autocomplete="off" aria-label="Từ khóa tìm kiếm" />
          <button class="l-search-overlay__submit" type="submit" aria-label="Tìm kiếm">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M14.9714 14.9714L19 19M17 11C17 14.3137 14.3137 17 11 17C7.68629 17 5 14.3137 5 11C5 7.68629 7.68629 5 11 5C14.3137 5 17 7.68629 17 11Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </div>
  <!-- ======= /SEARCH OVERLAY ======= -->

  <main class="site-main" id="main-content">
<?php

// Fallback nav khi chưa set menu trong WP Admin
function cmb_fallback_nav() { ?>
  <ul class="l-nav__list" role="list">
    <li class="l-nav__item"><a href="<?php echo home_url('/'); ?>" class="l-nav__link">Trang chủ</a></li>
    <li class="l-nav__item"><a href="<?php echo home_url('/gioi-thieu'); ?>" class="l-nav__link">Giới thiệu</a></li>
    <li class="l-nav__item has-dropdown">
      <span class="l-nav__link">Năng lực
        <svg class="l-nav__dropdown-arrow" width="10" height="6" viewBox="0 0 10 6" fill="none" aria-hidden="true">
          <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      <ul class="l-nav__dropdown">
        <li><a href="<?php echo home_url('/du-an'); ?>">Dự án tiêu biểu</a></li>
        <li><a href="<?php echo home_url('/thiet-bi'); ?>">Thiết bị</a></li>
        <li><a href="<?php echo home_url('/phong-thi-nghiem'); ?>">Phòng thí nghiệm</a></li>
        <li><a href="<?php echo home_url('/quan-he-co-dong'); ?>">Quan hệ cổ đông</a></li>
      </ul>
    </li>
    <li class="l-nav__item"><a href="<?php echo home_url('/tin-tuc'); ?>" class="l-nav__link">Tin tức</a></li>
    <li class="l-nav__item"><a href="https://cmb-recruitment.vercel.app/" target="_blank" class="l-nav__link">Tuyển dụng</a></li>
    <li class="l-nav__item"><a href="<?php echo home_url('/lien-he'); ?>" class="l-nav__link">Liên hệ</a></li>
  </ul>
<?php }
