<?php
/**
 * Module: about_intro
 * Fields: tag, heading, desc.
 */

$heading = trim((string) get_sub_field('heading'));
if ($heading === '') {
    return;
}
?>
<section class="border-b border-border bg-primary-softer" data-testid="about-intro">
    <div class="kmg-container pb-20 pt-12 md:pb-28 md:pt-16">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <div class="mt-10 grid gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-7">
                <?php if (get_sub_field('tag')) : ?><p class="kmg-eyebrow"><?php echo esc_html(get_sub_field('tag')); ?></p><?php endif; ?>
                <h1 class="kmg-display mt-4"><?php echo esc_html($heading); ?></h1>
            </div>
            <?php if (get_sub_field('desc')) : ?>
                <p class="kmg-lead self-end lg:col-span-5"><?php echo esc_html(get_sub_field('desc')); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
