<?php
/**
 * template-parts/du-an/archive-stats.php
 * Section: Stats Bar — Dự án
 */
$stats = function_exists('get_field') ? get_field('archive_du_an_stats', 'option') : [];
?>
<!-- ======= STATS BAR ======= -->
<div class="p-projects-stats" id="projects-stats" aria-label="Thống kê dự án">
  <div class="l-container">
    <div class="p-projects-stats__inner">
      <?php if ($stats) : ?>
      <?php foreach ($stats as $i => $stat) : ?>
      <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="<?php echo $i + 1; ?>">
        <span class="p-projects-stats__number"><?php echo esc_html($stat['number']); ?></span>
        <span class="p-projects-stats__label"><?php echo esc_html($stat['label']); ?></span>
      </div>
      <?php endforeach; ?>
      <?php else : ?>
      <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="1">
        <span class="p-projects-stats__number">300+</span>
        <span class="p-projects-stats__label">Dự án đã thực hiện</span>
      </div>
      <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="2">
        <span class="p-projects-stats__number">15+</span>
        <span class="p-projects-stats__label">Tỉnh thành hoạt động</span>
      </div>
      <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="3">
        <span class="p-projects-stats__number">20+</span>
        <span class="p-projects-stats__label">Năm kinh nghiệm</span>
      </div>
      <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="4">
        <span class="p-projects-stats__number">100%</span>
        <span class="p-projects-stats__label">Cam kết chất lượng</span>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- ======= /STATS BAR ======= -->
