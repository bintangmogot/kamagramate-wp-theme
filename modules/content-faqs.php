<?php
/**
 * Module: faqs
 * Fields: show_eyebrow, eyebrow, heading, description, faq_items (question, answer),
 * show_full_faq_link, link_text, link_url.
 */

$items = [];
if (have_rows('faq_items')) {
    while (have_rows('faq_items')) {
        the_row();
        $q = trim((string) get_sub_field('question'));
        if ($q !== '') {
            $items[] = ['q' => $q, 'a' => (string) get_sub_field('answer')];
        }
    }
}
if (!$items) {
    return;
}

$show_eyebrow = get_sub_field('show_eyebrow') !== false;
$link_text    = get_sub_field('show_full_faq_link') !== false ? trim((string) get_sub_field('link_text')) : '';
$link_url     = trim((string) get_sub_field('link_url'));
?>
<section class="kmg-section kmg-section-tint" data-testid="faqs">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => $show_eyebrow ? get_sub_field('eyebrow') : '',
            'title'   => get_sub_field('heading'),
            'lead'    => get_sub_field('description'),
        ]); ?>

        <div class="grid gap-x-12 md:grid-cols-2">
            <?php foreach (array_chunk($items, (int) ceil(count($items) / 2)) as $column) : ?>
                <div>
                    <?php foreach ($column as $item) : ?>
                        <details class="kmg-accordion group border-b border-ink/15" open>
                            <summary class="flex items-start justify-between gap-6 py-6">
                                <h3 class="font-heading text-[1.4375rem] font-semibold leading-snug text-ink group-hover:text-primary"><?php echo esc_html($item['q']); ?></h3>
                                <span class="kmg-accordion-icon mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-2 border-ink/70 text-ink"><?php echo kmg_glyph('plus', 'h-3.5 w-3.5'); ?></span>
                            </summary>
                            <div class="faq-answer prose -mt-2 max-w-none pb-6 pr-10 text-[17px] text-muted-foreground"><?php echo wp_kses_post(wpautop($item['a'])); ?></div>
                        </details>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($link_text !== '' && $link_url !== '') : ?>
            <div class="mt-12 flex justify-center"><?php kmg_button($link_text, $link_url, 'primary'); ?></div>
        <?php endif; ?>
    </div>
</section>
