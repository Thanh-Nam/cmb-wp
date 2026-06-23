<?php
/**
 * Template: Archive — Dự án (CPT archive)
 * Post type: du-an
 */
get_header(); ?>

<main class="site-main" id="main-content">
  <?php
  get_template_part('template-parts/du-an/archive-hero');
  get_template_part('template-parts/du-an/archive-stats');
  get_template_part('template-parts/du-an/archive-featured');
  get_template_part('template-parts/du-an/archive-filter');
  get_template_part('template-parts/du-an/archive-list');
  ?>
</main>

<?php get_footer();
