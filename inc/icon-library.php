<?php
/**
 * Kamagramate - line icon library and smart resolver
 *
 * Provides 100% editable + semantically aligned colorful icons across all ACF section modules:
 * - why_choose_us (Audiences / Benefits)
 * - trust_strip (Trust Badges)
 * - how_to_order (Step Cards)
 * - about_delivery (Delivery Feature Cards)
 * - about_mission (Mission & Vision Cards)
 * - track_order (Trust Benefit Cards)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Return the list of human-readable icon preset choices for ACF Select fields.
 */
function waklert_get_icon_preset_choices(): array {
    return [
        'auto'               => 'Auto-match from title and description (recommended)',
        'parcel_guarantee'   => 'Parcel guarantee / reship or refund',
        'live_chat_support'  => 'Real people on chat / support',
        'medical_rx_clarity' => 'Medicine information / Schedule 4 clarity',
        'aud_checkout'       => 'Simple AUD checkout / bank transfer',
        'express_shipping'   => 'Fast domestic shipping',
        'australia_delivery' => 'Australia-wide tracked delivery',
        'browse_catalogue'   => 'Browse the range / product catalogue',
        'listing_details'    => 'Clear listing details / compare strengths',
        'returning_loyalty'  => 'Returning customers / loyalty',
        'discreet_sealed'    => 'Discreet plain packaging',
        'po_box_delivery'    => 'PO Box and parcel locker delivery',
        'no_customs'         => 'Smooth domestic dispatch',
        'affordable_value'   => 'Affordable direct pricing',
        'secure_lock'        => 'Secure and encrypted',
        'clock'              => 'Hours / fast response',
        'medicine'           => 'Genuine medicine',
        'heart'              => 'Care / wellbeing',
        'person'             => 'Person / audience',
        'mail'               => 'Email',
        'phone'              => 'Phone',
    ];
}

/**
 * Return the single-colour line icon library keyed by preset slug.
 * Icons inherit currentColor so each module controls the tint.
 */
function waklert_get_colorful_icon_svgs(): array {
    return [
        'parcel_guarantee' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M21 8.5 12 4 3 8.5v7L12 20l9-4.5v-7Z"/><path d="m3 8.5 9 4.5 9-4.5M12 13v7"/><path d="m15.5 15.5 1.5 1.5 3-3"/></svg>',
        'live_chat_support' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M21 12a8 8 0 0 1-11.8 7L4 20l1.1-4.6A8 8 0 1 1 21 12Z"/><path d="M8.5 12h.01M12 12h.01M15.5 12h.01"/></svg>',
        'medical_rx_clarity' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 10h6M12 7v6M9 16h6"/></svg>',
        'aud_checkout' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="5.5" width="18" height="13" rx="2.5"/><path d="M3 10h18M7 15h3"/></svg>',
        'express_shipping' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M3 6h11v10H3zM14 9.5h4l3 3.5v3h-7"/><circle cx="7" cy="17.5" r="1.8"/><circle cx="17.5" cy="17.5" r="1.8"/></svg>',
        'australia_delivery' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>',
        'browse_catalogue' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>',
        'listing_details' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m20 20-4.8-4.8M8 10.5h5"/></svg>',
        'returning_loyalty' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="m12 3.5 2.6 5.3 5.9.8-4.3 4.1 1 5.8L12 16.8l-5.2 2.7 1-5.8-4.3-4.1 5.9-.8L12 3.5Z"/></svg>',
        'discreet_sealed' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M3 7.5 12 3l9 4.5v9L12 21l-9-4.5v-9Z"/><path d="m3 7.5 9 4.5 9-4.5M12 12v9"/></svg>',
        'po_box_delivery' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M4 9h16v11H4z"/><path d="M4 9 7 4h10l3 5M10 13h4"/></svg>',
        'no_customs' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 2.7 2.7L16.5 9.5"/></svg>',
        'affordable_value' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M20.5 13.5 13.5 20.5a2 2 0 0 1-2.8 0l-7.2-7.2V4.5h8.8l7.2 7.2a2 2 0 0 1 0 2.8Z"/><circle cx="8" cy="8" r="1.3"/></svg>',
        'secure_lock' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><rect x="4.5" y="10.5" width="15" height="10" rx="2"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3M12 14.5v2"/></svg>',
        'clock' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>',
        'medicine' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="8.5" width="18" height="7" rx="3.5" transform="rotate(-45 12 12)"/><path d="m9.5 9.5 5 5"/></svg>',
        'heart' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 20s-7.5-4.6-7.5-10.2A4.3 4.3 0 0 1 12 7.2a4.3 4.3 0 0 1 7.5 2.6C19.5 15.4 12 20 12 20Z"/></svg>',
        'person' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="4"/><path d="M4.5 20.5a7.5 7.5 0 0 1 15 0"/></svg>',
        'mail' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 6.5 8.5 6.5 8.5-6.5"/></svg>',
        'phone' => '<svg viewBox="0 0 24 24" class="h-11 w-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M21 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 5.2 2 2 0 0 1 4.1 3h3a2 2 0 0 1 2 1.7c.1.9.4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 10.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.8.7a2 2 0 0 1 1.7 2Z"/></svg>',
    ];
}

