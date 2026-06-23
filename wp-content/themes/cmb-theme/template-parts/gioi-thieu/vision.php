<?php
/**
 * template-parts/gioi-thieu/vision.php
 * Section: Vision & Mission
 */
$vision  = get_field('about_vision',  'option');
$mission = get_field('about_mission', 'option');
?>
<!-- ======= VISION ======= -->
<section class="p-vision" id="vision" aria-label="Tầm nhìn và sứ mệnh CMB">
  <div class="l-container">
    <div class="p-vision__card" data-reveal="fade-up">

      <div class="p-vision__half p-vision__half--left">
        <div class="p-vision__bg-wrap" aria-hidden="true">
          <img src="<?php echo esc_url($vision['img']['url'] ?? get_template_directory_uri() . '/assets/images/banner-1.png'); ?>"
               alt="" role="presentation" class="p-vision__bg-img" loading="lazy" />
          <div class="p-vision__overlay"></div>
        </div>
        <div class="p-vision__left-content">
          <h2 class="p-vision__left-title"><?php echo esc_html($vision['title'] ?? 'TẦM NHÌN'); ?></h2>
          <div class="p-vision__left-text"><?php echo wp_kses_post($vision['content'] ?? ''); ?></div>
        </div>
      </div>

      <div class="p-vision__half p-vision__half--right">
        <div class="p-vision__right-content">
          <h2 class="p-vision__right-title"><?php echo esc_html($mission['about_mission_title'] ?? 'SỨ MỆNH'); ?></h2>
          <ul class="p-vision__list" role="list">

            <?php if (!empty($mission['mission_list'])) : foreach ($mission['mission_list'] as $item) : ?>
            <li class="p-vision__item">
              <?php if (!empty($item['icon'])) : ?>
              <div class="p-vision__item-icon" aria-hidden="true">
                <img src="<?php echo esc_url($item['icon']['url']); ?>" alt="" loading="lazy" />
              </div>
              <?php endif; ?>
              <div class="p-vision__item-body">
                <p class="p-vision__item-text">
                  <?php if (!empty($item['title'])) : ?>
                  <strong class="p-vision__item-label"><?php echo esc_html($item['title']); ?></strong>
                  <?php endif; ?>
                  <?php echo esc_html($item['content'] ?? ''); ?>
                </p>
              </div>
            </li>
            <?php endforeach; endif; ?>

          </ul>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- ======= /VISION ======= -->
