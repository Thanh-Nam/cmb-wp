<?php
/**
 * template-parts/lien-he/form.php
 * Section: Contact Form
 */
$cf7_html    = '';
$cf7_post    = get_page_by_title('Liên hệ CMB', OBJECT, 'wpcf7_contact_form');
if ($cf7_post && function_exists('do_shortcode')) {
    $cf7_html = do_shortcode('[contact-form-7 id="' . $cf7_post->ID . '"]');
}
$privacy_url = function_exists('get_privacy_policy_url') && get_privacy_policy_url()
    ? get_privacy_policy_url()
    : '#';
?>
<!-- ======= CONTACT FORM ======= -->
<section class="p-lh-body" id="gui-thong-tin" aria-labelledby="form-title">
  <div class="l-container">
    <div class="p-lh-body__grid">

      <!-- Left: Image -->
      <div class="p-lh-body__image" data-reveal="fade-right">
        <?php if (has_post_thumbnail()) :
          the_post_thumbnail('large', ['class' => 'p-lh-body__img', 'alt' => 'Liên hệ CMB', 'loading' => 'lazy']);
        else : ?>
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
               alt="Tàu hàng tại cảng biển Việt Nam"
               class="p-lh-body__img"
               loading="lazy" />
        <?php endif; ?>
      </div>

      <!-- Right: Form -->
      <div class="p-lh-body__form-wrap" data-reveal="fade-left">
        <h2 id="form-title" class="p-lh-body__form-title">GỬI THÔNG TIN LIÊN HỆ</h2>

        <?php if ($cf7_html) : ?>
          <?php echo $cf7_html; ?>
        <?php else : ?>
        <form class="p-lh-form" id="contact-form" action="#" method="post" novalidate aria-label="Form liên hệ">

          <div class="p-lh-form__row">
            <div class="p-lh-form__group">
              <label class="p-lh-form__label" for="contact-name">
                Họ và tên <span class="p-lh-form__required" aria-label="bắt buộc">*</span>
              </label>
              <input type="text" id="contact-name" name="name" class="p-lh-form__input"
                     placeholder="Nhập họ và tên" required autocomplete="name" aria-required="true" />
            </div>
            <div class="p-lh-form__group">
              <label class="p-lh-form__label" for="contact-email">
                Email <span class="p-lh-form__required" aria-label="bắt buộc">*</span>
              </label>
              <input type="email" id="contact-email" name="email" class="p-lh-form__input"
                     placeholder="Nhập email" required autocomplete="email" aria-required="true" />
            </div>
          </div>

          <div class="p-lh-form__group">
            <label class="p-lh-form__label" for="contact-phone">
              Số điện thoại <span class="p-lh-form__required" aria-label="bắt buộc">*</span>
            </label>
            <input type="tel" id="contact-phone" name="phone" class="p-lh-form__input"
                   placeholder="Nhập số điện thoại" required autocomplete="tel" aria-required="true" />
          </div>

          <div class="p-lh-form__group">
            <label class="p-lh-form__label" for="contact-subject">Chủ đề</label>
            <select id="contact-subject" name="subject" class="p-lh-form__select">
              <option value="" disabled selected>Chọn chủ đề</option>
              <option value="tu-van-xay-dung">Tư vấn xây dựng</option>
              <option value="hop-tac">Hợp tác kinh doanh</option>
              <option value="quan-he-co-dong">Quan hệ cổ đông</option>
              <option value="tuyen-dung">Tuyển dụng</option>
              <option value="khac">Khác</option>
            </select>
          </div>

          <div class="p-lh-form__group">
            <label class="p-lh-form__label" for="contact-message">
              Nội dung <span class="p-lh-form__required" aria-label="bắt buộc">*</span>
            </label>
            <textarea id="contact-message" name="message" class="p-lh-form__textarea"
                      placeholder="Nhập nội dung liên hệ" required rows="5" aria-required="true"></textarea>
          </div>

          <div class="p-lh-form__checkbox-wrap">
            <input type="checkbox" id="contact-agree" name="agree"
                   class="p-lh-form__checkbox" required aria-required="true" />
            <label for="contact-agree" class="p-lh-form__checkbox-label">
              Tôi đồng ý cho phép CMB lưu trữ và xử lý thông tin theo
              <a href="<?php echo esc_url($privacy_url); ?>">Chính sách bảo mật</a>
            </label>
          </div>

          <button type="submit" class="p-lh-form__submit">GỬI THÔNG TIN</button>

        </form>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>
<!-- ======= /CONTACT FORM ======= -->
