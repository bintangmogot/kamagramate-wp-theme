<?php
/**
 * Kamagramate UI helpers.
 *
 * Presentation-only building blocks shared by modules and templates. They never
 * contain copy: every string they print is passed in from ACF fields.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Inline UI glyphs (arrows, ticks, chrome). Content icons live in icon-library.php.
 */
function kmg_glyph(string $name, string $class = 'h-5 w-5'): string {
    $paths = [
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'arrow-left'  => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
        'arrow-up-right' => '<path d="M7 17 17 7M8 7h9v9"/>',
        'check'       => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'plus'        => '<path d="M12 5v14M5 12h14"/>',
        'minus'       => '<path d="M5 12h14"/>',
        'close'       => '<path d="M6 6l12 12M18 6 6 18"/>',
        'menu'        => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'search'      => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'phone'       => '<path d="M21 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 5.2 2 2 0 0 1 4.1 3h3a2 2 0 0 1 2 1.7c.1.9.4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 10.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.8.7a2 2 0 0 1 1.7 2Z"/>',
        'mail'        => '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 6.5 8.5 6.5 8.5-6.5"/>',
        'chat'        => '<path d="M21 12a8 8 0 0 1-11.8 7L4 20l1.1-4.6A8 8 0 1 1 21 12Z"/>',
        'send'        => '<path d="m21 3-9.5 9.5M21 3l-6.5 18-3-8.5L3 9.5 21 3Z"/>',
        'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
        'chevron-right' => '<path d="m9 6 6 6-6 6"/>',
        'copy'        => '<rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"/>',
        'tag'         => '<path d="M20.5 13.5 13.5 20.5a2 2 0 0 1-2.8 0l-7.2-7.2V4.5h8.8l7.2 7.2a2 2 0 0 1 0 2.8Z"/><circle cx="8" cy="8" r="1.3"/>',
        'external'    => '<path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
        'quote'       => '<path d="M9 7H5v6h4v4H5M19 7h-4v6h4v4h-4"/>',
    ];

    if (!isset($paths[$name])) {
        return '';
    }

    return sprintf(
        '<svg class="%s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>',
        esc_attr($class),
        $paths[$name]
    );
}

/**
 * Filled star row for ratings (1-5).
 */
function kmg_stars(int $rating = 5, string $class = 'h-4 w-4'): string {
    $rating = max(0, min(5, $rating));
    $out = '<span class="kmg-stars" role="img" aria-label="' . esc_attr(sprintf('%d out of 5', $rating)) . '">';
    for ($i = 1; $i <= 5; $i++) {
        $fill = $i <= $rating ? 'currentColor' : 'none';
        $out .= '<svg class="' . esc_attr($class) . '" viewBox="0 0 24 24" fill="' . $fill . '" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="m12 3.5 2.6 5.3 5.9.8-4.3 4.1 1 5.8L12 16.8l-5.2 2.7 1-5.8-4.3-4.1 5.9-.8L12 3.5Z"/></svg>';
    }
    return $out . '</span>';
}

/**
 * Section header: eyebrow, heading, lead. Prints nothing when all are empty.
 *
 * @param array $args {
 *   @type string $eyebrow
 *   @type string $title
 *   @type string $lead
 *   @type string $align   'center' (default) or 'left'
 *   @type string $tag     Heading tag, default h2
 *   @type string $size    'h2' (default) or 'display'
 *   @type bool   $dark    Heading on a dark band
 *   @type string $class   Extra wrapper classes
 * }
 */
