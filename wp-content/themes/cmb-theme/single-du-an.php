<?php
/**
 * Template: Single — Dự án chi tiết
 * Post type: du-an
 */
get_header();

$hero_img      = get_field('project_hero_image');
$terms         = get_the_terms(get_the_ID(), 'du-an-category');
$term_name     = ($terms && !is_wp_error($terms)) ? $terms[0]->name : 'Dự án Tiêu biểu';
$term_ids      = ($terms && !is_wp_error($terms)) ? wp_list_pluck($terms, 'term_id') : [];

$owner         = get_field('project_owner');
$location      = get_field('project_location_detail');
$scale         = get_field('project_scale');
$timeline      = get_field('project_timeline');
$services      = get_field('project_services');
$services_list = get_field('project_services_list');
$gallery       = get_field('project_gallery');
$tech_specs    = get_field('project_tech_specs');

$related = new WP_Query([
    'post_type'      => 'du-an',
    'posts_per_page' => 4,
    'post__not_in'   => [get_the_ID()],
    'tax_query'      => $term_ids ? [[
        'taxonomy' => 'du-an-category',
        'field'    => 'term_id',
        'terms'    => $term_ids,
    ]] : [],
]);
?>

    <main class="site-main" id="main-content">

    <!-- ======= PAGE HERO ======= -->
    <section class="p-page-hero" id="project-hero" aria-label="<?php echo esc_attr(get_the_title()); ?>">

      <div class="p-page-hero__image-side" aria-hidden="true">
        <?php if ($hero_img) : ?>
        <img src="<?php echo esc_url($hero_img['url']); ?>"
             alt="<?php echo esc_attr($hero_img['alt'] ?: get_the_title()); ?>"
             class="p-page-hero__image" loading="eager" />
        <?php elseif (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('large', ['class' => 'p-page-hero__image', 'loading' => 'eager']); ?>
        <?php endif; ?>
      </div>

      <div class="p-page-hero__fade" aria-hidden="true"></div>

      <div class="l-container">
        <nav class="p-page-hero__breadcrumb" aria-label="Đường dẫn">
          <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <a href="<?php echo esc_url(get_post_type_archive_link('du-an')); ?>">Dự án tiêu biểu</a>
          <span class="p-page-hero__breadcrumb-sep" aria-hidden="true">›</span>
          <span class="p-page-hero__breadcrumb-current" aria-current="page">Chi tiết dự án</span>
        </nav>

        <div class="p-page-hero__content">
          <span class="p-page-hero__label"><?php echo esc_html($term_name); ?></span>
          <?php the_title('<h1 class="p-page-hero__title">', '</h1>'); ?>
        </div>
      </div>

    </section>
    <!-- ======= /PAGE HERO ======= -->


    <!-- ======= PROJECT INFO BAR ======= -->
    <div class="p-project-infobar" id="project-infobar" aria-label="Thông tin tổng quan dự án">
      <div class="l-container">
        <div class="p-project-infobar__inner">

          <?php if ($owner) : ?>
          <div class="p-project-infobar__item" id="infobar-owner">
            <div class="p-project-infobar__icon" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                <circle cx="11" cy="7" r="4" stroke="#0379CC" stroke-width="1.5"/>
                <path d="M3 19C3 15.134 6.134 12 10 12H12C15.866 12 19 15.134 19 19" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </div>
            <span class="p-project-infobar__label">CHỦ ĐẦU TƯ</span>
            <span class="p-project-infobar__value"><?php echo esc_html($owner); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($location) : ?>
          <div class="p-project-infobar__item" id="infobar-location">
            <div class="p-project-infobar__icon" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                <path d="M11 2C7.96 2 5.5 4.46 5.5 7.5C5.5 11.88 11 18 11 18C11 18 16.5 11.88 16.5 7.5C16.5 4.46 14.04 2 11 2Z"
                  stroke="#0379CC" stroke-width="1.5" stroke-linejoin="round"/>
                <circle cx="11" cy="7.5" r="2.25" stroke="#0379CC" stroke-width="1.5"/>
              </svg>
            </div>
            <span class="p-project-infobar__label">ĐỊA ĐIỂM</span>
            <span class="p-project-infobar__value"><?php echo esc_html($location); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($scale) : ?>
          <div class="p-project-infobar__item" id="infobar-scale">
            <div class="p-project-infobar__icon" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                <rect x="2" y="9" width="18" height="4" rx="2" stroke="#0379CC" stroke-width="1.5"/>
                <path d="M6 9V7M11 9V5M16 9V7" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </div>
            <span class="p-project-infobar__label">QUY MÔ</span>
            <span class="p-project-infobar__value"><?php echo esc_html($scale); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($timeline) : ?>
          <div class="p-project-infobar__item" id="infobar-time">
            <div class="p-project-infobar__icon" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                <circle cx="11" cy="11" r="8.5" stroke="#0379CC" stroke-width="1.5"/>
                <path d="M11 6V11L14.5 13.5" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <span class="p-project-infobar__label">THỜI GIAN</span>
            <span class="p-project-infobar__value"><?php echo esc_html($timeline); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($services) : ?>
          <div class="p-project-infobar__item p-project-infobar__item--services" id="infobar-services">
            <div class="p-project-infobar__icon" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                <rect x="3" y="2" width="13" height="17" rx="2" stroke="#0379CC" stroke-width="1.5"/>
                <path d="M7 7H13M7 11H13M7 15H10" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
                <circle cx="17" cy="16" r="3.5" fill="white" stroke="#0379CC" stroke-width="1.5"/>
                <path d="M15.5 16L16.5 17L18.5 15" stroke="#0379CC" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <span class="p-project-infobar__label">DỊCH VỤ TƯ VẤN CHÍNH</span>
            <span class="p-project-infobar__value"><?php echo esc_html($services); ?></span>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
    <!-- ======= /PROJECT INFO BAR ======= -->


    <!-- ======= PROJECT DETAIL CONTENT ======= -->
    <section class="p-project-detail" id="project-detail">
      <div class="l-container">
        <div class="p-project-detail__layout">

          <!-- ======= ARTICLE ======= -->
          <article class="p-project-detail__body" id="project-body">

            <!-- GIỚI THIỆU DỰ ÁN -->
            <div class="p-project-section" id="section-intro">
              <h2 class="p-project-section__title">GIỚI THIỆU DỰ ÁN</h2>
              <div class="p-project-section__content">
                <?php the_content(); ?>
              </div>
            </div>

            <!-- DỊCH VỤ CMB ĐẢM NHẬN -->
            <?php if ($services_list) : ?>
            <div class="p-project-section" id="section-services">
              <h2 class="p-project-section__title">DỊCH VỤ CMB ĐẢM NHẬN</h2>
              <div class="p-project-services" id="project-services-grid">
                <?php foreach ($services_list as $i => $service) :
                  if (empty($service['label'])) continue;
                ?>
                <div class="p-project-services__item" id="service-<?php echo $i + 1; ?>">
                  <div class="p-project-services__icon" aria-hidden="true">
                    <?php if (!empty($service['icon'])) : ?>
                    <img src="<?php echo esc_url($service['icon']['url']); ?>"
                         alt="" width="28" height="28" />
                    <?php else : ?>
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                      <rect x="5" y="3" width="15" height="20" rx="2" stroke="#0379CC" stroke-width="1.5"/>
                      <path d="M9 9H17M9 13H17M9 17H13" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <?php endif; ?>
                  </div>
                  <span class="p-project-services__label"><?php echo esc_html($service['label']); ?></span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

            <!-- HÌNH ẢNH DỰ ÁN -->
            <?php if ($gallery) : ?>
            <div class="p-project-section" id="section-gallery">
              <h2 class="p-project-section__title">HÌNH ẢNH DỰ ÁN</h2>
              <div class="p-project-gallery" id="project-gallery">
                <?php foreach ($gallery as $i => $img) : ?>
                <figure class="p-project-gallery__item" data-lightbox-index="<?php echo $i; ?>">
                  <img src="<?php echo esc_url($img['url']); ?>"
                       alt="<?php echo esc_attr($img['alt']); ?>"
                       class="p-project-gallery__img" loading="lazy" />
                </figure>
                <?php endforeach; ?>
              </div>
              <?php if (count($gallery) > 4) : ?>
              <div class="p-project-gallery__footer">
                <a href="#" class="p-project-gallery__all" id="btn-all-photos">
                  Xem tất cả hình ảnh
                  <svg width="16" height="12" viewBox="0 0 16 12" fill="none" aria-hidden="true">
                    <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </a>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>

          </article>
          <!-- ======= /ARTICLE ======= -->


          <!-- ======= SIDEBAR ======= -->
          <aside class="p-project-detail__sidebar" id="project-sidebar">

            <?php if ($tech_specs) : ?>
            <div class="p-project-info-card" id="project-info-card">
              <h2 class="p-project-info-card__title">THÔNG TIN DỰ ÁN</h2>
              <ul class="p-project-info-card__list" role="list">
                <?php foreach ($tech_specs as $spec) :
                  if (empty($spec['label'])) continue;
                ?>
                <li class="p-project-info-card__item">
                  <div class="p-project-info-card__icon" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                      <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/>
                      <path d="M5.5 8L7.5 10L10.5 6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <div class="p-project-info-card__text">
                    <span class="p-project-info-card__label"><?php echo esc_html($spec['label']); ?></span>
                    <span class="p-project-info-card__value"><?php echo wp_kses($spec['value'] ?? '', ['br' => []]); ?></span>
                  </div>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endif; ?>

          </aside>
          <!-- ======= /SIDEBAR ======= -->

        </div>
      </div>
    </section>
    <!-- ======= /PROJECT DETAIL CONTENT ======= -->


    <!-- ======= RELATED PROJECTS ======= -->
    <section class="p-related-projects" id="related-projects" aria-label="Dự án liên quan">
      <div class="l-container">

        <h2 class="p-related-projects__title">DỰ ÁN LIÊN QUAN</h2>

        <?php if ($related->have_posts()) : ?>
        <div class="p-related-projects__grid" id="related-projects-grid">

          <div class="p-related-projects__cards">
            <?php $ri = 0; while ($related->have_posts()) : $related->the_post(); $ri++; ?>
            <?php $rel_img = get_field('project_hero_image'); ?>
            <article class="p-related-projects__card" id="related-<?php echo $ri; ?>">
              <a href="<?php the_permalink(); ?>" class="p-related-projects__card-link" title="<?php the_title_attribute(); ?>">
                <div class="p-related-projects__card-img-wrap">
                  <?php if ($rel_img) : ?>
                  <img src="<?php echo esc_url($rel_img['url']); ?>"
                       alt="<?php echo esc_attr($rel_img['alt'] ?: get_the_title()); ?>"
                       class="p-related-projects__card-img" loading="lazy" />
                  <?php elseif (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('medium_large', ['class' => 'p-related-projects__card-img', 'loading' => 'lazy']); ?>
                  <?php endif; ?>
                </div>
                <div class="p-related-projects__card-body">
                  <span class="p-related-projects__card-name"><?php the_title(); ?></span>
                </div>
              </a>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>

          <div class="p-related-projects__all-wrap">
            <a href="<?php echo esc_url(get_post_type_archive_link('du-an')); ?>" class="p-related-projects__all" id="btn-all-projects">
              Xem tất cả dự án
              <svg width="16" height="12" viewBox="0 0 16 12" fill="none" aria-hidden="true">
                <path d="M1 6H15M10 1L15 6L10 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </a>
          </div>

        </div>
        <?php else : wp_reset_postdata(); ?>
        <?php endif; ?>

      </div>
    </section>
    <!-- ======= /RELATED PROJECTS ======= -->
    </main>

<?php get_footer();
