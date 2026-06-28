<?php
/**
 * Template: Archive — Phần mềm (CPT archive)
 * Post type: phan-mem
 */
get_header(); ?>

<main class="site-main" id="main-content">
  <?php
  get_template_part('template-parts/phan-mem/hero');
  get_template_part('template-parts/phan-mem/stats');
  get_template_part('template-parts/phan-mem/list');
  ?>
</main>

<?php get_footer(); ?>
