<?php
if ( ! defined( 'ABSPATH' ) ) exit;

const CMB_EMPLOYMENT_TYPE_LABELS = [
	'full-time' => 'Toàn thời gian',
	'part-time' => 'Bán thời gian',
	'contract'  => 'Hợp đồng',
	'internship' => 'Thực tập',
	'remote'    => 'Từ xa',
];

const CMB_SALARY_BUCKETS = [
	[ 'min' => 5_000_000,  'max' => 15_000_000,  'label' => '5 – 15 triệu' ],
	[ 'min' => 15_000_000, 'max' => 25_000_000,  'label' => '15 – 25 triệu' ],
	[ 'min' => 25_000_000, 'max' => 40_000_000,  'label' => '25 – 40 triệu' ],
	[ 'min' => 40_000_000, 'max' => 60_000_000,  'label' => '40 – 60 triệu' ],
	[ 'min' => 60_000_000, 'max' => 999_000_000, 'label' => 'Trên 60 triệu' ],
];

const CMB_DATE_POSTED_BUCKETS = [
	[ 'key' => 'today',      'label' => 'Hôm nay',   'after' => '1 day ago' ],
	[ 'key' => 'this-week',  'label' => 'Tuần này',  'after' => '1 week ago' ],
	[ 'key' => 'this-month', 'label' => 'Tháng này', 'after' => '1 month ago' ],
];

function cmb_get_acf_or( $post_id, $field, $default = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $field, $post_id ) : null;
	return ( $value === null || $value === false || $value === '' ) ? $default : $value;
}

function cmb_html_to_lines( $html ) {
	if ( ! $html ) return [];
	$html  = preg_replace( '/<li[^>]*>/i', "\n", $html );
	$text  = wp_strip_all_tags( $html );
	$lines = preg_split( '/\r\n|\r|\n/', $text );
	$lines = array_filter( array_map( 'trim', $lines ), fn( $l ) => $l !== '' );
	return array_values( $lines );
}

function cmb_get_job_category_term( $post_id ) {
	$terms = wp_get_post_terms( $post_id, 'tuyen-dung-category' );
	if ( is_wp_error( $terms ) || empty( $terms ) ) return null;
	return $terms[0];
}

function cmb_get_job_category_facets() {
	$terms = get_terms( [ 'taxonomy' => 'tuyen-dung-category', 'hide_empty' => false ] );
	if ( is_wp_error( $terms ) ) return [];
	return array_map( fn( $term ) => [
		'key'      => $term->slug,
		'label'    => $term->name,
		'count'    => (int) $term->count,
		'imageUrl' => cmb_get_term_image_url( $term->term_id ),
	], $terms );
}

// Một job có thể thuộc nhiều khu vực cùng lúc (dia_diem là ACF select multi-value).
function cmb_get_job_location_terms( $post_id ) {
	$terms = wp_get_post_terms( $post_id, 'tuyen-dung-location' );
	return is_wp_error( $terms ) ? [] : $terms;
}

// Trường "loai_hinh" là ACF select multi-value — chuẩn hoá về mảng dù dữ liệu cũ còn lưu dạng chuỗi đơn (trước khi cho phép chọn nhiều).
function cmb_get_job_employment_types( $post_id ) {
	$raw = cmb_get_acf_or( $post_id, 'loai_hinh', [ 'full-time' ] );
	if ( is_array( $raw ) ) return array_values( array_filter( $raw ) );
	return $raw ? [ $raw ] : [ 'full-time' ];
}

function cmb_get_job_location_facets() {
	$terms = get_terms( [ 'taxonomy' => 'tuyen-dung-location', 'hide_empty' => false ] );
	if ( is_wp_error( $terms ) ) return [];
	return array_map( fn( $term ) => [
		'key'      => $term->slug,
		'label'    => $term->name,
		'count'    => (int) $term->count,
		'imageUrl' => cmb_get_term_image_url( $term->term_id ),
	], $terms );
}

function cmb_get_job_application_count( $job_id ) {
	static $cache = [];
	if ( isset( $cache[ $job_id ] ) ) return $cache[ $job_id ];

	$query = new WP_Query( [
		'post_type'      => 'don_ung_tuyen',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'meta_query'     => [ [ 'key' => 'job_id', 'value' => (string) $job_id ] ],
	] );

	return $cache[ $job_id ] = (int) $query->found_posts;
}

