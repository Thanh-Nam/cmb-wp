<?php
/**
 * CMB Theme — functions.php
 */

// ============================================================
// CUSTOM NAV WALKER
// ============================================================
class CMB_Nav_Walker extends Walker_Nav_Menu {

    private $arrow_svg = '<svg class="l-nav__dropdown-arrow" width="10" height="6" viewBox="0 0 10 6" fill="none" aria-hidden="true"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="l-nav__dropdown">';
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '</ul>';
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $has_children = in_array( 'menu-item-has-children', (array) $item->classes );
        $is_current   = in_array( 'current-menu-item', (array) $item->classes )
                     || in_array( 'current-menu-ancestor', (array) $item->classes )
                     || in_array( 'current-menu-parent', (array) $item->classes );

        // Build <li> classes — dropdown children get no class
        if ( $depth === 0 ) {
            $li_classes = ['l-nav__item'];
            if ( $has_children ) $li_classes[] = 'has-dropdown';
            if ( $is_current )   $li_classes[] = 'is-active';
            $output .= '<li class="' . esc_attr( implode( ' ', $li_classes ) ) . '">';
        } else {
            $output .= '<li>';
        }

        // Build extra attributes (target, rel, title)
        $attrs = '';
        if ( ! empty( $item->target ) )     $attrs .= ' target="' . $item->target . '"';
        if ( ! empty( $item->xfn ) )        $attrs .= ' rel="' . $item->xfn . '"';
        if ( ! empty( $item->attr_title ) ) $attrs .= ' title="' . $item->attr_title . '"';

        $url   = $item->url;
        $title = $item->title;

        if ( $depth === 0 && $has_children ) {
            // Top-level dropdown trigger: use <span> (not a link)
            $output .= '<span class="l-nav__link">' . $title . $this->arrow_svg . '</span>';
        } elseif ( $depth === 0 ) {
            $output .= '<a href="' . $url . '" class="l-nav__link"' . $attrs . '>' . $title . '</a>';
        } else {
            // Dropdown child
            $output .= '<a href="' . $url . '"' . $attrs . '>' . $title . '</a>';
        }
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}

// ============================================================
// THEME SETUP
// ============================================================
function cmb_theme_setup() {
    load_theme_textdomain( 'cmb-theme', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ] );

    // Nav menus
    register_nav_menus( [
        'primary' => __( 'Menu chính', 'cmb-theme' ),
        'footer'  => __( 'Menu footer', 'cmb-theme' ),
    ] );
}
add_action( 'after_setup_theme', 'cmb_theme_setup' );

