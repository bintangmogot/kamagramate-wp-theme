<?php
/**
 * Site footer. Columns come from the four footer menu locations (heading =
 * menu name without the "Footer " prefix); everything else from Theme Settings.
 */

$opt = function ($name) {
    return function_exists('get_field') ? get_field($name, 'option') : null;
};
$txt = function ($name) use ($opt) {
    return trim((string) $opt($name));
};

$footer_tagline  = $txt('footer_tagline');
$footer_desc     = $txt('footer_description');
$footer_address  = $txt('location_text');
$footer_email    = $txt('support_email');
$contact_heading = $txt('footer_contact_heading');
$working_days    = $txt('working_days');
$office_hours    = $txt('office_hours');
$phone_raw       = $txt('whatsapp_number');
$payments_heading = $txt('footer_payments_heading');
$shipping_heading = $txt('footer_shipping_heading');
$payment_images  = $opt('footer_payment_images');
$shipping_images = $opt('footer_shipping_images');
$copyright_text  = $txt('copyright_text');
$disclaimer      = $txt('disclaimer_en');
$trustpilot_url  = $txt('trustpilot_url');
$trust_chips     = array_filter([$txt('ssl_text'), $txt('dispatch_text'), $txt('checkout_text')]);
$socials         = array_filter([
    'facebook'  => $txt('facebook_link'),
    'instagram' => $txt('instagram_link'),
    'x'         => $txt('twitter_link'),
]);

$footer_menu_locations = get_nav_menu_locations();
$footer_menus = [];
foreach (['footer_trending', 'footer_quick', 'footer_cities', 'footer_info'] as $location) {
    $menu_id = absint($footer_menu_locations[$location] ?? 0);
    $menu    = $menu_id ? wp_get_nav_menu_object($menu_id) : null;
    if (!$menu || empty(wp_get_nav_menu_items($menu_id))) {
        continue;
    }
    $footer_menus[$location] = [
        'id'      => $menu_id,
        'heading' => preg_replace('/^Footer\s+/i', '', $menu->name) ?: $menu->name,
    ];
}

$logo_row = static function ($rows, $class) {
    if (empty($rows) || !is_array($rows)) {
        return;
    }
    foreach ($rows as $row) {
        $img = $row['image'] ?? null;
        $src = is_array($img) ? ($img['url'] ?? '') : (is_numeric($img) ? wp_get_attachment_image_url((int) $img, 'full') : (string) $img);
        if (!$src) {
            continue;
        }
        $alt = $row['alt_text'] ?? (is_array($img) ? ($img['alt'] ?? '') : '');
        printf('<img src="%s" alt="%s" class="%s" loading="lazy" decoding="async">', esc_url($src), esc_attr($alt), esc_attr($class));
    }
};

// On the homepage the Support CTA sits above the footer unless a page module already printed it.
if (is_front_page() && empty($GLOBALS['waklert_support_cta_rendered'])) {
    get_template_part('modules/content', 'support_cta');
}

$pre_footer_enabled = $opt('pre_footer_badges_enabled');
$pre_footer_badges  = $opt('pre_footer_badges');
$badge_icon_map     = [
    'truck'            => 'express_shipping',
    'shield_pharma'    => 'no_customs',
    'shield_insurance' => 'parcel_guarantee',
    'parcel'           => 'discreet_sealed',
];
?>

