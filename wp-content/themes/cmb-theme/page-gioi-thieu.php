<?php
/**
 * Template Name: Giới thiệu
 */
get_header(); ?>

<main class="site-main" id="main-content">
  <?php
  get_template_part('template-parts/gioi-thieu/intro');
  get_template_part('template-parts/gioi-thieu/stats');
  get_template_part('template-parts/gioi-thieu/vision');
  get_template_part('template-parts/gioi-thieu/values');
  get_template_part('template-parts/gioi-thieu/leadership');
  get_template_part('template-parts/gioi-thieu/achievements');
  get_template_part('template-parts/section-partner');
  ?>
</main>

<?php get_footer();
