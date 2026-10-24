<?php
/**
 * Kamagramate - theme functions
 */

// Define constants for easy reference
define('MODMY_THEME_VERSION', '1.0.0');
define('MODMY_THEME_DIR', get_stylesheet_directory());
define('MODMY_THEME_URI', get_stylesheet_directory_uri());

/** Legacy decorative rings. The minimal design has no background ornaments, so this prints nothing. */
function waklert_output_pattern_rings($extra_class = '') {
}

// 1. Theme setup (menus, supports, image sizes) and shared UI helpers
require_once MODMY_THEME_DIR . '/inc/kmg-ui.php';
require_once MODMY_THEME_DIR . '/inc/security.php';
require_once MODMY_THEME_DIR . '/inc/theme-setup.php';
require_once MODMY_THEME_DIR . '/inc/nav-walkers.php';
require_once MODMY_THEME_DIR . '/inc/icon-library.php';

// 2. Asset enqueues (CSS, JS)
require_once MODMY_THEME_DIR . '/inc/enqueue.php';

// 3. ACF Theme Options (Global settings)
require_once MODMY_THEME_DIR . '/inc/theme-options.php';

// 4. Shortcodes
require_once MODMY_THEME_DIR . '/inc/shortcodes.php';

// 5. WooCommerce customizations
require_once MODMY_THEME_DIR . '/inc/woocommerce.php';
require_once MODMY_THEME_DIR . '/inc/waklert-currency.php';

// 6. Custom Post Types
require_once MODMY_THEME_DIR . '/inc/post-types.php';
require_once MODMY_THEME_DIR . '/inc/australia-delivery.php';


// 7. ACF Fallbacks (prevents fatal errors if ACF is not active)
if (!function_exists('get_field')) {
    function get_field($selector, $post_id = false, $format_value = true) { return false; }
}
if (!function_exists('get_sub_field')) {
    function get_sub_field($selector, $format_value = true) { return false; }
}
if (!function_exists('have_rows')) {
    function have_rows($selector, $post_id = false) { return false; }
}
if (!function_exists('the_row')) {
    function the_row() { return false; }
}

require_once MODMY_THEME_DIR . '/inc/product-presentation.php';
require_once MODMY_THEME_DIR . '/inc/ajax-reviews.php';

