<?php
/**
 * Empty cart page. Message comes from WooCommerce (wc_empty_cart_message),
 * the button label from the woocommerce_return_to_shop_text filter.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;
?>
<div class="kmg-empty-cart mx-auto max-w-xl py-16 text-center md:py-24 [&_.cart-empty]:!m-0 [&_.cart-empty]:!border-0 [&_.cart-empty]:!bg-transparent [&_.cart-empty]:!p-0 [&_.cart-empty]:font-heading [&_.cart-empty]:text-[1.75rem] [&_.cart-empty]:font-bold [&_.cart-empty]:text-ink [&_.cart-empty]:before:!hidden">
    <span class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-full bg-primary-soft text-primary">
        <?php echo waklert_render_card_icon(['icon_preset' => 'discreet_sealed', 'size_class' => 'h-10 w-10']); ?>
    </span>

    <?php do_action('woocommerce_cart_is_empty'); ?>

    <?php if (wc_get_page_id('shop') > 0) : ?>
        <p class="return-to-shop mt-10">
            <a class="kmg-btn kmg-btn-primary" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
                <?php echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce'))); ?>
            </a>
        </p>
    <?php endif; ?>
</div>
