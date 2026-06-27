<?php
/**
 * template-parts/gioi-thieu/stats.php
 * Section: Stats (Những con số ấn tượng)
 */
?>
<!-- ======= STATS ======= -->
<section class="p-stats" id="stats" aria-label="Những con số ấn tượng">
  <div class="l-container">
    <div class="p-stats__inner">

      <?php if (have_rows('about_stat_list', 'option')) : $delay = 0; while (have_rows('about_stat_list', 'option')) : the_row(); $delay++;
        $icon    = get_sub_field('icon');
        $number  = get_sub_field('number');
        $content = get_sub_field('content');
      ?>
      <div class="p-stats__item" data-reveal="fade-up" data-reveal-delay="<?php echo $delay; ?>">
        <?php if ($icon) : ?>
        <div class="p-stats__icon" aria-hidden="true">
          <img src="<?php echo $icon['url']; ?>" alt="" width="60" height="60" loading="lazy" />
        </div>
        <?php endif; ?>
        <span class="p-stats__value"><?php echo $number; ?></span>
        <span class="p-stats__label"><?php echo $content; ?></span>
      </div>
      <?php endwhile; endif; ?>

    </div>
  </div>
</section>
<!-- ======= /STATS ======= -->