// 9. Enable SVG Uploads in Media Library
add_filter('upload_mimes', function($mimes) {
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

// 10. Force precise Homepage Title
add_filter('document_title_parts', function($title) {
    if (is_front_page()) {
        $title['title'] = get_bloginfo('name');
        $title['tagline'] = get_bloginfo('description');
        unset($title['site']);
    }
    return $title;
});
add_filter('document_title_separator', function($sep) {
    if (is_front_page()) { return '|'; }
    return $sep;
});

// 11. Custom Gravity Forms submit button markup with envelope icon
add_filter('gform_submit_button', function($button, $form) {
    if ($form['id'] == 1) {
        $btn_text = function_exists('modmy_t') ? "Send Message" : "Send Message";
        return sprintf(
            '<button type="submit" id="gform_submit_button_%d" class="button gform_button flex items-center justify-center gap-2 w-full md:w-auto">
                <span>%s</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>',
            $form['id'],
            esc_html($btn_text)
        );
    }
    return $button;
}, 10, 2);


add_action("wp_head", function() {
    if (function_exists("is_checkout") && is_checkout()) {
        echo "<style>
            .woocommerce-terms-and-conditions-checkbox-text a {
                color: #2563eb !important;
                text-decoration: underline !important;
                font-weight: 600;
            }
            .woocommerce-terms-and-conditions-checkbox-text a:hover {
                color: #1d4ed8 !important;
            }
            .woocommerce-privacy-policy-text a {
                color: #2563eb !important;
                text-decoration: underline !important;
                font-weight: 600;
            }
            .woocommerce-privacy-policy-text a:hover {
                color: #1d4ed8 !important;
            }
        </style>";
    }
});


if( function_exists("acf_add_local_field_group") ):
    acf_add_local_field_group(array(
        "key" => "group_review_linked_product",
        "title" => "Additional Review Settings",
        "fields" => array(
            array(
                "key" => "field_review_linked_product",
                "label" => "Linked Product",
                "name" => "linked_product",
                "type" => "post_object",
                "instructions" => "Select a product if this is a Product Review. Leave empty for General Reviews.",
                "required" => 0,
                "post_type" => array(
                    0 => "product",
                ),
                "taxonomy" => "",
                "allow_null" => 1,
                "multiple" => 0,
                "return_format" => "id",
                "ui" => 1,
            ),
            ),
        "location" => array(
            array(
                array(
                    "param" => "post_type",
                    "operator" => "==",
                    "value" => "review",
                ),
            ),
        ),
        "menu_order" => 0,
        "position" => "side",
        "style" => "default",
        "label_placement" => "top",
        "instruction_placement" => "label",
        "hide_on_screen" => "",
        "active" => true,
        "description" => "",
    ));
endif;


// Add Custom Columns to Review CPT
add_filter("manage_review_posts_columns", function($columns) {

    $columns["linked_product"] = "Linked Product";
    return $columns;
});

// Populate Custom Columns
add_action("manage_review_posts_custom_column", function($column, $post_id) {
    if ($column === "review_category") {
        $terms = get_the_terms($post_id, "review_category");
        if ($terms && !is_wp_error($terms)) {
            $term_names = array_map(function($term) { return $term->name; }, $terms);
            echo implode(", ", $term_names);
        } else {
            echo "";
        }
    }
    if ($column === "linked_product") {
        $product_id = get_field("linked_product", $post_id);
        if ($product_id) {
            echo get_the_title($product_id);
        } else {
            echo "";
        }
    }
}, 10, 2);


// Keep product copy in the shared Product Summary and Product Description modules.
if( function_exists('acf_add_local_field_group') ):
acf_add_local_field_group(array(
    'key' => 'group_product_content',
    'title' => 'Product Content',
    'fields' => array(
        array(
            'key' => 'field_product_content_modules_notice',
            'label' => 'Edit product copy in Modules',
            'name' => 'product_content_modules_notice',
            'type' => 'message',
            'message' => 'Product Summary and Product Description content now lives in the Modules field below. The summary appears under the product title; the full description keeps its current position and styling.',
            'new_lines' => 'wpautop',
            'esc_html' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'product',
            ),
        ),
    ),
    'menu_order' => -1,
    
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array('the_content', 'excerpt'),
    'position' => 'normal',
));
endif;

// Keep product-only layouts out of the shared Modules editor on pages and posts.
add_filter('acf/prepare_field/key=field_modules', function($field) {
    if (empty($field['layouts']) || !function_exists('get_current_screen')) {
        return $field;
    }

    $screen = get_current_screen();
    if (!$screen || $screen->post_type === 'product') {
        return $field;
    }

    unset(
        $field['layouts']['layout_wa_product_summary'],
        $field['layouts']['layout_wa_product_description']
    );

    return $field;
});

// Product copy is managed through the Product Summary and Product Description modules.
add_action('admin_head', function() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'product') {
        echo '<style>
            #postdivrich, #postexcerpt { display: none !important; }
        </style>';
    }
});

/**
 * Add custom Open Graph and Twitter meta tags for WhatsApp, Facebook, Instagram
 * This ensures the featured image and correct domain are used when sharing links.
 */
add_action( 'wp_head', 'modamal_custom_social_meta_tags', 1 );
function modamal_custom_social_meta_tags() {
    // Only output on single posts or pages
    if ( is_singular() ) {
        global $post;
        
        $title = get_the_title();
        $url = get_permalink();
        $site_name = get_bloginfo('name');
        
        echo "\n<!-- Custom Social Meta Tags -->\n";
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
        
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";

        // Featured Image
        if ( has_post_thumbnail() ) {
            $image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            if ( $image_url ) {
                echo '<meta property="og:image" content="' . esc_url($image_url) . '" />' . "\n";
                echo '<meta property="og:image:secure_url" content="' . esc_url($image_url) . '" />' . "\n";
                echo '<meta property="og:image:width" content="1200" />' . "\n";
                echo '<meta property="og:image:height" content="630" />' . "\n";
                echo '<meta name="twitter:image" content="' . esc_url($image_url) . '" />' . "\n";
            }
        }
        
        // Description
        $excerpt = '';
        if ( has_excerpt() ) {
            $excerpt = get_the_excerpt();
        } elseif ( isset($post->post_content) && !empty($post->post_content) ) {
            $excerpt = wp_trim_words( $post->post_content, 20, '...' );
        }
        
        if ( $excerpt ) {
            echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags($excerpt) ) . '" />' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr( wp_strip_all_tags($excerpt) ) . '" />' . "\n";
        }
        echo "<!-- End Custom Social Meta Tags -->\n\n";
    }
}

/**
 * Hide variation attributes (e.g., Pack Size) from the "Product specs" / Additional Information tab.
 */
