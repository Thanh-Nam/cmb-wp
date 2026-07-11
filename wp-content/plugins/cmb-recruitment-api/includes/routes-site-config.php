<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Số liệu thống kê & đánh giá khách hàng hiển thị ở trang chủ site tuyển dụng React.
// Quản lý qua ACF Options Page "Cấu hình trang tuyển dụng" (wp-admin), không cần sửa code.

function cmb_transform_stat( $row ) {
	$icon = $row['icon'] ?? null;
	return [
		'label'   => $row['nhan'] ?? '',
		'value'   => (int) ( $row['gia_tri'] ?? 0 ),
		'suffix'  => $row['hau_to'] ?? '',
		'iconUrl' => is_array( $icon ) ? ( $icon['url'] ?? null ) : null,
	];
}

function cmb_transform_testimonial( $row ) {
	$image = $row['anh_dai_dien'] ?? null;
	return [
		'name'      => $row['ten'] ?? '',
		'role'      => $row['chuc_danh'] ?? '',
		'quote'     => $row['noi_dung'] ?? '',
		'avatarUrl' => is_array( $image ) ? ( $image['sizes']['thumbnail'] ?? $image['url'] ?? null ) : null,
	];
}

function cmb_transform_gallery_image( $image ) {
	if ( ! is_array( $image ) ) return null;
	return $image['sizes']['large'] ?? $image['url'] ?? null;
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'cmb/v1', '/company-gallery', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			$rows = function_exists( 'get_field' ) ? get_field( 'hinh_anh_cong_ty', 'option' ) : [];
			$urls = array_filter( array_map( 'cmb_transform_gallery_image', is_array( $rows ) ? $rows : [] ) );
			return new WP_REST_Response( array_values( $urls ), 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/stats', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			$rows = function_exists( 'get_field' ) ? get_field( 'thong_ke', 'option' ) : [];
			return new WP_REST_Response( array_map( 'cmb_transform_stat', is_array( $rows ) ? $rows : [] ), 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/testimonials', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			$rows = function_exists( 'get_field' ) ? get_field( 'danh_gia', 'option' ) : [];
			return new WP_REST_Response( array_map( 'cmb_transform_testimonial', is_array( $rows ) ? $rows : [] ), 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/footer-info', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			$has_acf = function_exists( 'get_field' );
			return new WP_REST_Response( [
				'hotline'      => $has_acf ? ( get_field( 'footer_hotline', 'option' ) ?: null ) : null,
				'facebookUrl'  => $has_acf ? ( get_field( 'footer_social_facebook', 'option' ) ?: null ) : null,
				'twitterUrl'   => $has_acf ? ( get_field( 'footer_social_twitter', 'option' ) ?: null ) : null,
				'linkedinUrl'  => $has_acf ? ( get_field( 'footer_social_linkedin', 'option' ) ?: null ) : null,
				'instagramUrl' => $has_acf ? ( get_field( 'footer_social_instagram', 'option' ) ?: null ) : null,
			], 200 );
		},
	] );
} );
