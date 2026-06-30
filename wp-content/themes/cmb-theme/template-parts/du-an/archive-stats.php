<?php
/**
 * template-parts/du-an/archive-stats.php
 * Section: Stats Bar — Dự án
 */
$acf_stats = function_exists('get_field') ? get_field('archive_du_an_stats', 'option') : [];
$stats = $acf_stats ?: [
  ['number' => '300+', 'label' => 'Dự án đã thực hiện'],
  ['number' => '15+',  'label' => 'Tỉnh thành hoạt động'],
  ['number' => '20+',  'label' => 'Năm kinh nghiệm'],
  ['number' => '100%', 'label' => 'Cam kết chất lượng'],
];
?>
<!-- ======= STATS BAR ======= -->
<div class="p-projects-stats" id="projects-stats" aria-label="Thống kê dự án">
  <div class="l-container">
    <div class="p-projects-stats__inner">
      <?php foreach ($stats as $i => $stat) : ?>
      <div class="p-projects-stats__item p-projects-stats__item--anim" style="--delay: <?php echo $i * 0.1; ?>s">
        <span class="p-projects-stats__number" data-countup><?php echo $stat['number']; ?></span>
        <span class="p-projects-stats__label"><?php echo cmb_arr($stat, 'label'); ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<!-- ======= /STATS BAR ======= -->
