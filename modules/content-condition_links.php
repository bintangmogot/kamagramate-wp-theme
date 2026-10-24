<?php
/**
 * Module: condition_links
 * Fields: eyebrow, title, description, link_text, link_url, items (icon_preset, title, subtitle, url).
 */

$items = array_values(array_filter((array) get_sub_field('items'), static function ($row) {
    return trim((string) ($row['title'] ?? '')) !== '' && trim((string) ($row['url'] ?? '')) !== '';
}));
if (!$items) {
    return;
}
$link_text = trim((string) get_sub_field('link_text'));
$link_url  = trim((string) get_sub_field('link_url'));
?>
<section class="kmg-section-sm" data-testid="condition-links">
    <div class="kmg-container">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <?php kmg_section_head([
                'eyebrow' => get_sub_field('eyebrow'),
                'title'   => get_sub_field('title'),
                'lead'    => get_sub_field('description'),
                'align'   => 'left',
                'class'   => '!mb-0',
            ]); ?>
            <?php if ($link_text !== '' && $link_url !== '') : ?>
                <a href="<?php echo esc_url($link_url); ?>" class="kmg-link shrink-0 text-[17px]"><?php echo esc_html($link_text); ?><?php echo kmg_glyph('arrow-right'); ?></a>
            <?php endif; ?>
        </div>

        <ul class="mt-12 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($items as $item) : ?>
                <li class="min-w-0">
                    <a href="<?php echo esc_url($item['url']); ?>" class="group flex items-center gap-4 rounded-2xl border border-border bg-white p-5 transition-colors hover:border-primary">
                        <span class="kmg-icon !h-12 !w-12 !rounded-xl"><?php echo waklert_render_card_icon(['icon_preset' => $item['icon_preset'] ?? 'medicine', 'title' => $item['title'], 'size_class' => 'h-6 w-6']); ?></span>
                        <span class="min-w-0 flex-1">
                            <span class="block font-heading text-[17px] font-bold text-ink"><?php echo esc_html($item['title']); ?></span>
                            <?php if (!empty($item['subtitle'])) : ?><span class="block truncate text-[14px] text-muted-foreground"><?php echo esc_html($item['subtitle']); ?></span><?php endif; ?>
                        </span>
                        <?php echo kmg_glyph('arrow-right', 'h-5 w-5 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-1 group-hover:text-primary'); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
