<?php
/**
 * Module: popular_searches
 * Fields: title, items (label, url).
 */

$items = array_values(array_filter((array) get_sub_field('items'), static function ($row) {
    return trim((string) ($row['label'] ?? '')) !== '' && trim((string) ($row['url'] ?? '')) !== '';
}));
if (!$items) {
    return;
}
?>
<section class="kmg-section-sm" data-testid="popular-searches">
    <div class="kmg-container">
        <?php if (get_sub_field('title')) : ?>
            <h2 class="kmg-h3"><?php echo esc_html(get_sub_field('title')); ?></h2>
        <?php endif; ?>
        <ul class="mt-6 flex flex-wrap gap-2.5">
            <?php foreach ($items as $item) : ?>
                <li><a href="<?php echo esc_url($item['url']); ?>" class="kmg-chip kmg-chip-outline"><?php echo kmg_glyph('search', 'h-3.5 w-3.5 text-muted-foreground'); ?><?php echo esc_html($item['label']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
