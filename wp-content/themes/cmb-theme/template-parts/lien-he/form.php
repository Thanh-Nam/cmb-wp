<?php
/**
 * template-parts/lien-he/form.php
 * Section: Contact Form
 */
$cf7_html = '';
$cf7_query = new WP_Query([
    'post_type'      => 'wpcf7_contact_form',
    'posts_per_page' => 1,
    'no_found_rows'  => true,
    'fields'         => 'ids',
]);
if ($cf7_query->have_posts()) {
    $cf7_id   = $cf7_query->posts[0];
    $cf7_html = do_shortcode('[contact-form-7 id="' . $cf7_id . '"]');
    wp_reset_postdata();
}
$privacy_url = function_exists('get_privacy_policy_url') && get_privacy_policy_url()
    ? get_privacy_policy_url()
    : '#';
?>
<!-- ======= CONTACT FORM ======= -->
<section class="p-lh-body" id="gui-thong-tin" aria-labelledby="form-title">
  <div class="l-container">
    <div class="p-lh-body__grid">

      <!-- Left: Image -->
      <div class="p-lh-body__image" data-reveal="fade-right">
        <?php if (has_post_thumbnail()) :
          the_post_thumbnail('large', ['class' => 'p-lh-body__img', 'alt' => 'Liên hệ CMB', 'loading' => 'lazy']);
        else : ?>
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
               alt="Tàu hàng tại cảng biển Việt Nam"
               class="p-lh-body__img"
               loading="lazy" />
        <?php endif; ?>
      </div>

      <!-- Right: Form -->
      <div class="p-lh-body__form-wrap" data-reveal="fade-left">
        <h2 id="form-title" class="p-lh-body__form-title">GỬI THÔNG TIN LIÊN HỆ</h2>

          <div class="p-lh-cf7-wrap"><?php the_content(); ?></div>
        
      </div>

    </div>
  </div>
</section>
<!-- ======= /CONTACT FORM ======= -->
