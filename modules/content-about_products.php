<?php
/**
 * Module: about_products
 * Fields: heading, desc, products (title, desc).
 */

$items = array_values(array_filter((array) get_sub_field('products'), static function ($row) {
    return trim((string) ($row['title'] ?? '')) !== '';
}));
if (!$items && !get_sub_field('heading')) {
    return;
}
?>
<section class="kmg-section kmg-section-tint" data-testid="about-products">
    <div class="kmg-container">
        <?php kmg_section_head(['title' => get_sub_field('heading'), 'lead' => get_sub_field('desc'), 'align' => 'left']); ?>
        <?php if ($items) : ?>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($items as $i => $item) : ?>
                    <div class="kmg-card">
                        <p class="kmg-num text-[15px]"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></p>
                        <h3 class="kmg-h3 mt-4"><?php echo esc_html($item['title']); ?></h3>
                        <?php if (!empty($item['desc'])) : ?><p class="kmg-body mt-3"><?php echo esc_html($item['desc']); ?></p><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
