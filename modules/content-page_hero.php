<?php
/**
 * Module: page_hero
 * Fields: eyebrow, title, subtitle, bullets (bullet).
 * Breadcrumb uses the page hierarchy, so no copy is hardcoded.
 */

$title = trim((string) get_sub_field('title')) ?: get_the_title();
if ($title === '') {
    return;
}
$bullets = array_values(array_filter(array_map(static function ($row) {
    return trim((string) ($row['bullet'] ?? ''));
}, (array) get_sub_field('bullets'))));
?>
<section class="border-b border-border bg-primary-softer" data-testid="page-hero">
    <div class="kmg-container pb-16 pt-12 md:pb-24 md:pt-20">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <div class="mt-8 max-w-4xl">
            <?php if (get_sub_field('eyebrow')) : ?>
                <p class="kmg-eyebrow"><?php echo esc_html(get_sub_field('eyebrow')); ?></p>
            <?php endif; ?>
            <h1 class="kmg-display mt-4"><?php echo esc_html($title); ?></h1>
            <?php if (get_sub_field('subtitle')) : ?>
                <p class="kmg-lead mt-6 max-w-2xl"><?php echo esc_html(get_sub_field('subtitle')); ?></p>
            <?php endif; ?>
            <?php if ($bullets) : ?>
                <ul class="mt-10 flex flex-wrap gap-x-8 gap-y-3">
                    <?php foreach ($bullets as $bullet) : ?>
                        <li class="inline-flex items-center gap-2 text-[16px] font-medium text-ink"><?php echo kmg_glyph('check', 'h-5 w-5 text-primary'); ?><?php echo esc_html($bullet); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</section>