function cmb_transform_job( $post ) {
	$id          = $post->ID;
	$salary_type = cmb_get_acf_or( $id, 'salary_type', 'negotiable' );
	$salary_min  = $salary_type === 'range' ? cmb_get_acf_or( $id, 'salary_min', null ) : null;
	$salary_max  = $salary_type === 'range' ? cmb_get_acf_or( $id, 'salary_max', null ) : null;

	$han_nop  = cmb_get_acf_or( $id, 'han_nop', null ); // ACF trả về "Ymd" (VD: 20260809)
	$deadline = $han_nop ? substr( $han_nop, 0, 4 ) . '-' . substr( $han_nop, 4, 2 ) . '-' . substr( $han_nop, 6, 2 ) : null;

	$category_term  = cmb_get_job_category_term( $id );
	$location_terms = cmb_get_job_location_terms( $id );

	return [
		'id'             => (string) $id,
		'title'          => get_the_title( $id ),
		'company'        => 'CMB',
		// Nhiều khu vực cách nhau bởi dấu phẩy (slug không dấu, label có dấu để hiển thị).
		'location'       => implode( ',', wp_list_pluck( $location_terms, 'slug' ) ),
		'locationLabel'  => implode( ', ', wp_list_pluck( $location_terms, 'name' ) ),
		'description'    => apply_filters( 'the_content', $post->post_content ),
		'postedAt'       => get_the_date( 'c', $id ),
		'category'       => $category_term ? $category_term->slug : '',
		'categoryLabel'  => $category_term ? $category_term->name : '',
		'employmentType' => cmb_get_job_employment_types( $id ),
		'salary'         => [
			'min'      => $salary_min !== null ? (int) $salary_min : 0,
			'max'      => $salary_max !== null ? (int) $salary_max : 0,
			'currency' => 'VND',
		],
		'salaryText'     => $salary_type === 'range' ? '' : 'Thoả thuận',
		'requirements'   => cmb_html_to_lines( cmb_get_acf_or( $id, 'yeu_cau', '' ) ),
		'benefits'       => cmb_html_to_lines( cmb_get_acf_or( $id, 'quyen_loi', '' ) ),
		'isFeatured'     => (bool) cmb_get_acf_or( $id, 'is_featured', false ),
		'deadline'       => $deadline,
		'vacancies'      => (int) cmb_get_acf_or( $id, 'so_luong', 1 ),
		'experience'     => cmb_get_acf_or( $id, 'kinh_nghiem', '' ),
		'education'      => cmb_get_acf_or( $id, 'hoc_van', '' ),
		'gender'         => cmb_get_acf_or( $id, 'gioi_tinh', 'any' ),
		'applicationCount' => cmb_get_job_application_count( $id ),
	];
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'cmb/v1', '/jobs', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			$page      = max( 1, (int) $req->get_param( 'page' ) ?: 1 );
			$page_size = max( 1, min( 100, (int) $req->get_param( 'pageSize' ) ?: 10 ) );

			$tax_query = [];
			if ( $category = $req->get_param( 'category' ) ) {
				// Hỗ trợ nhiều danh mục cùng lúc (VD: "marketing,sales" từ card gộp ở trang chủ) — khớp bất kỳ danh mục nào trong danh sách.
				$category_slugs = array_filter( array_map( 'sanitize_text_field', explode( ',', $category ) ) );
				if ( $category_slugs ) {
					$tax_query[] = [ 'taxonomy' => 'tuyen-dung-category', 'field' => 'slug', 'terms' => $category_slugs ];
				}
			}
			if ( $location = $req->get_param( 'location' ) ) {
				// Hỗ trợ nhiều khu vực cùng lúc, tương tự category.
				$location_slugs = array_filter( array_map( 'sanitize_text_field', explode( ',', $location ) ) );
				if ( $location_slugs ) {
					$tax_query[] = [ 'taxonomy' => 'tuyen-dung-location', 'field' => 'slug', 'terms' => $location_slugs ];
				}
			}

			$meta_query = [ 'relation' => 'AND' ];
			if ( $employment_type = $req->get_param( 'employmentType' ) ) {
				// Hỗ trợ chọn nhiều loại hình cùng lúc — khớp job có chứa BẤT KỲ loại hình nào trong danh sách
				// (loai_hinh là ACF select multi-value, lưu dạng mảng serialize nên dùng LIKE để so khớp từng phần tử).
				$types = array_filter( array_map( 'sanitize_text_field', explode( ',', $employment_type ) ) );
				if ( $types ) {
					$type_query = [ 'relation' => 'OR' ];
					foreach ( $types as $type ) {
						$type_query[] = [ 'key' => 'loai_hinh', 'value' => '"' . $type . '"', 'compare' => 'LIKE' ];
					}
					$meta_query[] = $type_query;
				}
			}
			if ( ( $salary_min = $req->get_param( 'salaryMin' ) ) !== null && $salary_min !== '' ) {
				$meta_query[] = [ 'key' => 'salary_max', 'value' => (int) $salary_min, 'compare' => '>=', 'type' => 'NUMERIC' ];
			}
			if ( ( $salary_max = $req->get_param( 'salaryMax' ) ) !== null && $salary_max !== '' ) {
				$meta_query[] = [ 'key' => 'salary_min', 'value' => (int) $salary_max, 'compare' => '<=', 'type' => 'NUMERIC' ];
			}

			$date_query = [];
			switch ( $req->get_param( 'datePosted' ) ) {
				case 'today':      $date_query[] = [ 'after' => '1 day ago' ]; break;
				case 'this-week':  $date_query[] = [ 'after' => '1 week ago' ]; break;
				case 'this-month': $date_query[] = [ 'after' => '1 month ago' ]; break;
			}

			$args = [
				'post_type'      => 'tuyen-dung',
				'post_status'    => 'publish',
				'posts_per_page' => $page_size,
				'paged'          => $page,
				'orderby'        => 'date',
				'order'          => 'DESC',
			];
			if ( $req->get_param( 'sort' ) === 'salary' ) {
				// Job "Thoả thuận" không lưu meta salary_max (field bị ẩn theo điều kiện salary_type == range),
				// nên không dùng orderby=meta_value_num + meta_key đơn thuần vì cách đó sẽ LOẠI HẲN các job
				// không có meta này ra khỏi kết quả. Dùng named clause với relation OR (EXISTS/NOT EXISTS) để
				// giữ đủ tất cả job, đồng thời job "Thoả thuận" (không có giá trị) sẽ tự rơi xuống cuối khi order DESC.
				$meta_query['salary_sort_clause'] = [
					'relation' => 'OR',
					'salary_sort' => [
						'key'     => 'salary_max',
						'compare' => 'EXISTS',
						'type'    => 'NUMERIC',
					],
					[
						'key'     => 'salary_max',
						'compare' => 'NOT EXISTS',
					],
				];
				$args['orderby'] = [ 'salary_sort' => 'DESC' ];
			}

			$applications_join_cb    = null;
			$applications_orderby_cb = null;
			if ( $req->get_param( 'sort' ) === 'applications' ) {
				global $wpdb;
				// Số hồ sơ ứng tuyển nằm ở CPT don_ung_tuyen riêng (meta job_id), không phải meta của chính job,
				// nên không dùng orderby=meta_value được — phải JOIN với bảng đếm số ứng tuyển theo từng job.
				$applications_join_cb = function ( $join, $q ) use ( $wpdb ) {
					if ( ! $q->get( 'cmb_sort_by_applications' ) ) return $join;
					return $join . " LEFT JOIN (
						SELECT pm.meta_value AS job_id, COUNT(*) AS app_count
						FROM {$wpdb->postmeta} pm
						INNER JOIN {$wpdb->posts} ap ON ap.ID = pm.post_id AND ap.post_type = 'don_ung_tuyen' AND ap.post_status = 'publish'
						WHERE pm.meta_key = 'job_id'
						GROUP BY pm.meta_value
					) cmb_app_counts ON cmb_app_counts.job_id = {$wpdb->posts}.ID ";
				};
				$applications_orderby_cb = function ( $orderby, $q ) use ( $wpdb ) {
					if ( ! $q->get( 'cmb_sort_by_applications' ) ) return $orderby;
					return "COALESCE(cmb_app_counts.app_count, 0) DESC, {$wpdb->posts}.post_date DESC";
				};
				add_filter( 'posts_join', $applications_join_cb, 10, 2 );
				add_filter( 'posts_orderby', $applications_orderby_cb, 10, 2 );
				$args['cmb_sort_by_applications'] = true;
			}

			if ( count( $meta_query ) > 1 ) $args['meta_query'] = $meta_query;
			if ( $tax_query ) $args['tax_query'] = $tax_query;
			if ( $date_query ) $args['date_query'] = $date_query;
			if ( $search = $req->get_param( 'search' ) ) $args['s'] = sanitize_text_field( $search );

			$query = new WP_Query( $args );

			if ( $applications_join_cb ) {
				remove_filter( 'posts_join', $applications_join_cb, 10 );
				remove_filter( 'posts_orderby', $applications_orderby_cb, 10 );
			}

			return new WP_REST_Response( [
				'items'      => array_map( 'cmb_transform_job', $query->posts ),
				'total'      => (int) $query->found_posts,
				'page'       => $page,
				'pageSize'   => $page_size,
				'totalPages' => (int) $query->max_num_pages,
			], 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/jobs/featured', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			$query = new WP_Query( [
				'post_type'      => 'tuyen-dung',
				'post_status'    => 'publish',
				'posts_per_page' => 6,
				'meta_query'     => [ [ 'key' => 'is_featured', 'value' => '1' ] ],
			] );
			return new WP_REST_Response( array_map( 'cmb_transform_job', $query->posts ), 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/jobs/categories', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			return new WP_REST_Response( cmb_get_job_category_facets(), 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/jobs/locations', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			return new WP_REST_Response( cmb_get_job_location_facets(), 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/jobs/facets', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			// loai_hinh giờ là ACF select multi-value (lưu mảng serialize) nên không thể GROUP BY
			// trực tiếp như trước — đếm từng loại hình bằng meta_query LIKE, giống cách đếm salary/date bucket.
			$employment_types = array_map( function ( $key ) {
				$q = new WP_Query( [
					'post_type'      => 'tuyen-dung',
					'post_status'    => 'publish',
					'fields'         => 'ids',
					'posts_per_page' => 1,
					'meta_query'     => [ [ 'key' => 'loai_hinh', 'value' => '"' . $key . '"', 'compare' => 'LIKE' ] ],
				] );
				return [ 'key' => $key, 'label' => CMB_EMPLOYMENT_TYPE_LABELS[ $key ], 'count' => (int) $q->found_posts ];
			}, array_keys( CMB_EMPLOYMENT_TYPE_LABELS ) );

			$date_posted = array_map( function ( $bucket ) {
				$q = new WP_Query( [
					'post_type'      => 'tuyen-dung',
					'post_status'    => 'publish',
					'fields'         => 'ids',
					'posts_per_page' => 1,
					'date_query'     => [ [ 'after' => $bucket['after'] ] ],
				] );
				return [ 'key' => $bucket['key'], 'label' => $bucket['label'], 'count' => (int) $q->found_posts ];
			}, CMB_DATE_POSTED_BUCKETS );

			$salary_ranges = array_map( function ( $bucket ) {
				$q = new WP_Query( [
					'post_type'      => 'tuyen-dung',
					'post_status'    => 'publish',
					'fields'         => 'ids',
					'posts_per_page' => 1,
					'meta_query'     => [
						'relation' => 'AND',
						[ 'key' => 'salary_max', 'value' => $bucket['min'], 'compare' => '>=', 'type' => 'NUMERIC' ],
						[ 'key' => 'salary_min', 'value' => $bucket['max'], 'compare' => '<=', 'type' => 'NUMERIC' ],
					],
				] );
				return [
					'min'   => $bucket['min'],
					'max'   => $bucket['max'],
					'label' => $bucket['label'],
					'count' => (int) $q->found_posts,
				];
			}, CMB_SALARY_BUCKETS );

			return new WP_REST_Response( [
				'categories'      => cmb_get_job_category_facets(),
				'locations'       => cmb_get_job_location_facets(),
				'employmentTypes' => $employment_types,
				'datePosted'      => $date_posted,
				'salaryRanges'    => $salary_ranges,
			], 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/jobs/(?P<id>\d+)', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			$post = get_post( (int) $req['id'] );
			if ( ! $post || $post->post_type !== 'tuyen-dung' || $post->post_status !== 'publish' ) {
				return new WP_Error( 'not_found', 'Không tìm thấy tin tuyển dụng', [ 'status' => 404 ] );
			}
			return new WP_REST_Response( cmb_transform_job( $post ), 200 );
		},
	] );
} );