/**
 * Determine the best matching icon preset slug based on a card's title and description.
 */
function waklert_detect_icon_preset_from_text(string $title = '', string $desc = '', string $default_preset = 'parcel_guarantee'): string {
    $t = strtolower(trim($title));
    $haystack = strtolower(trim($title . ' ' . $desc));
    if ($haystack === '') {
        return $default_preset;
    }

    // --- PHASE 1: High-priority matches on the CARD TITLE ($t) ---
    if ($t !== '') {
        if (str_contains($t, "doesn't land") || str_contains($t, "doesnt land") || str_contains($t, 'doesn’t land') || str_contains($t, 'fix it') || str_contains($t, 'reship') || str_contains($t, 'refund') || str_contains($t, 'guarantee')) {
            return 'parcel_guarantee';
        }
        if (str_contains($t, 'real people') || str_contains($t, 'on chat') || str_contains($t, 'support') || str_contains($t, 'question') || str_contains($t, 'whatsapp') || str_contains($t, 'sms')) {
            return 'live_chat_support';
        }
        if (str_contains($t, 'straight up') || str_contains($t, 'candy coating') || str_contains($t, 'schedule 4') || str_contains($t, 'prescription') || str_contains($t, 'clear boundaries') || str_contains($t, 'information first')) {
            return 'medical_rx_clarity';
        }
        if (str_contains($t, 'checkout') || str_contains($t, 'payid') || str_contains($t, 'payment') || str_contains($t, 'confirm your order')) {
            if (str_contains($t, 'delivery')) {
                return 'express_shipping';
            }
            return 'aud_checkout';
        }
        if (str_contains($t, 'returning') || str_contains($t, 'repeat') || str_contains($t, 'loyalty')) {
            return 'returning_loyalty';
        }
        if (str_contains($t, 'sealed')) {
            return 'browse_catalogue';
        }
        if (str_contains($t, 'discreet')) {
            return 'discreet_sealed';
        }
        if (str_contains($t, 'po box') || str_contains($t, 'parcel locker')) {
            return 'po_box_delivery';
        }
        if (str_contains($t, 'no customs') || str_contains($t, 'customs')) {
            return 'no_customs';
        }
        if (str_contains($t, 'affordable') || str_contains($t, 'price') || str_contains($t, 'value')) {
            return 'affordable_value';
        }
        if (str_contains($t, 'browse') || str_contains($t, 'review the range') || str_contains($t, 'focused range') || str_contains($t, 'focused product') || str_contains($t, 'our approach')) {
            return 'browse_catalogue';
        }
        if (str_contains($t, 'compare') || str_contains($t, 'listing detail') || str_contains($t, 'by listing') || str_contains($t, 'new customer')) {
            return 'listing_details';
        }
        if (str_contains($t, 'australia-wide') || str_contains($t, 'destination')) {
            return 'australia_delivery';
        }
        if (str_contains($t, 'track') || str_contains($t, 'shipping') || str_contains($t, 'delivery') || str_contains($t, 'dispatch')) {
            return 'express_shipping';
        }
    }

    // --- PHASE 2: Fallback matches on combined Title + Description ($haystack) ---
    if (
        str_contains($haystack, "doesn't land") ||
        str_contains($haystack, "doesnt land") ||
        str_contains($haystack, 'doesn’t land') ||
        str_contains($haystack, 'we fix it') ||
        str_contains($haystack, 'reship') ||
        str_contains($haystack, 'refund') ||
        str_contains($haystack, 'guarantee') ||
        str_contains($haystack, 'stalls') ||
        str_contains($haystack, 'goes missing')
    ) {
        return 'parcel_guarantee';
    }

    if (
        str_contains($haystack, 'real people') ||
        str_contains($haystack, 'on chat') ||
        str_contains($haystack, 'whatsapp') ||
        str_contains($haystack, 'sms') ||
        str_contains($haystack, '7 days a week') ||
        str_contains($haystack, '24/7')
    ) {
        return 'live_chat_support';
    }

    if (
        str_contains($haystack, 'straight up') ||
        str_contains($haystack, 'candy coating') ||
        str_contains($haystack, 'schedule 4') ||
        str_contains($haystack, 'prescription') ||
        str_contains($haystack, 'doctor') ||
        str_contains($haystack, 'pharmacist') ||
        str_contains($haystack, 'medical advice') ||
        str_contains($haystack, 'clear boundaries')
    ) {
        return 'medical_rx_clarity';
    }

    if (
        str_contains($haystack, 'aud checkout') ||
        str_contains($haystack, 'payid') ||
        str_contains($haystack, 'bank transfer') ||
        str_contains($haystack, 'secure checkout') ||
        str_contains($haystack, 'encrypted') ||
        str_contains($haystack, 'payment') ||
        str_contains($haystack, 'confirm your order') ||
        str_contains($haystack, 'pay securely')
    ) {
        return 'aud_checkout';
    }

    if (
        str_contains($haystack, 'returning customer') ||
        str_contains($haystack, 'repeat') ||
        str_contains($haystack, 'familiar with the product')
    ) {
        return 'returning_loyalty';
    }

    if (
        str_contains($haystack, 'discreet') ||
        str_contains($haystack, 'sealed') ||
        str_contains($haystack, 'unmarked') ||
        str_contains($haystack, 'plain pack') ||
        str_contains($haystack, 'blister pack')
    ) {
        return 'discreet_sealed';
    }

    if (
        str_contains($haystack, 'po box') ||
        str_contains($haystack, 'parcel locker') ||
        str_contains($haystack, 'mailbox')
    ) {
        return 'po_box_delivery';
    }

    if (
        str_contains($haystack, 'no customs') ||
        str_contains($haystack, 'customs')
    ) {
        return 'no_customs';
    }

    if (
        str_contains($haystack, 'affordable') ||
        str_contains($haystack, 'lowest price') ||
        str_contains($haystack, 'best value')
    ) {
        return 'affordable_value';
    }

    if (
        str_contains($haystack, 'listing detail') ||
        str_contains($haystack, 'compare') ||
        str_contains($haystack, 'by listing') ||
        str_contains($haystack, 'new customer') ||
        str_contains($haystack, 'stated strength')
    ) {
        return 'listing_details';
    }

    if (
        str_contains($haystack, 'focused range') ||
        str_contains($haystack, 'focused product') ||
        str_contains($haystack, 'browse') ||
        str_contains($haystack, 'review the range') ||
        str_contains($haystack, 'catalogue')
    ) {
        return 'browse_catalogue';
    }

    if (
        str_contains($haystack, 'australia-wide') ||
        str_contains($haystack, 'australian states') ||
        str_contains($haystack, 'metro and regional') ||
        str_contains($haystack, 'territories')
    ) {
        return 'australia_delivery';
    }

    if (
        str_contains($haystack, 'shipping') ||
        str_contains($haystack, 'delivery') ||
        str_contains($haystack, 'dispatch') ||
        str_contains($haystack, 'australia post') ||
        str_contains($haystack, 'track')
    ) {
        return 'express_shipping';
    }

    return $default_preset;
}