// ============================================================
// ENQUEUE SCRIPTS & STYLES
// ============================================================
function cmb_enqueue_assets() {
    $ver = wp_get_theme()->get( 'Version' );
    $uri = get_template_directory_uri();

    // CSS — luôn load (dùng filemtime để bust cache khi file thay đổi)
    $main_css = get_template_directory() . '/assets/css/main.css';
    wp_enqueue_style( 'cmb-main', $uri . '/assets/css/main.css', [], file_exists( $main_css ) ? filemtime( $main_css ) : $ver );

    // Google Fonts: dùng local nếu có (assets/css/fonts.css), fallback CDN
    $local_fonts = get_template_directory() . '/assets/css/fonts.css';
    if ( file_exists( $local_fonts ) ) {
        wp_enqueue_style( 'cmb-fonts', $uri . '/assets/css/fonts.css', [], $ver );
    } else {
        // Preconnect để giảm DNS lookup time khi còn dùng CDN
        add_action( 'wp_head', function() {
            echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
            echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        }, 1 );
        wp_enqueue_style( 'cmb-fonts', 'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Dancing+Script:wght@700&family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500&display=swap', [], null );
    }

    // Global JS — mọi trang (dùng filemtime để bust cache khi file thay đổi)
    $global_js = get_template_directory() . '/assets/js/global.js';
    wp_enqueue_script( 'cmb-global', $uri . '/assets/js/global.js', [], filemtime( $global_js ), true );

    // Trang chủ (homepage)
    if ( is_front_page() ) {
        wp_enqueue_style( 'swiper', $uri . '/assets/css/swiper.min.css', [], '11.0.0' );
        wp_enqueue_script( 'swiper', $uri . '/assets/js/vendors/swiper.min.js', [], '11.0.0', true );
        wp_enqueue_script( 'cmb-hero-slider',    $uri . '/assets/js/modules/hero-slider.js',    ['swiper', 'cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-history',        $uri . '/assets/js/modules/history.js',        ['cmb-global'],           $ver, true );
        wp_enqueue_script( 'cmb-location-map',   $uri . '/assets/js/modules/location-map.js',   ['swiper', 'cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-field-swiper',   $uri . '/assets/js/modules/field-swiper.js',   ['swiper', 'cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-project-filter', $uri . '/assets/js/modules/project-filter.js', ['cmb-global'],           $ver, true );
        wp_enqueue_script( 'cmb-stat-counter',   $uri . '/assets/js/modules/stat-counter.js',   ['cmb-global'],           $ver, true );
        wp_enqueue_script( 'cmb-news-swiper',    $uri . '/assets/js/modules/news-swiper.js',    ['swiper', 'cmb-global'], $ver, true );
    }

    // Trang giới thiệu
    if ( is_page( 'gioi-thieu' ) ) {
        wp_enqueue_style( 'swiper', $uri . '/assets/css/swiper.min.css', [], '11.0.0' );
        wp_enqueue_script( 'swiper', $uri . '/assets/js/vendors/swiper.min.js', [], '11.0.0', true );
        wp_enqueue_script( 'cmb-leadership',   $uri . '/assets/js/modules/leadership-swiper.js', ['swiper', 'cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-stat-counter', $uri . '/assets/js/modules/stat-counter.js',      ['cmb-global'],           $ver, true );

        // Video giới thiệu — dùng player mặc định của trình duyệt (controls gốc);
        // riêng ảnh che (poster) tự chụp khung hình đầu + icon play vẫn cần 1 script
        // nhỏ vì trình duyệt không tự vẽ khung hình đầu khi chỉ preload="metadata".
        wp_enqueue_script( 'cmb-video-poster', $uri . '/assets/js/modules/video-poster.js', ['cmb-global'], $ver, true );

        // Hồ sơ năng lực — nhúng bằng plugin "3D FlipBook" (DearFlip), shortcode [dflip]
        // được gọi trực tiếp trong profile-book.php, script/style của plugin tự enqueue.

        // Book loader thuần CSS thay cho spinner mặc định của DearFlip — xem
        // assets/js/modules/profile-book-loader.js + _profile-book.scss.
        wp_enqueue_script( 'cmb-profile-book-loader', $uri . '/assets/js/modules/profile-book-loader.js', ['cmb-global'], $ver, true );

        // Đồng bộ chiều cao 2 khung Video giới thiệu / Hồ sơ năng lực cho cân đối
        wp_enqueue_script( 'cmb-video-profile-sync', $uri . '/assets/js/modules/video-profile-sync.js', ['cmb-global'], $ver, true );
    }

    // Trang liên hệ
    if ( is_page( 'lien-he' ) ) {
        wp_enqueue_script( 'cmb-form-validation', $uri . '/assets/js/modules/form-validation.js', ['cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-google-map',      $uri . '/assets/js/modules/google-map.js',      ['cmb-global'], $ver, true );
    }

    // Archive tin tức
    if ( is_home() || ( is_archive() && get_post_type() === 'post' ) ) {
        wp_enqueue_style( 'swiper', $uri . '/assets/css/swiper.min.css', [], '11.0.0' );
        wp_enqueue_script( 'swiper', $uri . '/assets/js/vendors/swiper.min.js', [], '11.0.0', true );
        wp_enqueue_script( 'cmb-news-swiper', $uri . '/assets/js/modules/news-swiper.js', ['swiper', 'cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-news-filter', $uri . '/assets/js/modules/news-filter.js', ['cmb-global'],           $ver, true );
    }

    // Single tin tức
    if ( is_singular( 'post' ) ) {
        wp_enqueue_script( 'cmb-gallery-lightbox', $uri . '/assets/js/modules/gallery-lightbox.js', ['cmb-global'], $ver, true );
    }

    // Archive thiết bị
    if ( is_post_type_archive( 'thiet-bi' ) ) {
        wp_enqueue_script( 'cmb-equipment-modal', $uri . '/assets/js/modules/equipment-modal.js', ['cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-stat-counter',    $uri . '/assets/js/modules/stat-counter.js',    ['cmb-global'], $ver, true );
    }

    // Archive phần mềm
    if ( is_post_type_archive( 'phan-mem' ) ) {
        wp_enqueue_script( 'cmb-software-modal', $uri . '/assets/js/modules/software-modal.js', ['cmb-global'], $ver, true );
    }

    // Archive / single dự án
    if ( is_post_type_archive( 'du-an' ) || is_singular( 'du-an' ) ) {
        wp_enqueue_script( 'cmb-project-filter', $uri . '/assets/js/modules/project-filter.js', ['cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-stat-counter',   $uri . '/assets/js/modules/stat-counter.js',   ['cmb-global'], $ver, true );
    }
    if ( is_singular( 'du-an' ) ) {
        wp_enqueue_script( 'cmb-project-gallery', $uri . '/assets/js/modules/project-gallery.js', ['cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-gallery-lightbox', $uri . '/assets/js/modules/gallery-lightbox.js', ['cmb-global'], $ver, true );
    }
    if ( is_post_type_archive( 'du-an' ) ) {
        wp_enqueue_style( 'swiper', $uri . '/assets/css/swiper.min.css', [], '11.0.0' );
        wp_enqueue_script( 'swiper', $uri . '/assets/js/vendors/swiper.min.js', [], '11.0.0', true );
        wp_enqueue_script( 'cmb-featured-swiper', $uri . '/assets/js/modules/featured-swiper.js', ['swiper', 'cmb-global'], $ver, true );
    }

    // Quan hệ cổ đông
    if ( is_post_type_archive( 'quan-he-co-dong' ) || is_singular( 'quan-he-co-dong' ) ) {
        wp_enqueue_script( 'cmb-ir-tabs', $uri . '/assets/js/modules/ir-tabs.js', ['cmb-global'], $ver, true );
    }

    // CMB_Theme và CMB_Ajax — luôn cần cho global
    wp_localize_script( 'cmb-global', 'CMB_Theme', [
        'uri'  => $uri,
        'lang' => function_exists( 'pll_current_language' ) ? pll_current_language() : 'vi',
    ] );
    wp_localize_script( 'cmb-global', 'CMB_Ajax', [
        'url' => admin_url( 'admin-ajax.php' ),
    ] );

    // Localize ACF data chỉ cho trang chủ
    if ( is_front_page() && function_exists( 'get_field' ) ) {

        // History milestones
        $milestones = [];
        $items = get_field( 'history_item', 'option' );
        $lang  = function_exists( 'pll_current_language' ) ? pll_current_language() : 'vi';
        if ( $items ) {
            foreach ( $items as $item ) {
                $desc = ( $lang === 'en' && ! empty( $item['content_en'] ) ) ? $item['content_en'] : $item['content'];
                $milestones[] = [
                    'year' => $item['year'],
                    'desc' => $desc,
                ];
            }
        }
        wp_localize_script( 'cmb-global', 'CMB_History', $milestones );

        // Location map data — lấy từ post type "du-an" (field project_city),
        // thay cho ACF Options. Một tỉnh/thành có thể có nhiều dự án (slide).
        $loc_provinces = [
            'quang-ninh', 'hai-phong', 'thanh-hoa', 'nghe-an', 'quang-tri', 'da-nang',
            'quang-ngai', 'khanh-hoa', 'ninh-thuan', 'binh-thuan', 'dong-nai',
            'ba-ria-vung-tau', 'tay-ninh', 'tp-hcm', 'tien-giang', 'ben-tre', 'can-tho',
        ];
        $loc_query = new WP_Query( [
            'post_type'      => 'du-an',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
            'meta_query'     => [[
                'key'     => 'project_city',
                'value'   => $loc_provinces,
                'compare' => 'IN',
            ]],
        ] );

        $location_data = [];
        if ( $loc_query->have_posts() ) {
            foreach ( $loc_query->posts as $loc_post ) {
                $city = get_field( 'project_city', $loc_post->ID );
                if ( empty( $city ) || ! in_array( $city, $loc_provinces, true ) ) continue;

                // get_the_title() chạy qua filter "the_title" (wptexturize/convert_chars) nên tự
                // convert ký tự "&" thành entity "&#038;" — đúng khi echo thẳng ra HTML, nhưng ở
                // đây giá trị này được JSON hóa rồi JS (location-map.js) tự escape lại 1 lần nữa
                // (_escapeHtml) trước khi chèn vào innerHTML, khiến bị encode 2 lần và hiển thị
                // chữ thô "&#038;" thay vì ký tự "&". Decode ngược lại về text thuần trước khi
                // đưa vào JSON để JS chỉ escape đúng 1 lần.
                $p = [
                    'project' => html_entity_decode( get_the_title( $loc_post ), ENT_QUOTES, 'UTF-8' ),
                    'link'    => get_permalink( $loc_post ),
                ];

                $desc = get_field( 'project_short_desc', $loc_post->ID );
                if ( $desc ) $p['desc'] = wp_strip_all_tags( $desc );

                $img = get_the_post_thumbnail_url( $loc_post->ID, 'large' );
                if ( $img ) {
                    $p['imgSrc'] = $img;
                    $p['imgAlt'] = html_entity_decode( get_the_title( $loc_post ), ENT_QUOTES, 'UTF-8' );
                }

                $location_data[ $city ]['projects'][] = $p;
            }
        }
        wp_reset_postdata();

        if ( !empty( $location_data ) ) {
            wp_localize_script( 'cmb-global', 'CMB_LocationData', $location_data );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'cmb_enqueue_assets' );

// ============================================================
// EXCERPT LENGTH
// ============================================================
function cmb_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'cmb_excerpt_length' );

function cmb_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'cmb_excerpt_more' );

// ============================================================
// BODY CLASS — thêm slug page vào body class
// ============================================================
function cmb_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'page--' . get_post_field( 'post_name', get_the_ID() );
    }
    return $classes;
}
add_filter( 'body_class', 'cmb_body_classes' );

// ============================================================
// NEWS FILTER — AJAX HANDLER
// ============================================================
add_action( 'wp_ajax_cmb_filter_news',        'cmb_filter_news_handler' );
add_action( 'wp_ajax_nopriv_cmb_filter_news', 'cmb_filter_news_handler' );

function cmb_filter_news_handler() {
    check_ajax_referer( 'cmb_news_filter', 'nonce' );

    $cat_slug = isset( $_POST['category'] ) ? sanitize_key( $_POST['category'] ) : '';
    $sort     = isset( $_POST['sort'] )     ? sanitize_key( $_POST['sort'] )     : 'newest';
    $paged    = isset( $_POST['paged'] )    ? max( 1, absint( $_POST['paged'] ) ) : 1;

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => get_option( 'posts_per_page' ),
        'paged'          => $paged,
    ];

    if ( $sort === 'oldest' ) {
        $args['orderby'] = 'date';
        $args['order']   = 'ASC';
    } elseif ( $sort === 'popular' ) {
        $args['orderby'] = 'comment_count';
        $args['order']   = 'DESC';
    } else {
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
    }

    if ( $cat_slug ) {
        $args['category_name'] = $cat_slug;
    }

    $q = new WP_Query( $args );

    ob_start();
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            get_template_part( 'template-parts/news-item' );
        }
        wp_reset_postdata();
    } else {
        wp_reset_postdata();
        echo '<p style="padding:2rem 0;text-align:center;color:#888;">' . esc_html( cmb_txt( 'Không tìm thấy bài viết nào.', 'No posts found.' ) ) . '</p>';
    }
    $html = ob_get_clean();

    wp_send_json_success( [
        'html'       => $html,
        'pagination' => cmb_build_ajax_pagination( $paged, $q->max_num_pages ),
        'found'      => $q->found_posts,
        'max_pages'  => $q->max_num_pages,
    ] );
}

function cmb_build_ajax_pagination( $current, $total ) {
    if ( $total <= 1 ) return '';

    $html  = '';
    $start = max( 1, $current - 2 );
    $end   = min( $total, $current + 2 );

    if ( $current > 1 ) {
        $html .= '<button class="p-news-all__page-btn" data-paged="' . ( $current - 1 ) . '" aria-label="' . esc_attr( cmb_txt( 'Trang trước', 'Previous page' ) ) . '">&laquo;</button>';
    }

    if ( $start > 1 ) {
        $html .= '<button class="p-news-all__page-btn" data-paged="1">1</button>';
        if ( $start > 2 ) {
            $html .= '<span class="p-news-all__page-btn p-news-all__page-btn--dots">&#8230;</span>';
        }
    }

    for ( $i = $start; $i <= $end; $i++ ) {
        $active = ( $i === $current ) ? ' is-active" aria-current="page' : '';
        $html  .= '<button class="p-news-all__page-btn' . $active . '" data-paged="' . $i . '">' . $i . '</button>';
    }

    if ( $end < $total ) {
        if ( $end < $total - 1 ) {
            $html .= '<span class="p-news-all__page-btn p-news-all__page-btn--dots">&#8230;</span>';
        }
        $html .= '<button class="p-news-all__page-btn" data-paged="' . $total . '">' . $total . '</button>';
    }

    if ( $current < $total ) {
        $html .= '<button class="p-news-all__page-btn" data-paged="' . ( $current + 1 ) . '" aria-label="' . esc_attr( cmb_txt( 'Trang tiếp', 'Next page' ) ) . '">&raquo;</button>';
    }

    return $html;
}

// ============================================================
// SEARCH — include all public post types
// ============================================================
function cmb_search_all_post_types( $query ) {
    if ( $query->is_search() && $query->is_main_query() && ! is_admin() ) {
        $query->set( 'post_type', [
            'post',
            'du-an',
            'thiet-bi',
            'phong-thi-nghiem',
            'quan-he-co-dong',
        ] );
    }
}
add_action( 'pre_get_posts', 'cmb_search_all_post_types' );

// ============================================================
// CPT ARCHIVES — align main query's posts_per_page with the
// hardcoded value each archive template's own listing query uses
// (template-parts/du-an/archive-list.php, archive-phong-thi-nghiem.php).
// Without this, WP core's 404 check on `paged` uses the main query's
// max_num_pages (based on Settings → Reading), which can disagree
// with the template's own pagination links — same bug as the
// tin-tuc archive had.
// ============================================================
function cmb_cpt_archive_posts_per_page( $query ) {
    if ( ! $query->is_main_query() || is_admin() ) {
        return;
    }
    if ( $query->is_post_type_archive( 'du-an' ) ) {
        $query->set( 'posts_per_page', 6 );
    } elseif ( $query->is_post_type_archive( 'phong-thi-nghiem' ) ) {
        $query->set( 'posts_per_page', 6 );
    }
}
add_action( 'pre_get_posts', 'cmb_cpt_archive_posts_per_page' );

// ============================================================
// HELPER: get ACF field with fallback
// ============================================================
function cmb_field( $key, $fallback = '', $post_id = false ) {
    if ( function_exists( 'get_field' ) ) {
        $val = get_field( $key, $post_id ?: null );
        return $val ?: $fallback;
    }
    return $fallback;
}

// ============================================================
// MULTILINGUAL HELPERS (Polylang + ACF _en fields)
// ============================================================

// Get ACF options field, auto-pick EN version when current lang = en
function cmb_get_option( $key ) {
    if ( function_exists( 'pll_current_language' ) && pll_current_language() === 'en' ) {
        $en = get_field( $key . '_en', 'option' );
        if ( $en ) return $en;
    }
    return get_field( $key, 'option' );
}

// Get sub_field inside have_rows(), auto-pick _en when current lang = en
function cmb_sub( $key ) {
    if ( function_exists( 'pll_current_language' ) && pll_current_language() === 'en' ) {
        $en = get_sub_field( $key . '_en' );
        if ( $en ) return $en;
    }
    return get_sub_field( $key );
}

// Get value from ACF group/repeater array, auto-pick _en when current lang = en
function cmb_arr( $arr, $key ) {
    if ( function_exists( 'pll_current_language' ) && pll_current_language() === 'en' ) {
        if ( ! empty( $arr[ $key . '_en' ] ) ) return $arr[ $key . '_en' ];
    }
    return $arr[ $key ] ?? '';
}

// Static UI chrome string (aria-labels, button labels, headings not backed by ACF)
function cmb_txt( $vi, $en ) {
    if ( function_exists( 'pll_current_language' ) && pll_current_language() === 'en' ) {
        return $en;
    }
    return $vi;
}

// ============================================================
// HELPER: Get medal image src + alt từ ACF field value
// ============================================================
if ( ! function_exists( 'cmb_get_medal_img' ) ) {
    function cmb_get_medal_img( $medal ) {
        if ( empty( $medal['img'] ) ) return [ '', '' ];
        $img = $medal['img'];
        if ( is_numeric( $img ) ) {
            $src = wp_get_attachment_image_src( (int) $img, 'large' );
            return [
                $src ? $src[0] : '',
                get_post_meta( (int) $img, '_wp_attachment_image_alt', true ) ?: strip_tags( $medal['name'] ?? '' ),
            ];
        }
        return [
            is_array( $img ) ? ( $img['url'] ?? '' ) : $img,
            is_array( $img ) ? ( $img['alt'] ?? strip_tags( $medal['name'] ?? '' ) ) : strip_tags( $medal['name'] ?? '' ),
        ];
    }
}

// ============================================================
// TRANSIENT CACHE INVALIDATION
// Xóa cache khi admin save/delete post thuộc các CPT
// ============================================================
function cmb_invalidate_cpt_cache( $post_id, $post ) {
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) return;

    switch ( $post->post_type ) {
        case 'thiet-bi':
            delete_transient( 'cmb_thiet_bi_grouped' );
            break;

        case 'phan-mem':
            delete_transient( 'cmb_phan_mem_grouped' );
            break;

        case 'quan-he-co-dong':
            $terms = get_the_terms( $post_id, 'quan-he-co-dong-category' );
            if ( $terms && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    delete_transient( 'cmb_ir_grouped_'  . $term->term_id );
                    delete_transient( 'cmb_ir_featured_' . $term->term_id );
                }
            }
            break;

        case 'du-an':
            delete_transient( 'cmb_featured_du_an_id' );
            break;
    }
}
add_action( 'save_post',   'cmb_invalidate_cpt_cache', 10, 2 );

// In wp-admin, taxonomy checkboxes are saved via wp_set_object_terms() AFTER
// wp_update_post() runs — i.e. AFTER 'save_post' already fired. So a category
// change made while editing a post can be invisible to the cache-clear above.
// set_object_terms fires at the moment terms are actually attached, so it's
// the reliable place to invalidate the grouped-by-category caches.
add_action( 'set_object_terms', function( $post_id, $terms, $tt_ids, $taxonomy ) {
    if ( $taxonomy === 'phan-mem-category' ) {
        delete_transient( 'cmb_phan_mem_grouped' );
    } elseif ( $taxonomy === 'thiet-bi-category' ) {
        delete_transient( 'cmb_thiet_bi_grouped' );
    }
}, 10, 4 );
add_action( 'delete_post', function( $post_id ) {
    $post = get_post( $post_id );
    if ( $post ) cmb_invalidate_cpt_cache( $post_id, $post );
} );

// ============================================================
// SECURITY: Tắt XML-RPC + ẩn user enumeration qua REST API
// ============================================================
add_filter( 'xmlrpc_enabled', '__return_false' );

add_filter( 'wp_headers', function( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
} );

add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( ! current_user_can( 'administrator' ) ) {
        unset( $endpoints['/wp/v2/users'] );
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
} );

// ============================================================
// REST API CORS — cho phép trang tuyển dụng (app React ở domain khác,
// VD Vercel) gọi GET vào REST API để lấy danh sách tin tuyển dụng.
// Domain cho phép cấu hình tại wp-admin > Cấu hình chung > API Tuyển Dụng,
// không cần sửa code khi đổi domain demo/production.
// ============================================================
function cmb_is_allowed_cors_origin( $origin ) {
    $host = wp_parse_url( $origin, PHP_URL_HOST );
    if ( ! $host ) return false;
    $host = strtolower( $host );

    // Môi trường dev local (Vite): luôn cho phép, không phụ thuộc cấu hình admin.
    if ( $host === 'localhost' || $host === '127.0.0.1' ) return true;

    $configured = function_exists( 'get_field' ) ? get_field( 'cors_allowed_origins', 'option' ) : '';
    // Fallback khi admin chưa lưu trang "Cấu hình chung" lần nào (get_field không tự áp dụng default_value)
    if ( empty( $configured ) ) {
        $configured = "cmb-recruitment.vercel.app\n*.vercel.app";
    }
    $allowed = array_filter( array_map( 'trim', explode( "\n", (string) $configured ) ) );

    foreach ( $allowed as $pattern ) {
        $pattern = strtolower( $pattern );
        if ( $pattern === $host ) return true;
        if ( strpos( $pattern, '*.' ) === 0 && substr( $host, -( strlen( $pattern ) - 1 ) ) === substr( $pattern, 1 ) ) {
            return true;
        }
    }
    return false;
}

add_action( 'rest_api_init', function () {
    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
    add_filter( 'rest_pre_serve_request', function ( $value ) {
        $origin = get_http_origin();
        if ( $origin && cmb_is_allowed_cors_origin( $origin ) ) {
            header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
            header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
            header( 'Access-Control-Allow-Headers: Content-Type, Authorization' );
            header( 'Vary: Origin' );
        }
        return $value;
    } );
}, 15 );

// ============================================================
// PERFORMANCE: Preload LCP (hero) image — priority 1 = output sớm nhất
// ============================================================
add_action( 'wp_head', function() {
    $preload_url = '';

    // Front page: lấy ảnh slide đầu tiên từ ACF
    if ( is_front_page() && function_exists( 'get_field' ) ) {
        $slides = get_field( 'slide_banner', 'option' );
        if ( ! empty( $slides[0]['img']['url'] ) ) {
            $preload_url = $slides[0]['img']['url'];
        }
    }

    // Trang đơn có featured image
    if ( ! $preload_url && is_singular() && has_post_thumbnail() ) {
        $preload_url = get_the_post_thumbnail_url( null, 'large' );
    }

    // Fallback: ảnh hero mặc định của theme
    if ( ! $preload_url ) {
        $preload_url = get_template_directory_uri() . '/assets/images/hero_port.jpg';
    }

    $ext      = strtolower( pathinfo( wp_parse_url( $preload_url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
    $type_map = [
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',  'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
    ];
    $preload_type = $type_map[ $ext ] ?? 'image/jpeg';

    echo '<link rel="preload" as="image" href="' . $preload_url . '" type="' . $preload_type . '">' . "\n";
}, 1 );

// WordPress core tự output rel_canonical — xóa để tránh trùng với canonical của chúng ta
remove_action( 'wp_head', 'rel_canonical' );

// ============================================================
// FAVICON — lấy từ ACF Options (Cấu hình chung > Logo > Favicon)
// ============================================================
add_action( 'wp_head', function() {
    if ( ! function_exists( 'get_field' ) ) {
        return;
    }
    $favicon = get_field( 'favicon', 'option' );
    if ( empty( $favicon['url'] ) ) {
        return;
    }
    $url  = esc_url( $favicon['url'] );
    $type = ! empty( $favicon['mime_type'] ) ? esc_attr( $favicon['mime_type'] ) : 'image/png';

    echo '<link rel="icon" href="' . $url . '" type="' . $type . '">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . $url . '">' . "\n";
}, 1 );

// ============================================================
// SEO: Meta Description + Canonical + Open Graph + Twitter Card
// ============================================================
add_action( 'wp_head', function() {
    global $post;

    // Title
    if ( is_singular() && ! empty( $post ) ) {
        $title = get_the_title( $post );
    } elseif ( is_home() || is_front_page() ) {
        $title = get_bloginfo( 'name' ) . ' — ' . get_bloginfo( 'description' );
    } else {
        $title = wp_title( '—', false, 'right' ) . get_bloginfo( 'name' );
    }

    // Description
    $desc = get_bloginfo( 'description' );
    if ( is_singular() && ! empty( $post ) ) {
        $raw     = has_excerpt() ? get_the_excerpt( $post ) : wp_strip_all_tags( get_the_content( null, false, $post ) );
        $trimmed = wp_trim_words( $raw, 30, '...' );
        if ( $trimmed ) $desc = $trimmed;
    }

    // Canonical URL
    $url = is_singular() ? (string) get_permalink() : ( is_home() ? home_url( '/' ) : (string) get_pagenum_link() );

    // OG image: featured → ACF logo → nothing
    $image = '';
    if ( is_singular() && has_post_thumbnail() ) {
        $image = (string) get_the_post_thumbnail_url( null, 'large' );
    }
    if ( ! $image && function_exists( 'get_field' ) ) {
        $logo  = get_field( 'logo', 'option' );
        $image = is_array( $logo ) ? ( $logo['url'] ?? '' ) : (string) $logo;
    }

    // --- Meta description & canonical (Google SERP) ---
    echo '<meta name="description" content="' . $desc . '">' . "\n";
    echo '<link rel="canonical" href="' . $url . '">' . "\n";

    // --- Open Graph ---
    $og_type = is_singular( 'post' ) ? 'article' : 'website';
    echo '<meta property="og:locale"      content="vi_VN">' . "\n";
    echo '<meta property="og:type"        content="' . $og_type . '">' . "\n";
    echo '<meta property="og:site_name"   content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    echo '<meta property="og:url"         content="' . $url . '">' . "\n";
    echo '<meta property="og:title"       content="' . $title . '">' . "\n";
    echo '<meta property="og:description" content="' . $desc . '">' . "\n";
    if ( $image ) {
        echo '<meta property="og:image"        content="' . $image . '">' . "\n";
        echo '<meta property="og:image:width"  content="1200">' . "\n";
        echo '<meta property="og:image:height" content="630">' . "\n";
        echo '<meta property="og:image:alt"    content="' . $title . '">' . "\n";
    }

    // Article-specific: published/modified time
    if ( $og_type === 'article' && ! empty( $post ) ) {
        $pub = get_the_date( DATE_ATOM, $post );
        $mod = get_the_modified_date( DATE_ATOM, $post );
        if ( $pub ) echo '<meta property="article:published_time" content="' . $pub . '">' . "\n";
        if ( $mod ) echo '<meta property="article:modified_time"  content="' . $mod . '">' . "\n";
    }

    // --- Twitter Card ---
    echo '<meta name="twitter:card"        content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title"       content="' . $title . '">' . "\n";
    echo '<meta name="twitter:description" content="' . $desc . '">' . "\n";
    if ( $image ) {
        echo '<meta name="twitter:image" content="' . $image . '">' . "\n";
    }
}, 2 );

// ============================================================
// SEO: JSON-LD Schema — Organization + BreadcrumbList
// ============================================================
add_action( 'wp_head', function() {
    $site_name = get_bloginfo( 'name' );
    $site_url  = home_url( '/' );

    // Dữ liệu công ty từ ACF Options
    $logo_url = $phone = $email = $address = '';
    if ( function_exists( 'get_field' ) ) {
        $logo     = get_field( 'logo', 'option' );
        $logo_url = is_array( $logo ) ? ( $logo['url'] ?? '' ) : (string) $logo;
        $phone    = (string) ( get_field( 'company_phone',   'option' ) ?: '' );
        $email    = (string) ( get_field( 'company_email',   'option' ) ?: '' );
        $address  = (string) ( get_field( 'company_address', 'option' ) ?: '' );
    }

    $schema = [
        '@context'     => 'https://schema.org',
        '@type'        => [ 'Organization', 'LocalBusiness' ],
        'name'         => $site_name,
        'url'          => $site_url,
        'foundingDate' => '1997',
        'areaServed'   => 'Vietnam',
    ];
    if ( $logo_url ) {
        $schema['logo']  = [ '@type' => 'ImageObject', 'url' => $logo_url ];
        $schema['image'] = $logo_url;
    }
    if ( $phone ) {
        $schema['telephone'] = trim( explode( "\n", $phone )[0] );
    }
    if ( $email ) {
        $schema['email'] = trim( explode( "\n", $email )[0] );
    }
    if ( $address ) {
        $schema['address'] = [
            '@type'          => 'PostalAddress',
            'streetAddress'  => wp_strip_all_tags( $address ),
            'addressCountry' => 'VN',
        ];
    }

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n</script>\n";

    // NewsArticle schema cho bài viết đơn
    if ( is_singular( 'post' ) ) {
        global $post;
        $article = [
            '@context'      => 'https://schema.org',
            '@type'         => 'NewsArticle',
            'headline'      => get_the_title( $post ),
            'url'           => get_permalink( $post ),
            'datePublished' => get_the_date( DATE_ATOM, $post ),
            'dateModified'  => get_the_modified_date( DATE_ATOM, $post ),
            'inLanguage'    => 'vi',
            'description'   => wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ?: get_the_content( null, false, $post ) ), 30, '...' ),
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => $site_name,
                'url'   => $site_url,
            ],
        ];
        if ( $logo_url ) {
            $article['publisher']['logo'] = [ '@type' => 'ImageObject', 'url' => $logo_url ];
        }
        if ( has_post_thumbnail( $post ) ) {
            $article['image'] = [ '@type' => 'ImageObject', 'url' => get_the_post_thumbnail_url( $post, 'large' ) ];
        }
        $author_name = get_the_author_meta( 'display_name', $post->post_author );
        if ( $author_name ) {
            $article['author'] = [ '@type' => 'Person', 'name' => $author_name ];
        }
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode( $article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
        echo "\n</script>\n";
    }

    // BreadcrumbList (bỏ qua trang chủ)
    if ( is_front_page() || is_home() ) return;

    $items   = [ [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Trang chủ', 'item' => $site_url ] ];
    $pos     = 2;

    if ( is_singular() ) {
        $post_type = get_post_type();
        if ( ! in_array( $post_type, [ 'post', 'page' ], true ) ) {
            $archive_url = get_post_type_archive_link( $post_type );
            if ( $archive_url ) {
                $pt_label  = get_post_type_object( $post_type )->label ?? $post_type;
                $items[]   = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => $pt_label, 'item' => $archive_url ];
            }
        }
        $items[] = [ '@type' => 'ListItem', 'position' => $pos, 'name' => get_the_title(), 'item' => (string) get_permalink() ];
    } elseif ( is_post_type_archive() || is_archive() ) {
        $pt_obj  = get_post_type_object( get_post_type() );
        $label   = $pt_obj ? $pt_obj->label : (string) get_queried_object()->label;
        $items[] = [ '@type' => 'ListItem', 'position' => $pos, 'name' => $label, 'item' => (string) get_pagenum_link() ];
    } elseif ( is_page() ) {
        $items[] = [ '@type' => 'ListItem', 'position' => $pos, 'name' => get_the_title(), 'item' => (string) get_permalink() ];
    }

    $breadcrumb = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ];

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $breadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n</script>\n";
}, 3 );

// ============================================================
// SEO: Meta Robots — cho phép Google lấy preview dài + ảnh lớn
// ============================================================
add_action( 'wp_head', function() {
    echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
}, 4 );

// ============================================================
// SEO: robots.txt — thêm Disallow + Sitemap (tự đổi domain đúng)
// ============================================================
add_filter( 'robots_txt', function( $output, $public ) {
    if ( ! $public ) return $output;
    $output .= "Disallow: /wp-includes/\n";
    $output .= "Disallow: /wp-login.php\n";
    $output .= "Disallow: /?s=\n";
    $output .= "Disallow: /search/\n";
    $output .= "\nSitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
    return $output;
}, 10, 2 );

// Allow SVG uploads
add_filter( 'upload_mimes', function( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
} );

add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {
    if ( ! $data['type'] ) {
        $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
        if ( $ext === 'svg' || $ext === 'svgz' ) {
            $data['type'] = 'image/svg+xml';
            $data['ext']  = $ext;
        }
    }
    return $data;
}, 10, 4 );

// Read intrinsic width/height from an SVG file's width/height or viewBox attributes
function cmb_get_svg_dimensions( $svg_path ) {
    if ( ! file_exists( $svg_path ) ) {
        return false;
    }
    $svg = @simplexml_load_file( $svg_path );
    if ( ! $svg ) {
        return false;
    }
    $attrs  = $svg->attributes();
    $width  = isset( $attrs->width )  ? (float) $attrs->width  : 0;
    $height = isset( $attrs->height ) ? (float) $attrs->height : 0;

    if ( ( ! $width || ! $height ) && isset( $attrs->viewBox ) ) {
        $viewbox = preg_split( '/[\s,]+/', trim( (string) $attrs->viewBox ) );
        if ( count( $viewbox ) === 4 ) {
            $width  = $width  ?: (float) $viewbox[2];
            $height = $height ?: (float) $viewbox[3];
        }
    }

    if ( ! $width || ! $height ) {
        return false;
    }
    return [ 'width' => (int) round( $width ), 'height' => (int) round( $height ) ];
}

// WordPress can't generate real metadata for SVGs (no image editor support) —
// without width/height in the attachment metadata, image_downsize() bails out
// and the admin Featured Image box / media grid render the tiny broken-image icon.
add_filter( 'wp_generate_attachment_metadata', function( $metadata, $attachment_id ) {
    $file = get_attached_file( $attachment_id );
    if ( $file && preg_match( '/\.svgz?$/i', $file ) ) {
        $dims = cmb_get_svg_dimensions( $file );
        if ( $dims ) {
            $metadata['width']  = $dims['width'];
            $metadata['height'] = $dims['height'];
        }
    }
    return $metadata;
}, 10, 2 );

// Make image_downsize() (used by the Featured Image box, media library grid, etc.)
// return the SVG itself with correct dimensions instead of failing.
add_filter( 'image_downsize', function( $downsize, $attachment_id, $size ) {
    $file = get_attached_file( $attachment_id );
    if ( ! $file || ! preg_match( '/\.svgz?$/i', $file ) ) {
        return $downsize;
    }
    $dims = cmb_get_svg_dimensions( $file );
    if ( ! $dims ) {
        return $downsize;
    }
    return [ wp_get_attachment_url( $attachment_id ), $dims['width'], $dims['height'], false ];
}, 10, 3 );

// ============================================================
// ADMIN: Đổi nhãn "Bài viết" → "Tin tức & Sự kiện"
// ============================================================
add_action( 'init', function() {
    global $wp_post_types;
    if ( isset( $wp_post_types['post'] ) ) {
        $labels = $wp_post_types['post']->labels;
        $labels->name               = 'Tin tức & Sự kiện';
        $labels->singular_name      = 'Tin tức';
        $labels->add_new            = 'Thêm mới';
        $labels->add_new_item       = 'Thêm tin tức mới';
        $labels->edit_item          = 'Sửa tin tức';
        $labels->new_item           = 'Tin tức mới';
        $labels->view_item          = 'Xem tin tức';
        $labels->search_items       = 'Tìm tin tức';
        $labels->not_found          = 'Không tìm thấy tin tức nào';
        $labels->not_found_in_trash = 'Không có tin tức trong thùng rác';
        $labels->all_items          = 'Tất cả tin tức';
        $labels->menu_name          = 'Tin tức';
    }
} );

// ============================================================
// POLYLANG: Redirect front page translation to clean /lang/ URL
// e.g. /en/trang-chu-english/ → /en/
// Also prevents WordPress canonical redirect from reversing it.
// ============================================================

// Step 1: When at /en/trang-chu-english/, redirect to /en/
add_action('template_redirect', function () {
    if ( ! function_exists('pll_current_language') || ! function_exists('pll_default_language') ) return;

    $lang = pll_current_language();
    if ( ! $lang || $lang === pll_default_language() ) return;

    $front_page_id = (int) get_option('page_on_front');
    if ( ! $front_page_id ) return;

    $translations  = function_exists('pll_get_post_translations') ? pll_get_post_translations($front_page_id) : [];
    $lang_front_id = (int) ( $translations[$lang] ?? 0 );
    if ( ! $lang_front_id ) return;

    $lang_front_post = get_post($lang_front_id);
    if ( ! $lang_front_post ) return;

    $slug = $lang_front_post->post_name;

    if ( strpos( $_SERVER['REQUEST_URI'], '/' . $slug ) === false ) return;

    wp_redirect( trailingslashit( site_url( '/' . $lang ) ), 301 );
    exit;
}, 1 );

// Step 2: Prevent WordPress canonical redirect from sending /en/ back to /en/trang-chu-english/
add_filter('redirect_canonical', function ( $redirect_url, $requested_url ) {
    if ( ! function_exists('pll_current_language') || ! function_exists('pll_default_language') ) return $redirect_url;

    $lang = pll_current_language();
    if ( ! $lang || $lang === pll_default_language() ) return $redirect_url;

    $clean_home = trailingslashit( site_url( '/' . $lang ) );
    if ( trailingslashit( $requested_url ) === $clean_home ) {
        return false;
    }

    return $redirect_url;
}, 10, 2 );

// CF7 — đổi messages theo ngôn ngữ hiện tại (filter này override cả form đã có sẵn)
add_filter('wpcf7_display_message', function($message, $status) {
    $vi = [
        'mail_sent_ok'      => cmb_txt( 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.', 'Thank you for contacting us! We will respond as soon as possible.' ),
        'mail_sent_ng'      => cmb_txt( 'Đã xảy ra lỗi khi gửi mail. Vui lòng thử lại sau.', 'An error occurred while sending the email. Please try again later.' ),
        'validation_errors' => cmb_txt( 'Vui lòng kiểm tra lại thông tin đã nhập.', 'Please check the information you entered.' ),
        'spam'              => cmb_txt( 'Có lỗi xảy ra. Vui lòng thử lại.', 'An error occurred. Please try again.' ),
        'accept_terms'      => cmb_txt( 'Bạn cần đồng ý với Chính sách bảo mật để tiếp tục.', 'You must agree to the Privacy Policy to continue.' ),
        'invalid_required'  => cmb_txt( 'Vui lòng điền thông tin bắt buộc này.', 'Please fill in this required field.' ),
        'invalid_email'     => cmb_txt( 'Địa chỉ email không hợp lệ.', 'Invalid email address.' ),
        'invalid_tel'       => cmb_txt( 'Số điện thoại không hợp lệ.', 'Invalid phone number.' ),
    ];
    return $vi[$status] ?? $message;
}, 10, 2);

// ACF Local JSON — lưu field groups vào theme để track bằng git
add_filter('acf/settings/save_json', function () {
    return get_stylesheet_directory() . '/acf-json';
});
add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});

