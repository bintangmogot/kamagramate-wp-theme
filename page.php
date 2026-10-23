<?php
/**
 * Default page template. Pages built from ACF modules render them in order;
 * plain pages (and WooCommerce cart / checkout / account) render the editor content.
 */

get_header();
?>

<main id="main">
    <?php
    if (have_rows('modules')) {
        while (have_rows('modules')) {
            the_row();
            get_template_part('modules/content', get_row_layout());
        }
    } else {
        $is_shop_flow = class_exists('WooCommerce') && (is_cart() || is_checkout() || is_account_page());
        ?>
        <header class="border-b border-border bg-primary-softer">
            <div class="kmg-container <?php echo $is_shop_flow ? 'py-10 md:py-14' : 'pb-16 pt-12 md:pb-20 md:pt-16'; ?>">
                <?php get_template_part('template-parts/breadcrumbs'); ?>
                <h1 class="<?php echo $is_shop_flow ? 'kmg-h2' : 'kmg-display'; ?> mt-6"><?php the_title(); ?></h1>
            </div>
        </header>

        <div class="<?php echo $is_shop_flow ? 'py-12 md:py-16' : 'kmg-section'; ?>">
            <div class="<?php echo $is_shop_flow ? 'kmg-container' : 'kmg-container-narrow'; ?>">
                <div class="<?php echo $is_shop_flow ? '' : 'prose max-w-none text-[18px]'; ?>">
                    <?php
                    while (have_posts()) {
                        the_post();
                        the_content();
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</main>

<?php
get_footer();
