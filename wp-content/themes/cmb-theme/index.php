<?php get_header(); ?>

  <!-- ======= MAIN ======= -->
  <main class="site-main" id="main-content">
    <?php
    get_template_part('template-parts/home/hero');
    get_template_part('template-parts/home/about');
    get_template_part('template-parts/home/history');
    get_template_part('template-parts/home/info');
    get_template_part('template-parts/home/field');
    get_template_part('template-parts/home/location');
    get_template_part('template-parts/home/project');
    get_template_part('template-parts/home/news');
    get_template_part('template-parts/section-partner');
    ?>
  </main>
  <!-- ======= /MAIN ======= -->

<?php get_footer(); ?>
