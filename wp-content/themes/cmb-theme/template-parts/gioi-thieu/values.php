<?php
/**
 * template-parts/gioi-thieu/values.php
 * Section: Core Values (Giá trị cốt lõi)
 */
?>
<!-- ======= CORE VALUES ======= -->
<section class="p-values" id="values" aria-label="Giá trị cốt lõi CMB">
  <div class="l-container">
    <div class="p-values__frame" data-reveal="fade-up">

      <svg class="p-values__border" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 100"
           preserveAspectRatio="none" aria-hidden="true">
        <defs>
          <linearGradient id="pvFG1R" gradientUnits="userSpaceOnUse" x1="425" y1="0" x2="395" y2="0">
            <stop offset="0%" stop-color="white" /><stop offset="100%" stop-color="black" />
          </linearGradient>
          <linearGradient id="pvFG1L" gradientUnits="userSpaceOnUse" x1="355" y1="0" x2="325" y2="0">
            <stop offset="0%" stop-color="black" /><stop offset="100%" stop-color="white" />
          </linearGradient>
          <linearGradient id="pvFG2R" gradientUnits="userSpaceOnUse" x1="275" y1="0" x2="245" y2="0">
            <stop offset="0%" stop-color="white" /><stop offset="100%" stop-color="black" />
          </linearGradient>
          <linearGradient id="pvFG2L" gradientUnits="userSpaceOnUse" x1="205" y1="0" x2="175" y2="0">
            <stop offset="0%" stop-color="black" /><stop offset="100%" stop-color="white" />
          </linearGradient>
          <mask id="pvMask" maskUnits="userSpaceOnUse" x="-10" y="90" width="620" height="20">
            <rect x="-10" y="90" width="620" height="20" fill="white" />
            <rect x="355" y="90" width="40" height="20" fill="black" />
            <rect x="395" y="90" width="30" height="20" fill="url(#pvFG1R)" />
            <rect x="325" y="90" width="30" height="20" fill="url(#pvFG1L)" />
            <rect x="205" y="90" width="40" height="20" fill="black" />
            <rect x="245" y="90" width="30" height="20" fill="url(#pvFG2R)" />
            <rect x="175" y="90" width="30" height="20" fill="url(#pvFG2L)" />
          </mask>
        </defs>
        <path d="M 8 0 Q 300 -2.5 592 0 Q 600 0 600 8 Q 602.5 50 600 92 Q 600 100 592 100 M 8 100 Q 0 100 0 92 Q -2.5 50 0 8 Q 0 0 8 0"
              fill="none" stroke="#0379CC" stroke-opacity="0.4" stroke-width="1.5" vector-effect="non-scaling-stroke" />
        <path d="M 592 100 Q 300 102.5 8 100"
              fill="none" stroke="#0379CC" stroke-opacity="0.4" stroke-width="1.5"
              vector-effect="non-scaling-stroke" mask="url(#pvMask)" />
      </svg>

      <div class="p-values__title-bar">
        <h2 class="p-values__title"><?php echo esc_html( cmb_get_option( 'about_value_title' ) ?: 'GIÁ TRỊ CỐT LÕI' ); ?></h2>
      </div>

      <div class="p-values__grid">
        <?php if (have_rows('about_value_list', 'option')) : while (have_rows('about_value_list', 'option')) : the_row();
          $icon    = get_sub_field('icon');
          $title   = cmb_sub('title');
          $content = cmb_sub('content');
        ?>
        <div class="p-values__item">
          <?php if ($icon) : ?>
          <div class="p-values__icon" aria-hidden="true">
            <img src="<?php echo $icon['url']; ?>" alt="" loading="lazy" />
          </div>
          <?php endif; ?>
          <h3 class="p-values__name"><?php echo $title; ?></h3>
          <p class="p-values__desc"><?php echo $content; ?></p>
        </div>
        <?php endwhile; endif; ?>
      </div>

    </div>
  </div>
</section>
<!-- ======= /CORE VALUES ======= -->
