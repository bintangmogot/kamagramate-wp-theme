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
<section class="kmg-section" data-testid="faqs">
    <div class="kmg-container grid gap-12 lg:grid-cols-12 lg:gap-20">
        <div class="lg:col-span-4">
            <div class="lg:sticky lg:top-36">
                <?php kmg_section_head([
                    'eyebrow' => $show_eyebrow ? get_sub_field('eyebrow') : '',
                    'title'   => get_sub_field('heading'),
                    'lead'    => get_sub_field('description'),
                    'align'   => 'left',
                    'class'   => '!mb-0',
                ]); ?>
                <?php if ($link_text !== '' && $link_url !== '') : ?>
                    <a href="<?php echo esc_url($link_url); ?>" class="kmg-link mt-8 text-[17px]"><?php echo esc_html($link_text); ?><?php echo kmg_glyph('arrow-right'); ?></a>
                <?php endif; ?>
            </div>
        </div>

        <div class="lg:col-span-8">
            <?php foreach ($items as $i => $item) : ?>
                <details class="kmg-accordion group border-b border-border first:border-t" <?php echo $i === 0 ? 'open' : ''; ?>>
                    <summary class="flex items-start justify-between gap-6 py-7">
                        <h3 class="font-heading text-[clamp(1.1875rem,1.1rem+0.4vw,1.4375rem)] font-bold leading-snug tracking-tight text-ink group-hover:text-primary"><?php echo esc_html($item['q']); ?></h3>
                        <span class="kmg-accordion-icon mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-soft text-primary"><?php echo kmg_glyph('plus', 'h-4 w-4'); ?></span>
                    </summary>
                    <div class="faq-answer prose max-w-none pb-8 pr-14 text-[17px] text-muted-foreground"><?php echo wp_kses_post(wpautop($item['a'])); ?></div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
