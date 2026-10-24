<?php
/**
 * Module: audience_grid ("Used by")
 * Fields: eyebrow, title, description, items (image, title, text).
 */

$items = array_values(array_filter((array) get_sub_field('items'), static function ($row) {
    return trim((string) ($row['title'] ?? '')) !== '';
}));
if (!$items) {
    return;
}
?>
<section class="kmg-section" data-testid="audience-grid">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('eyebrow'),
            'title'   => get_sub_field('title'),
            'lead'    => get_sub_field('description'),
        ]); ?>

        <ul class="grid grid-cols-2 gap-x-6 gap-y-12 lg:grid-cols-4">
            <?php foreach ($items as $item) :
                $img = kmg_image($item['image'] ?? null, 'medium_large', ['class' => 'h-full w-full object-cover', 'loading' => 'lazy', 'alt' => '']);
                ?>
                <li>
                    <div class="aspect-[4/5] overflow-hidden rounded-[1.75rem] bg-primary-soft">
                        <?php echo $img ?: '<span class="flex h-full items-center justify-center text-primary">' . waklert_render_card_icon(['icon_preset' => 'person', 'size_class' => 'h-12 w-12']) . '</span>'; ?>
                    </div>
                    <h3 class="mt-5 font-heading text-[1.25rem] font-bold tracking-normal text-ink"><?php echo esc_html($item['title']); ?></h3>
                    <?php if (!empty($item['text'])) : ?><p class="mt-1 text-[15px] text-muted-foreground"><?php echo esc_html($item['text']); ?></p><?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
