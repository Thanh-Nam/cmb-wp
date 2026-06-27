<?php
/**
 * template-parts/du-an/single-detail.php
 * Section: Project Detail Content + Sidebar
 */
$services_list = get_field('project_services_list');
$gallery       = get_field('project_gallery');
$tech_specs    = get_field('project_tech_specs');
?>
<!-- ======= PROJECT DETAIL CONTENT ======= -->
<section class="p-project-detail" id="project-detail">
  <div class="l-container">
    <div class="p-project-detail__layout">

      <!-- ARTICLE -->
      <article class="p-project-detail__body" id="project-body">

        <div class="p-project-section" id="section-intro">
          <h2 class="p-project-section__title">GIỚI THIỆU DỰ ÁN</h2>
          <div class="p-project-section__content">
            <?php the_content(); ?>
          </div>
        </div>

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
                <img src="<?php echo $service['icon']['url']; ?>" alt="" width="28" height="28" loading="lazy" />
                <?php else : ?>
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                  <rect x="5" y="3" width="15" height="20" rx="2" stroke="#0379CC" stroke-width="1.5"/>
                  <path d="M9 9H17M9 13H17M9 17H13" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <?php endif; ?>
              </div>
              <span class="p-project-services__label"><?php echo $service['label']; ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($gallery) : ?>
        <div class="p-project-section" id="section-gallery">
          <h2 class="p-project-section__title">HÌNH ẢNH DỰ ÁN</h2>
          <div class="p-project-gallery" id="project-gallery">
            <?php foreach ($gallery as $i => $img) : ?>
            <figure class="p-project-gallery__item" data-lightbox-index="<?php echo $i; ?>">
              <img src="<?php echo $img['url']; ?>"
                   alt="<?php echo $img['alt']; ?>"
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

      <!-- SIDEBAR -->
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
                <span class="p-project-info-card__label"><?php echo $spec['label']; ?></span>
                <span class="p-project-info-card__value"><?php echo wp_kses($spec['value'] ?? '', ['br' => []]); ?></span>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </aside>

    </div>
  </div>
</section>
<!-- ======= /PROJECT DETAIL CONTENT ======= -->
