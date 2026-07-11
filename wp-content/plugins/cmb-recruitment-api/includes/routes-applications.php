<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', function () {
	register_rest_route( 'cmb/v1', '/applications', [
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => 'cmb_route_create_application',
	] );
} );

function cmb_route_create_application( WP_REST_Request $req ) {
	$full_name = sanitize_text_field( (string) $req->get_param( 'fullName' ) );
	$address   = sanitize_text_field( (string) $req->get_param( 'address' ) );
	$phone     = sanitize_text_field( (string) $req->get_param( 'phone' ) );
	$email     = sanitize_email( (string) $req->get_param( 'email' ) );
	$job_id    = (int) $req->get_param( 'jobId' );

	if ( ! $full_name || ! $email || ! $phone ) {
		return new WP_Error( 'missing_fields', 'Thiếu thông tin bắt buộc', [ 'status' => 400 ] );
	}
	if ( ! is_email( $email ) ) {
		return new WP_Error( 'invalid_email', 'Email không hợp lệ', [ 'status' => 400 ] );
	}
	if ( ! cmb_is_valid_phone( $phone ) ) {
		return new WP_Error( 'invalid_phone', 'Số điện thoại không đúng định dạng', [ 'status' => 400 ] );
	}
	if ( $job_id && ! get_post( $job_id ) ) {
		return new WP_Error( 'invalid_job', 'Tin tuyển dụng không tồn tại', [ 'status' => 400 ] );
	}

	$files = $req->get_file_params();
	if ( empty( $files['cvFile'] ) ) {
		return new WP_Error( 'missing_cv', 'Vui lòng đính kèm CV', [ 'status' => 400 ] );
	}
	$file = $files['cvFile'];

	if ( $file['size'] > 5 * 1024 * 1024 ) {
		return new WP_Error( 'file_too_large', 'File CV vượt quá 5MB', [ 'status' => 400 ] );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';

	$allowed_mimes = [
		'pdf'  => 'application/pdf',
		'doc'  => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	];

	$moved = wp_handle_upload( $file, [ 'test_form' => false, 'mimes' => $allowed_mimes ] );
	if ( isset( $moved['error'] ) ) {
		return new WP_Error( 'upload_failed', $moved['error'], [ 'status' => 400 ] );
	}

	$attachment_id = wp_insert_attachment( [
		'post_mime_type' => $moved['type'],
		'post_title'     => sanitize_file_name( basename( $moved['file'] ) ),
		'post_status'    => 'inherit',
	], $moved['file'] );

	$application_id = wp_insert_post( [
		'post_type'   => 'don_ung_tuyen',
		'post_title'  => $full_name . ' - ' . ( $job_id ? get_the_title( $job_id ) : 'Ứng tuyển' ),
		'post_status' => 'publish',
	] );

	update_post_meta( $application_id, 'full_name', $full_name );
	update_post_meta( $application_id, 'address', $address );
	update_post_meta( $application_id, 'phone', $phone );
	update_post_meta( $application_id, 'email', $email );
	update_post_meta( $application_id, 'job_id', $job_id );
	update_post_meta( $application_id, 'cv_attachment_id', $attachment_id );
	update_post_meta( $application_id, '_cmb_is_new', '1' );

	return new WP_REST_Response( [
		'id'      => $application_id,
		'message' => 'Nộp hồ sơ thành công',
	], 201 );
}
