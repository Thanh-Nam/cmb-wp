<?php
/**
 * Template: Single — Dự án chi tiết
 * Post type: du-an
 */
get_header(); ?>

<main class="site-main" id="main-content">
  <?php
  get_template_part('template-parts/du-an/single-hero');
  get_template_part('template-parts/du-an/single-infobar');
  get_template_part('template-parts/du-an/single-detail');
  get_template_part('template-parts/du-an/single-related');
  ?>
</main>

<?php get_footer();
