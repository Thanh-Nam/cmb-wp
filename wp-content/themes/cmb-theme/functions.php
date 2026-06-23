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
        if ( ! empty( $item->target ) )     $attrs .= ' target="' . esc_attr( $item->target ) . '"';
        if ( ! empty( $item->xfn ) )        $attrs .= ' rel="' . esc_attr( $item->xfn ) . '"';
        if ( ! empty( $item->attr_title ) ) $attrs .= ' title="' . esc_attr( $item->attr_title ) . '"';

        $url   = esc_url( $item->url );
        $title = esc_html( $item->title );

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

    // Main CSS (compiled from SCSS)
    wp_enqueue_style( 'cmb-main', get_template_directory_uri() . '/assets/css/main.css', [], $ver );

    // Swiper CSS
    wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11.0.0' );

    // Google Fonts
    wp_enqueue_style( 'cmb-fonts', 'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Dancing+Script:wght@700&family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500&display=swap', [], null );

    // Swiper JS
    wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11.0.0', true );

    // Main JS
    wp_enqueue_script( 'cmb-main', get_template_directory_uri() . '/assets/js/main.js', [ 'swiper' ], $ver, true );

    // Truyền history milestones từ ACF sang JS
    $milestones = [];
    if ( function_exists( 'get_field' ) ) {
        $items = get_field( 'history_item', 'option' );
        if ( $items ) {
            foreach ( $items as $item ) {
                $milestones[] = [
                    'year' => $item['year'],
                    'desc' => $item['content'],
                ];
            }
        }
    }
    wp_localize_script( 'cmb-main', 'CMB_History', $milestones );

    // Location map data override từ ACF Options
    // Tạo ACF Options group fields: location_hai_phong, location_nghe_an,
    // location_tay_ninh, location_tp_hcm, location_dong_nai
    // Mỗi group có sub-fields: project (Text), desc (Textarea), img (Image), link (URL)
    if ( function_exists( 'get_field' ) ) {
        $loc_map = [
            'hai-phong' => 'location_hai_phong',
            'nghe-an'   => 'location_nghe_an',
            'tay-ninh'  => 'location_tay_ninh',
            'tp-hcm'    => 'location_tp_hcm',
            'dong-nai'  => 'location_dong_nai',
        ];
        $location_data = [];
        foreach ( $loc_map as $key => $field_key ) {
            $group = get_field( $field_key, 'option' );
            if ( empty( $group ) ) continue;
            $entry = [];
            if ( !empty( $group['project'] ) ) $entry['project'] = $group['project'];
            if ( !empty( $group['desc'] ) )    $entry['desc']    = wp_strip_all_tags( $group['desc'] );
            if ( !empty( $group['link'] ) )    $entry['link']    = $group['link'];
            if ( !empty( $group['img'] ) ) {
                $img = $group['img'];
                if ( is_array( $img ) ) {
                    $entry['imgSrc'] = $img['url'] ?? '';
                    $entry['imgAlt'] = $img['alt'] ?? '';
                } elseif ( is_numeric( $img ) ) {
                    $src = wp_get_attachment_image_src( (int) $img, 'large' );
                    $entry['imgSrc'] = $src ? $src[0] : '';
                    $entry['imgAlt'] = get_post_meta( (int) $img, '_wp_attachment_image_alt', true ) ?: '';
                } else {
                    $entry['imgSrc'] = $img;
                    $entry['imgAlt'] = '';
                }
            }
            if ( !empty( $entry ) ) {
                $location_data[ $key ] = $entry;
            }
        }
        if ( !empty( $location_data ) ) {
            wp_localize_script( 'cmb-main', 'CMB_LocationData', $location_data );
        }
    }

    // Theme URI cho JS dùng build đường dẫn ảnh tĩnh
    wp_localize_script( 'cmb-main', 'CMB_Theme', [
        'uri' => get_template_directory_uri(),
    ] );

    // AJAX endpoint URL cho news filter
    wp_localize_script( 'cmb-main', 'CMB_Ajax', [
        'url' => admin_url( 'admin-ajax.php' ),
    ] );
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
        'posts_per_page' => 5,
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
        echo '<p style="padding:2rem 0;text-align:center;color:#888;">Không tìm thấy bài viết nào.</p>';
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
        $html .= '<button class="p-news-all__page-btn" data-paged="' . ( $current - 1 ) . '" aria-label="Trang trước">&laquo;</button>';
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
        $html .= '<button class="p-news-all__page-btn" data-paged="' . ( $current + 1 ) . '" aria-label="Trang tiếp">&raquo;</button>';
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
// HELPER: get ACF field with fallback
// ============================================================
function cmb_field( $key, $fallback = '', $post_id = false ) {
    if ( function_exists( 'get_field' ) ) {
        $val = get_field( $key, $post_id ?: null );
        return $val ?: $fallback;
    }
    return $fallback;
}

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
