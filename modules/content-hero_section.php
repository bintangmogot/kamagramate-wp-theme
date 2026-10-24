<?php
/**
 * Module: hero_section
 * Fields: location_text, heading, subtitle, description, background_image,
 * primary_button_text/link, secondary_button_text/link, stats (value, label).
 */

$location       = trim((string) get_sub_field('location_text'));
$heading        = trim((string) get_sub_field('heading'));
$subtitle       = trim((string) get_sub_field('subtitle'));
$description    = trim((string) get_sub_field('description'));
$primary_btn    = trim((string) get_sub_field('primary_button_text'));
$primary_link   = trim((string) get_sub_field('primary_button_link'));
$secondary_btn  = trim((string) get_sub_field('secondary_button_text'));
$secondary_link = trim((string) get_sub_field('secondary_button_link'));
$stats          = array_filter((array) get_sub_field('stats'), static function ($row) {
    return trim((string) ($row['value'] ?? '')) !== '';
});
$image          = get_sub_field('background_image');

if ($heading === '') {
    return;
}

$img_html = kmg_image($image, 'full', ['class' => 'h-full w-full object-cover', 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => $heading]);
$has_img  = $img_html !== '';
?>
<section data-testid="hero-section" class="relative overflow-hidden bg-white">
    <div class="kmg-container grid items-center gap-12 pb-16 pt-12 md:pb-24 md:pt-20 lg:grid-cols-12 lg:gap-16 lg:pb-28 lg:pt-24">
        <div class="<?php echo $has_img ? 'lg:col-span-6' : 'mx-auto max-w-4xl text-center lg:col-span-12 [&_.kmg-lead]:mx-auto [&_dl]:mx-auto [&_.flex-wrap]:justify-center'; ?>">
            <?php if ($location !== '') : ?>
                <p class="kmg-chip mb-8">
                    <span class="h-2 w-2 rounded-full bg-primary" aria-hidden="true"></span>
                    <?php echo esc_html($location); ?>
                </p>
            <?php endif; ?>

            <h1 class="kmg-display"><?php echo esc_html($heading); ?></h1>

            <?php if ($subtitle !== '') : ?>
                <p class="mt-6 font-heading text-[clamp(1.375rem,1.2rem+0.8vw,1.875rem)] font-semibold leading-snug tracking-tight text-primary"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>

            <?php if ($description !== '') : ?>
                <p class="kmg-lead mt-6 max-w-xl"><?php echo esc_html($description); ?></p>
            <?php endif; ?>

            <?php if ($primary_btn !== '' || $secondary_btn !== '') : ?>
            <div class="mt-10 flex flex-wrap gap-3">
                <?php kmg_button($primary_btn, $primary_link, 'primary'); ?>
                <?php kmg_button($secondary_btn, $secondary_link, 'outline', '', false); ?>
            </div>
            <?php endif; ?>

            <?php if ($stats) : ?>
            <dl class="mt-14 grid max-w-xl grid-cols-3 gap-x-4 gap-y-8 border-t border-border pt-10 sm:gap-x-8">
                <?php foreach ($stats as $stat) : ?>
                    <div>
                        <dt class="sr-only"><?php echo esc_html($stat['label'] ?? ''); ?></dt>
                        <dd class="font-heading text-[1.375rem] font-extrabold leading-none sm:text-[1.75rem] tracking-tight text-ink"><?php echo esc_html($stat['value']); ?></dd>
                        <?php if (!empty($stat['label'])) : ?>
                            <dd class="mt-2 text-[13px] leading-snug text-muted-foreground sm:text-[15px]"><?php echo esc_html($stat['label']); ?></dd>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </dl>
            <?php endif; ?>
        </div>

        <?php if ($has_img) : ?>
        <div class="lg:col-span-6">
            <div class="relative aspect-[4/5] overflow-hidden rounded-[2.5rem] bg-primary-soft sm:aspect-[5/4] lg:aspect-[4/5]">
                <?php echo $img_html; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
