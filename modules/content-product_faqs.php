<?php
/**
 * Module: product_faqs (product pages)
 * Fields: eyebrow, heading, faq_items (question, answer).
 */

$items = array_values(array_filter((array) get_sub_field('faq_items'), static function ($row) {
    return trim((string) ($row['question'] ?? '')) !== '';
}));
if (!$items) {
    return;
}
?>
<section class="kmg-section" data-testid="product-faqs">
    <div class="kmg-container grid gap-12 lg:grid-cols-12 lg:gap-20">
        <div class="lg:col-span-4">
            <?php kmg_section_head(['eyebrow' => get_sub_field('eyebrow'), 'title' => get_sub_field('heading'), 'align' => 'left', 'class' => '!mb-0 lg:sticky lg:top-36']); ?>
        </div>
        <div class="lg:col-span-8">
            <?php foreach ($items as $i => $item) : ?>
                <details class="kmg-accordion group border-b border-border first:border-t" <?php echo $i === 0 ? 'open' : ''; ?>>
                    <summary class="flex items-start justify-between gap-6 py-7">
                        <h3 class="font-heading text-[1.3125rem] font-bold leading-snug tracking-normal text-ink group-hover:text-primary"><?php echo esc_html(wp_strip_all_tags($item['question'])); ?></h3>
                        <span class="kmg-accordion-icon mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-soft text-primary"><?php echo kmg_glyph('plus', 'h-4 w-4'); ?></span>
                    </summary>
                    <div class="faq-answer prose max-w-none pb-8 pr-14 text-[17px] text-muted-foreground"><?php echo wp_kses_post(wpautop((string) ($item['answer'] ?? ''))); ?></div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
