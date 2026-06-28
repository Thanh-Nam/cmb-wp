<?php
/**
 * template-parts/phan-mem/list.php
 * Section: Software List grouped by category
 * Uses Transient caching (6h) to avoid N WP_Query calls per page load
 */

// Cache all software data grouped by category
$cached = get_transient('cmb_phan_mem_grouped');
if ($cached === false) {
    $software_cats = get_terms([
        'taxonomy'   => 'phan-mem-category',
        'hide_empty' => true,
        'orderby'    => 'term_order',
        'order'      => 'ASC',
    ]);

    $cached = [];
    if ($software_cats && !is_wp_error($software_cats)) {
        foreach ($software_cats as $cat) {
            $software_q = new WP_Query([
                'post_type'      => 'phan-mem',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
                'tax_query'      => [[
                    'taxonomy' => 'phan-mem-category',
                    'field'    => 'term_id',
                    'terms'    => $cat->term_id,
                ]],
            ]);

            if (!$software_q->have_posts()) {
                wp_reset_postdata();
                continue;
            }

            $items = [];
            while ($software_q->have_posts()) {
                $software_q->the_post();
                $gallery     = get_field('software_gallery');
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
    set_transient('cmb_phan_mem_grouped', $cached, 6 * HOUR_IN_SECONDS);
}

$default_thumb = get_template_directory_uri() . '/assets/images/equip-total-station.svg';
?>

<!-- ======= HỆ THỐNG PHẦN MỀM ======= -->
<section class="p-software-list" id="software-list" aria-label="Hệ thống phần mềm">
  <div class="l-container">

    <div class="p-software-list__heading" data-reveal="fade-up">
      <h2 class="p-software-list__section-title">HỆ THỐNG PHẦN MỀM</h2>
    </div>

    <?php if (!empty($cached)) : ?>
    <?php foreach ($cached as $group) : ?>

    <div class="p-software-group" data-reveal="fade-up">
      <div class="p-software-group__header">
        <h3 class="p-software-group__title"><?php echo $group['name']; ?></h3>
      </div>
      <div class="p-software-group__grid">

        <?php foreach ($group['items'] as $delay => $item) : ?>
        <a href="#" class="p-software-card js-software-card"
           data-reveal="fade-up" data-reveal-delay="<?php echo ($delay % 6) + 1; ?>"
           data-title="<?php echo $item['title']; ?>"
           data-images="<?php echo $item['images_json']; ?>"
           data-desc="<?php echo $item['content']; ?>">
          <div class="p-software-card__img-wrap">
            <?php if ($item['thumb_src']) : ?>
            <img src="<?php echo $item['thumb_src']; ?>"
                 alt="<?php echo esc_attr($item['thumb_alt'] ?: $item['title']); ?>"
                 class="p-software-card__img" loading="lazy" />
            <?php else : ?>
            <img src="<?php echo $default_thumb; ?>"
                 alt="<?php echo $item['title']; ?>"
                 class="p-software-card__img" loading="lazy" />
            <?php endif; ?>
          </div>
          <div class="p-software-card__body">
            <h4 class="p-software-card__name"><?php echo $item['title']; ?></h4>
          </div>
        </a>
        <?php endforeach; ?>

      </div>
    </div>

    <?php endforeach; ?>
    <?php else : ?>
    <p style="padding:2rem 0;text-align:center;color:#888;">Chưa có phần mềm nào.</p>
    <?php endif; ?>

  </div>
</section>
<!-- ======= /HỆ THỐNG PHẦN MỀM ======= -->


<!-- ======= SOFTWARE MODAL ======= -->
<div class="p-software-modal" id="software-modal" role="dialog" aria-modal="true" aria-labelledby="sw-modal-title">
  <div class="p-software-modal__overlay" id="sw-modal-overlay"></div>
  <div class="p-software-modal__box">
    <button class="p-software-modal__close" id="sw-modal-close" aria-label="Đóng">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M2 2L14 14M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
    <div class="p-software-modal__images" id="sw-modal-images"></div>
    <div class="p-software-modal__desc">
      <div class="p-software-modal__desc-inner">
        <p class="p-software-modal__desc-text" id="sw-modal-desc"></p>
      </div>
    </div>
  </div>
</div>
<!-- ======= /SOFTWARE MODAL ======= -->
