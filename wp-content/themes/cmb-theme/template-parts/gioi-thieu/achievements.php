<?php
/**
 * template-parts/gioi-thieu/achievements.php
 * Section: Achievements (Thành tựu)
 */
$ach_row1 = get_field('about_achievements_row-1', 'option');
$ach_row2 = get_field('about_achievements_row-2', 'option');
$ach_row3 = get_field('about_achievements_row-3', 'option');

?>
<!-- ======= ACHIEVEMENTS ======= -->
<section class="p-achievements" id="achievements" aria-label="Thành tựu đạt được của CMB">
  <div class="l-container">

    <div class="p-achievements__header" data-reveal="fade-up">
      <h2 class="c-section-title c-section-title--white">THÀNH TỰU</h2>
    </div>

    <!-- Hàng 1: 2 huy chương lớn -->
    <div class="p-achievements__row p-achievements__row--top" data-reveal="fade-up" data-reveal-delay="1">
      <?php foreach (['about_achievements_medal-1', 'about_achievements_medal-2'] as $key) :
        $medal = $ach_row1[$key] ?? null;
        if (empty($medal)) continue;
        [$img_url, $img_alt] = cmb_get_medal_img($medal);
        if (empty($img_url)) continue;
      ?>
      <div class="p-achievements__medal-item">
        <div class="p-achievements__medal-img-wrap p-achievements__medal-img-wrap--lg">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy" />
        </div>
        <?php if (!empty($medal['name'])) : ?>
        <p class="p-achievements__medal-caption"><?php echo nl2br(esc_html($medal['name'])); ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Hàng 2: 3 huy chương nhỏ -->
    <div class="p-achievements__row p-achievements__row--bottom" data-reveal="fade-up" data-reveal-delay="2">
      <?php foreach (['about_achievements_medal-3', 'about_achievements_medal-4', 'about_achievements_medal-5'] as $key) :
        $medal = $ach_row2[$key] ?? null;
        if (empty($medal)) continue;
        [$img_url, $img_alt] = cmb_get_medal_img($medal);
        if (empty($img_url)) continue;
      ?>
      <div class="p-achievements__medal-item">
        <div class="p-achievements__medal-img-wrap">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy" />
        </div>
        <?php if (!empty($medal['name'])) : ?>
        <p class="p-achievements__medal-caption"><?php echo nl2br(esc_html($medal['name'])); ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Bằng khen -->
    <?php if (!empty($ach_row3)) : ?>
    <div class="p-achievements__certs" data-reveal="fade-up" data-reveal-delay="3">
      <div class="p-achievements__cert-row">
        <?php if (!empty($ach_row3['img-1'])) : ?>
        <div class="p-achievements__cert-item">
          <img src="<?php echo esc_url($ach_row3['img-1']['url']); ?>"
               alt="<?php echo esc_attr($ach_row3['img-1']['alt']); ?>"
               class="p-achievements__cert-img" loading="lazy" />
        </div>
        <?php endif; ?>
        <?php if (!empty($ach_row3['img-2'])) : ?>
        <div class="p-achievements__cert-item">
          <img src="<?php echo esc_url($ach_row3['img-2']['url']); ?>"
               alt="<?php echo esc_attr($ach_row3['img-2']['alt']); ?>"
               class="p-achievements__cert-img" loading="lazy" />
        </div>
        <?php endif; ?>
      </div>
      <?php if (!empty($ach_row3['name'])) : ?>
      <p class="p-achievements__cert-caption"><?php echo nl2br(esc_html($ach_row3['name'])); ?></p>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </div>
</section>
<!-- ======= /ACHIEVEMENTS ======= -->
