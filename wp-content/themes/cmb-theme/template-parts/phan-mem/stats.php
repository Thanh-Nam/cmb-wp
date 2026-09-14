<?php
/**
 * template-parts/phan-mem/stats.php
 * Section: Stats Bar — Phần mềm
 */
$acf_stats = function_exists('get_field') ? get_field('archive_phan_mem_stats', 'option') : [];
$stats = $acf_stats ?: [
  ['number' => '20+',  'label' => cmb_txt('Phần mềm chuyên dụng', 'Specialized Software')],
  ['number' => '10+',  'label' => cmb_txt('Phần mềm khảo sát', 'Survey Software')],
  ['number' => '5+',   'label' => cmb_txt('Phần mềm thiết kế', 'Design Software')],
  ['number' => '100%', 'label' => cmb_txt('Bản quyền hợp lệ', 'Valid Licenses')],
];
?>
<!-- ======= STATS BAR ======= -->
<div class="p-projects-stats" id="software-stats" aria-label="<?php echo esc_attr(cmb_txt('Thống kê phần mềm', 'Software Statistics')); ?>">
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
