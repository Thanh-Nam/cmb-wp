<?php
/**
 * Template: Search Results
 * Searches across all post types registered in pre_get_posts (functions.php)
 */
get_header();

$search_query = get_search_query();
$paged        = get_query_var( 'paged' ) ?: 1;
$search_q     = $GLOBALS['wp_query'];
?>

  <!-- ======= PAGE HERO ======= -->
  <section class="p-page-hero" id="search-hero" aria-label="<?php echo esc_attr( cmb_txt( 'Kết quả tìm kiếm', 'Search results' ) ); ?>">

    <div class="p-page-hero__image-side">
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero_port.jpg"
        alt="<?php echo esc_attr( cmb_txt( 'Tìm kiếm', 'Search' ) ); ?>"
        class="p-page-hero__image"
        loading="eager" />
    </div>

    <div class="p-page-hero__fade" aria-hidden="true"></div>

    <div class="l-container">
      <nav class="p-page-hero__breadcrumb" aria-label="<?php echo esc_attr( cmb_txt( 'Đường dẫn', 'Breadcrumb' ) ); ?>">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo cmb_txt( 'Trang chủ', 'Home' ); ?></a>
        <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
        <span class="p-page-hero__breadcrumb-current" aria-current="page"><?php echo cmb_txt( 'Tìm kiếm', 'Search' ); ?></span>
      </nav>

      <div class="p-page-hero__content">
        <h1 class="p-page-hero__title"><?php echo cmb_txt( 'KẾT QUẢ TÌM KIẾM', 'SEARCH RESULTS' ); ?></h1>
        <?php if ( $search_query ) : ?>
        <p class="p-page-hero__subtitle">
          <?php echo cmb_txt( 'Từ khóa:', 'Keyword:' ); ?> <strong>"<?php echo $search_query; ?>"</strong>
          &mdash; <?php echo intval( $search_q->found_posts ); ?> <?php echo cmb_txt( 'kết quả', 'results' ); ?>
        </p>
        <?php endif; ?>
      </div>
    </div>

  </section>
  <!-- ======= /PAGE HERO ======= -->


  <!-- ======= SEARCH RESULTS ======= -->
  <section class="p-search-results" id="search-results-section" aria-label="<?php echo esc_attr( cmb_txt( 'Kết quả tìm kiếm', 'Search results' ) ); ?>">
    <div class="l-container">

      <!-- Results list -->
      <div class="p-search-results__list" id="search-results-list">
        <?php if ( have_posts() ) :
          while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/search-item' );
          endwhile;
          wp_reset_postdata();
        else : ?>
        <div class="p-search-results__empty">
          <p><?php echo cmb_txt( 'Không tìm thấy kết quả nào cho từ khóa', 'No results found for' ); ?> <strong>"<?php echo $search_query; ?>"</strong>.</p>
          <p><?php echo cmb_txt( 'Thử tìm với từ khóa khác hoặc', 'Try searching with a different keyword or' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo cmb_txt( 'quay về trang chủ', 'return to the homepage' ); ?></a>.</p>
        </div>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <nav class="p-news-all__pagination" aria-label="<?php echo esc_attr( cmb_txt( 'Phân trang kết quả', 'Results pagination' ) ); ?>">
        <?php
        $max = $search_q->max_num_pages;
        if ( $max > 1 ) {
            $links = paginate_links( [
                'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => $max,
                'type'      => 'array',
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ] );
            if ( $links ) {
                foreach ( $links as $link ) {
                    $link = str_replace( 'class="prev page-numbers"',     'class="p-news-all__page-btn" aria-label="' . esc_attr( cmb_txt( 'Trang trước', 'Previous page' ) ) . '"', $link );
                    $link = str_replace( 'class="next page-numbers"',     'class="p-news-all__page-btn" aria-label="' . esc_attr( cmb_txt( 'Trang tiếp', 'Next page' ) ) . '"',  $link );
                    $link = str_replace( 'class="page-numbers current"',  'class="p-news-all__page-btn is-active" aria-current="page"', $link );
                    $link = str_replace( 'class="page-numbers dots"',     'class="p-news-all__page-btn p-news-all__page-btn--dots"', $link );
                    $link = str_replace( 'class="page-numbers"',          'class="p-news-all__page-btn"', $link );
                    echo $link;
                }
            }
        }
        ?>
      </nav>

    </div>
  </section>
  <!-- ======= /SEARCH RESULTS ======= -->

<?php get_footer(); ?>
