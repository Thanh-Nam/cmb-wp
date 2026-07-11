<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function cmb_count_new_of_type( $post_type ) {
	$query = new WP_Query( [
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'meta_key'       => '_cmb_is_new',
		'meta_value'     => '1',
		'fields'         => 'ids',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
	] );
	return count( $query->posts );
}

function cmb_count_new_applications() {
	return cmb_count_new_of_type( 'don_ung_tuyen' );
}

function cmb_count_new_contacts() {
	return cmb_count_new_of_type( 'lien_he_msg' );
}

function cmb_menu_badge( $count ) {
	return sprintf(
		' <span class="update-plugins count-%1$d"><span class="update-count">%1$d</span></span>',
		$count
	);
}

add_action( 'admin_menu', function () {
	global $menu, $submenu;

	$applications_count = cmb_count_new_applications();
	$contacts_count      = cmb_count_new_contacts();
	$total_count         = $applications_count + $contacts_count;

	if ( $total_count > 0 ) {
		foreach ( $menu as $key => $item ) {
			if ( isset( $item[2] ) && $item[2] === 'edit.php?post_type=tuyen-dung' ) {
				$menu[ $key ][0] .= cmb_menu_badge( $total_count );
			}
		}
	}

	if ( isset( $submenu['edit.php?post_type=tuyen-dung'] ) ) {
		foreach ( $submenu['edit.php?post_type=tuyen-dung'] as $key => $item ) {
			if ( ! isset( $item[2] ) ) continue;

			if ( $item[2] === 'edit.php?post_type=don_ung_tuyen' && $applications_count > 0 ) {
				$submenu['edit.php?post_type=tuyen-dung'][ $key ][0] .= cmb_menu_badge( $applications_count );
			}
			if ( $item[2] === 'edit.php?post_type=lien_he_msg' && $contacts_count > 0 ) {
				$submenu['edit.php?post_type=tuyen-dung'][ $key ][0] .= cmb_menu_badge( $contacts_count );
			}
		}
	}
}, 999 );

// Đánh dấu đã xem khi admin mở chi tiết một hồ sơ ứng tuyển / liên hệ.
add_action( 'load-post.php', function () {
	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
	if ( ! $post_id ) return;

	$post_type = get_post_type( $post_id );
	if ( in_array( $post_type, [ 'don_ung_tuyen', 'lien_he_msg' ], true ) ) {
		delete_post_meta( $post_id, '_cmb_is_new' );
	}
} );
