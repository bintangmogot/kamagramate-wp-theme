<?php
/**
 * Large spotlight product card (homepage "featured_products" module).
 * Copy: product title, product_badge, shop_page_text / short description.
 * Labels: Theme Settings -> Product Cards.
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}
if (empty($product) || !$product->is_visible()) {
    return;
}

$product_id = $product->get_id();
$title      = $product->get_title();
$link       = $product->get_permalink();
$badge      = trim((string) get_field('product_badge', $product_id));
$ingredient = trim((string) get_field('product_active_ingredient', $product_id));
$excerpt    = trim(wp_strip_all_tags((string) (get_field('shop_page_text', $product_id) ?: $product->get_short_description())));
$excerpt    = wp_trim_words($excerpt, 32, '...');
$from_label = waklert_get_product_presentation_option('card_from_label');
$link_label = waklert_get_product_presentation_option('card_spotlight_link_label');
$min_price  = $product->is_type('variable') ? $product->get_variation_price('min', true) : $product->get_price();
?>
<article class="group grid overflow-hidden rounded-[2rem] border border-border bg-white transition-shadow duration-300 hover:shadow-[var(--shadow-card-hover)] md:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
    <a href="<?php echo esc_url($link); ?>" class="relative flex aspect-[4/3] items-center justify-center bg-primary-soft md:aspect-auto md:min-h-[320px]">
        <?php
        echo $product->get_image_id()
            ? wp_get_attachment_image($product->get_image_id(), 'woocommerce_single', false, ['class' => 'h-full max-h-[300px] w-full object-contain p-8 transition-transform duration-500 ease-out group-hover:scale-105', 'loading' => 'lazy', 'alt' => $title])
            : '';
        ?>
    </a>

    <div class="flex flex-col p-8 md:p-10">
        <?php if ($badge !== '') : ?>
            <p class="kmg-eyebrow"><?php echo esc_html($badge); ?></p>
        <?php endif; ?>
        <h3 class="mt-3 font-heading text-[clamp(1.75rem,1.4rem+1.2vw,2.375rem)] font-extrabold leading-[1.05] tracking-[-0.03em] text-ink">
            <a href="<?php echo esc_url($link); ?>" class="hover:text-primary"><?php echo esc_html($title); ?></a>
        </h3>
        <?php if ($ingredient !== '') : ?>
            <p class="mt-2 text-[15px] font-medium text-muted-foreground"><?php echo esc_html($ingredient); ?></p>
        <?php endif; ?>
        <?php if ($excerpt !== '') : ?>
            <p class="mt-5 text-[17px] leading-relaxed text-muted-foreground"><?php echo esc_html($excerpt); ?></p>
        <?php endif; ?>

        <div class="mt-auto flex flex-wrap items-end justify-between gap-4 pt-8">
            <?php if ($min_price !== '') : ?>
                <p class="text-[15px] text-muted-foreground">
                    <?php if ($from_label !== '') : ?><span class="mr-1"><?php echo esc_html($from_label); ?></span><?php endif; ?>
                    <span class="font-heading text-[1.75rem] font-extrabold tracking-tight text-ink"><?php echo wp_kses_post(wc_price((float) $min_price)); ?></span>
                </p>
            <?php endif; ?>
            <?php if ($link_label !== '') : ?>
                <a href="<?php echo esc_url($link); ?>" class="kmg-btn kmg-btn-primary kmg-btn-sm"><?php echo esc_html($link_label); ?><?php echo kmg_glyph('arrow-right'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</article>
