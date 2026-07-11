<?php
if ( ! defined( 'ABSPATH' ) ) exit;

const CMB_BLOG_CATEGORY_SLUG = 'tuyen-dung-tin-tuc';

/**
 * Bài viết thuộc category "Tuyển dụng" chỉ phục vụ site tuyển dụng React qua REST API
 * (cmb/v1/blog), không hiển thị trên giao diện site WP (trang chủ tin tức, chuyên mục,
 * tìm kiếm, RSS...). Admin vẫn thấy và quản lý bình thường trong wp-admin.
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
add_action( 'pre_get_posts', 'cmb_hide_blog_category_from_frontend_queries' );

function cmb_hide_blog_category_from_term_lists( $terms, $taxonomies ) {
	if ( is_admin() ) return $terms;
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) return $terms;
	if ( ! in_array( 'category', (array) $taxonomies, true ) ) return $terms;

	return array_filter( $terms, function ( $term ) {
		return is_object( $term ) ? $term->slug !== CMB_BLOG_CATEGORY_SLUG : true;
	} );
}
add_filter( 'get_terms', 'cmb_hide_blog_category_from_term_lists', 10, 2 );

/**
 * "Tin nổi bật" (field_6a38eb09ef2bc, group "Cấu hình tin tức") chỉ có tác dụng
 * hiển thị khung tin nổi bật trên trang chủ/archive của site WP — không liên quan
 * tới blog tuyển dụng (bị ẩn khỏi site WP). Ẩn field này khi sửa bài thuộc category
 * "Tuyển dụng" để tránh gây nhầm lẫn cho editor; các bài tin tức khác không đổi.
 */
add_filter( 'acf/prepare_field/key=field_6a38eb09ef2bc', function ( $field ) {
	if ( ! function_exists( 'acf_get_valid_post_id' ) ) return $field;

	$post_id = acf_get_valid_post_id();
	if ( ! is_numeric( $post_id ) ) return $field;

	$term = cmb_get_blog_category_term();
	if ( ! $term || is_wp_error( $term ) ) return $field;

	if ( has_term( $term->term_id, 'category', (int) $post_id ) ) {
		return false;
	}

	return $field;
} );
