<?php
/**
 * template-parts/du-an/archive-stats.php
 * Section: Stats Bar — Dự án
 */
$acf_stats = function_exists('get_field') ? get_field('archive_du_an_stats', 'option') : [];
$stats = $acf_stats ?: [
  ['number' => '300+', 'label' => cmb_txt( 'Dự án đã thực hiện', 'Projects completed' )],
  ['number' => '15+',  'label' => cmb_txt( 'Tỉnh thành hoạt động', 'Provinces served' )],
  ['number' => '20+',  'label' => cmb_txt( 'Năm kinh nghiệm', 'Years of experience' )],
  ['number' => '100%', 'label' => cmb_txt( 'Cam kết chất lượng', 'Quality commitment' )],
];
?>
<!-- ======= STATS BAR ======= -->
<div class="p-projects-stats" id="projects-stats" aria-label="<?php echo esc_attr( cmb_txt( 'Thống kê dự án', 'Project statistics' ) ); ?>">
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