// ============================================================
// ACF OPTIONS: Cấu hình banner
// ============================================================
add_action('acf/init', function () {
    if ( ! function_exists('acf_add_options_sub_page') ) return;
    acf_add_options_sub_page([
        'page_title'  => 'Cấu hình banner',
        'menu_title'  => 'Cấu hình banner',
        'parent_slug' => 'cau-hinh-chung',
        'capability'  => 'manage_options',
        'menu_slug'   => 'cau-hinh-banner',
    ]);
});



define('FS_METHOD', 'direct');

// ============================================================
// ADMIN: Ẩn Comments + Sắp xếp sidebar menu
// ============================================================
add_action( 'admin_menu', function() {
    remove_menu_page( 'edit-comments.php' );
} );

add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', function( $menu_order ) {
    return [
        'index.php',                                  // Dashboard
        // -- Nội dung do khách nhập --
        'cau-hinh-chung',                             // Cấu hình chung
        'edit.php?post_type=du-an',                   // Dự án
        'edit.php?post_type=thiet-bi',                // Thiết bị
        'edit.php?post_type=phong-thi-nghiem',        // Phòng thí nghiệm
        'edit.php?post_type=quan-he-co-dong',         // Quan hệ cổ đông
        'edit.php?post_type=phan-mem',                // Phần mềm
        'edit.php?post_type=linh-vuc',                // Lĩnh vực
        'edit.php?post_type=tuyen-dung',              // Tuyển dụng
        'edit.php',                                   // Tin tức
        // -- Mặc định WordPress --
        'separator1',
        'upload.php',
        'edit.php?post_type=page',
        'themes.php',
        'plugins.php',
        'users.php',
        'tools.php',
        'options-general.php',
        'separator-last',
    ];
} );


