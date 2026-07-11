<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Số di động VN: 10 số bắt đầu 0 hoặc +84, đầu số 3/5/7/8/9 — dùng chung cho form ứng tuyển và liên hệ.
function cmb_is_valid_phone( $phone ) {
	$normalized = preg_replace( '/[\s.\-]/', '', (string) $phone );
	return (bool) preg_match( '/^(\+84|0)(3|5|7|8|9)\d{8}$/', $normalized );
}
