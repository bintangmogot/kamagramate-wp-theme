<?php
/**
 * Navigation walkers.
 *
 * Desktop: flat links; a parent item opens a product mega-menu. Child items that
 * point to WooCommerce products show the product image, active ingredient and
 * lowest price straight from the product. The dropdown intro text comes from the
 * parent item's "Description" and the footer link label from its "Title
 * Attribute" (Appearance -> Menus -> Screen Options).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product data for a menu item, or null when the item is not a product.
 */
function kmg_menu_product_data($item): ?array {
    if (($item->object ?? '') !== 'product' || !function_exists('wc_get_product')) {
        return null;
    }
    $product = wc_get_product((int) $item->object_id);
    if (!$product) {
        return null;
    }
    $price = $product->is_type('variable') ? $product->get_variation_price('min', true) : $product->get_price();
    return [
        'image'      => get_the_post_thumbnail_url($product->get_id(), 'woocommerce_thumbnail'),
        'subtitle'   => function_exists('get_field') ? (string) get_field('product_active_ingredient', $product->get_id()) : '',
        'badge'      => function_exists('get_field') ? (string) get_field('product_badge', $product->get_id()) : '',
        'price_html' => $price !== '' ? wc_price((float) $price) : '',
    ];
}

function kmg_menu_item_is_active($item): bool {
    $classes = (array) ($item->classes ?? []);
    $is_shop = strpos((string) $item->url, '/shop') !== false
        && ((function_exists('is_shop') && is_shop()) || (function_exists('is_product') && is_product()));
    return $item->current
        || in_array('current-menu-item', $classes, true)
        || in_array('current-menu-ancestor', $classes, true)
        || in_array('current-page-ancestor', $classes, true)
        || $is_shop;
}

class Waklert_Desktop_Nav_Walker extends Walker_Nav_Menu {
    /** @var WP_Post|null Current top-level parent, used for dropdown header/footer. */
    private $parent_item = null;

    public function start_lvl(&$output, $depth = 0, $args = null) {
        if ($depth !== 0) {
            $output .= '<ul class="sub-menu">';
            return;
        }
        $intro = $this->parent_item ? trim((string) $this->parent_item->description) : '';
        $output .= '<div class="kmg-mega absolute left-1/2 top-full z-50 w-[640px] max-w-[92vw] -translate-x-1/2 pt-4 opacity-0 invisible translate-y-2 transition duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100">';
        $output .= '<div class="overflow-hidden rounded-3xl border border-border bg-white shadow-[0_32px_64px_-32px_rgb(26_16_48/0.35)]">';
        if ($intro !== '') {
            $output .= '<p class="px-7 pt-6 text-sm text-muted-foreground">' . esc_html($intro) . '</p>';
        }
        $output .= '<ul class="sub-menu grid grid-cols-2 gap-2 p-4">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        if ($depth !== 0) {
            $output .= '</ul>';
            return;
        }
        $output .= '</ul>';
        if ($this->parent_item) {
            $label = trim((string) $this->parent_item->attr_title);
            if ($label !== '') {
                $output .= '<a href="' . esc_url($this->parent_item->url) . '" class="flex items-center justify-between border-t border-border bg-primary-softer px-7 py-4 text-sm font-bold text-primary transition-colors hover:text-primary-dark">';
                $output .= '<span>' . esc_html($label) . '</span>' . kmg_glyph('arrow-right', 'h-4 w-4') . '</a>';
            }
        }
        $output .= '</div></div>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes      = empty($item->classes) ? [] : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes, true);
        $url          = !empty($item->url) ? $item->url : '#';
        $title        = $item->title;

        if ($depth === 0) {
            $this->parent_item = $has_children ? $item : null;
            $active = kmg_menu_item_is_active($item);
            $output .= '<li class="' . esc_attr('relative list-none group ' . implode(' ', $classes)) . '">';
            $output .= '<a href="' . esc_url($url) . '" class="' . ($active ? 'text-primary' : 'text-ink hover:text-primary') . ' inline-flex items-center gap-1.5 px-3 py-2 text-[18px] font-semibold transition-colors"' . ($active ? ' aria-current="page"' : '') . '>';
            $output .= '<span>' . esc_html($title) . '</span>';
            if ($has_children) {
                $output .= kmg_glyph('chevron-down', 'h-4 w-4 opacity-60 transition-transform duration-200 group-hover:rotate-180');
            }
            $output .= '</a>';
            return;
        }

