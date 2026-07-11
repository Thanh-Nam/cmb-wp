<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Cho phép admin upload ảnh đại diện (ảnh nền) khi thêm/sửa term của taxonomy khu vực
// (tuyen-dung-location) và danh mục (tuyen-dung-category) — dùng để thay ảnh random
// picsum/unsplash hiện đang hiển thị ở trang chủ React. Lưu attachment ID vào term meta
// "term_image_id", expose qua REST là "imageUrl".

const CMB_TAXONOMY_IMAGE_TAXONOMIES = [ 'tuyen-dung-location', 'tuyen-dung-category' ];

function cmb_get_term_image_url( $term_id ) {
	$attachment_id = get_term_meta( $term_id, 'term_image_id', true );
	if ( ! $attachment_id ) return null;
	$url = wp_get_attachment_image_url( $attachment_id, 'medium_large' );
	return $url ?: null;
}

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, [ 'term.php', 'edit-tags.php' ], true ) ) return;
	$taxonomy = $_GET['taxonomy'] ?? '';
	if ( ! in_array( $taxonomy, CMB_TAXONOMY_IMAGE_TAXONOMIES, true ) ) return;

	wp_enqueue_media();
	wp_add_inline_script( 'media-editor', <<<JS
		jQuery(function ($) {
			function cmbBindImagePicker(context) {
				context.find('.cmb-term-image-pick').off('click').on('click', function (e) {
					e.preventDefault();
					var wrap = $(this).closest('.cmb-term-image-field');
					var frame = wp.media({ title: 'Chọn ảnh', multiple: false, library: { type: 'image' } });
					frame.on('select', function () {
						var attachment = frame.state().get('selection').first().toJSON();
						wrap.find('.cmb-term-image-input').val(attachment.id);
						wrap.find('.cmb-term-image-preview').html('<img src="' + (attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" style="max-width:120px;height:auto;border-radius:6px;" />');
						wrap.find('.cmb-term-image-remove').show();
					});
					frame.open();
				});
				context.find('.cmb-term-image-remove').off('click').on('click', function (e) {
					e.preventDefault();
					var wrap = $(this).closest('.cmb-term-image-field');
					wrap.find('.cmb-term-image-input').val('');
					wrap.find('.cmb-term-image-preview').html('');
					$(this).hide();
				});
			}
			cmbBindImagePicker($(document));
			$(document).ajaxComplete(function (e, xhr, settings) {
				if (settings.data && String(settings.data).indexOf('action=add-tag') !== -1) {
					cmbBindImagePicker($(document));
				}
			});
		});
		JS
	);
} );

function cmb_render_term_image_field( $term = null ) {
	$term_id = is_object( $term ) ? $term->term_id : 0;
	$attachment_id = $term_id ? get_term_meta( $term_id, 'term_image_id', true ) : '';
	$preview_url   = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : '';
	?>
	<div class="cmb-term-image-field">
		<input type="hidden" name="cmb_term_image_id" class="cmb-term-image-input" value="<?php echo esc_attr( $attachment_id ); ?>" />
		<div class="cmb-term-image-preview"><?php if ( $preview_url ) : ?><img src="<?php echo esc_url( $preview_url ); ?>" style="max-width:120px;height:auto;border-radius:6px;" /><?php endif; ?></div>
		<p>
			<button type="button" class="button cmb-term-image-pick">Chọn ảnh</button>
			<button type="button" class="button cmb-term-image-remove" style="<?php echo $attachment_id ? '' : 'display:none;'; ?>">Bỏ ảnh</button>
		</p>
	</div>
	<?php
}

function cmb_save_term_image_field( $term_id ) {
	if ( ! isset( $_POST['cmb_term_image_id'] ) ) return;
	$attachment_id = (int) $_POST['cmb_term_image_id'];
	if ( $attachment_id > 0 ) {
		update_term_meta( $term_id, 'term_image_id', $attachment_id );
	} else {
		delete_term_meta( $term_id, 'term_image_id' );
	}
}

foreach ( CMB_TAXONOMY_IMAGE_TAXONOMIES as $taxonomy ) {
	add_action( "{$taxonomy}_add_form_fields", function () {
		?>
		<div class="form-field">
			<label>Ảnh đại diện</label>
			<?php cmb_render_term_image_field(); ?>
		</div>
		<?php
	} );

	add_action( "{$taxonomy}_edit_form_fields", function ( $term ) {
		?>
		<tr class="form-field">
			<th scope="row"><label>Ảnh đại diện</label></th>
			<td><?php cmb_render_term_image_field( $term ); ?></td>
		</tr>
		<?php
	} );

	add_action( "create_{$taxonomy}", 'cmb_save_term_image_field' );
	add_action( "edited_{$taxonomy}", 'cmb_save_term_image_field' );
}
