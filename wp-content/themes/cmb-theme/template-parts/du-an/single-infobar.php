<?php
/**
 * template-parts/du-an/single-infobar.php
 * Section: Project Info Bar — chủ đầu tư, địa điểm, quy mô, thời gian, dịch vụ
 */
$owner    = get_field('project_owner');
$location = get_field('project_location_detail');
$scale    = get_field('project_scale');
$timeline = get_field('project_timeline');
$services = get_field('project_services');
?>
<!-- ======= PROJECT INFO BAR ======= -->
<div class="p-project-infobar" id="project-infobar" aria-label="Thông tin tổng quan dự án">
  <div class="l-container">
    <div class="p-project-infobar__inner">

      <?php if ($owner) : ?>
      <div class="p-project-infobar__item">
        <div class="p-project-infobar__icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
            <circle cx="11" cy="7" r="4" stroke="#0379CC" stroke-width="1.5"/>
            <path d="M3 19C3 15.134 6.134 12 10 12H12C15.866 12 19 15.134 19 19" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </div>
        <span class="p-project-infobar__label">CHỦ ĐẦU TƯ</span>
        <span class="p-project-infobar__value"><?php echo $owner; ?></span>
      </div>
      <?php endif; ?>

      <?php if ($location) : ?>
      <div class="p-project-infobar__item">
        <div class="p-project-infobar__icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
            <path d="M11 2C7.96 2 5.5 4.46 5.5 7.5C5.5 11.88 11 18 11 18C11 18 16.5 11.88 16.5 7.5C16.5 4.46 14.04 2 11 2Z" stroke="#0379CC" stroke-width="1.5" stroke-linejoin="round"/>
            <circle cx="11" cy="7.5" r="2.25" stroke="#0379CC" stroke-width="1.5"/>
          </svg>
        </div>
        <span class="p-project-infobar__label">ĐỊA ĐIỂM</span>
        <span class="p-project-infobar__value"><?php echo $location; ?></span>
      </div>
      <?php endif; ?>

      <?php if ($scale) : ?>
      <div class="p-project-infobar__item">
        <div class="p-project-infobar__icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
            <rect x="2" y="9" width="18" height="4" rx="2" stroke="#0379CC" stroke-width="1.5"/>
            <path d="M6 9V7M11 9V5M16 9V7" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </div>
        <span class="p-project-infobar__label">QUY MÔ</span>
        <span class="p-project-infobar__value"><?php echo $scale; ?></span>
      </div>
      <?php endif; ?>

      <?php if ($timeline) : ?>
      <div class="p-project-infobar__item">
        <div class="p-project-infobar__icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
            <circle cx="11" cy="11" r="8.5" stroke="#0379CC" stroke-width="1.5"/>
            <path d="M11 6V11L14.5 13.5" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <span class="p-project-infobar__label">THỜI GIAN</span>
        <span class="p-project-infobar__value"><?php echo $timeline; ?></span>
      </div>
      <?php endif; ?>

      <?php if ($services) : ?>
      <div class="p-project-infobar__item p-project-infobar__item--services">
        <div class="p-project-infobar__icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
            <rect x="3" y="2" width="13" height="17" rx="2" stroke="#0379CC" stroke-width="1.5"/>
            <path d="M7 7H13M7 11H13M7 15H10" stroke="#0379CC" stroke-width="1.5" stroke-linecap="round"/>
            <circle cx="17" cy="16" r="3.5" fill="white" stroke="#0379CC" stroke-width="1.5"/>
            <path d="M15.5 16L16.5 17L18.5 15" stroke="#0379CC" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <span class="p-project-infobar__label">DỊCH VỤ TƯ VẤN CHÍNH</span>
        <span class="p-project-infobar__value"><?php echo $services; ?></span>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>
<!-- ======= /PROJECT INFO BAR ======= -->
