<?php
/**
 * Template Part: Single news-all item
 * Used by archive.php (initial render) and cmb_filter_news_handler (AJAX).
 * Expects the post loop to be active.
 */

$cats      = get_the_category();
$term      = $cats ? $cats[0] : null;
$cat_slug  = $term ? $term->slug : '';
$cat_label = $term ? strtoupper($term->name) : '';

$is_featured = function_exists('get_field') ? get_field('is_featured') : false;
if ($is_featured) {
    $cat_label = 'TIN NỔI BẬT';
    $cat_slug  = 'noi-bat';
}

$badge_class = 'p-news-all__item-badge' . ($cat_slug ? ' p-news-all__item-badge--' . $cat_slug : '');
?>
<article class="p-news-all__item" data-category="<?php echo esc_attr($cat_slug); ?>" data-reveal="fade-up">

  <a href="<?php the_permalink(); ?>" class="p-news-all__item-img-wrap" tabindex="-1" aria-hidden="true">
    <?php if (has_post_thumbnail()) :
      the_post_thumbnail('medium', ['class' => 'p-news-all__item-img', 'loading' => 'lazy']);
    else : ?>
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero_port.jpg"
        alt="<?php the_title_attribute(); ?>"
        class="p-news-all__item-img" loading="lazy" />
    <?php endif; ?>
  </a>

  <div class="p-news-all__item-body">
    <div class="p-news-all__item-meta">
      <time class="p-news-all__item-date"
        datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
        <?php echo get_the_date('d/m/Y'); ?>
      </time>
      <?php if ($cat_label) : ?>
      <span class="<?php echo esc_attr($badge_class); ?>"><?php echo esc_html($cat_label); ?></span>
      <?php endif; ?>
    </div>
    <h3 class="p-news-all__item-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
    <p class="p-news-all__item-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 25)); ?></p>
  </div>

  <span class="p-news-all__item-arrow" aria-hidden="true">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
      <path d="M5 12H19M14 7L19 12L14 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </span>

</article>
