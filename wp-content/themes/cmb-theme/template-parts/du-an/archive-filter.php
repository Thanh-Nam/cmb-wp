<?php
/**
 * template-parts/du-an/archive-filter.php
 * Section: Filter Tabs — Lĩnh vực dự án
 */
$project_cats = get_terms(['taxonomy' => 'du-an-category', 'hide_empty' => false]);
?>
<!-- ======= FILTER TABS ======= -->
<div class="p-projects-filter" id="projects-filter" role="navigation" aria-label="Lọc dự án theo lĩnh vực">
  <div class="l-container">
    <div class="p-projects-filter__inner">
      <div class="p-projects-filter__tabs" role="tablist" aria-label="Lĩnh vực dự án">
        <button class="p-projects-filter__tab is-active" role="tab" aria-selected="true"
                data-filter="all" id="filter-tab-all">Tất cả</button>
        <?php if ($project_cats && !is_wp_error($project_cats)) : ?>
        <?php foreach ($project_cats as $cat) : ?>
        <button class="p-projects-filter__tab" role="tab" aria-selected="false"
                data-filter="<?php echo esc_attr($cat->slug); ?>"
                id="filter-tab-<?php echo esc_attr($cat->slug); ?>">
          <?php echo esc_html($cat->name); ?>
        </button>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- ======= /FILTER TABS ======= -->
