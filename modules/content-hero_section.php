<?php
/**
 * Module: hero_section
 * Fields: location_text, heading, subtitle, description, background_image, image_fit
 * (contain | cover), primary_button_text/link, secondary_button_text/link, stats (value, label).
 * Layout follows modafinilaustraliadirect.com: left-aligned copy, image on the right.
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
if ($heading === '') {
    return;
}

$cover    = get_sub_field('image_fit') === 'cover';
$img_html = kmg_image(get_sub_field('background_image'), 'full', [
    'class'         => $cover ? 'h-full w-full object-cover' : 'h-full w-full object-contain drop-shadow-[0_24px_40px_rgba(34,18,79,0.25)]',
    'loading'       => 'eager',
    'fetchpriority' => 'high',
    'alt'           => $heading,
]);
?>
<section data-testid="hero-section" class="relative overflow-hidden bg-[linear-gradient(180deg,var(--color-primary-soft)_0%,var(--color-primary-softer)_60%,#fff_100%)]">
    <div class="kmg-container grid items-center gap-10 py-14 md:py-20 lg:grid-cols-12 lg:gap-12 lg:py-24">
        <div class="<?php echo $img_html ? 'lg:col-span-7' : 'lg:col-span-12 max-w-4xl'; ?>">
            <?php if ($location !== '') : ?>
                <p class="mb-6 inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-[15px] font-semibold text-primary shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-primary" aria-hidden="true"></span>
                    <?php echo esc_html($location); ?>
                </p>
            <?php endif; ?>

            <h1 class="kmg-display"><?php echo esc_html($heading); ?></h1>

            <?php if ($subtitle !== '') : ?>
                <p class="mt-4 font-heading text-[clamp(1.5rem,1.25rem+1vw,2.25rem)] font-semibold leading-snug text-primary"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>

            <?php if ($description !== '') : ?>
                <p class="kmg-lead mt-6 max-w-2xl"><?php echo esc_html($description); ?></p>
            <?php endif; ?>

            <?php if ($primary_btn !== '' || $secondary_btn !== '') : ?>
                <div class="mt-9 flex flex-wrap gap-3">
                    <?php kmg_button($primary_btn, $primary_link, 'primary'); ?>
                    <?php kmg_button($secondary_btn, $secondary_link, 'outline', '', false); ?>
                </div>
            <?php endif; ?>

            <?php if ($stats) : ?>
                <dl class="mt-12 flex max-w-2xl flex-wrap gap-3">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="min-w-[10rem] flex-1 rounded-2xl bg-white px-5 py-4 text-center shadow-[0_10px_30px_-18px_rgba(34,18,79,0.35)]">
                            <dt class="sr-only"><?php echo esc_html($stat['label'] ?? ''); ?></dt>
                            <dd class="whitespace-nowrap font-heading text-[1.75rem] font-bold leading-none text-ink sm:text-[1.875rem]"><?php echo esc_html($stat['value']); ?></dd>
                            <?php if (!empty($stat['label'])) : ?>
                                <dd class="mt-1.5 text-[15px] leading-snug text-muted-foreground sm:text-[16px]"><?php echo esc_html($stat['label']); ?></dd>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>
        </div>

        <?php if ($img_html) : ?>
            <div class="hidden lg:col-span-5 lg:block">
                <div class="relative mx-auto aspect-square max-w-[20rem] sm:max-w-[26rem] lg:max-w-[34rem] <?php echo $cover ? 'overflow-hidden rounded-[2rem]' : ''; ?>">
                    <?php echo $img_html; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
