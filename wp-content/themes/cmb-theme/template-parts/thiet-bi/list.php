<?php
/**
 * template-parts/thiet-bi/list.php
 * Section: Equipment List grouped by category
 * Uses Transient caching (6h) to avoid N WP_Query calls per page load
 */

// Cache all equipment data grouped by category
$cached = get_transient('cmb_thiet_bi_grouped');
if ($cached === false) {
    $equipment_cats = get_terms([
        'taxonomy'   => 'thiet-bi-category',
        'hide_empty' => true,
        'orderby'    => 'term_order',
        'order'      => 'ASC',
    ]);

    $cached = [];
    if ($equipment_cats && !is_wp_error($equipment_cats)) {
        foreach ($equipment_cats as $cat) {
            $equip_q = new WP_Query([
                'post_type'      => 'thiet-bi',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
                'tax_query'      => [[
                    'taxonomy' => 'thiet-bi-category',
                    'field'    => 'term_id',
                    'terms'    => $cat->term_id,
                ]],
            ]);

            if (!$equip_q->have_posts()) {
                wp_reset_postdata();
                continue;
            }

            $items = [];
            while ($equip_q->have_posts()) {
                $equip_q->the_post();
                $gallery     = get_field('device_gallery');
                $image_urls  = $gallery ? array_column($gallery, 'url') : [];
                $thumb_id    = get_post_thumbnail_id();
                $thumb_src   = $thumb_id ? wp_get_attachment_image_src($thumb_id, 'medium') : false;

                $items[] = [
                    'id'          => get_the_ID(),
                    'title'       => get_the_title(),
                    'title_attr'  => esc_attr(get_the_title()),
                    'thumb_src'   => $thumb_src ? $thumb_src[0] : '',
                    'thumb_alt'   => $thumb_src ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '',
                    'images_json' => wp_json_encode($image_urls),
                    'content'     => wp_strip_all_tags(get_the_content()),
                ];
            }
            wp_reset_postdata();

            $cached[] = [
                'name'  => $cat->name,
                'items' => $items,
            ];
        }
    }
    set_transient('cmb_thiet_bi_grouped', $cached, 6 * HOUR_IN_SECONDS);
}

$default_thumb = get_template_directory_uri() . '/assets/images/equip-total-station.svg';
?>

<!-- ======= HỆ THỐNG THIẾT BỊ ======= -->
<section class="p-equipment-list" id="equipment-list" aria-label="Hệ thống thiết bị khảo sát">
  <div class="l-container">

    <div class="p-equipment-list__heading" data-reveal="fade-up">
      <h2 class="p-equipment-list__section-title">HỆ THỐNG THIẾT BỊ</h2>
    </div>

    <?php if (!empty($cached)) : ?>
    <?php foreach ($cached as $group) : ?>

    <div class="p-equipment-group" data-reveal="fade-up">
      <div class="p-equipment-group__header">
        <h3 class="p-equipment-group__title"><?php echo esc_html($group['name']); ?></h3>
      </div>
      <div class="p-equipment-group__grid">

        <?php foreach ($group['items'] as $delay => $item) : ?>
        <a href="#" class="p-equipment-card js-equip-card"
           data-reveal="fade-up" data-reveal-delay="<?php echo ($delay % 6) + 1; ?>"
           data-title="<?php echo esc_attr($item['title']); ?>"
           data-images="<?php echo esc_attr($item['images_json']); ?>"
           data-desc="<?php echo esc_attr($item['content']); ?>">
          <div class="p-equipment-card__img-wrap">
            <?php if ($item['thumb_src']) : ?>
            <img src="<?php echo esc_url($item['thumb_src']); ?>"
                 alt="<?php echo esc_attr($item['thumb_alt'] ?: $item['title']); ?>"
                 class="p-equipment-card__img" loading="lazy" />
            <?php else : ?>
            <img src="<?php echo esc_url($default_thumb); ?>"
                 alt="<?php echo esc_attr($item['title']); ?>"
                 class="p-equipment-card__img" loading="lazy" />
            <?php endif; ?>
          </div>
          <div class="p-equipment-card__body">
            <h4 class="p-equipment-card__name"><?php echo esc_html($item['title']); ?></h4>
          </div>
        </a>
        <?php endforeach; ?>

      </div>
    </div>

    <?php endforeach; ?>
    <?php else : ?>
    <p style="padding:2rem 0;text-align:center;color:#888;">Chưa có thiết bị nào.</p>
    <?php endif; ?>

  </div>
</section>
<!-- ======= /HỆ THỐNG THIẾT BỊ ======= -->


<!-- ======= EQUIPMENT MODAL ======= -->
<div class="p-equipment-modal" id="equipment-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
  <div class="p-equipment-modal__overlay" id="modal-overlay"></div>
  <div class="p-equipment-modal__box">
    <button class="p-equipment-modal__close" id="modal-close" aria-label="Đóng">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M2 2L14 14M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
    <div class="p-equipment-modal__images" id="modal-images"></div>
    <div class="p-equipment-modal__desc">
      <div class="p-equipment-modal__desc-inner">
        <p class="p-equipment-modal__desc-text" id="modal-desc"></p>
      </div>
    </div>
  </div>
</div>
<!-- ======= /EQUIPMENT MODAL ======= -->
