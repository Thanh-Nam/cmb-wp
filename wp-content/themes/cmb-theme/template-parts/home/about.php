<?php
/**
 * template-parts/home/about.php
 * Section: About / CEO Quote
 */
$about_sub_title = cmb_get_option( 'about_sub_title' );
$about_title     = cmb_get_option( 'about_title' );
$about_content   = cmb_get_option( 'about_content' );
$about_link      = get_field( 'about_link', 'option' );
$about_img       = get_field( 'about_img', 'option' );
$about_name      = cmb_get_option( 'about_name' );
$about_position  = cmb_get_option( 'about_position' );
?>
<!-- ======= ABOUT ======= -->
<section class="p-about" id="about">
  <div class="l-container">

    <!-- Header -->
    <div class="p-about__header" data-reveal="fade-up">
      <?php if ( $about_sub_title ) : ?>
        <span class="c-section-label c-section-label--center"><?php echo $about_sub_title; ?></span>
      <?php endif; ?>
      <?php if ( $about_title ) : ?>
        <h2 class="c-section-title p-about__title"><?php echo $about_title; ?></h2>
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
            <?php echo $about_content; ?>
          </blockquote>
        <?php endif; ?>

        <!-- Author info -->
        <?php if ( $about_name || $about_position ) : ?>
          <div class="p-about__author">
            <span class="p-about__author-line" aria-hidden="true"></span>
            <div class="p-about__author-info">
              <?php if ( $about_name ) : ?>
                <cite class="p-about__author-name"><?php echo $about_name; ?></cite>
              <?php endif; ?>
              <?php if ( $about_position ) : ?>
                <span class="p-about__author-title"><?php echo $about_position; ?></span>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- Button -->
        <?php if ( $about_link ) : ?>
          <div class="p-about__action">
            <a href="<?php echo $about_link; ?>" class="c-btn p-about__btn" id="btn-about-more">
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
            <img src="<?php echo $about_img['url']; ?>"
                 alt="<?php echo esc_attr( $about_img['alt'] ?: $about_name ); ?>"
                 class="p-about__ceo-img" loading="lazy" />
          </div>
        </div>
      <?php endif; ?>

    </div>

  </div>
</section>
<!-- ======= /ABOUT ======= -->
