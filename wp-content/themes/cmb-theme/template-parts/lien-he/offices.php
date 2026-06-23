<?php
/**
 * template-parts/lien-he/offices.php
 * Section: Offices + Google Map
 */
$offices = function_exists('get_field') ? get_field('offices', 'option') : [];
if (empty($offices)) {
    $offices = [
        [
            'office_name'    => 'Văn phòng Hà Nội',
            'office_address' => 'Tầng 11, Tòa nhà CMB, 512 Tôn Thất Thuyết, Cầu Giấy, Hà Nội',
            'office_phone'   => '(84) 24 3786 6291',
            'office_map_src' => 'https://maps.google.com/maps?q=512+Ton+That+Thuyet,+Cau+Giay,+Ha+Noi,+Viet+Nam&output=embed&hl=vi',
        ],
        [
            'office_name'    => 'VP Hải Phòng',
            'office_address' => 'Số 12 Lô 22 Lê Hồng Phong, Ngô Quyền, Hải Phòng',
            'office_phone'   => '(84) 225 3 768 629',
            'office_map_src' => 'https://maps.google.com/maps?q=Le+Hong+Phong,+Ngo+Quyen,+Hai+Phong,+Viet+Nam&output=embed&hl=vi',
        ],
        [
            'office_name'    => 'VP TP HCM',
            'office_address' => 'Tầng 6, Tòa nhà Sailing, 111A Pasteur, Quận 1, TP.HCM',
            'office_phone'   => '(84) 28 6287 4840',
            'office_map_src' => 'https://maps.google.com/maps?q=111A+Pasteur,+Quan+1,+Ho+Chi+Minh+City,+Viet+Nam&output=embed&hl=vi',
        ],
    ];
}
$first_map_src = !empty($offices[0]['office_map_src']) ? $offices[0]['office_map_src'] : '';
?>
<!-- ======= OFFICES + MAP ======= -->
<section class="p-lh-map" id="van-phong" aria-label="Văn phòng và chi nhánh CMB">
  <div class="l-container">
    <div class="p-lh-map__wrapper">

      <!-- Sidebar: Offices -->
      <div class="p-lh-map__sidebar" data-reveal="fade-right">
        <h2 class="p-lh-map__sidebar-title">VĂN PHÒNG / CHI NHÁNH</h2>

        <ul class="p-lh-offices" role="list">
          <?php foreach ($offices as $i => $office) :
            $is_first  = ($i === 0);
            $map_src   = esc_attr($office['office_map_src'] ?? '');
            $phone_raw = $office['office_phone'] ?? '';
            $phone_href = 'tel:+' . preg_replace('/[^0-9]/', '', $phone_raw);
          ?>
          <li class="p-lh-office<?php echo $is_first ? ' p-lh-office--active' : ''; ?>"
              id="office-<?php echo esc_attr(sanitize_title($office['office_name'] ?? $i)); ?>"
              data-map-src="<?php echo $map_src; ?>"
              role="button" tabindex="0"
              aria-pressed="<?php echo $is_first ? 'true' : 'false'; ?>">
            <div class="p-lh-office__name">
              <?php if ($is_first) : ?>
              <svg class="p-lh-office__pin" width="14" height="18" viewBox="0 0 14 18" fill="none" aria-hidden="true">
                <path d="M7 1C4.24 1 2 3.24 2 6C2 9.75 7 17 7 17C7 17 12 9.75 12 6C12 3.24 9.76 1 7 1Z" fill="currentColor"/>
                <circle cx="7" cy="6" r="2" fill="white"/>
              </svg>
              <?php else : ?>
              <svg class="p-lh-office__pin" width="14" height="18" viewBox="0 0 14 18" fill="none" aria-hidden="true">
                <path d="M7 1C4.24 1 2 3.24 2 6C2 9.75 7 17 7 17C7 17 12 9.75 12 6C12 3.24 9.76 1 7 1Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                <circle cx="7" cy="6" r="2" stroke="currentColor" stroke-width="1.5"/>
              </svg>
              <?php endif; ?>
              <?php echo esc_html($office['office_name'] ?? ''); ?>
            </div>
            <?php if (!empty($office['office_address'])) : ?>
            <p class="p-lh-office__address"><?php echo nl2br(esc_html($office['office_address'])); ?></p>
            <?php endif; ?>
            <?php if ($phone_raw) : ?>
            <p class="p-lh-office__phone">
              <a href="<?php echo esc_attr($phone_href); ?>"><?php echo esc_html($phone_raw); ?></a>
            </p>
            <?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Map -->
      <div class="p-lh-map__embed" id="google-map">
        <iframe
          src="<?php echo esc_url($first_map_src); ?>"
          title="Bản đồ vị trí văn phòng CMB"
          allowfullscreen
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          aria-label="Bản đồ Google Maps vị trí CMB">
        </iframe>
      </div>

    </div>
  </div>
</section>
<!-- ======= /OFFICES + MAP ======= -->
