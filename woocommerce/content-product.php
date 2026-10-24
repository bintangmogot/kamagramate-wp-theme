<?php
/**
 * Product card used by every product loop (shop, category, related, modules).
 *
 * Variable products show a 2-column grid of pack-size buttons; choosing one
 * swaps the price below (assets/js/main.js, [data-kmg-pill]). Labels come from
 * Theme Settings -> Product Cards.
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
$in_stock   = $product->is_in_stock();
$badge      = trim((string) get_field('product_badge', $product_id));
if ($badge === '' && $product->is_featured()) {
    $badge = waklert_get_product_presentation_option('best_seller_label');
}
$ingredient = trim((string) get_field('product_active_ingredient', $product_id));
$reviews    = waklert_get_product_review_summary($product_id);
$unit_price = waklert_get_product_unit_price($product);

$label_in      = waklert_get_product_presentation_option('card_in_stock_label');
$label_out     = waklert_get_product_presentation_option('card_out_of_stock_label');
$label_cta     = waklert_get_product_presentation_option('card_cta_label');
$label_unit    = waklert_get_product_presentation_option('card_pack_unit_label');
$label_per     = waklert_get_product_presentation_option('card_per_unit_suffix');
$label_from    = waklert_get_product_presentation_option('card_from_label');

$pills = [];
if ($product->is_type('variable')) {
    $variations = $product->get_available_variations();
    waklert_sort_product_variations($variations, $product);
    foreach ($variations as $variation) {
        $label = '';
        foreach ($variation['attributes'] as $value) {
            if ($value !== '') {
                $label = $value;
                break;
            }
        }
        $label = trim(str_ireplace(['-tabs', '-', 'tablets'], [' tabs', ' ', 'tabs'], (string) $label));
        if ($label === '') {
            continue;
        }
        if ($label_unit !== '' && stripos($label, $label_unit) === false && !preg_match('/[a-z]{3,}/i', $label)) {
            $label .= ' ' . $label_unit;
        }
        $label = preg_replace('/\btabs\b/i', 'Tabs', $label);
        $pills[] = [
            'label' => $label,
            'price' => wc_price($variation['display_price']),
        ];
    }
}
$first_price = $pills ? $pills[0]['price'] : $product->get_price_html();
?>
<article class="group flex h-full flex-col rounded-2xl bg-primary-soft p-2.5 shadow-[0_14px_28px_-18px_rgba(34,18,79,0.45)] transition-transform duration-200 hover:-translate-y-1" data-kmg-card>
    <a href="<?php echo esc_url($link); ?>" class="relative block aspect-[4/3] overflow-hidden rounded-xl bg-white">
        <?php if ($badge !== '') : ?>
            <span class="absolute left-3 top-3 z-10 rounded-md bg-primary px-2.5 py-1 text-[13px] font-semibold text-white"><?php echo esc_html($badge); ?></span>
        <?php endif; ?>
        <?php
        echo $product->get_image_id()
            ? wp_get_attachment_image($product->get_image_id(), 'woocommerce_single', false, ['class' => 'h-full w-full object-contain p-5 transition-transform duration-500 ease-out group-hover:scale-105', 'loading' => 'lazy', 'alt' => $title])
            : '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="" class="h-full w-full object-contain p-5">';
        ?>
    </a>

    <div class="flex flex-1 flex-col px-3 pb-3 pt-4 text-center">
        <?php if ($reviews['review_count'] > 0) : ?>
            <a href="<?php echo esc_url($link . '#product-reviews'); ?>" class="mx-auto mb-1.5 inline-flex items-center gap-2 text-[14px] font-semibold uppercase tracking-wide text-ink">
                <?php echo kmg_stars((int) round($reviews['average_rating'] ?: 5), 'h-3.5 w-3.5'); ?>
                <span>(<?php echo esc_html($reviews['review_count']); ?>)</span>
            </a>
        <?php endif; ?>

        <h3 class="font-heading text-[1.5rem] font-bold leading-tight text-ink">
            <a href="<?php echo esc_url($link); ?>" class="hover:text-primary"><?php echo esc_html($title); ?></a>
        </h3>
        <?php if ($ingredient !== '') : ?>
            <p class="text-[16px] text-muted-foreground"><?php echo esc_html($ingredient); ?></p>
        <?php endif; ?>

        <?php if ($pills) : ?>
        <div class="mt-4 grid grid-cols-2 gap-1.5" role="group" aria-label="<?php echo esc_attr($title); ?>">
            <?php foreach ($pills as $index => $pill) : ?>
                <button type="button" data-kmg-pill data-price="<?php echo esc_attr($pill['price']); ?>" aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                    class="truncate rounded-lg border border-primary/25 bg-white px-2 py-2 text-[15px] font-semibold text-primary transition-colors hover:border-primary aria-pressed:border-primary aria-pressed:bg-primary aria-pressed:text-white">
                    <?php echo esc_html($pill['label']); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p class="dynamic-price mt-4 font-heading text-[1.875rem] font-bold leading-none text-ink [&_del]:mr-2 [&_del]:text-base [&_del]:font-medium [&_del]:text-muted-foreground [&_ins]:no-underline"><?php echo wp_kses_post($first_price); ?></p>
        <?php if ($unit_price !== null && $label_per !== '') : ?>
            <p class="mt-1 text-[14px] text-muted-foreground"><?php echo esc_html(trim($label_from . ' ' . waklert_format_product_unit_price($unit_price) . $label_per)); ?></p>
        <?php endif; ?>

        <div class="mt-auto pt-4">
            <?php if ($in_stock) : ?>
                <?php if ($label_in !== '') : ?>
                    <p class="mb-2.5 inline-flex items-center gap-1.5 text-[14px] font-semibold text-success"><?php echo kmg_glyph('check', 'h-4 w-4'); ?><?php echo esc_html($label_in); ?></p>
                <?php endif; ?>
                <?php if ($label_cta !== '') : ?>
                    <a href="<?php echo esc_url($link); ?>" class="kmg-btn kmg-btn-primary kmg-btn-sm w-full"><?php echo esc_html($label_cta); ?></a>
                <?php endif; ?>
            <?php elseif ($label_out !== '') : ?>
                <span class="kmg-btn kmg-btn-sm w-full cursor-not-allowed bg-muted text-muted-foreground"><?php echo esc_html($label_out); ?></span>
            <?php endif; ?>
        </div>
    </div>
</article>