<?php if (!is_front_page() && !empty($pre_footer_enabled) && !empty($pre_footer_badges) && is_array($pre_footer_badges)) : ?>
<section class="border-t border-border bg-primary-softer">
    <div class="kmg-container grid grid-cols-2 gap-x-6 gap-y-10 py-14 md:grid-cols-4 md:py-16">
        <?php foreach ($pre_footer_badges as $badge) :
            $title = trim((string) ($badge['title'] ?? ''));
            if ($title === '') { continue; }
            $preset = $badge_icon_map[$badge['icon'] ?? 'truck'] ?? 'express_shipping';
            ?>
            <div class="flex flex-col items-center gap-4 text-center md:flex-row md:items-start md:text-left">
                <span class="kmg-icon">
                    <?php echo waklert_render_card_icon([
                        'icon_image'  => ($badge['icon'] ?? '') === 'custom' ? ($badge['custom_icon'] ?? null) : null,
                        'icon_preset' => $preset,
                        'title'       => $title,
                        'size_class'  => 'h-7 w-7',
                    ]); ?>
                </span>
                <span>
                    <span class="block font-heading text-[17px] font-bold text-ink"><?php echo esc_html($title); ?></span>
                    <?php if (!empty($badge['subtitle'])) : ?>
                        <span class="mt-1 block text-[14px] text-muted-foreground"><?php echo esc_html($badge['subtitle']); ?></span>
                    <?php endif; ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<footer class="bg-ink text-white/70">
    <div class="kmg-container pt-20 md:pt-28">

        <div class="grid gap-10 border-b border-white/10 pb-16 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-7">
                <?php waklert_the_logo('h-10 w-auto max-w-[220px] object-contain', 'footer'); ?>
                <?php if ($footer_tagline !== '') : ?>
                    <p class="mt-10 max-w-2xl font-heading text-[clamp(2rem,1.4rem+2.4vw,3.25rem)] font-bold leading-[1.08] tracking-[-0.035em] text-white"><?php echo esc_html($footer_tagline); ?></p>
                <?php endif; ?>
                <?php if ($footer_desc !== '') : ?>
                    <p class="mt-6 max-w-xl text-[16px] leading-relaxed text-white/65"><?php echo esc_html($footer_desc); ?></p>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-5 lg:pt-2">
                <?php if ($contact_heading !== '') : ?>
                    <h2 class="kmg-eyebrow !text-primary-light"><?php echo esc_html($contact_heading); ?></h2>
                <?php endif; ?>
                <ul class="mt-6 space-y-4 text-[16px]">
                    <?php if ($phone_raw !== '') : ?>
                        <li><a href="<?php echo esc_attr(kmg_phone_href($phone_raw)); ?>" class="inline-flex items-center gap-3 font-heading text-2xl font-bold tracking-tight text-white hover:text-primary-light"><?php echo kmg_glyph('phone', 'h-5 w-5 text-primary-light'); ?><?php echo esc_html(kmg_phone_display($phone_raw)); ?></a></li>
                    <?php endif; ?>
                    <?php if ($footer_email !== '') : ?>
                        <li><a href="mailto:<?php echo esc_attr($footer_email); ?>" class="inline-flex items-center gap-3 text-white hover:text-primary-light"><?php echo kmg_glyph('mail', 'h-5 w-5 text-primary-light'); ?><?php echo esc_html($footer_email); ?></a></li>
                    <?php endif; ?>
                    <?php if ($footer_address !== '') : ?>
                        <li class="flex items-start gap-3"><?php echo waklert_render_card_icon(['icon_preset' => 'australia_delivery', 'size_class' => 'mt-0.5 h-5 w-5 shrink-0 text-primary-light']); ?><span><?php echo esc_html($footer_address); ?></span></li>
                    <?php endif; ?>
                    <?php foreach (array_filter([$working_days, $office_hours]) as $hours) : ?>
                        <li class="flex items-start gap-3"><?php echo waklert_render_card_icon(['icon_preset' => 'clock', 'size_class' => 'mt-0.5 h-5 w-5 shrink-0 text-primary-light']); ?><span><?php echo esc_html($hours); ?></span></li>
                    <?php endforeach; ?>
                </ul>
                <?php if ($trust_chips) : ?>
                    <div class="mt-8 flex flex-wrap gap-2">
                        <?php foreach ($trust_chips as $chip) : ?>
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 px-3.5 py-1.5 text-[13px] text-white/80"><?php echo kmg_glyph('check', 'h-3.5 w-3.5 text-primary-light'); ?><?php echo esc_html($chip); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($footer_menus) : ?>
        <div class="grid grid-cols-2 gap-x-6 gap-y-12 border-b border-white/10 py-16 md:grid-cols-4">
            <?php foreach ($footer_menus as $menu) : ?>
                <nav aria-label="<?php echo esc_attr($menu['heading']); ?>">
                    <h2 class="text-[15px] font-semibold text-white"><?php echo esc_html($menu['heading']); ?></h2>
                    <?php wp_nav_menu([
                        'menu'        => $menu['id'],
                        'container'   => false,
                        'depth'       => 1,
                        'menu_class'  => 'mt-5 space-y-3.5 text-[15px] [&_a]:transition-colors [&_a:hover]:text-white',
                        'fallback_cb' => false,
                    ]); ?>
                </nav>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($payment_images) || !empty($shipping_images) || $trustpilot_url !== '') : ?>
        <div class="flex flex-col gap-10 border-b border-white/10 py-12 md:flex-row md:items-end md:justify-between">
            <div class="flex flex-wrap gap-10">
                <?php if (!empty($payment_images)) : ?>
                <div>
                    <?php if ($payments_heading !== '') : ?><p class="mb-4 text-[13px] font-semibold uppercase tracking-[0.14em] text-white/50"><?php echo esc_html($payments_heading); ?></p><?php endif; ?>
                    <div class="flex flex-wrap items-center gap-3"><?php $logo_row($payment_images, 'footer-payment-logo h-11 w-auto rounded-lg bg-white px-3 py-2 object-contain'); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($shipping_images)) : ?>
                <div>
                    <?php if ($shipping_heading !== '') : ?><p class="mb-4 text-[13px] font-semibold uppercase tracking-[0.14em] text-white/50"><?php echo esc_html($shipping_heading); ?></p><?php endif; ?>
                    <div class="flex flex-wrap items-center gap-3"><?php $logo_row($shipping_images, 'footer-shipping-logo h-11 w-auto rounded-lg bg-white px-3 py-2 object-contain'); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($trustpilot_url !== '') : ?>
                <a href="<?php echo esc_url($trustpilot_url); ?>" target="_blank" rel="noopener" class="inline-flex opacity-90 transition-opacity hover:opacity-100">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/trustpilot-white.svg'); ?>" alt="Trustpilot" width="100" height="25" class="h-auto w-[100px]">
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($disclaimer !== '') : ?>
            <p class="max-w-4xl py-10 text-[13px] leading-relaxed text-white/50"><?php echo esc_html($disclaimer); ?></p>
        <?php endif; ?>

        <div class="flex flex-col gap-6 border-t border-white/10 py-8 text-[13px] text-white/50 sm:flex-row sm:items-center sm:justify-between">
            <p><?php echo $copyright_text !== '' ? wp_kses_post($copyright_text) : '&copy; ' . esc_html(date('Y') . ' ' . get_bloginfo('name')); ?></p>
            <?php if ($socials) : ?>
            <ul class="flex items-center gap-2">
                <?php foreach ($socials as $network => $url) : ?>
                    <li><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr(ucfirst($network)); ?>" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 hover:border-white hover:text-white">
                        <?php if ($network === 'facebook') : ?><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 8.5V6.8c0-.8.5-1 .9-1h2.4V2.1L14 2c-3.6 0-4.4 2.7-4.4 4.4v2.1H7.5v3.8h2.1V22H14v-9.7h2.9l.4-3.8H14Z"/></svg>
                        <?php elseif ($network === 'instagram') : ?><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8" fill="currentColor"/></svg>
                        <?php else : ?><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.8 3h3.1l-6.8 7.7L22 21h-6.2l-4.9-6.4L5.3 21H2.2l7.3-8.3L2 3h6.4l4.4 5.8L17.8 3Zm-1.1 16.2h1.7L7.4 4.7H5.5l11.2 14.5Z"/></svg><?php endif; ?>
                    </a></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php
