<?php
/**
 * Module: featured_products
 * Fields: subheading, title, description, products (relationship), card_style
 * (carousel | grid | spotlight), button_text, button_url.
 */

$products = array_filter((array) get_sub_field('products'));
if (!$products) {
    return;
}

$style    = in_array(get_sub_field('card_style'), ['grid', 'spotlight'], true) ? get_sub_field('card_style') : 'carousel';
$track_id = 'kmg-products-' . wp_unique_id();

$render = static function ($product_id, $template) {
    $GLOBALS['post'] = get_post($product_id);
    setup_postdata($GLOBALS['post']);
    $GLOBALS['product'] = wc_get_product($product_id);
    wc_get_template_part('content', $template);
};
$ids = array_values(array_filter(array_map(static function ($p) {
    return is_numeric($p) ? (int) $p : (is_a($p, 'WC_Product') ? $p->get_id() : (int) ($p->ID ?? 0));
}, $products)));
?>
<section class="kmg-section" data-testid="featured-products">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('subheading'),
            'title'   => get_sub_field('title'),
            'lead'    => get_sub_field('description'),
        ]); ?>

        <?php if ($style === 'carousel') : ?>
            <div class="relative">
                <div id="<?php echo esc_attr($track_id); ?>" class="kmg-scroll-x -mx-2 px-2 pb-6 pt-1">
                    <?php foreach ($ids as $id) : ?>
                        <div class="w-[82%] sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] xl:w-[calc(25%-18px)]"><?php $render($id, 'product'); ?></div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($ids) > 1) : ?>
                    <button type="button" data-kmg-scroll="<?php echo esc_attr($track_id); ?>" data-dir="-1" class="absolute -left-3 top-[118px] hidden h-12 w-12 items-center justify-center rounded-full bg-white text-ink shadow-[0_8px_20px_-8px_rgba(34,18,79,0.4)] hover:text-primary md:flex xl:-left-6" aria-label="<?php echo esc_attr__('Previous', 'kamagramate'); ?>"><?php echo kmg_glyph('arrow-left'); ?></button>
                    <button type="button" data-kmg-scroll="<?php echo esc_attr($track_id); ?>" data-dir="1" class="absolute -right-3 top-[118px] hidden h-12 w-12 items-center justify-center rounded-full bg-white text-ink shadow-[0_8px_20px_-8px_rgba(34,18,79,0.4)] hover:text-primary md:flex xl:-right-6" aria-label="<?php echo esc_attr__('Next', 'kamagramate'); ?>"><?php echo kmg_glyph('arrow-right'); ?></button>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="grid <?php echo $style === 'grid' ? 'gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4' : 'gap-6 md:gap-8 lg:grid-cols-2'; ?>">
                <?php foreach ($ids as $id) { $render($id, $style === 'grid' ? 'product' : 'product-horizontal'); } ?>
            </div>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>

        <?php if (get_sub_field('button_text') && get_sub_field('button_url')) : ?>
            <div class="mt-12 flex justify-center">
                <?php kmg_button((string) get_sub_field('button_text'), (string) get_sub_field('button_url'), 'primary'); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
