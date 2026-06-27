<?php
/**
 * Template Part: Khách hàng - Đối tác
 * Usage: get_template_part('template-parts/section-partner');
 * Data source: ACF Options — partner_list (Repeater > logo: Image)
 */
$partner_logos = get_field('partner_list', 'option');
?>
<section class="p-partner" id="partner" aria-label="Khách hàng - Đối tác">

  <div class="l-container">
    <div class="p-partner__header" data-reveal="fade-up">
      <h2 class="c-section-title p-partner__title">KHÁCH HÀNG - ĐỐI TÁC</h2>
    </div>
  </div>

  <?php if ($partner_logos) : ?>
  <div class="p-partner__rows" aria-label="Danh sách khách hàng và đối tác">

    <!-- DESKTOP: 1 hàng chạy rtl -->
    <div class="p-partner__track-wrap p-partner__track-wrap--desktop">
      <div class="p-partner__track p-partner__track--rtl" role="list">
        <?php foreach ($partner_logos as $item) : if (empty($item['logo'])) continue; ?>
        <div class="p-partner__item" role="listitem">
          <img src="<?php echo $item['logo']['url']; ?>"
               alt="<?php echo $item['logo']['alt']; ?>"
               class="p-partner__logo" loading="lazy" />
        </div>
        <?php endforeach; ?>
        <?php foreach ($partner_logos as $item) : if (empty($item['logo'])) continue; ?>
        <div class="p-partner__item" aria-hidden="true">
          <img src="<?php echo $item['logo']['url']; ?>"
               alt="" class="p-partner__logo" loading="lazy" />
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- MOBILE hàng 1: rtl -->
    <div class="p-partner__track-wrap p-partner__track-wrap--sp-r1" aria-hidden="true">
      <div class="p-partner__track p-partner__track--rtl">
        <?php foreach ($partner_logos as $item) : if (empty($item['logo'])) continue; ?>
        <div class="p-partner__item">
          <img src="<?php echo $item['logo']['url']; ?>"
               alt="" class="p-partner__logo" loading="lazy" />
        </div>
        <?php endforeach; ?>
        <?php foreach ($partner_logos as $item) : if (empty($item['logo'])) continue; ?>
        <div class="p-partner__item">
          <img src="<?php echo $item['logo']['url']; ?>"
               alt="" class="p-partner__logo" loading="lazy" />
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- MOBILE hàng 2: ltr -->
    <div class="p-partner__track-wrap p-partner__track-wrap--sp-r2" aria-hidden="true">
      <div class="p-partner__track p-partner__track--ltr">
        <?php foreach ($partner_logos as $item) : if (empty($item['logo'])) continue; ?>
        <div class="p-partner__item">
          <img src="<?php echo $item['logo']['url']; ?>"
               alt="" class="p-partner__logo" loading="lazy" />
        </div>
        <?php endforeach; ?>
        <?php foreach ($partner_logos as $item) : if (empty($item['logo'])) continue; ?>
        <div class="p-partner__item">
          <img src="<?php echo $item['logo']['url']; ?>"
               alt="" class="p-partner__logo" loading="lazy" />
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
  <?php endif; ?>

</section>