add_filter( 'woocommerce_display_product_attributes', 'modmy_hide_variation_attributes_from_specs', 10, 2 );
function modmy_hide_variation_attributes_from_specs( $product_attributes, $product ) {
    if ( ! is_object( $product ) ) return $product_attributes;
    
    $attributes = $product->get_attributes();
    
    foreach ( $attributes as $attribute ) {
        if ( $attribute->get_variation() ) {
            $label = wc_attribute_label( $attribute->get_name() );
            
            foreach ( $product_attributes as $key => $formatted_attribute ) {
                if ( isset( $formatted_attribute['label'] ) && $formatted_attribute['label'] === $label ) {
                    unset( $product_attributes[ $key ] );
                }
            }
        }
    }
    
    return $product_attributes;
}

/**
 * Remove the "Additional Information" tab entirely if there are no non-variation attributes to display.
 */
add_filter( 'woocommerce_product_tabs', 'modmy_remove_empty_additional_information_tab', 98 );
function modmy_remove_empty_additional_information_tab( $tabs ) {
    global $product;
    if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
        return $tabs;
    }
    
    $has_non_variation_attributes = false;
    $attributes = $product->get_attributes();
    
    foreach ( $attributes as $attribute ) {
        if ( $attribute->get_visible() && ! $attribute->get_variation() ) {
            $has_non_variation_attributes = true;
            break;
        }
    }
    
    if ( ! $has_non_variation_attributes && isset( $tabs['additional_information'] ) ) {
        unset( $tabs['additional_information'] );
    }
    
    return $tabs;
}

/**
 * ONE-TIME SCRIPT TO FIX "0" VARIATIONS (Run by visiting /?fix_pack_sizes=1)
 */
add_action( 'init', 'modmy_fix_zero_pack_sizes' );
function modmy_fix_zero_pack_sizes() {
    if ( ! isset( $_GET['fix_pack_sizes'] ) || ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 100, // Process in batches to avoid timeout
        'paged'          => isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => 'variable',
            ),
        ),
    );

    $products = get_posts( $args );
    if ( empty( $products ) ) {
        echo "<h1>Migration Complete!</h1>";
        echo "<p>All products have been processed.</p>";
        echo "<p>Please remove the <code>modmy_fix_zero_pack_sizes</code> function from <code>functions.php</code> now.</p>";
        exit;
    }

    $count = 0;
    $skipped = 0;

    foreach ( $products as $post ) {
        $product = wc_get_product( $post->ID );
        if ( ! $product ) continue;
        
        $variations = $product->get_children();
        if ( empty( $variations ) ) continue;

        $attributes = $product->get_attributes();
        foreach ( $attributes as $attr ) {
            if ( $attr->get_variation() ) {
                $attr_key = 'attribute_' . sanitize_title( $attr->get_name() );
                
                // Check if already processed (first variation has a numeric value > 0)
                $first_var = wc_get_product( $variations[0] );
                if ( $first_var ) {
                    $existing_val = $first_var->get_meta( $attr_key );
                    if ( preg_match( '/\d+/', (string) $existing_val, $m ) && (int) $m[0] > 0 ) {
                        $skipped++;
                        break; // Skip this product
                    }
                }

                $pack_sizes = array();
                
                // Handle both Global Taxonomies (pa_pack-size) and Custom Product Attributes ("Pack Size")
                if ( $attr->is_taxonomy() ) {
                    $terms = wc_get_product_terms( $product->get_id(), $attr->get_name(), array( 'fields' => 'names' ) );
                    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                        foreach ( $terms as $term ) {
                            if ( preg_match( '/\d+/', $term, $matches ) ) {
                                $pack_sizes[] = (int) $matches[0];
                            }
                        }
                    }
                } else {
                    $options = $attr->get_options();
                    if ( ! empty( $options ) ) {
                        foreach ( $options as $option ) {
                            if ( preg_match( '/\d+/', $option, $matches ) ) {
                                $pack_sizes[] = (int) $matches[0];
                            }
                        }
                    }
                }
                
                if ( empty( $pack_sizes ) ) break;

                sort( $pack_sizes ); // Sort ascending: 50, 100, 200, 300...

                $var_objs = array();
                foreach ( $variations as $var_id ) {
                    $var_obj = wc_get_product( $var_id );
                    if ( $var_obj ) {
                        $var_objs[] = $var_obj;
                    }
                }

                // Sort variations by price ascending
                usort( $var_objs, function( $a, $b ) {
                    return (float) $a->get_price() <=> (float) $b->get_price();
                });

                $offset = max( 0, count( $pack_sizes ) - count( $var_objs ) );
                
                foreach ( $var_objs as $index => $var_obj ) {
                    if ( isset( $pack_sizes[ $index + $offset ] ) ) {
                        $new_val = $pack_sizes[ $index + $offset ];
                        $term_slug = (string) $new_val;
                        
                        if ( $attr->is_taxonomy() ) {
                            foreach ( $attr->get_terms() as $term_obj ) {
                                if ( preg_match( '/\d+/', $term_obj->name, $m ) && $m[0] == $new_val ) {
                                    $term_slug = $term_obj->slug;
                                    break;
                                }
                            }
                        } else {
                            foreach ( $attr->get_options() as $option ) {
                                if ( preg_match( '/\d+/', $option, $m ) && $m[0] == $new_val ) {
                                    $term_slug = $option;
                                    break;
                                }
                            }
                        }
                        
                        update_post_meta( $var_obj->get_id(), $attr_key, $term_slug );
                    }
                }
                $count++;
                
                break; // process only the primary variation attribute (Pack Size)
            }
        }
    }

    $next_page = $args['paged'] + 1;
    $next_url = "/?fix_pack_sizes=1&paged={$next_page}";

    echo "<h1>Migration Batch Complete!</h1>";
    echo "<p>Processed page {$args['paged']} (Updated: {$count}, Skipped: {$skipped}).</p>";
    echo "<p><a href='{$next_url}' style='font-size: 20px; font-weight: bold; color: blue;'>Click here to process the NEXT 100 products!</a></p>";
    echo "<p>(Keep clicking until it says 'All products have been processed.')</p>";
    exit;
}