/**
 * Render an icon for any ACF card/badge/step row.
 *
 * Priority order:
 * 1. Custom uploaded image (`icon_image` array/URL/ID) -> renders `<img>`
 * 2. Explicit `icon_preset` selected in ACF (when not empty and not 'auto') -> renders preset SVG
 * 3. Custom colorful `<svg>` pasted in `icon_svg` (ignoring legacy monochrome `stroke="currentColor"` wireframes) -> renders SVG
 * 4. Smart semantic detection from `$title` + `$desc` -> renders matching colorful preset SVG
 *
 * @param array  $args {
 *   @type mixed  $icon_image      ACF image array, attachment ID, or URL.
 *   @type string $icon_preset     ACF select value (e.g. 'auto', 'parcel_guarantee', etc.).
 *   @type string $icon_svg        ACF textarea raw SVG markup.
 *   @type string $title           Card title or label for semantic matching and alt text.
 *   @type string $desc            Card description for semantic matching.
 *   @type string $default_preset  Fallback preset key if text is empty.
 *   @type string $size_class      Tailwind size classes for the SVG/img (default: 'h-11 w-11').
 * }
 * @return string HTML markup for the icon.
 */
function waklert_render_card_icon(array $args = []): string {
    $icon_image     = $args['icon_image'] ?? null;
    $icon_preset    = isset($args['icon_preset']) ? trim((string) $args['icon_preset']) : 'auto';
    $icon_svg       = isset($args['icon_svg']) ? trim((string) $args['icon_svg']) : '';
    $title          = isset($args['title']) ? trim((string) $args['title']) : '';
    $desc           = isset($args['desc']) ? trim((string) $args['desc']) : '';
    $default_preset = isset($args['default_preset']) ? trim((string) $args['default_preset']) : 'parcel_guarantee';
    $size_class     = isset($args['size_class']) ? trim((string) $args['size_class']) : 'h-11 w-11';

    // 1. Custom Uploaded Image (PNG / SVG / WebP from Flaticon, SVGRepo, etc.)
    $img_url = '';
    $img_alt = $title !== '' ? $title : 'Section Icon';
    if (is_array($icon_image) && !empty($icon_image['url'])) {
        $img_url = (string) $icon_image['url'];
        if (!empty($icon_image['alt'])) {
            $img_alt = (string) $icon_image['alt'];
        }
    } elseif (is_numeric($icon_image) && (int) $icon_image > 0) {
        $resolved = wp_get_attachment_image_url((int) $icon_image, 'full');
        if ($resolved) {
            $img_url = $resolved;
        }
    } elseif (is_string($icon_image) && filter_var($icon_image, FILTER_VALIDATE_URL)) {
        $img_url = $icon_image;
    }

    if ($img_url !== '') {
        return sprintf(
            '<img src="%s" alt="%s" class="%s object-contain select-none" loading="lazy" decoding="async" />',
            esc_url($img_url),
            esc_attr($img_alt),
            esc_attr($size_class)
        );
    }

    $library = waklert_get_colorful_icon_svgs();

    // 2. Explicit Preset Chosen in WP Admin (not 'auto')
    if ($icon_preset !== '' && $icon_preset !== 'auto' && isset($library[$icon_preset])) {
        return str_replace('class="h-11 w-11"', 'class="' . esc_attr($size_class) . '"', $library[$icon_preset]);
    }

    // 3. Custom Colorful SVG Code Pasted in `icon_svg` (ignore legacy monochrome wireframe strokes)
    if (
        $icon_svg !== '' &&
        stripos($icon_svg, '<svg') !== false
    ) {
        return $icon_svg;
    }

    // 4. Smart Semantic Match from Title + Description
    $matched_key = waklert_detect_icon_preset_from_text($title, $desc, $default_preset);
    $svg = $library[$matched_key] ?? $library['parcel_guarantee'];

    return str_replace('class="h-11 w-11"', 'class="' . esc_attr($size_class) . '"', $svg);
}