function kmg_section_head(array $args): void {
    $eyebrow = trim((string) ($args['eyebrow'] ?? ''));
    $title   = trim((string) ($args['title'] ?? ''));
    $lead    = trim((string) ($args['lead'] ?? ''));

    if ($eyebrow === '' && $title === '' && $lead === '') {
        return;
    }

    $align   = ($args['align'] ?? 'center') === 'left' ? '' : ' kmg-head-center';
    $tag     = in_array($args['tag'] ?? 'h2', ['h1', 'h2', 'h3'], true) ? ($args['tag'] ?? 'h2') : 'h2';
    $size    = ($args['size'] ?? 'h2') === 'display' ? 'kmg-display' : 'kmg-h2';
    $dark    = !empty($args['dark']) ? ' text-white' : '';
    $class   = trim((string) ($args['class'] ?? ''));
    ?>
    <header class="kmg-head<?php echo $align; ?><?php echo $class !== '' ? ' ' . esc_attr($class) : ''; ?>">
        <?php if ($eyebrow !== '') : ?>
            <p class="kmg-eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <?php endif; ?>
        <?php if ($title !== '') : ?>
            <<?php echo $tag; ?> class="<?php echo esc_attr($size . $dark); ?>"><?php echo esc_html($title); ?></<?php echo $tag; ?>>
        <?php endif; ?>
        <?php if ($lead !== '') : ?>
            <p class="kmg-lead"><?php echo nl2br(esc_html($lead)); ?></p>
        <?php endif; ?>
    </header>
    <?php
}

/**
 * Pill button. Prints nothing without both label and URL.
 */
function kmg_button(string $label, string $url, string $variant = 'primary', string $extra = '', bool $arrow = true): void {
    $label = trim($label);
    $url   = trim($url);
    if ($label === '' || $url === '') {
        return;
    }
    $external = preg_match('#^https?://#i', $url) && strpos($url, home_url()) !== 0;
    printf(
        '<a href="%s" class="kmg-btn kmg-btn-%s%s"%s>%s%s</a>',
        esc_url($url),
        esc_attr($variant),
        $extra !== '' ? ' ' . esc_attr($extra) : '',
        $external ? ' target="_blank" rel="noopener"' : '',
        esc_html($label),
        $arrow ? ($variant === 'primary'
            ? '<span class="-mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-white text-primary">' . kmg_glyph('arrow-right', 'h-4 w-4') . '</span>'
            : kmg_glyph('arrow-right')) : ''
    );
}

/**
 * Normalise an ACF image value (array, ID or URL) to an attachment ID or URL.
 */
function kmg_image($image, string $size = 'large', array $attr = []): string {
    if (empty($image)) {
        return '';
    }
    $id = is_array($image) ? (int) ($image['ID'] ?? $image['id'] ?? 0) : (is_numeric($image) ? (int) $image : 0);
    if ($id > 0) {
        return wp_get_attachment_image($id, $size, false, $attr);
    }
    $url = is_array($image) ? ($image['url'] ?? '') : (string) $image;
    if ($url === '') {
        return '';
    }
    $attr_html = '';
    foreach ($attr as $key => $value) {
        $attr_html .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
    }
    return sprintf('<img src="%s"%s>', esc_url($url), $attr_html);
}

/**
 * Australian phone helpers shared by header, footer and contact modules.
 */
function kmg_phone_display(string $raw): string {
    $digits = preg_replace('/\D+/', '', $raw);
    if (str_starts_with($digits, '61') && strlen($digits) >= 11) {
        $digits = '0' . substr($digits, 2);
    }
    if (strlen($digits) === 10 && str_starts_with($digits, '0')) {
        return substr($digits, 0, 4) . ' ' . substr($digits, 4, 3) . ' ' . substr($digits, 7, 3);
    }
    return trim($raw);
}

function kmg_phone_href(string $raw): string {
    $digits = preg_replace('/\D+/', '', $raw);
    return $digits === '' ? '' : 'tel:' . $digits;
}

/**
 * Build a wa.me / t.me link from a number. Returns '' when no number is set.
 */
function kmg_chat_url(string $raw, string $network = 'whatsapp'): string {
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $raw)) {
        return $raw;
    }
    $digits = preg_replace('/\D+/', '', $raw);
    if (str_starts_with($digits, '0')) {
        $digits = '61' . substr($digits, 1);
    }
    return $network === 'telegram' ? 'https://t.me/+' . $digits : 'https://wa.me/' . $digits;
}
