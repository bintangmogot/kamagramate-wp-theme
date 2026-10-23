<?php
/**
 * Module: contact
 * Fields: eyebrow, heading, description, whatsapp_title/number/url/subtext, email_title,
 * contact_email, email_subtext, hours_title, hours_mon_fri, hours_sat, hours_sun,
 * address_title, address_line_1, address_city_state_zip, address_country,
 * directions_url, google_maps_url, map_button_text, form_eyebrow, form_heading,
 * form_description, form_shortcode.
 */

$heading = trim((string) get_sub_field('heading')) ?: get_the_title();
$f = static function ($name) {
    return trim((string) get_sub_field($name));
};

$wa_url = kmg_chat_url($f('whatsapp_url') ?: $f('whatsapp_number'));
$email  = $f('contact_email');
$hours  = array_filter([$f('hours_mon_fri'), $f('hours_sat'), $f('hours_sun')]);
$address_lines = array_filter([$f('address_line_1'), $f('address_city_state_zip'), $f('address_country')]);

// Accept either an embed URL or a pasted <iframe> for the map.
$map_embed = $f('google_maps_url');
if (preg_match('/src=["\']([^"\']+)["\']/', $map_embed, $match)) {
    $map_embed = $match[1];
}
if (strpos($map_embed, '/maps/embed') === false) {
    $map_embed = '';
}
$directions = $f('directions_url') ?: ($address_lines ? 'https://maps.google.com/?q=' . rawurlencode(implode(', ', $address_lines)) : '');

$channels = [];
if ($f('whatsapp_title') !== '' && $wa_url !== '') {
    $channels[] = ['icon' => 'chat', 'title' => $f('whatsapp_title'), 'value' => $f('whatsapp_number'), 'sub' => $f('whatsapp_subtext'), 'url' => $wa_url, 'external' => true];
}
if ($f('email_title') !== '' && $email !== '') {
    $channels[] = ['icon' => 'mail', 'title' => $f('email_title'), 'value' => $email, 'sub' => $f('email_subtext'), 'url' => 'mailto:' . $email, 'external' => false];
}
?>
<section class="border-b border-border bg-primary-softer" data-testid="contact">
    <div class="kmg-container pb-16 pt-12 md:pb-20 md:pt-16">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <div class="mt-8 max-w-4xl">
            <?php if ($f('eyebrow') !== '') : ?><p class="kmg-eyebrow"><?php echo esc_html($f('eyebrow')); ?></p><?php endif; ?>
            <h1 class="kmg-display mt-4"><?php echo esc_html($heading); ?></h1>
            <?php if ($f('description') !== '') : ?><p class="kmg-lead mt-6 max-w-2xl"><?php echo esc_html($f('description')); ?></p><?php endif; ?>
        </div>
    </div>
</section>

<section class="kmg-section">
    <div class="kmg-container grid gap-14 lg:grid-cols-12 lg:gap-20">
        <div class="space-y-10 lg:col-span-5">
            <?php foreach ($channels as $c) : ?>
                <a href="<?php echo esc_url($c['url'], ['http', 'https', 'mailto']); ?>" class="group flex gap-5"<?php echo $c['external'] ? ' target="_blank" rel="noopener"' : ''; ?>>
                    <span class="kmg-icon"><?php echo kmg_glyph($c['icon'], 'h-6 w-6'); ?></span>
                    <span>
                        <span class="block text-[14px] font-semibold uppercase tracking-[0.12em] text-muted-foreground"><?php echo esc_html($c['title']); ?></span>
                        <span class="mt-1 block font-heading text-[1.5rem] font-bold tracking-tight text-ink group-hover:text-primary"><?php echo esc_html($c['value']); ?></span>
                        <?php if ($c['sub'] !== '') : ?><span class="mt-1 block text-[15px] text-muted-foreground"><?php echo esc_html($c['sub']); ?></span><?php endif; ?>
                    </span>
                </a>
            <?php endforeach; ?>

            <?php if ($hours) : ?>
                <div class="flex gap-5">
                    <span class="kmg-icon"><?php echo waklert_render_card_icon(['icon_preset' => 'clock', 'size_class' => 'h-6 w-6']); ?></span>
                    <div>
                        <?php if ($f('hours_title') !== '') : ?><p class="text-[14px] font-semibold uppercase tracking-[0.12em] text-muted-foreground"><?php echo esc_html($f('hours_title')); ?></p><?php endif; ?>
                        <ul class="mt-2 space-y-1 text-[17px] text-ink"><?php foreach ($hours as $line) : ?><li><?php echo esc_html($line); ?></li><?php endforeach; ?></ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($address_lines) : ?>
                <div class="flex gap-5">
                    <span class="kmg-icon"><?php echo waklert_render_card_icon(['icon_preset' => 'australia_delivery', 'size_class' => 'h-6 w-6']); ?></span>
                    <div>
                        <?php if ($f('address_title') !== '') : ?><p class="text-[14px] font-semibold uppercase tracking-[0.12em] text-muted-foreground"><?php echo esc_html($f('address_title')); ?></p><?php endif; ?>
                        <address class="mt-2 text-[17px] not-italic text-ink"><?php echo implode('<br>', array_map('esc_html', $address_lines)); ?></address>
                        <?php if ($directions !== '' && $f('map_button_text') !== '') : ?>
                            <a href="<?php echo esc_url($directions); ?>" target="_blank" rel="noopener" class="kmg-link mt-3"><?php echo esc_html($f('map_button_text')); ?><?php echo kmg_glyph('arrow-up-right'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($map_embed !== '') : ?>
                <div class="aspect-[4/3] overflow-hidden rounded-[1.75rem] bg-muted">
                    <iframe src="<?php echo esc_url($map_embed); ?>" class="h-full w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php echo esc_attr($f('address_title')); ?>"></iframe>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($f('form_shortcode') !== '') : ?>
        <div class="lg:col-span-7">
            <div class="rounded-[2rem] bg-primary-softer p-8 md:p-12">
                <?php if ($f('form_eyebrow') !== '') : ?><p class="kmg-eyebrow"><?php echo esc_html($f('form_eyebrow')); ?></p><?php endif; ?>
                <?php if ($f('form_heading') !== '') : ?><h2 class="kmg-h2 mt-3 !text-[clamp(2rem,1.6rem+1.4vw,2.75rem)]"><?php echo esc_html($f('form_heading')); ?></h2><?php endif; ?>
                <?php if ($f('form_description') !== '') : ?><p class="kmg-body mt-4"><?php echo esc_html($f('form_description')); ?></p><?php endif; ?>
                <div class="contact-form-wrapper mt-8"><?php echo do_shortcode($f('form_shortcode')); ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
