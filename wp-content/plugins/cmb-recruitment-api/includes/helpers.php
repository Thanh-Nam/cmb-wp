<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Số di động VN: 10 số bắt đầu 0 hoặc +84, đầu số 3/5/7/8/9 — dùng chung cho form ứng tuyển và liên hệ.
function cmb_is_valid_phone( $phone ) {
	$normalized = preg_replace( '/[\s.\-]/', '', (string) $phone );
	return (bool) preg_match( '/^(\+84|0)(3|5|7|8|9)\d{8}$/', $normalized );
}

// Các trường plain-text (title, list item...) được frontend render trực tiếp dưới dạng text node
// (không dangerouslySetInnerHTML), nên entity như &#8211;, &nbsp; (sinh ra bởi wptexturize/TinyMCE)
// phải được decode ở API trước khi trả JSON, nếu không trình duyệt sẽ hiện nguyên ký tự entity.
function cmb_decode_entities( $str ) {
	return $str === null || $str === '' ? $str : html_entity_decode( (string) $str, ENT_QUOTES, 'UTF-8' );
}

// Nội dung Page/Post đôi khi được biên tập viên chèn link tuyệt đối trỏ về domain lúc soạn thảo
// (VD: http://localhost:8888/... khi soạn trên máy dev). Nếu giữ nguyên, link sẽ sai domain khi
// nội dung đó được xem trên môi trường khác (production, staging...). Hàm này rút gọn mọi href
// trỏ tới domain hiện tại (home_url) hoặc các domain dev quen thuộc (localhost, 127.0.0.1, *.local,
// *.test) về đường dẫn tương đối — nhờ đó link luôn trỏ đúng domain đang được truy cập, bất kể
// nội dung được soạn ở môi trường nào.
function cmb_make_links_relative( $html ) {
	if ( ! $html ) return $html;

	// Toàn bộ group host dùng non-capturing (?:...) để chỉ số capturing group cố định,
	// không phụ thuộc vào nhánh nào khớp.
	$hosts = [
		preg_quote( home_url(), '/' ),
		'https?:\/\/localhost(?::\d+)?',
		'https?:\/\/127\.0\.0\.1(?::\d+)?',
		'https?:\/\/[a-z0-9-]+\.(?:local|test)(?::\d+)?',
	];

	$pattern = '/(href|src)=(["\'])(?:' . implode( '|', $hosts ) . ')(\/[^"\']*)?\2/i';

	return preg_replace_callback( $pattern, function ( $m ) {
		$path = ! empty( $m[3] ) ? $m[3] : '/';
		return $m[1] . '=' . $m[2] . $path . $m[2];
	}, $html );
}
