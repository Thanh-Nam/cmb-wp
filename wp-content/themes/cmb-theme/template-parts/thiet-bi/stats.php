<?php
/**
 * template-parts/thiet-bi/stats.php
 * Section: Stats Bar — Thiết bị
 */
$stats = [
  ['number' => '35+', 'label' => 'Thiết bị khảo sát'],
  ['number' => '12+', 'label' => 'Thiết bị thủy văn'],
  ['number' => '8+',  'label' => 'Drone chuyên dụng'],
  ['number' => '100%','label' => 'Hiệu chuẩn định kỳ'],
];
?>
<!-- ======= STATS BAR ======= -->
<div class="p-projects-stats" id="equipment-stats" aria-label="Thống kê thiết bị">
  <div class="l-container">
    <div class="p-projects-stats__inner">
      <?php foreach ($stats as $i => $stat) : ?>
      <div class="p-projects-stats__item p-projects-stats__item--anim" style="--delay: <?php echo $i * 0.1; ?>s">
        <span class="p-projects-stats__number" data-countup><?php echo $stat['number']; ?></span>
        <span class="p-projects-stats__label"><?php echo $stat['label']; ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<!-- ======= /STATS BAR ======= -->
