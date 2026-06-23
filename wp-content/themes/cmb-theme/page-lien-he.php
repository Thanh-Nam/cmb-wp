<?php
/**
 * Template Name: Liên hệ
 */
get_header();
while (have_posts()) : the_post(); ?>

  <main class="site-main" id="main-content">
    <?php
    get_template_part('template-parts/lien-he/hero');
    get_template_part('template-parts/lien-he/infobar');
    get_template_part('template-parts/lien-he/form');
    get_template_part('template-parts/lien-he/offices');
    ?>
  </main>

<?php endwhile;
get_footer();
