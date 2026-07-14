<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Tinh chỉnh giao diện các field select multi-value (loai_hinh, dia_diem) trên màn hình sửa tin tuyển dụng:
// - Đưa dòng ghi chú (VD: "Có thể chọn nhiều loại hình cùng lúc.") vào trong ngoặc, ngay cạnh tiêu đề (vẫn màu mờ).
// - Style lại khung select2 (multi-select) cho giống các field select/input khác trong cùng group.
add_action( 'acf/input/admin_footer', function () {
	global $post;
	if ( ! $post || $post->post_type !== 'tuyen-dung' ) return;
	$field_keys = [ 'field_421fa40ebb19', 'field_6f15f7e7d6df' ];
	?>
	<style>
		<?php foreach ( $field_keys as $key ) : ?>
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-container,
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-container .selection {
			display: block !important;
			vertical-align: top !important;
		}
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-selection--multiple {
			display: flex !important;
			align-items: center !important;
			box-sizing: border-box !important;
			border: 1px solid #7e8993 !important;
			border-radius: 4px !important;
			height: 40px !important;
			min-height: 40px !important;
			max-height: 40px !important;
			line-height: normal !important;
			box-shadow: none !important;
			overflow: hidden !important;
			white-space: nowrap !important;
			padding: 0 6px !important;
		}
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-container--focus .select2-selection--multiple {
			border-color: #2271b1 !important;
			box-shadow: 0 0 0 1px #2271b1 !important;
		}
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-selection__rendered {
			display: flex !important;
			align-items: center !important;
			flex-wrap: nowrap !important;
			white-space: nowrap !important;
			overflow: hidden !important;
			line-height: normal !important;
			padding: 0 !important;
			margin: 0 !important;
			width: 100% !important;
		}
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-selection__rendered li {
			display: flex !important;
			align-items: center !important;
			float: none !important;
		}
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-selection__placeholder {
			line-height: normal !important;
		}
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-selection--multiple .select2-selection__choice {
			display: inline-flex !important;
			align-items: center !important;
			float: none !important;
			background: #f0f0f1 !important;
			border-color: #dcdcde !important;
			border-radius: 3px !important;
			margin: 0 4px 0 0 !important;
			padding: 0 5px !important;
			line-height: normal !important;
		}
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-search--inline {
			display: flex !important;
			align-items: center !important;
			float: none !important;
			flex: 1 !important;
		}
		.acf-field[data-key="<?php echo esc_attr( $key ); ?>"] .select2-search--inline .select2-search__field {
			height: 20px !important;
			line-height: 20px !important;
			margin: 0 !important;
			padding: 0 !important;
		}
		<?php endforeach; ?>
	</style>
	<script>
	( function ( $ ) {
		var fieldKeys = <?php echo wp_json_encode( $field_keys ); ?>;
		function cmbMoveMultiSelectInstructions() {
			fieldKeys.forEach( function ( key ) {
				var $field = $( '.acf-field[data-key="' + key + '"]' );
				var $instr = $field.find( '> .acf-label p.description' );
				var $label = $field.find( '> .acf-label label' );
				if ( $instr.length && $label.length && ! $label.find( '.cmb-instr-inline' ).length ) {
					$( '<span class="cmb-instr-inline" style="font-weight:normal;color:#646970;font-size:12px;"> (' + $instr.text() + ')</span>' ).appendTo( $label );
					$instr.remove();
				}
			} );
		}
		$( document ).ready( cmbMoveMultiSelectInstructions );
		if ( window.acf && acf.addAction ) {
			acf.addAction( 'append', cmbMoveMultiSelectInstructions );
		}
	} )( jQuery );
	</script>
	<?php
} );