        $product = kmg_menu_product_data($item);
        $output .= '<li class="list-none">';
        $output .= '<a href="' . esc_url($url) . '" class="flex items-center gap-4 rounded-2xl p-3 transition-colors hover:bg-primary-softer">';
        $output .= '<span class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-primary-soft">';
        if ($product && $product['image']) {
            $output .= '<img src="' . esc_url($product['image']) . '" alt="" class="h-full w-full object-contain p-1" loading="lazy" />';
        } else {
            $output .= kmg_glyph('arrow-up-right', 'h-5 w-5 text-primary');
        }
        $output .= '</span><span class="min-w-0 flex-1">';
        $output .= '<span class="flex items-center gap-2"><span class="truncate font-heading text-[15px] font-bold text-ink">' . esc_html($title) . '</span>';
        if ($product && $product['badge'] !== '') {
            $output .= '<span class="shrink-0 rounded-full bg-primary px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">' . esc_html($product['badge']) . '</span>';
        }
        $output .= '</span>';
        if ($product) {
            $output .= '<span class="mt-0.5 flex items-center justify-between gap-2 text-[13px] text-muted-foreground"><span class="truncate">' . esc_html($product['subtitle']) . '</span><span class="shrink-0 font-semibold text-ink">' . wp_kses_post($product['price_html']) . '</span></span>';
        } elseif (trim((string) $item->description) !== '') {
            $output .= '<span class="mt-0.5 block truncate text-[13px] text-muted-foreground">' . esc_html($item->description) . '</span>';
        }
        $output .= '</span></a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

class Waklert_Mobile_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub-menu is-open space-y-1 pb-3 pl-2">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes      = empty($item->classes) ? [] : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes, true);
        $url          = !empty($item->url) ? $item->url : '#';
        $active       = kmg_menu_item_is_active($item);

        if ($depth === 0) {
            $output .= '<li class="' . esc_attr('list-none border-b border-border ' . implode(' ', $classes)) . '">';
            $output .= '<div class="flex items-center justify-between gap-2">';
            $output .= '<a href="' . esc_url($url) . '" class="flex-1 py-4 font-heading text-2xl font-bold tracking-normal ' . ($active ? 'text-primary' : 'text-ink') . '">' . esc_html($item->title) . '</a>';
            if ($has_children) {
                $output .= '<button type="button" class="mobile-submenu-toggle is-active flex h-11 w-11 items-center justify-center rounded-full text-ink hover:bg-primary-soft" aria-expanded="true" aria-label="' . esc_attr($item->title) . '">';
                $output .= kmg_glyph('chevron-down', 'h-5 w-5 transition-transform duration-200');
                $output .= '</button>';
            }
            $output .= '</div>';
            return;
        }

        $product = kmg_menu_product_data($item);
        $output .= '<li class="list-none">';
        $output .= '<a href="' . esc_url($url) . '" class="flex items-center gap-3 rounded-2xl p-2 hover:bg-primary-softer">';
        $output .= '<span class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-primary-soft">';
        if ($product && $product['image']) {
            $output .= '<img src="' . esc_url($product['image']) . '" alt="" class="h-full w-full object-contain p-1" loading="lazy" />';
        } else {
            $output .= kmg_glyph('arrow-up-right', 'h-4 w-4 text-primary');
        }
        $output .= '</span><span class="min-w-0 flex-1"><span class="block truncate text-[15px] font-semibold text-ink">' . esc_html($item->title) . '</span>';
        if ($product && $product['price_html'] !== '') {
            $output .= '<span class="block text-[13px] text-muted-foreground">' . wp_kses_post($product['price_html']) . '</span>';
        }
        $output .= '</span></a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}
