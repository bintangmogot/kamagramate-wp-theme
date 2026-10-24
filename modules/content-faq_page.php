<?php
/**
 * Module: faq_page
 * Fields: eyebrow, heading, description, faq_categories (title, items (question, answer)),
 * cta_heading, cta_desc, cta_btn_text, cta_btn_link.
 * Category chips filter the list client-side (assets/js/main.js, [data-kmg-faq-filter]).
 */

$categories = [];
foreach ((array) get_sub_field('faq_categories') as $i => $cat) {
    $items = array_values(array_filter((array) ($cat['items'] ?? []), static function ($row) {
        return trim((string) ($row['question'] ?? '')) !== '';
    }));
    if ($items) {
        $categories[] = ['id' => 'faq-cat-' . ($i + 1), 'title' => trim((string) ($cat['title'] ?? '')), 'items' => $items];
    }
}
$heading = trim((string) get_sub_field('heading')) ?: get_the_title();
?>
<section class="border-b border-border bg-primary-softer" data-testid="faq-page">
    <div class="kmg-container pb-16 pt-12 md:pb-20 md:pt-16">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <div class="mt-8 max-w-4xl">
            <?php if (get_sub_field('eyebrow')) : ?><p class="kmg-eyebrow"><?php echo esc_html(get_sub_field('eyebrow')); ?></p><?php endif; ?>
            <h1 class="kmg-display mt-4"><?php echo esc_html($heading); ?></h1>
            <?php if (get_sub_field('description')) : ?><p class="kmg-lead mt-6 max-w-2xl"><?php echo esc_html(get_sub_field('description')); ?></p><?php endif; ?>
        </div>
        <?php if (count($categories) > 1) : ?>
            <div class="mt-10 flex flex-wrap gap-2" role="tablist">
                <?php foreach ($categories as $cat) : ?>
                    <button type="button" data-kmg-faq-filter="<?php echo esc_attr($cat['id']); ?>" aria-pressed="false" class="kmg-chip kmg-chip-outline aria-pressed:border-primary aria-pressed:bg-primary aria-pressed:text-white">
                        <?php echo esc_html($cat['title']); ?> <span class="opacity-60"><?php echo count($cat['items']); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="kmg-section">
    <div class="kmg-container-narrow space-y-20">
        <?php foreach ($categories as $cat) : ?>
            <div id="<?php echo esc_attr($cat['id']); ?>" data-kmg-faq-group class="scroll-mt-36">
                <?php if ($cat['title'] !== '') : ?><h2 class="kmg-h3 mb-6 !text-[1.75rem]"><?php echo esc_html($cat['title']); ?></h2><?php endif; ?>
                <?php foreach ($cat['items'] as $item) : ?>
                    <details class="kmg-accordion group border-b border-border first-of-type:border-t">
                        <summary class="flex items-start justify-between gap-6 py-6">
                            <h3 class="font-heading text-[1.25rem] font-bold leading-snug tracking-normal text-ink group-hover:text-primary"><?php echo esc_html($item['question']); ?></h3>
                            <span class="kmg-accordion-icon mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-soft text-primary"><?php echo kmg_glyph('plus', 'h-4 w-4'); ?></span>
                        </summary>
                        <div class="faq-answer prose max-w-none pb-8 pr-14 text-[17px] text-muted-foreground"><?php echo wp_kses_post(wpautop((string) ($item['answer'] ?? ''))); ?></div>
                    </details>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <?php if (get_sub_field('cta_heading')) : ?>
            <div class="rounded-[2rem] bg-ink p-10 text-center md:p-14">
                <h2 class="kmg-h3 !text-[1.875rem] !text-white"><?php echo esc_html(get_sub_field('cta_heading')); ?></h2>
                <?php if (get_sub_field('cta_desc')) : ?><p class="mx-auto mt-4 max-w-xl text-[17px] text-white/70"><?php echo esc_html(get_sub_field('cta_desc')); ?></p><?php endif; ?>
                <div class="mt-8 flex justify-center"><?php kmg_button((string) get_sub_field('cta_btn_text'), (string) get_sub_field('cta_btn_link'), 'light'); ?></div>
            </div>
        <?php endif; ?>
    </div>
</section>
