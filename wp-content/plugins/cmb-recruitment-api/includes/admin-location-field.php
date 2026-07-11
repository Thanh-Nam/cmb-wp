<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ACF field "dia_diem" trên CPT tuyen-dung là select thường (không dùng field type
// "taxonomy" vì nó luôn ép Select2, không tôn trọng ui:0). Khu vực vẫn quản lý qua
// taxonomy thật "tuyen-dung-location" — hàm dưới đây nạp choices động từ taxonomy
// và đồng bộ giá trị đã chọn ngược lại vào term relationship khi lưu bài.

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
	$slug = get_field( 'dia_diem', $post_id );
	if ( $slug ) {
		wp_set_object_terms( $post_id, [ $slug ], 'tuyen-dung-location', false );
	}
}, 20 );

// Khi mở form sửa bài, hiển thị đúng term đang gán (field lưu ở postmeta riêng,
// nên cần đọc từ taxonomy để field select hiện đúng lựa chọn hiện tại).
add_filter( 'acf/load_value/key=field_6f15f7e7d6df', function ( $value, $post_id ) {
	if ( $value ) return $value;
	$terms = wp_get_post_terms( $post_id, 'tuyen-dung-location' );
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		return $terms[0]->slug;
	}
	return $value;
}, 10, 2 );
