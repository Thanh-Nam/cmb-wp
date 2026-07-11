<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function cmb_jwt_base64url_encode( $data ) {
	return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' );
}

function cmb_jwt_base64url_decode( $data ) {
	return base64_decode( strtr( $data, '-_', '+/' ) );
}

function cmb_jwt_encode( array $payload ) {
	$header   = [ 'typ' => 'JWT', 'alg' => 'HS256' ];
	$segments = [
		cmb_jwt_base64url_encode( wp_json_encode( $header ) ),
		cmb_jwt_base64url_encode( wp_json_encode( $payload ) ),
	];
	$signing_input = implode( '.', $segments );
	$signature     = hash_hmac( 'sha256', $signing_input, JWT_AUTH_SECRET_KEY, true );
	$segments[]    = cmb_jwt_base64url_encode( $signature );
	return implode( '.', $segments );
}

function cmb_jwt_decode( $token ) {
	$parts = explode( '.', (string) $token );
	if ( count( $parts ) !== 3 ) {
		return new WP_Error( 'invalid_token', 'Token không hợp lệ', [ 'status' => 401 ] );
	}
	[ $header64, $payload64, $sig64 ] = $parts;

	$expected_sig = cmb_jwt_base64url_encode( hash_hmac( 'sha256', "$header64.$payload64", JWT_AUTH_SECRET_KEY, true ) );
	if ( ! hash_equals( $expected_sig, $sig64 ) ) {
		return new WP_Error( 'invalid_signature', 'Token không hợp lệ', [ 'status' => 401 ] );
	}

	$payload = json_decode( cmb_jwt_base64url_decode( $payload64 ), true );
	if ( ! is_array( $payload ) ) {
		return new WP_Error( 'invalid_payload', 'Token không hợp lệ', [ 'status' => 401 ] );
	}
	if ( isset( $payload['exp'] ) && time() > $payload['exp'] ) {
		return new WP_Error( 'token_expired', 'Token đã hết hạn', [ 'status' => 401 ] );
	}
	return $payload;
}