$float_on  = $opt('floating_enabled');
$float_wa  = kmg_chat_url($txt('floating_whatsapp_number'));
$float_tg  = kmg_chat_url($txt('floating_telegram_number'), 'telegram');
$float_wal = $txt('floating_whatsapp_label');
$float_tgl = $txt('floating_telegram_label');
if (!empty($float_on) && (($float_wa && $float_wal) || ($float_tg && $float_tgl))) : ?>
<div class="kmg-float fixed bottom-24 right-4 z-[9990] flex flex-col items-end gap-2 sm:bottom-6 sm:right-24">
    <?php if ($float_wa && $float_wal) : ?>
        <a href="<?php echo esc_url($float_wa); ?>" target="_blank" rel="noopener" class="inline-flex h-12 items-center gap-2.5 rounded-full bg-ink px-5 text-[15px] font-semibold text-white shadow-[0_16px_32px_-12px_rgb(26_16_48/0.5)] transition-transform hover:-translate-y-0.5"><?php echo kmg_glyph('chat', 'h-[18px] w-[18px]'); ?><span><?php echo esc_html($float_wal); ?></span></a>
    <?php endif; ?>
    <?php if ($float_tg && $float_tgl) : ?>
        <a href="<?php echo esc_url($float_tg); ?>" target="_blank" rel="noopener" class="inline-flex h-12 items-center gap-2.5 rounded-full bg-white px-5 text-[15px] font-semibold text-ink shadow-[0_16px_32px_-12px_rgb(26_16_48/0.35)] ring-1 ring-border transition-transform hover:-translate-y-0.5"><?php echo kmg_glyph('send', 'h-[18px] w-[18px]'); ?><span><?php echo esc_html($float_tgl); ?></span></a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php
$toast_on    = $opt('promo_toast_enabled');
$toast_title = $txt('promo_toast_title');
$toast_code  = $txt('promo_toast_code');
if (!empty($toast_on) && $toast_title !== '' && $toast_code !== '') : ?>
<div id="kmg-promo-toast" class="fixed bottom-4 left-4 z-[9991] hidden w-[calc(100%-2rem)] max-w-sm rounded-3xl bg-white p-6 shadow-[0_32px_64px_-24px_rgb(26_16_48/0.4)] ring-1 ring-border" role="dialog" aria-labelledby="kmg-promo-title" data-copied="<?php echo esc_attr($txt('promo_toast_copied')); ?>">
    <button type="button" data-kmg-toast-close class="absolute right-3 top-3 inline-flex h-9 w-9 items-center justify-center rounded-full text-muted-foreground hover:bg-muted" aria-label="<?php echo esc_attr__('Close', 'kamagramate'); ?>"><?php echo kmg_glyph('close', 'h-4 w-4'); ?></button>
    <p id="kmg-promo-title" class="pr-8 font-heading text-xl font-bold text-ink"><?php echo esc_html($toast_title); ?></p>
    <?php if ($txt('promo_toast_text') !== '') : ?><p class="mt-2 text-[15px] leading-relaxed text-muted-foreground"><?php echo esc_html($txt('promo_toast_text')); ?></p><?php endif; ?>
    <button type="button" data-kmg-copy="<?php echo esc_attr($toast_code); ?>" class="kmg-btn kmg-btn-primary kmg-btn-sm mt-5 w-full">
        <?php echo kmg_glyph('copy', 'h-4 w-4'); ?><span data-kmg-copy-label><?php echo esc_html($txt('promo_toast_button') ?: $toast_code); ?></span>
    </button>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
