<?php
/**
 * template-parts/thiet-bi/stats.php
 * Section: Stats Bar — Thiết bị
 */
$acf_stats = function_exists('get_field') ? get_field('archive_thiet_bi_stats', 'option') : [];
$stats = $acf_stats ?: [
  ['number' => '35+', 'label' => cmb_txt('Thiết bị khảo sát', 'Survey Equipment')],
  ['number' => '12+', 'label' => cmb_txt('Thiết bị thủy văn', 'Hydrographic Equipment')],
  ['number' => '8+',  'label' => cmb_txt('Drone chuyên dụng', 'Specialized Drones')],
  ['number' => '100%','label' => cmb_txt('Hiệu chuẩn định kỳ', 'Periodic Calibration')],
];
?>
<!-- ======= STATS BAR ======= -->
<div class="p-projects-stats" id="equipment-stats" aria-label="<?php echo esc_attr(cmb_txt('Thống kê thiết bị', 'Equipment Statistics')); ?>">
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