// ============================================================
// MIXED CONTENT FIX: khi site chạy HTTPS, ép các URL cùng domain
// (iframe/img/a/embed...) trong nội dung bài viết về https — tránh
// bị browser chặn nếu nội dung được nhập/dán với link http:// cứng
// (ví dụ nhúng PDF qua iframe trỏ http://domain/...).
// ============================================================
add_filter( 'the_content', function( $content ) {
    if ( ! is_ssl() ) {
        return $content;
    }
    $host = wp_parse_url( home_url(), PHP_URL_HOST );
    if ( $host ) {
        $content = str_ireplace( 'http://' . $host, 'https://' . $host, $content );
    }
    return $content;
}, 20 );

// ============================================================
// Tự động cập nhật số trang & dung lượng khi lưu file PDF
// Áp dụng cho: phong-thi-nghiem (1 file), quan-he-co-dong (nhiều file — repeater "documents")
// ============================================================
function cmb_pdf_meta_from_attachment( $attachment_id ) {
    $file_path = get_attached_file( (int) $attachment_id );
    if ( ! $file_path || ! file_exists( $file_path ) ) {
        return null;
    }

    // Dung lượng
    $bytes     = filesize( $file_path );
    $formatted = $bytes >= 1048576
        ? round( $bytes / 1048576, 1 ) . ' MB'
        : round( $bytes / 1024 ) . ' KB';

    // Số trang — đọc cấu trúc PDF, tìm /Type /Page (không phải /Pages)
    $content    = file_get_contents( $file_path );
    $page_count = 0;
    if ( $content ) {
        preg_match_all( '/\/Type\s*\/Page[^s]/i', $content, $matches );
        $page_count = count( $matches[0] );
    }

    return [ 'size' => $formatted, 'pages' => $page_count ];
}

