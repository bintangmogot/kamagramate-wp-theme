<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="lg:grid lg:grid-cols-12 lg:gap-10 lg:items-start my-8">
    <div class="lg:col-span-8">
        <form class="woocommerce-cart-form bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="overflow-x-auto">
                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents w-full text-left whitespace-nowrap" cellspacing="0">
                    <thead class="bg-stone-50 border-b border-stone-200">
                        <tr>
                            <th class="product-remove px-6 py-4 text-xs font-semibold tracking-wider text-stone-500 uppercase"><span class="screen-reader-text"><?php esc_html_e( 'Remove item', 'woocommerce' ); ?></span></th>
                            <th class="product-thumbnail px-6 py-4 text-xs font-semibold tracking-wider text-stone-500 uppercase"><span class="screen-reader-text"><?php esc_html_e( 'Thumbnail image', 'woocommerce' ); ?></span></th>
                            <th scope="col" class="product-name px-6 py-4 text-xs font-semibold tracking-wider text-stone-500 uppercase"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                            <th scope="col" class="product-price px-6 py-4 text-xs font-semibold tracking-wider text-stone-500 uppercase"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                            <th scope="col" class="product-quantity px-6 py-4 text-xs font-semibold tracking-wider text-stone-500 uppercase"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                            <th scope="col" class="product-subtotal px-6 py-4 text-xs font-semibold tracking-wider text-stone-500 uppercase"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            $visible = apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key );

                            if ( $_product instanceof WC_Product && $_product->exists() && $cart_item['quantity'] > 0 && $visible ) {
                                $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                <tr class="woocommerce-cart-form__cart-item hover:bg-stone-50 transition-colors <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                                    <td class="product-remove px-6 py-6 text-center align-middle">
                                        <?php
                                            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a role="button" href="%s" class="remove inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></a>',
                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                    esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                                    esc_attr( $product_id ),
                                                    esc_attr( $_product->get_sku() )
                                                ),
                                                $cart_item_key
                                            );
                                        ?>
                                    </td>

                                    <td class="product-thumbnail px-6 py-6 align-middle">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(array(80, 80), array('class' => 'rounded-lg border border-stone-200 object-cover w-16 h-16')), $cart_item, $cart_item_key );

                                    if ( ! $product_permalink ) {
                                        echo $thumbnail; // PHPCS: XSS ok.
                                    } else {
                                        printf( '<a href="%s" class="block shrink-0">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                    }
                                    ?>
                                    </td>

                                    <td class="product-name px-6 py-6 align-middle" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                        <div class="font-semibold text-slate-900 text-base">
                                            <?php
                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( $product_name . '&nbsp;' );
                                            } else {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="hover:text-primary transition-colors">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                        // Meta data.
                                        $meta = wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
                                        if ($meta) {
                                            echo '<div class="text-sm text-stone-500 mt-1">' . $meta . '</div>';
                                        }

                                        // Backorder notification.
                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-amber-600 mt-1">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                        }
                                        ?>
                                    </td>

                                    <td class="product-price px-6 py-6 font-medium text-slate-700 align-middle" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                        <?php
                                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                        ?>
                                    </td>

                                    <td class="product-quantity px-6 py-6 align-middle" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $min_quantity = 1;
                                        $max_quantity = 1;
                                    } else {
                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();
                                    }

                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_quantity,
                                            'min_value'    => $min_quantity,
                                            'product_name' => $product_name,
                                            'classes'      => array('w-20', 'rounded-md', 'border-stone-300', 'shadow-sm', 'focus:border-primary', 'focus:ring-primary', 'sm:text-sm'),
                                        ),
                                        $_product,
                                        false
                                    );

                                    // Let's modify the quantity input directly if needed via CSS or filter, 
                                    // but default WooCommerce wrapper isn't great. We'll rely on global CSS to fix the input.
                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                    ?>
                                    </td>

                                    <td class="product-subtotal px-6 py-6 font-bold text-slate-900 align-middle" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                        <?php
                                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>

                        <tr>
                            <td colspan="6" class="actions px-6 py-5 bg-stone-50 border-t border-stone-200">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <?php if ( wc_coupons_enabled() ) { ?>
                                        <div class="coupon flex items-center gap-2 w-full sm:w-auto">
                                            <label for="coupon_code" class="sr-only"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> 
                                            <input type="text" name="coupon_code" class="input-text rounded-lg border-stone-300 py-2.5 px-4 shadow-sm focus:border-primary focus:ring-primary sm:text-sm flex-1 sm:w-48" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> 
                                            <button type="submit" class="button px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg transition-colors whitespace-nowrap" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></button>
                                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                        </div>
                                    <?php } ?>

                                    <button type="submit" class="button px-6 py-2.5 bg-stone-200 hover:bg-stone-300 text-slate-700 font-medium rounded-lg transition-colors w-full sm:w-auto" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

                                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                                </div>
                            </td>
                        </tr>

                        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                    </tbody>
                </table>
            </div>
            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>
    </div>

    <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

    <div class="lg:col-span-4 mt-8 lg:mt-0">
        <div class="cart-collaterals bg-white rounded-2xl border border-stone-200 shadow-sm p-6 lg:p-8 relative">
            <!-- decorative gradient -->
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-t-2xl"></div>
            
            <?php
                /**
                 * Cart collaterals hook.
                 *
                 * @hooked woocommerce_cross_sell_display
                 * @hooked woocommerce_cart_totals - 10
                 */
                do_action( 'woocommerce_cart_collaterals' );
            ?>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
