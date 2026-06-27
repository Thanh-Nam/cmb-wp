<?php
/**
 * template-parts/gioi-thieu/intro.php
 * Section: CMB Intro Banner
 */
$bg      = get_field('about_banner_bg',   'option');
$logo    = get_field('about_banner_logo', 'option');
$slogan  = cmb_get_option( 'about_banner_slogan' );
$title   = cmb_get_option( 'about_banner_title' );
$content = cmb_get_option( 'about_banner_content' );
?>
<!-- ======= CMB INTRO ======= -->
<section class="p-cmb-intro" id="cmb-intro" aria-label="Hơn nửa thế kỷ đồng hành">

  <div class="p-cmb-intro__bg" aria-hidden="true">
    <img src="<?php echo esc_url($bg['url'] ?? get_template_directory_uri() . '/assets/images/hero_port.jpg'); ?>"
         alt="" role="presentation" loading="lazy" />
  </div>
  <div class="p-cmb-intro__overlay" aria-hidden="true"></div>

  <div class="l-container">
    <div class="p-cmb-intro__inner">
      <div class="p-cmb-intro__body" data-reveal="fade-up">
        <?php if ($logo) : ?>
        <div class="p-cmb-intro__logo-wrap" aria-hidden="true">
          <img src="<?php echo esc_url($logo['url']); ?>" alt="CMB" class="p-cmb-intro__logo" loading="lazy" />
        </div>
        <?php endif; ?>
        <?php if ($slogan) : ?>
        <p class="p-cmb-intro__tagline"><?php echo esc_html($slogan); ?></p>
        <?php endif; ?>
        <?php if ($title) : ?>
        <h2 class="p-cmb-intro__title"><?php echo nl2br(esc_html($title)); ?></h2>
        <?php endif; ?>
        <?php if ($content) : ?>
        <div class="p-cmb-intro__text"><?php echo wp_kses_post($content); ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</section>
<!-- ======= /CMB INTRO ======= -->
