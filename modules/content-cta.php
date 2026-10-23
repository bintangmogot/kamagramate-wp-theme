<?php
/**
 * Module: cta
 * Fields: title, description, button_text, button_url, secondary_button_text, secondary_button_url.
 */

$title = trim((string) (get_sub_field('title') ?: get_sub_field('heading')));
if ($title === '') {
    return;
}
?>
<section class="kmg-section-sm" data-testid="cta">
    <div class="kmg-container">
        <div class="rounded-[2.5rem] bg-ink px-8 py-16 text-center md:px-16 md:py-24">
            <h2 class="kmg-h2 mx-auto max-w-3xl !text-white"><?php echo esc_html($title); ?></h2>
            <?php if (get_sub_field('description')) : ?>
                <p class="kmg-lead mx-auto mt-6 max-w-2xl !text-white/70"><?php echo esc_html(get_sub_field('description')); ?></p>
            <?php endif; ?>
            <div class="mt-10 flex flex-wrap justify-center gap-3">
                <?php kmg_button((string) get_sub_field('button_text'), (string) get_sub_field('button_url'), 'light'); ?>
                <?php kmg_button((string) get_sub_field('secondary_button_text'), (string) get_sub_field('secondary_button_url'), 'ghost-light', '', false); ?>
            </div>
        </div>
    </div>
</section>
