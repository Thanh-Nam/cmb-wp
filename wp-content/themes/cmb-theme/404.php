<?php get_header(); ?>

  <div class="l-container" style="padding: 120px 0; text-align: center;">
    <h1 style="font-size: 80px; color: #ED202E;">404</h1>
    <p style="font-size: 20px; margin-bottom: 32px;"><?php echo cmb_txt( 'Trang bạn tìm không tồn tại.', 'The page you are looking for does not exist.' ); ?></p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;padding:12px 32px;background:#ED202E;color:#fff;text-decoration:none;border-radius:4px;">
      <?php echo cmb_txt( 'Về trang chủ', 'Back to Home' ); ?>
    </a>
  </div>

<?php get_footer();