add_action( 'acf/save_post', function( $post_id ) {
    $post_type = get_post_type( $post_id );
    if ( ! in_array( $post_type, [ 'phong-thi-nghiem', 'quan-he-co-dong' ], true ) ) {
        return;
    }

    if ( $post_type === 'quan-he-co-dong' ) {
        // Nhiều PDF — tính số trang/dung lượng riêng cho từng dòng trong repeater "documents"
        $rows = get_field( 'documents', $post_id );
        if ( empty( $rows ) || ! is_array( $rows ) ) {
            return;
        }
        foreach ( $rows as $i => $row ) {
            if ( empty( $row['file']['id'] ) ) continue;
            $meta = cmb_pdf_meta_from_attachment( $row['file']['id'] );
            if ( ! $meta ) continue;
            update_sub_field( [ 'documents', $i + 1, 'size' ], $meta['size'], $post_id );
            if ( $meta['pages'] > 0 ) {
                update_sub_field( [ 'documents', $i + 1, 'pages' ], $meta['pages'], $post_id );
            }
        }
        return;
    }

    // phong-thi-nghiem: 1 file duy nhất (hành vi cũ)
    $pdf_field = get_field( 'document_pdf', $post_id );
    if ( empty( $pdf_field['id'] ) ) {
        return;
    }
    $meta = cmb_pdf_meta_from_attachment( $pdf_field['id'] );
    if ( ! $meta ) {
        return;
    }
    update_field( 'document_size', $meta['size'], $post_id );
    if ( $meta['pages'] > 0 ) {
        update_field( 'document_pages', $meta['pages'], $post_id );
    }
}, 20 );

