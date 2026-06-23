<?php
/**
 * Template: Page mặc định
 */
get_header(); ?>

  <?php while ( have_posts() ) : the_post(); ?>
    <?php get_template_part( 'template-parts/page-hero' ); ?>
    <div class="l-container" style="padding-top: 60px; padding-bottom: 80px;">
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </div>
  <?php endwhile; ?>

<?php get_footer();
