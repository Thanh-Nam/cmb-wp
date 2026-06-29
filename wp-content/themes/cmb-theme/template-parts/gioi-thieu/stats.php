<?php
/**
 * template-parts/gioi-thieu/stats.php
 * Section: Stats (Những con số ấn tượng)
 */
$is_en = function_exists('pll_current_language') && pll_current_language() === 'en';

// Collect all items into array
$stat_items = [];
if (have_rows('about_stat_list', 'option')) {
  while (have_rows('about_stat_list', 'option')) {
    the_row();
    $stat_items[] = [
      'icon'    => get_sub_field('icon'),
      'number'  => get_sub_field('number'),
      'content' => ($is_en && get_sub_field('content_en')) ? get_sub_field('content_en') : get_sub_field('content'),
    ];
  }
}

// Swap icons of position 3 and 4 (index 2 and 3)
if (isset($stat_items[2], $stat_items[3])) {
  $tmp = $stat_items[2]['icon'];
  $stat_items[2]['icon'] = $stat_items[3]['icon'];
  $stat_items[3]['icon'] = $tmp;
}
?>
<!-- ======= STATS ======= -->
<section class="p-stats" id="stats" aria-label="Những con số ấn tượng">
  <div class="l-container">
    <div class="p-stats__inner">

      <?php foreach ($stat_items as $i => $stat) : ?>
      <div class="p-stats__item p-stats__item--anim" style="--delay: <?php echo $i * 0.1; ?>s">
        <?php if ($stat['icon']) : ?>
        <div class="p-stats__icon" aria-hidden="true">
          <img src="<?php echo $stat['icon']['url']; ?>" alt="" width="60" height="60" loading="lazy" />
        </div>
        <?php endif; ?>
        <span class="p-stats__value" data-countup><?php echo $stat['number']; ?></span>
        <span class="p-stats__label"><?php echo $stat['content']; ?></span>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>
<!-- ======= /STATS ======= -->