// Ép cấu hình flipbook (plugin 3D FlipBook/DearFlip) qua code thay vì để trong
// DB (option _dflip_settings) — set tay trên từng site sẽ không đồng bộ giữa
// local/staging/production. Ép cứng qua code để chạy đúng ở mọi môi trường:
// - bg_color: nền trắng thay vì xám mặc định.
// - texture_size: giảm độ phân giải render mỗi trang PDF (1600 -> 1024px) để
//   tải/render nhanh hơn đáng kể với các file PDF dung lượng lớn, đổi lấy một
//   chút độ nét khi zoom sâu.
// - height: mặc định "auto" khiến DearFlip tự đo lại chiều cao khung theo tỉ lệ
//   trang thật SAU KHI PDF load xong (lúc đang load nó dùng 1 tỉ lệ mặc định tạm
//   thời) -> khung bị nhảy cao giữa lúc đang tải và lúc hiển thị xong. Ép height
//   "100%" để khung luôn khóa theo chiều cao của .p-book-wrap (đã có aspect-ratio
//   cố định trong CSS) ngay từ đầu, không đổi giữa 2 giai đoạn nữa.
// - text_loading: chuỗi mặc định "DearFlip: Loading " lộ tên plugin ra ngoài giao
//   diện — đổi thành text trung tính, không nhắc tên plugin.
function cmb_dflip_settings_override( $settings ) {
    if ( ! is_array( $settings ) ) {
        $settings = [];
    }
    $settings['bg_color']     = '#FFFFFF';
    $settings['texture_size'] = '1024';
    $settings['height']       = '100%';
    $settings['text_loading'] = cmb_txt( 'Đang tải tài liệu ', 'Loading document ' );
    return $settings;
}
// - option__dflip_settings: áp dụng khi row "_dflip_settings" ĐÃ tồn tại trong
//   bảng wp_options (VD: đã từng lưu qua trang cài đặt DearFlip ở wp-admin).
// - default_option__dflip_settings: WordPress dùng hook RIÊNG này (không phải
//   option__dflip_settings) khi row đó CHƯA từng được lưu vào DB — bỏ sót hook
//   này khiến toàn bộ override vô tác dụng trên site chưa từng lưu cài đặt
//   DearFlip qua UI (dù code không lỗi gì) — phải đăng ký cả 2 mới chắc chắn
//   override có hiệu lực bất kể option đã tồn tại trong DB hay chưa.
add_filter( 'option__dflip_settings', 'cmb_dflip_settings_override' );
add_filter( 'default_option__dflip_settings', 'cmb_dflip_settings_override' );