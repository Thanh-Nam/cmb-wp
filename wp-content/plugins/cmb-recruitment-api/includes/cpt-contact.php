<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	register_post_type( 'lien_he_msg', [
		'labels' => [
			'name'          => 'Liên hệ',
			'singular_name' => 'Liên hệ',
			'menu_name'     => 'Liên hệ',
			'all_items'     => 'Tất cả liên hệ',
			'view_item'     => 'Xem liên hệ',
			'search_items'  => 'Tìm liên hệ',
			'not_found'     => 'Không có liên hệ nào',
		],
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'edit.php?post_type=tuyen-dung',
		'show_in_rest'    => false,
		'supports'        => [ 'title' ],
		'capability_type' => 'post',
		'menu_icon'       => 'dashicons-email-alt2',
	] );
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'cmb_lien_he_msg_info', 'Thông tin liên hệ', 'cmb_render_lien_he_msg_meta_box', 'lien_he_msg', 'normal', 'high' );
} );

function cmb_render_lien_he_msg_meta_box( $post ) {
	$full_name = get_post_meta( $post->ID, 'full_name', true );
	$address   = get_post_meta( $post->ID, 'address', true );
	$phone     = get_post_meta( $post->ID, 'phone', true );
	$email     = get_post_meta( $post->ID, 'email', true );
	$message   = get_post_meta( $post->ID, 'message', true );
	?>
	<table class="form-table">
		<tr><th>Họ tên</th><td><?php echo esc_html( $full_name ); ?></td></tr>
		<tr><th>Địa chỉ</th><td><?php echo esc_html( $address ); ?></td></tr>
		<tr><th>Điện thoại</th><td><?php echo esc_html( $phone ); ?></td></tr>
		<tr><th>Email</th><td><?php echo esc_html( $email ); ?></td></tr>
		<tr><th>Nội dung</th><td><?php echo nl2br( esc_html( $message ) ); ?></td></tr>
	</table>
	<?php
}
