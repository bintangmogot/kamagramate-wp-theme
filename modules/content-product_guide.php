<?php
/**
 * Module: product_guide
 * Fields: eyebrow, heading, description, best_for_label, link_label,
 * guide_items (title, description, best_for, product_link).
 */

$items = array_values(array_filter((array) get_sub_field('guide_items'), static function ($row) {
    return trim((string) ($row['title'] ?? '')) !== '';
}));
if (!$items) {
    return;
}
$best_for_label = trim((string) get_sub_field('best_for_label'));
$link_label     = trim((string) get_sub_field('link_label'));
?>
<section class="kmg-section kmg-section-tint" data-testid="product-guide">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('eyebrow'),
            'title'   => get_sub_field('heading'),
            'lead'    => get_sub_field('description'),
        ]); ?>
        <ol class="mx-auto max-w-4xl space-y-4">
            <?php foreach ($items as $i => $item) :
                $link = $item['product_link'] ?? '';
                $link = is_array($link) ? ($link['url'] ?? '') : (is_numeric($link) ? get_permalink((int) $link) : (string) $link);
                ?>
                <li class="kmg-card grid gap-6 md:grid-cols-[auto_1fr] md:gap-10">
                    <span class="kmg-num text-[2.5rem] leading-none opacity-30"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                    <div>
                        <h3 class="kmg-h3"><?php echo esc_html($item['title']); ?></h3>
                        <?php if (!empty($item['description'])) : ?><p class="kmg-body mt-3"><?php echo esc_html($item['description']); ?></p><?php endif; ?>
                        <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                            <?php if (!empty($item['best_for'])) : ?>
                                <p class="kmg-chip"><?php if ($best_for_label !== '') : ?><strong><?php echo esc_html($best_for_label); ?></strong><?php endif; ?><?php echo esc_html($item['best_for']); ?></p>
                            <?php endif; ?>
                            <?php if ($link && $link_label !== '') : ?>
                                <a href="<?php echo esc_url($link); ?>" class="kmg-link"><?php echo esc_html($link_label); ?><?php echo kmg_glyph('arrow-right'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
