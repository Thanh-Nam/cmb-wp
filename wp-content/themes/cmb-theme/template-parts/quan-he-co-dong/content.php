<?php
/**
 * template-parts/quan-he-co-dong/content.php
 * Section: IR Tabs + Timeline + Featured Docs
 * Uses Transient caching (2h) per taxonomy term to avoid 2N WP_Query calls
 */
$ir_terms = get_terms([
    'taxonomy'   => 'quan-he-co-dong-category',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);
if (is_wp_error($ir_terms)) $ir_terms = [];

if (empty($ir_terms)) : ?>
<div class="l-container" style="padding:4rem 0;text-align:center;color:#888;"><?php echo cmb_txt('Chưa có tài liệu nào.', 'No documents available yet.'); ?></div>
<?php return; endif; ?>


<?php
/**
 * Icon cho hàng pill (row 2) — chọn theo từ khóa trong slug, có icon mặc định
 * dự phòng cho các category chưa khớp từ khóa nào.
 */
function cmb_ir_tab_icon($slug) {
    if ($slug === 'cong-bo-thong-tin') {
        return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M2 8V12H4.5L9 15V5L4.5 8H2Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11.5 7C12.5 7.8 13 8.9 13 10C13 11.1 12.5 12.2 11.5 13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M13.5 4.5C15.2 5.9 16.2 7.9 16.2 10C16.2 12.1 15.2 14.1 13.5 15.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>';
    }
    if (strpos($slug, 'tai-chinh') !== false) {
        return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4 2.5H12L16 6.5V17.5H4V2.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M12 2.5V6.5H16" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M7 14V11.5M10 14V9.5M13 14V12.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>';
    }
    if (strpos($slug, 'thuong-nien') !== false) {
        return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3" y="2.5" width="14" height="15" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M6.5 6.5H13.5M6.5 10H13.5M6.5 13.5H10.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>';
    }
    if (strpos($slug, 'quan-tri') !== false) {
        return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3 17.5V8L10 3L17 8V17.5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M7 17.5V12H13V17.5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>';
    }
    if (strpos($slug, 'tai-lieu') !== false || strpos($slug, 'co-dong') !== false) {
        return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3 5.5C3 4.67157 3.67157 4 4.5 4H8L9.5 5.5H15.5C16.3284 5.5 17 6.17157 17 7V14.5C17 15.3284 16.3284 16 15.5 16H4.5C3.67157 16 3 15.3284 3 14.5V5.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>';
    }
    return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4 2.5H12L16 6.5V17.5H4V2.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M12 2.5V6.5H16" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M6.5 10H13.5M6.5 13.5H11" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>';
}

/**
 * "Công bố thông tin" luôn thuộc row 2 (kiểu header nổi bật), bất kể thứ tự
 * alphabet đưa nó vào vị trí nào — tách riêng ra trước, rồi mới chia phần còn
 * lại thành row 1 (tối đa 4) và phần còn dư của row 2.
 */
$ir_header = null;
$ir_rest   = [];
foreach ($ir_terms as $term) {
    if ($term->slug === 'cong-bo-thong-tin') {
        $ir_header = $term;
    } else {
        $ir_rest[] = $term;
    }
}

$ir_row1 = array_slice($ir_rest, 0, 4);
$ir_row2 = array_slice($ir_rest, 4);
if ($ir_header) {
    array_unshift($ir_row2, $ir_header);
}

// Tab/panel mặc định active — dựa theo slug của item đầu row 1 (không phải $i===0
// của $ir_terms gốc), để tab đang bấm sáng và panel đang hiện luôn khớp nhau dù
// "Công bố thông tin" bị tách ra khỏi thứ tự gốc.
$ir_default_slug = !empty($ir_row1) ? $ir_row1[0]->slug : (!empty($ir_terms) ? $ir_terms[0]->slug : null);
?>
<!-- ======= TAB NAVIGATION ======= -->
<nav class="p-ir-tabs" id="ir-tabs" aria-label="<?php echo esc_attr(cmb_txt('Danh mục quan hệ cổ đông', 'Shareholder Relations Categories')); ?>">
  <div class="l-container">
    <div class="p-ir-tabs__box">
    <ul class="p-ir-tabs__list" role="tablist">
      <?php foreach ($ir_row1 as $term) :
        $is_default = ($term->slug === $ir_default_slug);
      ?>
      <li class="p-ir-tabs__item" role="presentation">
        <button class="p-ir-tabs__link<?php echo $is_default ? ' is-active' : ''; ?>"
                id="<?php echo esc_attr('tab-' . $term->slug); ?>"
                role="tab"
                aria-selected="<?php echo $is_default ? 'true' : 'false'; ?>"
                aria-controls="<?php echo esc_attr('panel-' . $term->slug); ?>"
                data-target="<?php echo esc_attr('panel-' . $term->slug); ?>">
          <span class="p-ir-tabs__label"><?php echo $term->name; ?></span>
        </button>
      </li>
      <?php endforeach; ?>
    </ul>

    <?php if (!empty($ir_row2)) : ?>
    <ul class="p-ir-tabs__list p-ir-tabs__list--pill" role="tablist">
      <?php foreach ($ir_row2 as $term) :
        $is_header = ($term->slug === 'cong-bo-thong-tin');
      ?>
      <li class="p-ir-tabs__item p-ir-tabs__item--pill<?php echo $is_header ? ' p-ir-tabs__item--header' : ''; ?>" role="presentation">
        <?php if ($is_header) : ?>
        <span class="p-ir-tabs__link p-ir-tabs__link--pill p-ir-tabs__link--header" aria-hidden="true">
          <span class="p-ir-tabs__icon"><?php echo cmb_ir_tab_icon($term->slug); ?></span>
          <span class="p-ir-tabs__label"><?php echo $term->name; ?></span>
        </span>
        <?php else : ?>
        <button class="p-ir-tabs__link p-ir-tabs__link--pill"
                id="<?php echo esc_attr('tab-' . $term->slug); ?>"
                role="tab"
                aria-selected="false"
                aria-controls="<?php echo esc_attr('panel-' . $term->slug); ?>"
                data-target="<?php echo esc_attr('panel-' . $term->slug); ?>">
          <span class="p-ir-tabs__icon"><?php echo cmb_ir_tab_icon($term->slug); ?></span>
          <span class="p-ir-tabs__label"><?php echo $term->name; ?></span>
        </button>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    </div>
  </div>
</nav>
<!-- ======= /TAB NAVIGATION ======= -->


<!-- ======= NỘI DUNG CHÍNH ======= -->
<section class="p-ir-body" id="ir-content">

  <?php foreach ($ir_terms as $i => $term) :

    // Grouped timeline data (cached 2h per term)
    $cache_key = 'cmb_ir_grouped_' . $term->term_id;
    $grouped   = get_transient($cache_key);
    if ($grouped === false) {
        $term_q = new WP_Query([
            'post_type'      => 'quan-he-co-dong',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => [[
                'taxonomy' => 'quan-he-co-dong-category',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ]],
        ]);
        $grouped = [];
        while ($term_q->have_posts()) {
            $term_q->the_post();
            $raw    = get_field('document_updated', false, false);
            $ts     = $raw ? strtotime((string) $raw) : get_the_time('U');
            $year   = date('Y', $ts);
            $docs   = get_field('documents');
            $doc0   = (is_array($docs) && !empty($docs[0]['file']['url'])) ? $docs[0] : null;
            $grouped[$year][] = [
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'date_md'   => date('d/m', $ts),
                'pdf_url'   => $doc0 ? $doc0['file']['url'] : '',
            ];
        }
        wp_reset_postdata();
        krsort($grouped);
        set_transient($cache_key, $grouped, 2 * HOUR_IN_SECONDS);
    }

    // Featured docs (cached 2h per term)
    $feat_cache_key = 'cmb_ir_featured_' . $term->term_id;
    $feat_data      = get_transient($feat_cache_key);
    if ($feat_data === false) {
        $feat_q = new WP_Query([
            'post_type'      => 'quan-he-co-dong',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => [[
                'taxonomy' => 'quan-he-co-dong-category',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ]],
        ]);
        $feat_data = [];
        while ($feat_q->have_posts()) {
            $feat_q->the_post();
            $feat_docs = get_field('documents');
            $feat_doc0 = (is_array($feat_docs) && !empty($feat_docs[0]['file']['url'])) ? $feat_docs[0] : null;
            $thumb_id  = get_post_thumbnail_id();
            $thumb_src = $thumb_id ? wp_get_attachment_image_src($thumb_id, 'thumbnail') : false;
            $feat_data[] = [
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'pdf_url'   => $feat_doc0 ? $feat_doc0['file']['url'] : '',
                'size'      => $feat_doc0 ? ($feat_doc0['size'] ?: '') : '',
                'thumb_src' => $thumb_src ? $thumb_src[0] : '',
                'thumb_alt' => $thumb_id ? (get_post_meta($thumb_id, '_wp_attachment_image_alt', true) ?: get_the_title()) : '',
            ];
        }
        wp_reset_postdata();
        set_transient($feat_cache_key, $feat_data, 2 * HOUR_IN_SECONDS);
    }
  ?>

  <div class="p-ir-panel<?php echo ($term->slug === $ir_default_slug) ? ' is-active' : ''; ?>"
       id="<?php echo esc_attr('panel-' . $term->slug); ?>"
       role="tabpanel"
       aria-labelledby="<?php echo esc_attr('tab-' . $term->slug); ?>">
    <div class="p-ir-panel__inner">
      <div class="l-container">

        <?php if (!empty($grouped)) : ?>
        <div class="p-ir-panel__filters">
          <select class="p-ir-panel__select" data-ir-year-filter
                  aria-label="<?php echo esc_attr(cmb_txt('Lọc theo năm', 'Filter by year')); ?>">
            <option value=""><?php echo cmb_txt('Tất cả năm', 'All years'); ?></option>
            <?php foreach (array_keys($grouped) as $year) : ?>
            <option value="<?php echo esc_attr($year); ?>"><?php echo esc_html($year); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php endif; ?>

        <div class="p-ir-panel__grid">

          <!-- TIMELINE -->
          <div class="p-ir-timeline" data-reveal="fade-right">
            <?php if (!empty($grouped)) :
              $first_year = true;
              foreach ($grouped as $year => $posts) :
                $first_post = true;
            ?>
            <div class="p-ir-timeline__group" data-year="<?php echo esc_attr($year); ?>">
              <div class="p-ir-timeline__year">
                <span class="p-ir-timeline__year-ghost" aria-hidden="true"><?php echo substr($year, -2); ?></span>
                <span class="p-ir-timeline__year-label"><?php echo $year; ?></span>
              </div>
              <div class="p-ir-timeline__items">
                <?php foreach ($posts as $post_data) :
                  $highlight  = ($first_year && $first_post) ? ' p-ir-timeline__item--highlight' : '';
                  $first_post = false;
                ?>
                <div class="p-ir-timeline__item<?php echo $highlight; ?>">
                  <span class="p-ir-timeline__date"><?php echo $post_data['date_md']; ?></span>
                  <a href="<?php echo $post_data['permalink']; ?>" class="p-ir-timeline__title"><?php echo $post_data['title']; ?></a>
                  <?php if ($post_data['pdf_url']) : ?>
                  <a href="<?php echo $post_data['pdf_url']; ?>" class="p-ir-timeline__action" aria-label="<?php echo esc_attr(cmb_txt('Tải PDF', 'Download PDF')); ?>" download>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                      <path d="M8 2V10M8 10L5 7M8 10L11 7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M2 13H14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                  </a>
                  <?php else : ?>
                  <span class="p-ir-timeline__action p-ir-timeline__action--disabled" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                      <path d="M8 2V10M8 10L5 7M8 10L11 7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M2 13H14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                  </span>
                  <?php endif; ?>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php $first_year = false; endforeach;
            else : ?>
            <p style="color:#888;padding:1rem 0;"><?php echo cmb_txt('Chưa có tài liệu.', 'No documents available.'); ?></p>
            <?php endif; ?>
          </div>
          <!-- /TIMELINE -->


          <!-- TÀI LIỆU NỔI BẬT -->
          <aside class="p-ir-featured" aria-label="<?php echo esc_attr(cmb_txt('Tài liệu nổi bật', 'Featured Documents')); ?>" data-reveal="fade-left">
            <div class="p-ir-featured__heading">
              <h2 class="p-ir-featured__title"><?php echo cmb_txt('Tài liệu nổi bật', 'Featured Documents'); ?></h2>
            </div>
            <?php if (!empty($feat_data)) : ?>
            <ul class="p-ir-featured__list" role="list">
              <?php foreach ($feat_data as $doc) : ?>
              <li>
                <a href="<?php echo $doc['permalink']; ?>" class="p-ir-feat-doc">
                  <div class="p-ir-feat-doc__thumb">
                    <?php if ($doc['thumb_src']) : ?>
                    <img src="<?php echo $doc['thumb_src']; ?>"
                         alt="<?php echo $doc['thumb_alt']; ?>"
                         class="p-ir-feat-doc__thumb-img" loading="lazy" />
                    <?php else : ?>
                    <svg width="48" height="62" viewBox="0 0 48 62" fill="none" aria-hidden="true">
                      <rect width="48" height="62" rx="4" fill="#0379CC"/>
                      <rect width="5" height="62" fill="#015A99"/>
                      <path d="M12 20H36M12 27H30M12 34H22" stroke="white" stroke-width="2" stroke-opacity="0.5" stroke-linecap="round"/>
                      <path d="M12 44H36M12 51H28" stroke="white" stroke-width="2" stroke-opacity="0.3" stroke-linecap="round"/>
                    </svg>
                    <?php endif; ?>
                  </div>
                  <div class="p-ir-feat-doc__body">
                    <h3 class="p-ir-feat-doc__title"><?php echo $doc['title']; ?></h3>
                    <div class="p-ir-feat-doc__meta">
                      <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                        <rect x="1" y="0.5" width="10" height="11" rx="1.5" stroke="currentColor" stroke-width="1.2"/>
                        <path d="M3.5 4H8.5M3.5 6.5H6.5" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                      </svg>
                      <span><?php echo $doc['size'] ? $doc['size'] : cmb_txt('Xem chi tiết', 'View details'); ?></span>
                    </div>
                  </div>
                </a>
                <?php if ($doc['pdf_url']) : ?>
                <a href="<?php echo $doc['pdf_url']; ?>" class="p-ir-feat-doc__download" aria-label="<?php echo esc_attr(cmb_txt('Tải PDF', 'Download PDF')); ?>" download>
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M8 2V10M8 10L5 7M8 10L11 7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 13H14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                  </svg>
                  <?php echo cmb_txt('Tải PDF', 'Download PDF'); ?>
                </a>
                <?php endif; ?>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </aside>
          <!-- /TÀI LIỆU NỔI BẬT -->

        </div>
      </div>
    </div>
  </div>

  <?php endforeach; ?>

</section>
<!-- ======= /NỘI DUNG CHÍNH ======= -->
