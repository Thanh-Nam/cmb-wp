<?php
/**
 * Template Name: Giới thiệu
 */
get_header(); ?>

<main class="site-main" id="main-content">
  <?php
  get_template_part('template-parts/gioi-thieu/intro');
  get_template_part('template-parts/gioi-thieu/stats');
  ?>
  <div class="p-video-profile-row">
    <?php
    get_template_part('template-parts/gioi-thieu/video-intro');
    get_template_part('template-parts/gioi-thieu/profile-book');
    ?>
  </div>
  <?php
  get_template_part('template-parts/gioi-thieu/vision');
  get_template_part('template-parts/gioi-thieu/values');
  get_template_part('template-parts/gioi-thieu/leadership');
  get_template_part('template-parts/gioi-thieu/achievements');
  get_template_part('template-parts/section-partner');
  ?>
</main>

<?php get_footer();
