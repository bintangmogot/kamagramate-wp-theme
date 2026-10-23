<?php
/**
 * Module: products_grid - the main WooCommerce product loop (shop / category query).
 */
?>
<section class="kmg-section" data-testid="products-grid">
    <div class="kmg-container">
        <?php
        if (woocommerce_product_loop()) {
            woocommerce_product_loop_start();
            if (wc_get_loop_prop('total')) {
                while (have_posts()) {
                    the_post();
                    do_action('woocommerce_shop_loop');
                    wc_get_template_part('content', 'product');
                }
            }
            woocommerce_product_loop_end();
            woocommerce_pagination();
        } else {
            do_action('woocommerce_no_products_found');
        }
        ?>
    </div>
</section>
