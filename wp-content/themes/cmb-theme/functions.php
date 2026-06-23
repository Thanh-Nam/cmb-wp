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
    $uri = get_template_directory_uri();

    // CSS — luôn load
    wp_enqueue_style( 'cmb-main', $uri . '/assets/css/main.css', [], $ver );

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

    // Global JS — mọi trang
    wp_enqueue_script( 'cmb-global', $uri . '/assets/js/global.js', [], $ver, true );

    // Trang chủ (homepage)
    if ( is_front_page() ) {
        wp_enqueue_style( 'swiper', $uri . '/assets/css/swiper.min.css', [], '11.0.0' );
        wp_enqueue_script( 'swiper', $uri . '/assets/js/vendors/swiper.min.js', [], '11.0.0', true );
        wp_enqueue_script( 'cmb-hero-slider',    $uri . '/assets/js/modules/hero-slider.js',    ['swiper', 'cmb-global'], $ver, true );
        wp_enqueue_script( 'cmb-history',        $uri . '/assets/js/modules/history.js',        ['cmb-global'],           $ver, true );
        wp_enqueue_script( 'cmb-location-map',   $uri . '/assets/js/modules/location-map.js',   ['cmb-global'],           $ver, true );
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
    }

    // Archive / single dự án
    if ( is_post_type_archive( 'du-an' ) || is_singular( 'du-an' ) ) {
        wp_enqueue_script( 'cmb-project-filter', $uri . '/assets/js/modules/project-filter.js', ['cmb-global'], $ver, true );
    }

    // Quan hệ cổ đông
    if ( is_post_type_archive( 'quan-he-co-dong' ) || is_singular( 'quan-he-co-dong' ) ) {
        wp_enqueue_script( 'cmb-ir-tabs', $uri . '/assets/js/modules/ir-tabs.js', ['cmb-global'], $ver, true );
    }

    // CMB_Theme và CMB_Ajax — luôn cần cho global
    wp_localize_script( 'cmb-global', 'CMB_Theme', [
        'uri' => $uri,
    ] );
    wp_localize_script( 'cmb-global', 'CMB_Ajax', [
        'url' => admin_url( 'admin-ajax.php' ),
    ] );

    // Localize ACF data chỉ cho trang chủ
    if ( is_front_page() && function_exists( 'get_field' ) ) {

        // History milestones
        $milestones = [];
        $items = get_field( 'history_item', 'option' );
        if ( $items ) {
            foreach ( $items as $item ) {
                $milestones[] = [
                    'year' => $item['year'],
                    'desc' => $item['content'],
                ];
            }
        }
        wp_localize_script( 'cmb-global', 'CMB_History', $milestones );

        // Location map data override từ ACF Options
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

    echo '<link rel="preload" as="image" href="' . esc_url( $preload_url ) . '" type="' . esc_attr( $preload_type ) . '">' . "\n";
}, 1 );

// WordPress core tự output rel_canonical — xóa để tránh trùng với canonical của chúng ta
remove_action( 'wp_head', 'rel_canonical' );

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
    echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";

    // --- Open Graph ---
    $og_type = is_singular( 'post' ) ? 'article' : 'website';
    echo '<meta property="og:locale"      content="vi_VN">' . "\n";
    echo '<meta property="og:type"        content="' . esc_attr( $og_type ) . '">' . "\n";
    echo '<meta property="og:site_name"   content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    echo '<meta property="og:url"         content="' . esc_url( $url ) . '">' . "\n";
    echo '<meta property="og:title"       content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
    if ( $image ) {
        echo '<meta property="og:image"        content="' . esc_url( $image ) . '">' . "\n";
        echo '<meta property="og:image:width"  content="1200">' . "\n";
        echo '<meta property="og:image:height" content="630">' . "\n";
        echo '<meta property="og:image:alt"    content="' . esc_attr( $title ) . '">' . "\n";
    }

    // Article-specific: published/modified time
    if ( $og_type === 'article' && ! empty( $post ) ) {
        $pub = get_the_date( DATE_ATOM, $post );
        $mod = get_the_modified_date( DATE_ATOM, $post );
        if ( $pub ) echo '<meta property="article:published_time" content="' . esc_attr( $pub ) . '">' . "\n";
        if ( $mod ) echo '<meta property="article:modified_time"  content="' . esc_attr( $mod ) . '">' . "\n";
    }

    // --- Twitter Card ---
    echo '<meta name="twitter:card"        content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title"       content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
    if ( $image ) {
        echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
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
