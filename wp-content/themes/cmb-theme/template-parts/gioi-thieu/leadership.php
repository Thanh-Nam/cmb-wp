<?php
/**
 * template-parts/gioi-thieu/leadership.php
 * Section: Leadership (Ban lãnh đạo)
 */
$leadership_title    = cmb_get_option( 'about_leadership_title' );
$leadership_subtitle = cmb_get_option( 'about_leadership_subtitle' );
$leadership_list     = get_field('about_leadership_list', 'option');
?>
<!-- ======= LEADERSHIP ======= -->
<section class="p-leadership" id="leadership" aria-label="Ban lãnh đạo CMB">

  <div class="p-leadership__bg" aria-hidden="true">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bg-bld.png"
         alt="" role="presentation" loading="lazy" />
  </div>

  <div class="l-container">

    <div class="p-leadership__header" data-reveal="fade-up">
      <div class="p-leadership__title-wrap">
        <span class="p-leadership__title-line" aria-hidden="true"></span>
        <h2 class="p-leadership__title"><?php echo esc_html($leadership_title ?: 'BAN LÃNH ĐẠO'); ?></h2>
        <span class="p-leadership__title-line" aria-hidden="true"></span>
      </div>
      <?php if ($leadership_subtitle) : ?>
      <p class="p-leadership__subtitle"><?php echo esc_html($leadership_subtitle); ?></p>
      <?php endif; ?>
    </div>

    <div class="p-leadership__slider-wrap">
      <div class="swiper p-leadership__swiper" id="leadership-swiper">
        <div class="swiper-wrapper">

          <?php if (!empty($leadership_list)) :
            foreach ($leadership_list as $member) :
              $m_name     = cmb_arr( $member, 'name' );
              $m_position = cmb_arr( $member, 'position' );
            ?>
          <div class="swiper-slide">
            <div class="p-leadership__card">
              <div class="p-leadership__photo-outer">
                <div class="p-leadership__photo-wrap">
                  <img src="<?php echo esc_url($member['img']['url']); ?>"
                       alt="<?php echo esc_attr( $m_name . ' - ' . $m_position ); ?>"
                       class="p-leadership__photo" loading="lazy" />
                </div>
              </div>
              <h3 class="p-leadership__name"><?php echo esc_html( $m_name ); ?></h3>
              <p class="p-leadership__role"><?php echo esc_html( $m_position ); ?></p>
              <div class="p-leadership__underline"></div>
            </div>
          </div>
          <?php endforeach;
            foreach ($leadership_list as $member) :
              $m_name     = cmb_arr( $member, 'name' );
              $m_position = cmb_arr( $member, 'position' );
            ?>
          <div class="swiper-slide" aria-hidden="true">
            <div class="p-leadership__card">
              <div class="p-leadership__photo-outer">
                <div class="p-leadership__photo-wrap">
                  <img src="<?php echo esc_url($member['img']['url']); ?>" alt=""
                       class="p-leadership__photo" loading="lazy" />
                </div>
              </div>
              <h3 class="p-leadership__name"><?php echo esc_html( $m_name ); ?></h3>
              <p class="p-leadership__role"><?php echo esc_html( $m_position ); ?></p>
              <div class="p-leadership__underline"></div>
            </div>
          </div>
          <?php endforeach; endif; ?>

        </div>
      </div>
    </div>

  </div>
</section>
<!-- ======= /LEADERSHIP ======= -->
