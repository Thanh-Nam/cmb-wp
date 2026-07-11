<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', function () {
	register_rest_route( 'cmb/v1', '/auth/register', [
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => 'cmb_route_register',
	] );
	register_rest_route( 'cmb/v1', '/auth/login', [
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => 'cmb_route_login',
	] );
} );

function cmb_user_to_response( WP_User $user ) {
	return [
		'id'       => $user->ID,
		'email'    => $user->user_email,
		'fullName' => $user->display_name,
	];
}

function cmb_issue_session( WP_User $user ) {
	$token = cmb_jwt_encode( [
		'sub' => $user->ID,
		'iat' => time(),
		'exp' => time() + ( 7 * DAY_IN_SECONDS ),
	] );
	return [ 'token' => $token, 'user' => cmb_user_to_response( $user ) ];
}

function cmb_route_register( WP_REST_Request $req ) {
	$full_name = sanitize_text_field( (string) $req->get_param( 'fullName' ) );
	$email     = sanitize_email( (string) $req->get_param( 'email' ) );
	$password  = (string) $req->get_param( 'password' );

	if ( ! $full_name || ! $email || ! $password ) {
		return new WP_Error( 'missing_fields', 'Thiếu thông tin bắt buộc', [ 'status' => 400 ] );
	}
	if ( ! is_email( $email ) ) {
		return new WP_Error( 'invalid_email', 'Email không hợp lệ', [ 'status' => 400 ] );
	}
	if ( email_exists( $email ) ) {
		return new WP_Error( 'email_exists', 'Email đã được sử dụng', [ 'status' => 409 ] );
	}
	if ( strlen( $password ) < 6 ) {
		return new WP_Error( 'weak_password', 'Mật khẩu phải có ít nhất 6 ký tự', [ 'status' => 400 ] );
	}

	$username = sanitize_user( current( explode( '@', $email ) ) . '_' . wp_generate_password( 4, false ), true );
	$user_id  = wp_insert_user( [
		'user_login'   => $username,
		'user_email'   => $email,
		'user_pass'    => $password,
		'display_name' => $full_name,
		'role'         => 'subscriber',
	] );
	if ( is_wp_error( $user_id ) ) {
		return new WP_Error( 'register_failed', $user_id->get_error_message(), [ 'status' => 400 ] );
	}

	return new WP_REST_Response( cmb_issue_session( get_user_by( 'id', $user_id ) ), 201 );
}

function cmb_route_login( WP_REST_Request $req ) {
	$email    = sanitize_email( (string) $req->get_param( 'email' ) );
	$password = (string) $req->get_param( 'password' );

	if ( ! $email || ! $password ) {
		return new WP_Error( 'missing_fields', 'Thiếu email hoặc mật khẩu', [ 'status' => 400 ] );
	}

	$user = get_user_by( 'email', $email );
	if ( ! $user ) {
		return new WP_Error( 'invalid_credentials', 'Email hoặc mật khẩu không đúng', [ 'status' => 401 ] );
	}
	$checked = wp_authenticate( $user->user_login, $password );
	if ( is_wp_error( $checked ) ) {
		return new WP_Error( 'invalid_credentials', 'Email hoặc mật khẩu không đúng', [ 'status' => 401 ] );
	}

	return new WP_REST_Response( cmb_issue_session( $checked ), 200 );
}
