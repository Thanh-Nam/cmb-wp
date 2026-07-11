<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function cmb_get_blog_event_gallery( $id ) {
	if ( ! function_exists( 'get_field' ) ) return [];
	$gallery = get_field( 'event_gallery', $id );
	if ( ! is_array( $gallery ) ) return [];

	return array_values( array_filter( array_map( function ( $item ) {
		if ( ! is_array( $item ) ) return null;
		$is_video = strpos( $item['mime_type'] ?? '', 'video' ) === 0;
		$url      = $is_video ? ( $item['url'] ?? null ) : ( $item['sizes']['large'] ?? $item['url'] ?? null );
		return $url ? [
			'id'    => (string) ( $item['ID'] ?? '' ),
			'url'   => $url,
			'alt'   => $item['alt'] ?? '',
			'isVideo' => $is_video,
		] : null;
	}, $gallery ) ) );
}

function cmb_transform_blog_post( $post ) {
	$id       = $post->ID;
	$thumb_id = get_post_thumbnail_id( $id );

	return [
		'id'           => (string) $id,
		'title'        => get_the_title( $id ),
		'slug'         => $post->post_name,
		'excerpt'      => wp_strip_all_tags( get_the_excerpt( $post ) ),
		'content'      => apply_filters( 'the_content', $post->post_content ),
		'coverImage'   => $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : null,
		'eventGallery' => cmb_get_blog_event_gallery( $id ),
		'postedAt'     => get_the_date( 'c', $id ),
		'author'       => get_the_author_meta( 'display_name', $post->post_author ),
	];
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'cmb/v1', '/blog', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			$term = cmb_get_blog_category_term();
			if ( ! $term || is_wp_error( $term ) ) {
				return new WP_REST_Response( [
					'items' => [], 'total' => 0, 'page' => 1, 'pageSize' => 0, 'totalPages' => 1,
				], 200 );
			}

			$page      = max( 1, (int) $req->get_param( 'page' ) ?: 1 );
			$page_size = max( 1, min( 50, (int) $req->get_param( 'pageSize' ) ?: 6 ) );

			$args = [
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $page_size,
				'paged'          => $page,
				'tax_query'      => [
					[ 'taxonomy' => 'category', 'field' => 'term_id', 'terms' => $term->term_id ],
				],
			];
			if ( $search = $req->get_param( 'search' ) ) {
				$args['s'] = sanitize_text_field( $search );
			}

			$query = new WP_Query( $args );
			$items = array_map( 'cmb_transform_blog_post', $query->posts );

			return new WP_REST_Response( [
				'items'      => $items,
				'total'      => (int) $query->found_posts,
				'page'       => $page,
				'pageSize'   => $page_size,
				'totalPages' => max( 1, (int) $query->max_num_pages ),
			], 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/blog/(?P<id>\d+)', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			$id   = (int) $req['id'];
			$post = get_post( $id );
			$term = cmb_get_blog_category_term();

			if ( ! $post || $post->post_type !== 'post' || $post->post_status !== 'publish'
				|| ! $term || is_wp_error( $term ) || ! has_term( $term->term_id, 'category', $post ) ) {
				return new WP_Error( 'not_found', 'Không tìm thấy bài viết.', [ 'status' => 404 ] );
			}

			return new WP_REST_Response( cmb_transform_blog_post( $post ), 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/blog/featured', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			$term = cmb_get_blog_category_term();
			if ( ! $term || is_wp_error( $term ) ) {
				return new WP_REST_Response( [], 200 );
			}

			$limit = max( 1, min( 20, (int) $req->get_param( 'limit' ) ?: 3 ) );
			$query = new WP_Query( [
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $limit,
				'tax_query'      => [
					[ 'taxonomy' => 'category', 'field' => 'term_id', 'terms' => $term->term_id ],
				],
			] );

			return new WP_REST_Response( array_map( 'cmb_transform_blog_post', $query->posts ), 200 );
		},
	] );
} );
