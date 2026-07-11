<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Tinh chỉnh giao diện field "Loại hình công việc" (loai_hinh) trên màn hình sửa tin tuyển dụng:
// - Đưa dòng ghi chú "Có thể chọn nhiều loại hình cùng lúc." vào trong ngoặc, ngay cạnh tiêu đề (vẫn màu mờ).
// - Style lại khung select2 (multi-select) cho giống các field select/input khác trong cùng group.
add_action( 'acf/input/admin_footer', function () {
	global $post;
	if ( ! $post || $post->post_type !== 'tuyen-dung' ) return;
	?>
	<style>
		.acf-field[data-key="field_421fa40ebb19"] .select2-container,
		.acf-field[data-key="field_421fa40ebb19"] .select2-container .selection {
			display: block !important;
			vertical-align: top !important;
		}
		.acf-field[data-key="field_421fa40ebb19"] .select2-selection--multiple {
			display: block !important;
			box-sizing: border-box !important;
			border: 1px solid #7e8993 !important;
			border-radius: 4px !important;
			height: 40px !important;
			min-height: 40px !important;
			max-height: 40px !important;
			line-height: 38px !important;
			box-shadow: none !important;
			overflow: hidden !important;
			white-space: nowrap !important;
			padding: 0 6px !important;
		}
		.acf-field[data-key="field_421fa40ebb19"] .select2-container--focus .select2-selection--multiple {
			border-color: #2271b1 !important;
			box-shadow: 0 0 0 1px #2271b1 !important;
		}
		.acf-field[data-key="field_421fa40ebb19"] .select2-selection__rendered {
			display: block !important;
			white-space: nowrap !important;
			overflow: hidden !important;
			line-height: 38px !important;
			padding: 0 !important;
			margin: 0 !important;
		}
		.acf-field[data-key="field_421fa40ebb19"] .select2-selection__rendered li {
			display: inline-block !important;
			float: none !important;
			vertical-align: middle !important;
		}
		.acf-field[data-key="field_421fa40ebb19"] .select2-selection--multiple .select2-selection__choice {
			display: inline-block !important;
			float: none !important;
			vertical-align: middle !important;
			background: #f0f0f1 !important;
			border-color: #dcdcde !important;
			border-radius: 3px !important;
			margin: 0 4px 0 0 !important;
			padding: 0 5px !important;
			line-height: 18px !important;
		}
		.acf-field[data-key="field_421fa40ebb19"] .select2-search--inline {
			display: inline-block !important;
			float: none !important;
			vertical-align: middle !important;
		}
		.acf-field[data-key="field_421fa40ebb19"] .select2-search--inline .select2-search__field {
			height: 20px !important;
			line-height: 20px !important;
			margin: 0 !important;
			padding: 0 !important;
			vertical-align: middle !important;
		}
	</style>
	<script>
	( function ( $ ) {
		function cmbMoveLoaiHinhInstructions() {
			var $field = $( '.acf-field[data-key="field_421fa40ebb19"]' );
			var $instr = $field.find( '> .acf-label p.description' );
			var $label = $field.find( '> .acf-label label' );
			if ( $instr.length && $label.length && ! $label.find( '.cmb-instr-inline' ).length ) {
				$( '<span class="cmb-instr-inline" style="font-weight:normal;color:#646970;font-size:12px;"> (' + $instr.text() + ')</span>' ).appendTo( $label );
				$instr.remove();
			}
		}
		$( document ).ready( cmbMoveLoaiHinhInstructions );
		if ( window.acf && acf.addAction ) {
			acf.addAction( 'append', cmbMoveLoaiHinhInstructions );
		}
	} )( jQuery );
	</script>
	<?php
} );