// Custom BACS card on the Thank You page. Accounts come from WooCommerce -> Payments ->
// Direct bank transfer; labels from Theme Settings -> Interface Labels.
add_action("woocommerce_thankyou_bacs", "modmy_custom_bacs_details", 5);
function modmy_custom_bacs_details($order_id) {
    $gateways = function_exists('WC') && WC()->payment_gateways ? WC()->payment_gateways->payment_gateways() : [];
    $accounts = isset($gateways['bacs']) ? (array) $gateways['bacs']->account_details : [];
    if (!$accounts) {
        return;
    }
    $label = static function ($name) {
        return trim((string) get_field($name, 'option'));
    };
    $rows = [
        'bank_name'      => $label('label_bank'),
        'account_name'   => $label('label_account_name'),
        'sort_code'      => $label('label_bsb'),
        'account_number' => $label('label_account_number'),
        'bic'            => $label('label_swift'),
    ];

    echo '<style>.woocommerce-bacs-bank-details { display: none !important; }</style>';
    echo '<div class="custom-bacs-details my-8 rounded-2xl border border-border bg-primary-softer p-6 md:p-8">';
    if ($label('label_bank_details') !== '') {
        echo '<h3 class="font-heading text-xl font-bold text-ink">' . esc_html($label('label_bank_details')) . '</h3>';
    }
    foreach ($accounts as $account) {
        echo '<dl class="mt-4 grid gap-x-8 gap-y-3 sm:grid-cols-2">';
        foreach ($rows as $key => $text) {
            if (empty($account[$key])) {
                continue;
            }
            echo '<div><dt class="text-[13px] font-semibold uppercase tracking-[0.12em] text-muted-foreground">' . esc_html($text) . '</dt>';
            echo '<dd class="mt-1 font-heading text-lg font-bold text-ink">' . esc_html($account[$key]) . '</dd></div>';
        }
        echo '</dl>';
    }
    echo '</div>';
}

// Added missing function
function modmy_get_post_category($post_id) {
    $categories = get_the_category($post_id);
    if (!empty($categories)) {
        return $categories[0]->name;
    }
    return '';
}

// Disable comments on standard blog posts
add_action('init', function () {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('post', 'trackbacks');
});

add_filter('comments_open', function ($open, $post_id) {
    if (get_post_type($post_id) === 'post') {
        return false;
    }
    return $open;
}, 10, 2);

add_filter('pings_open', function ($open, $post_id) {
    if (get_post_type($post_id) === 'post') {
        return false;
    }
    return $open;
}, 10, 2);

// Redirect /about to /about-us
add_action('template_redirect', function() {
    if (is_page('about')) {
        wp_safe_redirect(home_url('/about-us/'), 301);
        exit;
    }
});


