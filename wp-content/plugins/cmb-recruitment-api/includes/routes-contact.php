<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Trang Liên hệ của site tuyển dụng React — dùng chung nội dung với trang "Liên hệ" của
// site WP chính (ACF Options Page slug "lien-he": company_address/phone/email/working_hours,
// repeater "offices"). Fallback giống hệt template-parts/lien-he/*.php khi admin chưa nhập.

function cmb_contact_lines( $raw, $fallback ) {
	if ( ! $raw ) return $fallback;
	$lines = array_values( array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) );
	return $lines ?: $fallback;
}

function cmb_transform_office( $row ) {
	return [
		'name'    => $row['office_name'] ?? '',
		'address' => $row['office_address'] ?? '',
		'phone'   => $row['office_phone'] ?? '',
		'mapSrc'  => $row['office_map_src'] ?? '',
	];
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'cmb/v1', '/contact-info', [
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			$has_acf = function_exists( 'get_field' );

			$address       = $has_acf ? get_field( 'company_address', 'option' ) : '';
			$phones_raw    = $has_acf ? get_field( 'company_phone', 'option' ) : '';
			$emails_raw    = $has_acf ? get_field( 'company_email', 'option' ) : '';
			$working_hours = $has_acf ? get_field( 'company_working_hours', 'option' ) : '';
			$offices       = $has_acf ? get_field( 'offices', 'option' ) : [];

			if ( ! $address ) {
				$address = "Tầng 11, Tòa nhà CMB, 512 Tôn Thất Thuyết,\nCầu Giấy, Hà Nội, Việt Nam";
			}
			if ( ! $working_hours ) {
				$working_hours = "Thứ 2 – Thứ 6\n08:00 – 17:30";
			}
			if ( empty( $offices ) || ! is_array( $offices ) ) {
				$offices = [
					[
						'office_name'    => 'Văn phòng Hà Nội',
						'office_address' => 'Tầng 11, Tòa nhà CMB, 512 Tôn Thất Thuyết, Cầu Giấy, Hà Nội',
						'office_phone'   => '(84) 24 3786 6291',
						'office_map_src' => 'https://maps.google.com/maps?q=512+Ton+That+Thuyet,+Cau+Giay,+Ha+Noi,+Viet+Nam&output=embed&hl=vi',
					],
					[
						'office_name'    => 'VP Hải Phòng',
						'office_address' => 'Số 12 Lô 22 Lê Hồng Phong, Ngô Quyền, Hải Phòng',
						'office_phone'   => '(84) 225 3 768 629',
						'office_map_src' => 'https://maps.google.com/maps?q=Le+Hong+Phong,+Ngo+Quyen,+Hai+Phong,+Viet+Nam&output=embed&hl=vi',
					],
					[
						'office_name'    => 'VP TP HCM',
						'office_address' => 'Tầng 6, Tòa nhà Sailing, 111A Pasteur, Quận 1, TP.HCM',
						'office_phone'   => '(84) 28 6287 4840',
						'office_map_src' => 'https://maps.google.com/maps?q=111A+Pasteur,+Quan+1,+Ho+Chi+Minh+City,+Viet+Nam&output=embed&hl=vi',
					],
				];
			}

			return new WP_REST_Response( [
				'address'       => $address,
				'phones'        => cmb_contact_lines( $phones_raw, [ '(84) 24 3786 6291', '(84) 225 3 760 629' ] ),
				'emails'        => cmb_contact_lines( $emails_raw, [ 'info@cmb.com.vn', 'ir@cmb.com.vn' ] ),
				'workingHours'  => $working_hours,
				'offices'       => array_map( 'cmb_transform_office', $offices ),
			], 200 );
		},
	] );

	register_rest_route( 'cmb/v1', '/contact', [
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => 'cmb_route_submit_contact',
	] );
} );

function cmb_route_submit_contact( WP_REST_Request $req ) {
	$name    = sanitize_text_field( (string) $req->get_param( 'name' ) );
	$address = sanitize_text_field( (string) $req->get_param( 'address' ) );
	$phone   = sanitize_text_field( (string) $req->get_param( 'phone' ) );
	$email   = sanitize_email( (string) $req->get_param( 'email' ) );
	$message = sanitize_textarea_field( (string) $req->get_param( 'message' ) );

	if ( ! $name || ! $phone || ! $email || ! $message ) {
		return new WP_Error( 'missing_fields', 'Thiếu thông tin bắt buộc', [ 'status' => 400 ] );
	}
	if ( ! is_email( $email ) ) {
		return new WP_Error( 'invalid_email', 'Email không hợp lệ', [ 'status' => 400 ] );
	}
	if ( ! cmb_is_valid_phone( $phone ) ) {
		return new WP_Error( 'invalid_phone', 'Số điện thoại không đúng định dạng', [ 'status' => 400 ] );
	}

	$message_id = wp_insert_post( [
		'post_type'   => 'lien_he_msg',
		'post_title'  => $name . ' - Liên hệ',
		'post_status' => 'publish',
	] );

	update_post_meta( $message_id, 'full_name', $name );
	update_post_meta( $message_id, 'address', $address );
	update_post_meta( $message_id, 'phone', $phone );
	update_post_meta( $message_id, 'email', $email );
	update_post_meta( $message_id, 'message', $message );
	update_post_meta( $message_id, '_cmb_is_new', '1' );

	return new WP_REST_Response( [ 'message' => 'Gửi thông tin liên hệ thành công' ], 200 );
}
