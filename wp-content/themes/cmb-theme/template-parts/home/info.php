<?php
/**
 * template-parts/home/info.php
 * Section: Info Stats / Count-up
 */
$info_title   = cmb_get_option( 'info_title' );
$info_slogan  = cmb_get_option( 'info_slogan' );
$info_content = cmb_get_option( 'info_content' );
$info_items   = get_field( 'info_item', 'option' );
?>
<!-- ======= INFO ======= -->
<section class="p-info" id="info" aria-label="Giới thiệu chung">

  <!-- Background: cùng cấp container, absolute full-width, không bị giới hạn bởi container -->
  <div class="p-info__bg" aria-hidden="true"></div>

  <div class="l-container">
    <div class="p-info__card" data-reveal="fade-up">
      <div class="p-info__inner">

        <!-- Left: content -->
        <div class="p-info__left">
          <?php if ( $info_slogan ) : ?>
            <span class="c-section-label c-section-label--white p-info__label"><?php echo $info_title; ?></span>
          <?php endif; ?>
          <?php if ( $info_title ) : ?>
            <h2 class="c-section-title c-section-title--white p-info__title"><?php echo $info_slogan; ?></h2>
          <?php endif; ?>
          <?php if ( $info_content ) : ?>
            <p class="p-info__desc"><?php echo $info_content; ?></p>
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
                  <img src="<?php echo $stat['icon']['url']; ?>"
                       alt=""
                       role="presentation"
                       class="p-info__stat-icon"
                       loading="lazy" />
                <?php endif; ?>
                <div class="p-info__stat-body">
                  <?php if ( isset( $stat['number'] ) && $stat['number'] !== '' ) : ?>
                    <span class="p-info__stat-number"><?php echo $stat['number']; ?></span>
                  <?php endif; ?>
                  <?php if ( ! empty( $stat['content'] ) || ! empty( $stat['content_en'] ) ) : ?>
                    <span class="p-info__stat-label"><?php echo wp_kses_post( cmb_arr( $stat, 'content' ) ); ?></span>
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
