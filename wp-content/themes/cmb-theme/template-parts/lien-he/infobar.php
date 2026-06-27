<?php
/**
 * template-parts/lien-he/infobar.php
 * Section: Info Bar — địa chỉ, điện thoại, email, giờ làm việc
 */
$address       = function_exists('get_field') ? get_field('company_address',       'option') : '';
$phones_raw    = function_exists('get_field') ? get_field('company_phone',         'option') : '';
$emails_raw    = function_exists('get_field') ? get_field('company_email',         'option') : '';
$working_hours = function_exists('get_field') ? get_field('company_working_hours', 'option') : '';

if (!$address)       $address       = "Tầng 11, Tòa nhà CMB, 512 Tôn Thất Thuyết,\nCầu Giấy, Hà Nội, Việt Nam";
if (!$working_hours) $working_hours = "Thứ 2 – Thứ 6\n08:00 – 17:30";

$phones = $phones_raw
    ? array_values(array_filter(array_map('trim', explode("\n", $phones_raw))))
    : ['(84) 24 3786 6291', '(84) 225 3 760 629'];
$emails = $emails_raw
    ? array_values(array_filter(array_map('trim', explode("\n", $emails_raw))))
    : ['info@cmb.com.vn', 'ir@cmb.com.vn'];
?>
<!-- ======= INFO BAR ======= -->
<div class="p-lh-infobar" id="lien-he-info">
  <div class="l-container">
    <div class="p-lh-infobar__card">

      <div class="p-lh-infobar__item" data-reveal="fade-up" data-reveal-delay="1">
        <span class="p-lh-infobar__icon" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
            <path d="M14 3C9.58 3 6 6.58 6 11C6 16.5 14 25 14 25C14 25 22 16.5 22 11C22 6.58 18.42 3 14 3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <circle cx="14" cy="11" r="3" stroke="currentColor" stroke-width="1.6"/>
          </svg>
        </span>
        <div class="p-lh-infobar__text">
          <span class="p-lh-infobar__label">Địa chỉ</span>
          <span class="p-lh-infobar__value"><?php echo $address; ?></span>
        </div>
      </div>

      <div class="p-lh-infobar__divider" aria-hidden="true"></div>

      <div class="p-lh-infobar__item" data-reveal="fade-up" data-reveal-delay="2">
        <span class="p-lh-infobar__icon" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
            <path d="M4.5 5H9L11 9.5L8.5 11.5C9.33 13.17 10.83 14.67 12.5 15.5L14.5 13L18.5 15V19.5C18.5 19.78 18.28 20 18 20C9.72 20 3 13.28 3 5C3 4.72 3.22 4.5 3.5 4.5L4.5 5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round"/>
          </svg>
        </span>
        <div class="p-lh-infobar__text">
          <span class="p-lh-infobar__label">Điện thoại</span>
          <span class="p-lh-infobar__value">
            <?php foreach ($phones as $phone) : ?>
            <a href="tel:+<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>"><?php echo $phone; ?></a>
            <?php endforeach; ?>
          </span>
        </div>
      </div>

      <div class="p-lh-infobar__divider" aria-hidden="true"></div>

      <div class="p-lh-infobar__item" data-reveal="fade-up" data-reveal-delay="3">
        <span class="p-lh-infobar__icon" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
            <rect x="3" y="6" width="22" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/>
            <path d="M3 10L14 17L25 10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
        <div class="p-lh-infobar__text">
          <span class="p-lh-infobar__label">Email</span>
          <span class="p-lh-infobar__value">
            <?php foreach ($emails as $email) : ?>
            <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
            <?php endforeach; ?>
          </span>
        </div>
      </div>

      <div class="p-lh-infobar__divider" aria-hidden="true"></div>

      <div class="p-lh-infobar__item" data-reveal="fade-up" data-reveal-delay="4">
        <span class="p-lh-infobar__icon" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
            <circle cx="14" cy="14" r="10.5" stroke="currentColor" stroke-width="1.6"/>
            <path d="M14 8V14L17.5 17.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
        <div class="p-lh-infobar__text">
          <span class="p-lh-infobar__label">Giờ làm việc</span>
          <span class="p-lh-infobar__value"><?php echo $working_hours; ?></span>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- ======= /INFO BAR ======= -->
