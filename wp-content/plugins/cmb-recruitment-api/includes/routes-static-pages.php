<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Các trang tĩnh footer (Chính sách bảo mật, Điều khoản dịch vụ, Sơ đồ trang web...) được quản lý
// như Page thường trong wp-admin (Pages → nằm trong trang cha "Trang tĩnh (Footer)"), không cần ACF.
// Trang React lấy nội dung qua slug.

add_action( 'rest_api_init', function () {
	register_rest_route( 'cmb/v1', '/pages/(?P<slug>[a-zA-Z0-9-]+)', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			$query = new WP_Query( [
				'name'           => sanitize_title( $req['slug'] ),
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
			] );
			$page = $query->posts[0] ?? null;

			if ( ! $page ) {
				return new WP_Error( 'not_found', 'Không tìm thấy trang', [ 'status' => 404 ] );
			}

			return new WP_REST_Response( [
				'slug'    => $page->post_name,
				'title'   => get_the_title( $page ),
				'content' => apply_filters( 'the_content', $page->post_content ),
			], 200 );
		},
	] );
} );
