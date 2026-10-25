<?php
/**
 * Single product.
 *
 * Copy sources: product modules (product_summary / product_description), product
 * ACF fields (badge, specs) and Theme Settings -> Product Page / Product Cards.
 * Behaviour kept from the previous build: pack-size rows drive hidden variation
 * inputs, the buy button shows the running total, a sticky bar appears once the
 * form scrolls out of view, extra ACF modules render below the description.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Product pages always show plain "$" (never "A$").
add_filter('woocommerce_currency_symbol', function () {
    return '$';
}, 999, 2);

get_header('shop');

global $product;
if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}
if (!$product) {
    get_footer('shop');
    return;
}

$opt        = 'waklert_get_product_presentation_option';
$product_id = $product->get_id();

// Summary and description come from the product modules, with legacy fallbacks.
$summary = '';
$description = '';
foreach ((array) get_field('modules', $product_id) as $module) {
    $layout = $module['acf_fc_layout'] ?? '';
    if ($layout === 'product_summary' && $summary === '') {
        $summary = (string) ($module['content'] ?? '');
    } elseif ($layout === 'product_description' && $description === '') {
        $description = (string) ($module['content'] ?? '');
    }
}
if ($summary === '') {
    $summary = (string) get_post_meta($product_id, 'short_desc', true);
}
if ($summary === '') {
    $raw = $product->get_short_description();
    $summary = preg_match('/<!-- en -->(.+?)<!-- \/en -->/s', $raw, $m) ? trim($m[1]) : strip_tags($raw, '<p><a><strong><b><i><em><ul><ol><li><br>');
}
if ($description === '') {
    $description = (string) get_post_meta($product_id, 'main_desc', true) ?: get_the_content();
}

$image_id  = $product->get_image_id();
$image_url = wp_get_attachment_image_url($image_id, 'large') ?: '';

// Variations sorted by pack size; default to the cheapest in-stock one.
$variations = [];
if ($product->is_type('variable')) {
    $variations = $product->get_available_variations();
    waklert_sort_product_variations($variations, $product);
}
$default_index = 0;
$lowest = INF;
foreach ($variations as $i => $v) {
    if (!is_numeric($v['display_price'] ?? null) || (isset($v['is_in_stock']) && !$v['is_in_stock'])) {
        continue;
    }
    if ((float) $v['display_price'] < $lowest) {
        $lowest = (float) $v['display_price'];
        $default_index = $i;
    }
}

$badge = trim((string) get_field('product_badge', $product_id));
if ($badge === '' && $product->is_featured()) {
    $badge = $opt('best_seller_label');
}
$in_stock      = $product->is_in_stock();
$stock_badge   = $in_stock ? $opt('product_in_stock_badge') : $opt('product_out_of_stock_label');
$text_under    = get_field('text_under_product_image', $product_id);
$ingredient    = trim((string) get_field('product_active_ingredient', $product_id));
$qty_heading   = $opt('product_quantity_heading');
$qty_label     = $opt('product_quantity_label');
$buy_label     = $opt('product_buy_button_label');
$sticky_label  = $opt('product_sticky_button_label');
$free_message  = $opt('free_shipping_message');
$free_threshold = get_field('free_shipping_threshold', 'option');

$trust_items = array_values(array_filter([
    ['title' => $opt('product_trust_dispatch'), 'desc' => $opt('product_trust_dispatch_desc'), 'icon' => 'express_shipping'],
    ['title' => $opt('product_trust_checkout'), 'desc' => $opt('product_trust_checkout_desc'), 'icon' => 'secure_lock'],
    ['title' => $opt('product_trust_quality'), 'desc' => $opt('product_trust_quality_desc'), 'icon' => 'no_customs'],
], static function ($item) {
    return $item['title'] !== '';
}));

$specs = array_values(array_filter([
    [$opt('product_specs_active_label'), $ingredient],
    [$opt('product_specs_indication_label'), get_field('product_indication', $product_id)],
    [$opt('product_specs_manufacturer_label'), get_field('product_manufacturer', $product_id) ?: (waklert_get_product_attribute_by_label($product, '/manufacturer|brand/i') ?: $product->get_meta('brand'))],
    [$opt('product_specs_strength_label'), waklert_get_product_strength($product)],
    [$opt('product_specs_packaging_label'), get_field('product_packaging', $product_id) ?: waklert_get_product_attribute_by_label($product, '/packaging/i')],
    [$opt('product_specs_delivery_label'), get_field('product_delivery_time', $product_id)],
], static function ($spec) {
    return trim((string) $spec[0]) !== '' && trim(wp_strip_all_tags((string) $spec[1])) !== '';
}));
$specs_heading = $opt('product_specs_heading');

$initial_price = $variations
    ? (float) ($variations[$default_index]['display_price'] ?? $product->get_variation_price('min'))
    : (float) $product->get_price();
?>
<main id="main">
<section class="border-b border-border">
    <div class="kmg-container pt-8 md:pt-10">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
    </div>

    <div class="kmg-container grid gap-10 pb-16 pt-8 md:grid-cols-2 md:gap-12 md:pb-24 lg:gap-20">
        <div class="md:sticky md:top-32 md:self-start">
            <div class="relative flex aspect-square items-center justify-center overflow-hidden rounded-[2.5rem] bg-primary-soft" data-testid="product-image">
                <?php if ($badge !== '') : ?>
                    <span class="absolute left-6 top-6 rounded-full bg-white px-4 py-1.5 text-[13px] font-bold text-ink"><?php echo esc_html($badge); ?></span>
                <?php endif; ?>
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'large', false, ['class' => 'h-full w-full object-contain p-10 mix-blend-multiply md:p-14', 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => $product->get_name()]); ?>
                <?php endif; ?>
            </div>
            <?php if (!empty($text_under)) : ?>
                <div class="prose mt-6 max-w-none rounded-[1.75rem] border border-border p-6 text-[16px] md:p-8"><?php echo wp_kses_post($text_under); ?></div>
            <?php endif; ?>
        </div>

        <div class="min-w-0">
            <?php if ($stock_badge !== '') : ?>
                <p class="inline-flex items-center gap-2 text-[14px] font-semibold <?php echo $in_stock ? 'text-success' : 'text-destructive'; ?>">
                    <span class="h-2 w-2 rounded-full <?php echo $in_stock ? 'bg-success' : 'bg-destructive'; ?>" aria-hidden="true"></span>
                    <?php echo esc_html($stock_badge); ?>
                </p>
            <?php endif; ?>

            <h1 class="mt-4 font-heading text-[clamp(2.25rem,1.7rem+2.4vw,3.75rem)] font-bold leading-[1.04] tracking-normal text-ink" data-testid="text-product-title"><?php echo esc_html($product->get_name()); ?></h1>
            <?php if ($ingredient !== '') : ?>
                <p class="mt-3 text-[18px] text-muted-foreground"><?php echo esc_html($ingredient); ?></p>
            <?php endif; ?>

            <p class="mt-8 font-heading text-[2.5rem] font-bold leading-none tracking-normal text-ink" data-testid="text-product-price">
                <?php echo $variations ? wp_kses_post(wc_price($initial_price)) : wp_kses_post($product->get_price_html()); ?>
            </p>

            <?php if (trim(wp_strip_all_tags($summary)) !== '') : ?>
                <div class="prose mt-8 max-w-none text-[18px] text-muted-foreground" data-testid="text-product-description"><?php echo wp_kses_post($summary); ?></div>
            <?php endif; ?>

            <?php if ($free_message !== '' && is_numeric($free_threshold) && (float) $free_threshold > 0) :
                $free_threshold = (float) $free_threshold; ?>
                <p class="mt-8 flex items-center gap-3 rounded-2xl bg-primary-softer px-5 py-4 text-[15px] text-ink">
                    <?php echo waklert_render_card_icon(['icon_preset' => 'express_shipping', 'size_class' => 'h-6 w-6 shrink-0 text-primary']); ?>
                    <span><?php echo esc_html($free_message); ?> <strong><?php echo wp_kses_post(wc_price($free_threshold, ['decimals' => $free_threshold === floor($free_threshold) ? 0 : wc_get_price_decimals()])); ?></strong></span>
                </p>
            <?php endif; ?>

            <?php if ($in_stock && $variations) :
                $attr_key = array_key_first($variations[0]['attributes'] ?? []) ?: 'attribute_pa_quantity';
                $sticky_pack = '';
                ?>
                <form id="product-purchase-form" class="cart custom-variations-form mt-10" action="" method="post" enctype="multipart/form-data" data-buy-label="<?php echo esc_attr($buy_label); ?>">
                    <fieldset data-testid="variation-selector">
                        <?php if ($qty_heading !== '') : ?>
                            <legend class="mb-4 font-heading text-[18px] font-bold text-ink"><?php echo esc_html($qty_heading); ?></legend>
                        <?php endif; ?>
                        <div class="grid gap-2 sm:grid-cols-2" id="variation-rows">
                            <?php foreach ($variations as $i => $variation) :
                                $raw_val  = $variation['attributes'][$attr_key] ?? (string) reset($variation['attributes']);
                                $var_obj  = wc_get_product($variation['variation_id']);
                                $label    = $var_obj ? $var_obj->get_attribute(preg_replace('/^attribute_/', '', $attr_key)) : '';
                                $label    = trim(preg_replace('/\s+/', ' ', wp_strip_all_tags($label ?: str_replace(['-', '_'], ' ', (string) $raw_val))));
                                $label    = ucwords($label);
                                $count    = $var_obj ? waklert_get_product_pack_count($product, $var_obj) : 0;
                                $price    = (float) $variation['display_price'];
                                $active   = $i === $default_index;
                                if ($active) {
                                    $sticky_pack = $label;
                                }
                                ?>
                                <button type="button" aria-pressed="<?php echo $active ? 'true' : 'false'; ?>"
                                    class="variation-row-btn <?php echo $active ? 'active-row' : ''; ?> flex items-center justify-between gap-3 rounded-lg border border-border bg-white px-5 py-4 text-left transition-colors hover:border-primary aria-pressed:border-primary aria-pressed:bg-primary-softer aria-pressed:ring-1 aria-pressed:ring-primary"
                                    data-id="<?php echo esc_attr($variation['variation_id']); ?>"
                                    data-qty="<?php echo esc_attr($count); ?>" data-price="<?php echo esc_attr($price); ?>"
                                    data-pack-label="<?php echo esc_attr($label); ?>"
                                    data-display-price="<?php echo esc_attr(wp_strip_all_tags(wc_price($price))); ?>"
                                    data-display-price-html="<?php echo esc_attr(wc_price($price)); ?>"
                                    data-per-tab="<?php echo esc_attr($count > 0 ? $price / $count : ''); ?>"
                                    data-val="<?php echo esc_attr($raw_val); ?>">
                                    <span class="text-[16px] font-semibold text-ink"><?php echo esc_html($label); ?></span>
                                    <span class="text-[16px] font-bold text-ink"><?php echo wp_kses_post(wc_price($price)); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>

                    <div class="mt-6 flex flex-col gap-3 lg:flex-row">
                        <div class="flex items-center justify-between gap-4 rounded-full border border-border px-2 lg:justify-start">
                            <?php if ($qty_label !== '') : ?><span class="pl-4 text-[15px] font-semibold text-ink lg:hidden"><?php echo esc_html($qty_label); ?></span><?php endif; ?>
                            <div class="flex items-center">
                                <button type="button" id="qty-dec" class="flex h-12 w-12 items-center justify-center rounded-full text-ink hover:bg-muted" aria-label="<?php echo esc_attr__('Decrease quantity', 'kamagramate'); ?>"><?php echo kmg_glyph('minus'); ?></button>
                                <span id="qty-val" class="w-8 text-center text-[17px] font-bold text-ink" aria-live="polite">1</span>
                                <button type="button" id="qty-inc" class="flex h-12 w-12 items-center justify-center rounded-full text-ink hover:bg-muted" aria-label="<?php echo esc_attr__('Increase quantity', 'kamagramate'); ?>"><?php echo kmg_glyph('plus'); ?></button>
                            </div>
                        </div>
                        <button type="submit" class="kmg-btn kmg-btn-primary flex-1">
                            <span id="submit-btn-text"><?php echo esc_html(trim($buy_label . ' - ')); ?> <?php echo wp_kses_post(wc_price($initial_price)); ?></span>
                        </button>
                    </div>

                    <input type="hidden" name="add-to-cart" value="<?php echo absint($product_id); ?>" />
                    <input type="hidden" name="product_id" value="<?php echo absint($product_id); ?>" />
                    <input type="hidden" name="variation_id" class="variation_id" value="<?php echo esc_attr($variations[$default_index]['variation_id'] ?? ''); ?>" />
                    <input type="hidden" name="<?php echo esc_attr($attr_key); ?>" class="variation_attribute_hidden" value="<?php echo esc_attr($variations[$default_index]['attributes'][$attr_key] ?? ''); ?>" />
                    <input type="hidden" name="quantity" class="form-quantity" value="1" />
                </form>
            <?php elseif ($in_stock && $product->is_purchasable()) : ?>
                <div class="mt-10"><?php woocommerce_template_single_add_to_cart(); ?></div>
            <?php endif; ?>

            <?php if ($trust_items) : ?>
                <ul class="mt-10 grid gap-6 border-t border-border pt-10 sm:grid-cols-3">
                    <?php foreach ($trust_items as $item) : ?>
                        <li class="flex items-start gap-3 sm:flex-col">
                            <span class="kmg-icon !h-11 !w-11 !rounded-xl"><?php echo waklert_render_card_icon(['icon_preset' => $item['icon'], 'size_class' => 'h-5 w-5']); ?></span>
                            <span>
                                <span class="block text-[15px] font-semibold text-ink"><?php echo esc_html($item['title']); ?></span>
                                <?php if ($item['desc'] !== '') : ?><span class="block text-[14px] text-muted-foreground"><?php echo esc_html($item['desc']); ?></span><?php endif; ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($specs) : ?>
                <details open class="kmg-accordion group mt-10 border-t border-border">
                    <summary class="flex items-center justify-between py-6">
                        <span class="font-heading text-[20px] font-bold text-ink"><?php echo esc_html($specs_heading); ?></span>
                        <span class="kmg-accordion-icon flex h-8 w-8 items-center justify-center rounded-full bg-primary-soft text-primary"><?php echo kmg_glyph('plus', 'h-4 w-4'); ?></span>
                    </summary>
                    <dl class="divide-y divide-border">
                        <?php foreach ($specs as $spec) : ?>
                            <div class="flex items-start justify-between gap-6 py-4 text-[16px]">
                                <dt class="text-muted-foreground"><?php echo esc_html($spec[0]); ?></dt>
                                <dd class="text-right font-semibold text-ink"><?php echo esc_html(wp_strip_all_tags((string) $spec[1])); ?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                </details>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($in_stock && $variations && $sticky_label !== '') : ?>
<div id="sticky-product-bar" class="pointer-events-none fixed inset-x-0 bottom-0 z-40 translate-y-full border-t border-border bg-white/95 opacity-0 backdrop-blur-md transition duration-300" aria-hidden="true" inert>
    <div class="kmg-container flex items-center justify-between gap-3 py-3">
        <div class="flex min-w-0 items-center gap-3">
            <?php if ($image_url) : ?><img src="<?php echo esc_url($image_url); ?>" alt="" class="hidden h-12 w-12 shrink-0 rounded-xl bg-primary-soft object-contain p-1 min-[420px]:block"><?php endif; ?>
            <div class="min-w-0">
                <p class="hidden truncate text-[14px] font-semibold text-ink sm:block"><?php echo esc_html($product->get_name()); ?></p>
                <p class="text-[15px]"><span id="sticky-product-price" class="font-bold text-ink"><?php echo esc_html(wp_strip_all_tags(wc_price($initial_price))); ?></span> <span id="sticky-product-pack" class="text-muted-foreground"><?php echo esc_html($sticky_pack); ?></span></p>
            </div>
        </div>
        <button id="sticky-product-submit" type="button" class="kmg-btn kmg-btn-primary kmg-btn-sm shrink-0"><?php echo esc_html($sticky_label); ?></button>
    </div>
</div>
<?php endif; ?>

<?php if (trim(wp_strip_all_tags($description)) !== '') : ?>
<section class="kmg-section kmg-section-tint" data-testid="section-product-description">
    <div class="kmg-container-narrow">
        <div class="prose max-w-none text-[18px]"><?php echo apply_filters('the_content', $description); ?></div>
    </div>
</section>
<?php endif; ?>

<?php
if (apply_filters('waklert_show_product_reviews', false)) {
    get_template_part('template-parts/product-reviews', null, ['product_id' => $product_id, 'product_name' => $product->get_name()]);
}

if (have_rows('modules', $product_id)) {
    while (have_rows('modules', $product_id)) {
        the_row();
        if (in_array(get_row_layout(), ['product_summary', 'product_description', 'reviews', 'reviews_carousel'], true)) {
            continue;
        }
        get_template_part('modules/content', get_row_layout());
    }
}

$related = wc_get_related_products($product_id, 4);
if ($related) : ?>
<section class="kmg-section border-t border-border">
    <div class="kmg-container">
        <?php kmg_section_head(['eyebrow' => $opt('product_related_eyebrow'), 'title' => $opt('product_related_heading')]); ?>
        <div class="grid grid-cols-1 gap-x-6 gap-y-14 sm:grid-cols-2 lg:grid-cols-4">
            <?php
            foreach ($related as $related_id) {
                $GLOBALS['post'] = get_post($related_id);
                setup_postdata($GLOBALS['post']);
                $GLOBALS['product'] = wc_get_product($related_id);
                wc_get_template_part('content', 'product');
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
<?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('product-purchase-form');
    if (!form) return;
    const rows = form.querySelectorAll('.variation-row-btn');
    const inputVarId = form.querySelector('input.variation_id');
    const inputAttr = form.querySelector('input.variation_attribute_hidden');
    const inputQty = form.querySelector('input.form-quantity');
    const qtyVal = document.getElementById('qty-val');
    const btnText = document.getElementById('submit-btn-text');
    const mainPrice = document.querySelector('[data-testid="text-product-price"]');
    const stickyBar = document.getElementById('sticky-product-bar');
    const stickyPack = document.getElementById('sticky-product-pack');
    const stickyPrice = document.getElementById('sticky-product-price');
    const stickySubmit = document.getElementById('sticky-product-submit');
    const buyLabel = form.dataset.buyLabel || '';
    let selected = form.querySelector('.variation-row-btn.active-row') || rows[0];
    let price = selected ? parseFloat(selected.dataset.price) : 0;
    let quantity = 1;
    const money = (n) => '$' + n.toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    function render() {
        const total = money(price * quantity);
        btnText.textContent = (buyLabel ? buyLabel + ' - ' : '') + total;
        if (stickyPrice) stickyPrice.textContent = total;
    }

    rows.forEach((row) => row.addEventListener('click', () => {
        rows.forEach((r) => { r.classList.remove('active-row'); r.setAttribute('aria-pressed', 'false'); });
        row.classList.add('active-row');
        row.setAttribute('aria-pressed', 'true');
        inputVarId.value = row.dataset.id;
        inputAttr.value = row.dataset.val;
        price = parseFloat(row.dataset.price);
        if (mainPrice && row.dataset.displayPriceHtml) mainPrice.innerHTML = row.dataset.displayPriceHtml;
        if (stickyPack) stickyPack.textContent = row.dataset.packLabel || '';
        render();
    }));

    document.getElementById('qty-dec').addEventListener('click', () => {
        if (quantity > 1) { quantity--; qtyVal.textContent = quantity; inputQty.value = quantity; render(); }
    });
    document.getElementById('qty-inc').addEventListener('click', () => {
        quantity++; qtyVal.textContent = quantity; inputQty.value = quantity; render();
    });

    if (stickyBar) {
        let frame = 0;
        const toggle = () => {
            const show = form.getBoundingClientRect().bottom <= 0;
            stickyBar.classList.toggle('translate-y-full', !show);
            stickyBar.classList.toggle('opacity-0', !show);
            stickyBar.classList.toggle('pointer-events-none', !show);
            stickyBar.setAttribute('aria-hidden', show ? 'false' : 'true');
            stickyBar.inert = !show;
            document.body.classList.toggle('has-sticky-product-bar', show);
        };
        const queue = () => { if (!frame) frame = requestAnimationFrame(() => { toggle(); frame = 0; }); };
        ['scroll', 'resize', 'pageshow'].forEach((evt) => window.addEventListener(evt, queue, { passive: true }));
        queue();
        if (stickySubmit) stickySubmit.addEventListener('click', () => (form.requestSubmit ? form.requestSubmit() : form.submit()));
    }
});
</script>
<?php get_footer('shop'); ?>
