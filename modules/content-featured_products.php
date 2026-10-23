<?php
/**
 * Module: featured_products
 * Fields: subheading, title, description, products (relationship), card_style
 * (spotlight | grid), button_text, button_url.
 */

$products = array_filter((array) get_sub_field('products'));
if (!$products) {
    return;
}

$style  = get_sub_field('card_style') === 'grid' ? 'grid' : 'spotlight';
$cols   = $style === 'grid' ? 'sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-14' : 'lg:grid-cols-2 gap-6 md:gap-8';
?>
<section class="kmg-section" data-testid="featured-products">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('subheading'),
            'title'   => get_sub_field('title'),
            'lead'    => get_sub_field('description'),
        ]); ?>

        <div class="grid <?php echo esc_attr($cols); ?>">
            <?php
            foreach ($products as $p) {
                $product_id = is_numeric($p) ? (int) $p : (is_a($p, 'WC_Product') ? $p->get_id() : (int) ($p->ID ?? 0));
                if (!$product_id) {
                    continue;
                }
                $GLOBALS['post'] = get_post($product_id);
                setup_postdata($GLOBALS['post']);
                $GLOBALS['product'] = wc_get_product($product_id);
                wc_get_template_part('content', $style === 'grid' ? 'product' : 'product-horizontal');
            }
            wp_reset_postdata();
            ?>
        </div>

        <?php if (get_sub_field('button_text') && get_sub_field('button_url')) : ?>
            <div class="mt-14 flex justify-center md:mt-16">
                <?php kmg_button((string) get_sub_field('button_text'), (string) get_sub_field('button_url'), 'outline'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
