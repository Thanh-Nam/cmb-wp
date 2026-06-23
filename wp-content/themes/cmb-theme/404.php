<?php get_header(); ?>

  <div class="l-container" style="padding: 120px 0; text-align: center;">
    <h1 style="font-size: 80px; color: #ED202E;">404</h1>
    <p style="font-size: 20px; margin-bottom: 32px;">Trang bạn tìm không tồn tại.</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;padding:12px 32px;background:#ED202E;color:#fff;text-decoration:none;border-radius:4px;">
      Về trang chủ
    </a>
  </div>

<?php get_footer();
