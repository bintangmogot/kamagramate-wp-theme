<?php
/**
 * Module: seo_text
 * Fields: content (wysiwyg), show_cta, cta_title, cta_desc, cta_link, cta_button_text.
 */

$content = (string) get_sub_field('content');
$link    = get_sub_field('cta_link');
$link    = is_array($link) ? ($link['url'] ?? '') : (string) $link;
$has_cta = get_sub_field('show_cta') && trim((string) get_sub_field('cta_title')) !== '';
if (trim(wp_strip_all_tags($content)) === '' && !$has_cta) {
    return;
}
?>
<section class="kmg-section" data-testid="seo-text">
    <div class="kmg-container grid gap-14 lg:grid-cols-12 lg:gap-20">
        <div class="prose max-w-none text-[18px] <?php echo $has_cta ? 'lg:col-span-8' : 'lg:col-span-10 lg:col-start-2'; ?>"><?php echo wp_kses_post(apply_filters('acf_the_content', $content)); ?></div>
        <?php if ($has_cta) : ?>
            <aside class="lg:col-span-4">
                <div class="sticky top-36 rounded-[2rem] bg-primary-softer p-8 md:p-10">
                    <h2 class="kmg-h3"><?php echo esc_html(get_sub_field('cta_title')); ?></h2>
                    <?php if (get_sub_field('cta_desc')) : ?><p class="kmg-body mt-3"><?php echo esc_html(get_sub_field('cta_desc')); ?></p><?php endif; ?>
                    <div class="mt-8"><?php kmg_button((string) get_sub_field('cta_button_text'), $link, 'primary', 'kmg-btn-sm'); ?></div>
                </div>
            </aside>
        <?php endif; ?>
    </div>
</section>
