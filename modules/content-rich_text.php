<?php
/**
 * Module: rich_text
 * Fields: content (wysiwyg), width (default | narrow | full).
 */

$content = (string) get_sub_field('content');
if (trim(wp_strip_all_tags($content)) === '') {
    return;
}
$width = get_sub_field('width') ?: 'default';
$wrap  = ['narrow' => 'kmg-container-narrow', 'full' => 'kmg-container', 'default' => 'mx-auto w-full max-w-[64rem] px-5 md:px-8'][$width] ?? 'kmg-container-narrow';
?>
<section class="kmg-section-sm" data-testid="rich-text">
    <div class="<?php echo esc_attr($wrap); ?>">
        <div class="prose max-w-none text-[18px]"><?php echo wp_kses_post(apply_filters('acf_the_content', $content)); ?></div>
    </div>
</section>
