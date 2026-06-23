<?php
/**
 * Template: Archive — Thiết bị khảo sát (CPT archive)
 * Post type: thiet-bi
 */
get_header(); ?>

<main class="site-main" id="main-content">
  <?php
  get_template_part('template-parts/thiet-bi/hero');
  get_template_part('template-parts/thiet-bi/stats');
  get_template_part('template-parts/thiet-bi/list');
  ?>
</main>

<?php get_footer(); ?>
