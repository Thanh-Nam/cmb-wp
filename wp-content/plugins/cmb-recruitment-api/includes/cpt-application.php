<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	register_post_type( 'don_ung_tuyen', [
		'labels' => [
			'name'          => 'Hồ sơ ứng tuyển',
			'singular_name' => 'Hồ sơ ứng tuyển',
			'menu_name'     => 'Hồ sơ ứng tuyển',
			'all_items'     => 'Tất cả hồ sơ',
			'view_item'     => 'Xem hồ sơ',
			'search_items'  => 'Tìm hồ sơ',
			'not_found'     => 'Không có hồ sơ nào',
		],
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'edit.php?post_type=tuyen-dung',
		'show_in_rest'    => false,
		'supports'        => [ 'title' ],
		'capability_type' => 'post',
		'menu_icon'       => 'dashicons-media-document',
		// Hồ sơ chỉ được tạo qua form ứng tuyển ở frontend — admin chỉ xem, không tạo tay.
		'map_meta_cap'    => true,
		'capabilities'    => [ 'create_posts' => 'do_not_allow' ],
	] );
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'cmb_don_ung_tuyen_info', 'Thông tin ứng viên', 'cmb_render_don_ung_tuyen_meta_box', 'don_ung_tuyen', 'normal', 'high' );
} );

function cmb_render_don_ung_tuyen_meta_box( $post ) {
	$full_name = get_post_meta( $post->ID, 'full_name', true );
	$address   = get_post_meta( $post->ID, 'address', true );
	$phone     = get_post_meta( $post->ID, 'phone', true );
	$email     = get_post_meta( $post->ID, 'email', true );
	$job_id    = get_post_meta( $post->ID, 'job_id', true );
	$cv_id     = get_post_meta( $post->ID, 'cv_attachment_id', true );
	$job_title = $job_id ? get_the_title( $job_id ) : '—';
	$cv_url    = $cv_id ? wp_get_attachment_url( $cv_id ) : '';
	?>
	<table class="form-table">
		<tr><th>Họ tên</th><td><?php echo esc_html( $full_name ); ?></td></tr>
		<tr><th>Địa chỉ</th><td><?php echo esc_html( $address ); ?></td></tr>
		<tr><th>Điện thoại</th><td><?php echo esc_html( $phone ); ?></td></tr>
		<tr><th>Email</th><td><?php echo esc_html( $email ); ?></td></tr>
		<tr><th>Vị trí ứng tuyển</th><td><?php echo esc_html( $job_title ); ?></td></tr>
		<tr><th>CV</th><td><?php echo $cv_url ? '<a href="' . esc_url( $cv_url ) . '" target="_blank">Xem CV</a>' : '—'; ?></td></tr>
	</table>
	<?php
}
