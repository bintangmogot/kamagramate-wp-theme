<?php
/**
 * Product archives.
 *
 * Shop page: renders the Shop page's ACF modules (page_hero, products_grid, faqs...).
 * Category / tag / search archives: term header (name, description, "Top Image"),
 * the product loop, then the category's "bottom_content" WYSIWYG field.
 */

defined('ABSPATH') || exit;

get_header('shop');

$shop_page_id = wc_get_page_id('shop');
?>
<main id="main">
<?php
if (is_shop() && have_rows('modules', $shop_page_id)) {
    while (have_rows('modules', $shop_page_id)) {
        the_row();
        // Guide, comparison and SEO copy are kept on the Shop page record but not shown on the grid page.
        if (in_array(get_row_layout(), ['product_guide', 'catalog_comparison', 'comparison', 'seo_text'], true)) {
            continue;
        }
        get_template_part('modules/content', get_row_layout());
    }
} else {
    $term        = get_queried_object();
    $is_term     = $term instanceof WP_Term;
    $description = $is_term ? term_description($term) : '';
    $top_image   = $is_term ? get_field('top_image', $term) : null;
    $bottom      = $is_term ? get_field('bottom_content', $term) : get_field('bottom_content', $shop_page_id);
    $top_img     = kmg_image($top_image, 'large', ['class' => 'h-full w-full object-contain p-8', 'alt' => '']);
    ?>
    <section class="border-b border-border bg-primary-softer">
        <div class="kmg-container grid items-center gap-10 pb-16 pt-12 md:pb-20 md:pt-16 <?php echo $top_img ? 'lg:grid-cols-12' : ''; ?>">
            <div class="<?php echo $top_img ? 'lg:col-span-7' : 'max-w-4xl'; ?>">
                <?php get_template_part('template-parts/breadcrumbs'); ?>
                <h1 class="kmg-display mt-8"><?php woocommerce_page_title(); ?></h1>
                <?php if ($description) : ?>
                    <div class="kmg-lead mt-6 max-w-2xl"><?php echo wp_kses_post($description); ?></div>
                <?php endif; ?>
            </div>
            <?php if ($top_img) : ?>
                <div class="aspect-[4/3] overflow-hidden rounded-[2rem] bg-white lg:col-span-5"><?php echo $top_img; ?></div>
            <?php endif; ?>
        </div>
    </section>

    <section class="kmg-section">
        <div class="kmg-container">
            <?php
            if (woocommerce_product_loop()) {
                woocommerce_product_loop_start();
                while (have_posts()) {
                    the_post();
                    do_action('woocommerce_shop_loop');
                    wc_get_template_part('content', 'product');
                }
                woocommerce_product_loop_end();
                woocommerce_pagination();
            } else {
                do_action('woocommerce_no_products_found');
            }
            ?>
        </div>
    </section>

    <?php if (!empty($bottom)) : ?>
    <section class="kmg-section kmg-section-tint">
        <div class="kmg-container-narrow prose max-w-none text-[18px]"><?php echo wp_kses_post($bottom); ?></div>
    </section>
    <?php endif;
}
?>
</main>
<?php get_footer('shop'); ?>
