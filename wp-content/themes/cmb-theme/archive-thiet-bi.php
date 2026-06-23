<?php get_header(); ?>

<main class="site-main" id="main-content">

    <!-- ======= PAGE HERO ======= -->
    <section class="p-page-hero" id="equipment-hero" aria-label="Thiết bị khảo sát CMB">

      <div class="p-page-hero__image-side">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
          alt="Thiết bị khảo sát hiện đại - CMB tư vấn xây dựng công trình hàng hải"
          class="p-page-hero__image"
          loading="eager" />
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">

        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page">Thiết bị khảo sát</span>
        </nav>

        <div class="p-page-hero__content">
          <h1 class="p-page-hero__title">THIẾT BỊ KHẢO SÁT</h1>
          <p class="p-page-hero__subtitle">
            Hệ thống thiết bị hiện đại, đồng bộ phục vụ<br />
            khảo sát địa hình, địa chất và thủy hải văn.
          </p>
        </div>

      </div>

    </section>
    <!-- ======= /PAGE HERO ======= -->


    <!-- ======= STATS BAR ======= -->
    <div class="p-projects-stats" id="equipment-stats" aria-label="Thống kê thiết bị">
      <div class="l-container">
        <div class="p-projects-stats__inner">

          <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="1">
            <span class="p-projects-stats__number">35+</span>
            <span class="p-projects-stats__label">Thiết bị khảo sát</span>
          </div>

          <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="2">
            <span class="p-projects-stats__number">12+</span>
            <span class="p-projects-stats__label">Thiết bị thủy văn</span>
          </div>

          <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="3">
            <span class="p-projects-stats__number">8+</span>
            <span class="p-projects-stats__label">Drone chuyên dụng</span>
          </div>

          <div class="p-projects-stats__item" data-reveal="fade-up" data-reveal-delay="4">
            <span class="p-projects-stats__number">100%</span>
            <span class="p-projects-stats__label">Hiệu chuẩn định kỳ</span>
          </div>

        </div>
      </div>
    </div>
    <!-- ======= /STATS BAR ======= -->


    <!-- ======= HỆ THỐNG THIẾT BỊ ======= -->
    <section class="p-equipment-list" id="equipment-list" aria-label="Hệ thống thiết bị khảo sát">
      <div class="l-container">

        <div class="p-equipment-list__heading" data-reveal="fade-up">
          <h2 class="p-equipment-list__section-title">HỆ THỐNG THIẾT BỊ</h2>
        </div>

        <?php
        $equipment_cats = get_terms([
            'taxonomy'   => 'thiet-bi-category',
            'hide_empty' => true,
            'orderby'    => 'term_order',
            'order'      => 'ASC',
        ]);

        if ($equipment_cats && !is_wp_error($equipment_cats)) :
            foreach ($equipment_cats as $cat) :

                $equip_q = new WP_Query([
                    'post_type'      => 'thiet-bi',
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order title',
                    'order'          => 'ASC',
                    'tax_query'      => [[
                        'taxonomy' => 'thiet-bi-category',
                        'field'    => 'term_id',
                        'terms'    => $cat->term_id,
                    ]],
                ]);

                if (!$equip_q->have_posts()) {
                    wp_reset_postdata();
                    continue;
                }
        ?>

        <div class="p-equipment-group" data-reveal="fade-up">
          <div class="p-equipment-group__header">
            <h3 class="p-equipment-group__title"><?php echo esc_html($cat->name); ?></h3>
          </div>
          <div class="p-equipment-group__grid">

            <?php
            $delay = 0;
            while ($equip_q->have_posts()) : $equip_q->the_post();
                $delay++;

                $gallery = get_field('device_gallery');
                $image_urls = [];
                if ($gallery) {
                    foreach ($gallery as $img) {
                        $image_urls[] = $img['url'];
                    }
                }
                $images_json = esc_attr(wp_json_encode($image_urls));
                $content     = esc_attr(wp_strip_all_tags(get_the_content()));
            ?>
            <a href="#" class="p-equipment-card js-equip-card"
               data-reveal="fade-up" data-reveal-delay="<?php echo $delay; ?>"
               data-title="<?php the_title_attribute(); ?>"
               data-images="<?php echo $images_json; ?>"
               data-desc="<?php echo $content; ?>">
              <div class="p-equipment-card__img-wrap">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('medium', ['class' => 'p-equipment-card__img', 'loading' => 'lazy']); ?>
                <?php else : ?>
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/equip-total-station.svg"
                       alt="<?php the_title_attribute(); ?>"
                       class="p-equipment-card__img" loading="lazy" />
                <?php endif; ?>
              </div>
              <div class="p-equipment-card__body">
                <h4 class="p-equipment-card__name"><?php the_title(); ?></h4>
              </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>

          </div>
        </div>

        <?php
            endforeach;
        else :
        ?>
        <p style="padding:2rem 0;text-align:center;color:#888;">Chưa có thiết bị nào.</p>
        <?php endif; ?>

      </div>
    </section>
    <!-- ======= /HỆ THỐNG THIẾT BỊ ======= -->

  </main>
  <!-- ======= /MAIN ======= -->


  <!-- ======= EQUIPMENT MODAL ======= -->
  <div class="p-equipment-modal" id="equipment-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="p-equipment-modal__overlay" id="modal-overlay"></div>
    <div class="p-equipment-modal__box">
      <button class="p-equipment-modal__close" id="modal-close" aria-label="Đóng">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M2 2L14 14M14 2L2 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
      <div class="p-equipment-modal__images" id="modal-images"></div>
      <div class="p-equipment-modal__desc">
        <div class="p-equipment-modal__desc-inner">
          <p class="p-equipment-modal__desc-text" id="modal-desc"></p>
        </div>
      </div>
    </div>
  </div>
  <!-- ======= /EQUIPMENT MODAL ======= -->

<?php get_footer(); ?>
