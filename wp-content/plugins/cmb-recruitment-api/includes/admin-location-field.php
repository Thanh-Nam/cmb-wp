<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ACF field "dia_diem" trên CPT tuyen-dung là select thường (không dùng field type
// "taxonomy" vì nó luôn ép Select2, không tôn trọng ui:0). Khu vực vẫn quản lý qua
// taxonomy thật "tuyen-dung-location" — hàm dưới đây nạp choices động từ taxonomy
// và đồng bộ giá trị đã chọn ngược lại vào term relationship khi lưu bài.
// Field cho phép chọn nhiều khu vực cùng lúc (multiple: 1) nên giá trị là mảng slug.

add_filter( 'acf/load_field/key=field_6f15f7e7d6df', function ( $field ) {
	$terms   = get_terms( [ 'taxonomy' => 'tuyen-dung-location', 'hide_empty' => false ] );
	$choices = [];
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$choices[ $term->slug ] = $term->name;
		}
	}
	$field['choices'] = $choices;
	return $field;
} );

add_action( 'acf/save_post', function ( $post_id ) {
	if ( get_post_type( $post_id ) !== 'tuyen-dung' ) return;
	$slugs = get_field( 'dia_diem', $post_id );
	$slugs = is_array( $slugs ) ? array_filter( $slugs ) : array_filter( [ $slugs ] );
	wp_set_object_terms( $post_id, array_values( $slugs ), 'tuyen-dung-location', false );
}, 20 );

// Khi mở form sửa bài, hiển thị đúng các term đang gán (field lưu ở postmeta riêng,
// nên cần đọc từ taxonomy để field select hiện đúng các lựa chọn hiện tại).
add_filter( 'acf/load_value/key=field_6f15f7e7d6df', function ( $value, $post_id ) {
	if ( $value ) return $value;
	$terms = wp_get_post_terms( $post_id, 'tuyen-dung-location' );
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		return wp_list_pluck( $terms, 'slug' );
	}
	return $value;
}, 10, 2 );
