<?php
/**
 * Template Name: Giới thiệu
 * Tương ứng: gioi-thieu.html
 */
get_header(); ?>

  <main class="site-main" id="main-content">

    <!-- ======= CMB INTRO ======= -->
    <?php
      $bg      = get_field('about_banner_bg',      'option');
      $logo    = get_field('about_banner_logo',    'option');
      $slogan  = get_field('about_banner_slogan',  'option');
      $title   = get_field('about_banner_title',   'option');
      $content = get_field('about_banner_content', 'option');
    ?>
    <section class="p-cmb-intro" id="cmb-intro" aria-label="Hơn nửa thế kỷ đồng hành">

      <div class="p-cmb-intro__bg" aria-hidden="true">
        <img src="<?php echo esc_url($bg['url'] ?? get_template_directory_uri() . '/assets/images/hero_port.jpg'); ?>" alt="" role="presentation" loading="lazy" />
      </div>
      <div class="p-cmb-intro__overlay" aria-hidden="true"></div>

      <div class="l-container">
        <div class="p-cmb-intro__inner">

          <div class="p-cmb-intro__body" data-reveal="fade-up">
            <?php if ($logo) : ?>
            <div class="p-cmb-intro__logo-wrap" aria-hidden="true">
              <img src="<?php echo esc_url($logo['url']); ?>" alt="CMB" class="p-cmb-intro__logo" />
            </div>
            <?php endif; ?>
            <?php if ($slogan) : ?>
            <p class="p-cmb-intro__tagline"><?php echo esc_html($slogan); ?></p>
            <?php endif; ?>
            <?php if ($title) : ?>
            <h2 class="p-cmb-intro__title"><?php echo nl2br(esc_html($title)); ?></h2>
            <?php endif; ?>
            <?php if ($content) : ?>
            <div class="p-cmb-intro__text"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
          </div>

        </div>
      </div>

    </section>
    <!-- ======= /CMB INTRO ======= -->


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
              <img src="<?php echo esc_url($icon['url']); ?>" alt="" width="60" height="60" />
            </div>
            <?php endif; ?>
            <span class="p-stats__value"><?php echo esc_html($number); ?></span>
            <span class="p-stats__label"><?php echo esc_html($content); ?></span>
          </div>
          <?php endwhile; endif; ?>

        </div>
      </div>
    </section>
    <!-- ======= /STATS ======= -->


    <!-- ======= VISION ======= -->
    <?php
      $vision  = get_field('about_vision',  'option');
      $mission = get_field('about_mission', 'option');
    ?>
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
                    <img src="<?php echo esc_url($item['icon']['url']); ?>" alt="" />
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


    <!-- ======= CORE VALUES ======= -->
    <section class="p-values" id="values" aria-label="Giá trị cốt lõi CMB">
      <div class="l-container">
        <div class="p-values__frame" data-reveal="fade-up">

          <!-- SVG border: top+sides không mask, chỉ bottom dùng mask fade -->
          <svg class="p-values__border" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 100"
            preserveAspectRatio="none" aria-hidden="true">
            <defs>
              <linearGradient id="pvFG1R" gradientUnits="userSpaceOnUse" x1="425" y1="0" x2="395" y2="0">
                <stop offset="0%" stop-color="white" />
                <stop offset="100%" stop-color="black" />
              </linearGradient>
              <linearGradient id="pvFG1L" gradientUnits="userSpaceOnUse" x1="355" y1="0" x2="325" y2="0">
                <stop offset="0%" stop-color="black" />
                <stop offset="100%" stop-color="white" />
              </linearGradient>
              <linearGradient id="pvFG2R" gradientUnits="userSpaceOnUse" x1="275" y1="0" x2="245" y2="0">
                <stop offset="0%" stop-color="white" />
                <stop offset="100%" stop-color="black" />
              </linearGradient>
              <linearGradient id="pvFG2L" gradientUnits="userSpaceOnUse" x1="205" y1="0" x2="175" y2="0">
                <stop offset="0%" stop-color="black" />
                <stop offset="100%" stop-color="white" />
              </linearGradient>
              <!-- maskUnits=userSpaceOnUse: vùng mask tính theo SVG coordinates, không bị clamp vào bbox -->
              <mask id="pvMask" maskUnits="userSpaceOnUse" x="-10" y="90" width="620" height="20">
                <rect x="-10" y="90" width="620" height="20" fill="white" />
                <rect x="355" y="90" width="40" height="20" fill="black" />
                <rect x="395" y="90" width="30" height="20" fill="url(#pvFG1R)" />
                <rect x="325" y="90" width="30" height="20" fill="url(#pvFG1L)" />
                <rect x="205" y="90" width="40" height="20" fill="black" />
                <rect x="245" y="90" width="30" height="20" fill="url(#pvFG2R)" />
                <rect x="175" y="90" width="30" height="20" fill="url(#pvFG2L)" />
              </mask>
            </defs>

            <!-- Top + sides: KHÔNG mask → không bao giờ bị cắt -->
            <path d="M 8 0
                 Q 300 -2.5 592 0
                 Q 600 0 600 8
                 Q 602.5 50 600 92
                 Q 600 100 592 100
                 M 8 100
                 Q 0 100 0 92
                 Q -2.5 50 0 8
                 Q 0 0 8 0" fill="none" stroke="#0379CC" stroke-opacity="0.4" stroke-width="1.5"
              vector-effect="non-scaling-stroke" />

            <!-- Bottom only: mask fade tại 2 gap -->
            <path d="M 592 100 Q 300 102.5 8 100" fill="none" stroke="#0379CC" stroke-opacity="0.4" stroke-width="1.5"
              vector-effect="non-scaling-stroke" mask="url(#pvMask)" />
          </svg>

          <div class="p-values__title-bar">
            <h2 class="p-values__title"><?php echo esc_html(get_field('about_value_title', 'option') ?: 'GIÁ TRỊ CỐT LÕI'); ?></h2>
          </div>

          <div class="p-values__grid">

            <?php if (have_rows('about_value_list', 'option')) : while (have_rows('about_value_list', 'option')) : the_row();
              $icon    = get_sub_field('icon');
              $title   = get_sub_field('title');
              $content = get_sub_field('content');
            ?>
            <div class="p-values__item">
              <?php if ($icon) : ?>
              <div class="p-values__icon" aria-hidden="true">
                <img src="<?php echo esc_url($icon['url']); ?>" alt="" />
              </div>
              <?php endif; ?>
              <h3 class="p-values__name"><?php echo esc_html($title); ?></h3>
              <p class="p-values__desc"><?php echo esc_html($content); ?></p>
            </div>
            <?php endwhile; endif; ?>

          </div>
        </div>
      </div>
    </section>
    <!-- ======= /CORE VALUES ======= -->


    <!-- ======= LEADERSHIP ======= -->
    <section class="p-leadership" id="leadership" aria-label="Ban lãnh đạo CMB">

      <div class="p-leadership__bg" aria-hidden="true">
        <img src="assets/images/bg-bld.png" alt="" role="presentation" loading="lazy" />
      </div>

      <div class="l-container">

        <!-- Header -->
        <?php
          $leadership_title    = get_field('about_leadership_title',    'option');
          $leadership_subtitle = get_field('about_leadership_subtitle', 'option');
          $leadership_list     = get_field('about_leadership_list',     'option');
        ?>
        <div class="p-leadership__header" data-reveal="fade-up">
          <div class="p-leadership__title-wrap">
            <span class="p-leadership__title-line" aria-hidden="true"></span>
            <h2 class="p-leadership__title"><?php echo esc_html($leadership_title ?: 'BAN LÃNH ĐẠO'); ?></h2>
            <span class="p-leadership__title-line" aria-hidden="true"></span>
          </div>
          <?php if ($leadership_subtitle) : ?>
          <p class="p-leadership__subtitle"><?php echo esc_html($leadership_subtitle); ?></p>
          <?php endif; ?>
        </div>

        <!-- Swiper -->
        <div class="p-leadership__slider-wrap">
          <div class="swiper p-leadership__swiper" id="leadership-swiper">
            <div class="swiper-wrapper">

              <?php if (!empty($leadership_list)) :
                // Render real slides
                foreach ($leadership_list as $member) : ?>
              <div class="swiper-slide">
                <div class="p-leadership__card">
                  <div class="p-leadership__photo-outer">
                    <div class="p-leadership__photo-wrap">
                      <img src="<?php echo esc_url($member['img']['url']); ?>"
                        alt="<?php echo esc_attr($member['name'] . ' - ' . $member['position']); ?>"
                        class="p-leadership__photo" loading="lazy" />
                    </div>
                  </div>
                  <h3 class="p-leadership__name"><?php echo esc_html($member['name']); ?></h3>
                  <p class="p-leadership__role"><?php echo esc_html($member['position']); ?></p>
                  <div class="p-leadership__underline"></div>
                </div>
              </div>
              <?php endforeach;
                // Duplicate slides for swiper loop
                foreach ($leadership_list as $member) : ?>
              <div class="swiper-slide" aria-hidden="true">
                <div class="p-leadership__card">
                  <div class="p-leadership__photo-outer">
                    <div class="p-leadership__photo-wrap">
                      <img src="<?php echo esc_url($member['img']['url']); ?>" alt=""
                        class="p-leadership__photo" loading="lazy" />
                    </div>
                  </div>
                  <h3 class="p-leadership__name"><?php echo esc_html($member['name']); ?></h3>
                  <p class="p-leadership__role"><?php echo esc_html($member['position']); ?></p>
                  <div class="p-leadership__underline"></div>
                </div>
              </div>
              <?php endforeach; endif; ?>

            </div>
          </div>
        </div>

      </div>
    </section>
    <!-- ======= /LEADERSHIP ======= -->


    <!-- ======= ACHIEVEMENTS ======= -->
    <?php
      $ach_row1 = get_field('about_achievements_row-1', 'option');
      $ach_row2 = get_field('about_achievements_row-2', 'option');
      $ach_row3 = get_field('about_achievements_row-3', 'option');
    ?>
    <section class="p-achievements" id="achievements" aria-label="Thành tựu đạt được của CMB">
      <div class="l-container">

        <div class="p-achievements__header" data-reveal="fade-up">
          <h2 class="c-section-title c-section-title--white">THÀNH TỰU</h2>
        </div>

        <!-- Hàng 1: 2 huy chương (to hơn) -->
        <div class="p-achievements__row p-achievements__row--top" data-reveal="fade-up" data-reveal-delay="1">
          <?php foreach (['about_achievements_medal-1', 'about_achievements_medal-2'] as $key) :
            $medal = $ach_row1[$key] ?? null;
            if (empty($medal['img'])) continue;
            $img = $medal['img'];
            if (is_numeric($img)) {
              $src = wp_get_attachment_image_src((int)$img, 'large');
              $img_url = $src ? $src[0] : '';
              $img_alt = get_post_meta((int)$img, '_wp_attachment_image_alt', true) ?: strip_tags($medal['name'] ?? '');
            } else {
              $img_url = is_array($img) ? ($img['url'] ?? '') : $img;
              $img_alt = is_array($img) ? ($img['alt'] ?? strip_tags($medal['name'] ?? '')) : strip_tags($medal['name'] ?? '');
            }
            if (empty($img_url)) continue;
          ?>
          <div class="p-achievements__medal-item">
            <div class="p-achievements__medal-img-wrap p-achievements__medal-img-wrap--lg">
              <img src="<?php echo esc_url($img_url); ?>"
                   alt="<?php echo esc_attr($img_alt); ?>"
                   loading="lazy" />
            </div>
            <?php if (!empty($medal['name'])) : ?>
            <p class="p-achievements__medal-caption"><?php echo nl2br(esc_html($medal['name'])); ?></p>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Hàng 2: 3 huy chương (nhỏ hơn) -->
        <div class="p-achievements__row p-achievements__row--bottom" data-reveal="fade-up" data-reveal-delay="2">
          <?php foreach (['about_achievements_medal-3', 'about_achievements_medal-4', 'about_achievements_medal-5'] as $key) :
            $medal = $ach_row2[$key] ?? null;
            if (empty($medal['img'])) continue;
            $img = $medal['img'];
            if (is_numeric($img)) {
              $src = wp_get_attachment_image_src((int)$img, 'large');
              $img_url = $src ? $src[0] : '';
              $img_alt = get_post_meta((int)$img, '_wp_attachment_image_alt', true) ?: strip_tags($medal['name'] ?? '');
            } else {
              $img_url = is_array($img) ? ($img['url'] ?? '') : $img;
              $img_alt = is_array($img) ? ($img['alt'] ?? strip_tags($medal['name'] ?? '')) : strip_tags($medal['name'] ?? '');
            }
            if (empty($img_url)) continue;
          ?>
          <div class="p-achievements__medal-item">
            <div class="p-achievements__medal-img-wrap">
              <img src="<?php echo esc_url($img_url); ?>"
                   alt="<?php echo esc_attr($img_alt); ?>"
                   loading="lazy" />
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


    <!-- ======= PARTNER ======= -->
    <?php get_template_part('template-parts/section-partner'); ?>
    <!-- ======= /PARTNER ======= -->
</main>

<?php get_footer();
