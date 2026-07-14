<?php
if ( ! defined( 'ABSPATH' ) ) exit;

const CMB_BLOG_CATEGORY_SLUG = 'tuyen-dung-tin-tuc';

/**
 * Trước đây bài viết thuộc category "Tuyển dụng" bị ẩn khỏi giao diện site WP
 * (trang chủ tin tức, chuyên mục, tìm kiếm, RSS...) và chỉ phục vụ site tuyển dụng
 * React qua REST API (cmb/v1/blog). Theo yêu cầu, category này giờ hiển thị bình
 * thường trên site WP như mọi category tin tức khác, kèm field "Tin nổi bật".
 * Các hàm dưới đây được giữ lại (không hook) để có thể bật ẩn lại nếu cần.
 */
function cmb_get_blog_category_term() {
	return get_term_by( 'slug', CMB_BLOG_CATEGORY_SLUG, 'category' );
}

function cmb_hide_blog_category_from_frontend_queries( $query ) {
	if ( is_admin() && ! wp_doing_ajax() ) return;
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) return;

	$post_type = $query->get( 'post_type' );
	$is_post_query = $post_type === '' || $post_type === 'post'
		|| ( is_array( $post_type ) && in_array( 'post', $post_type, true ) );
	if ( ! $is_post_query ) return;

	$term = cmb_get_blog_category_term();
	if ( ! $term || is_wp_error( $term ) ) return;

	$excluded   = (array) $query->get( 'category__not_in' );
	$excluded[] = $term->term_id;
	$query->set( 'category__not_in', $excluded );
}

function cmb_hide_blog_category_from_term_lists( $terms, $taxonomies ) {
	if ( is_admin() ) return $terms;
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) return $terms;
	if ( ! in_array( 'category', (array) $taxonomies, true ) ) return $terms;

	return array_filter( $terms, function ( $term ) {
		return is_object( $term ) ? $term->slug !== CMB_BLOG_CATEGORY_SLUG : true;
	} );
}
