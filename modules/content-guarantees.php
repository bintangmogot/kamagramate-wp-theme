<?php
/**
 * Module: guarantees (dark band)
 * Fields: eyebrow, title, description, items (icon_preset, title, text, link_text, link_url),
 * facts_heading, facts (label, url) - external references such as the TGA.
 */

$items = array_values(array_filter((array) get_sub_field('items'), static function ($row) {
    return trim((string) ($row['title'] ?? '')) !== '';
}));
if (!$items) {
    return;
}
$facts = array_values(array_filter((array) get_sub_field('facts'), static function ($row) {
    return trim((string) ($row['label'] ?? '')) !== '' && trim((string) ($row['url'] ?? '')) !== '';
}));
?>
<section class="kmg-section kmg-section-dark" data-testid="guarantees">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('eyebrow'),
            'title'   => get_sub_field('title'),
            'lead'    => get_sub_field('description'),
            'align'   => 'left',
            'dark'    => true,
        ]); ?>

        <div class="grid gap-px overflow-hidden rounded-[2rem] bg-white/10 md:grid-cols-2">
            <?php foreach ($items as $item) : ?>
                <div class="flex flex-col bg-ink p-8 md:p-12">
                    <span class="kmg-icon"><?php echo waklert_render_card_icon(['icon_preset' => $item['icon_preset'] ?? 'auto', 'title' => $item['title'], 'desc' => $item['text'] ?? '', 'size_class' => 'h-7 w-7']); ?></span>
                    <h3 class="mt-8 font-heading text-[1.625rem] font-bold leading-tight tracking-normal text-white"><?php echo esc_html($item['title']); ?></h3>
                    <?php if (!empty($item['text'])) : ?>
                        <p class="mt-4 text-[17px] leading-relaxed text-white/65"><?php echo esc_html($item['text']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($item['link_text']) && !empty($item['link_url'])) : ?>
                        <a href="<?php echo esc_url($item['link_url']); ?>" class="kmg-link mt-6 !text-primary-light hover:!text-white"><?php echo esc_html($item['link_text']); ?><?php echo kmg_glyph('arrow-right'); ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($facts) : ?>
            <div class="mt-14 flex flex-col gap-5 md:flex-row md:items-center md:gap-10">
                <?php if (get_sub_field('facts_heading')) : ?>
                    <p class="shrink-0 text-[13px] font-semibold uppercase tracking-[0.14em] text-white/50"><?php echo esc_html(get_sub_field('facts_heading')); ?></p>
                <?php endif; ?>
                <ul class="flex flex-wrap gap-x-8 gap-y-3">
                    <?php foreach ($facts as $fact) : ?>
                        <li><a href="<?php echo esc_url($fact['url']); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-[16px] text-white underline decoration-white/30 underline-offset-4 hover:decoration-white"><?php echo esc_html($fact['label']); ?><?php echo kmg_glyph('arrow-up-right', 'h-4 w-4'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>
