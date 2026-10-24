<?php
/**
 * Module: dosage_guide (medicine information guide page)
 * Fields: page_badge, page_title, page_subtitle, disclaimer_label, disclaimer,
 * guide_heading, guide_intro, dosage_cards (dose, label, desc),
 * products_heading, product_cards (name, use, dose, duration, link), products_button_text/link,
 * timing_heading, timing_intro, timing_items (time, desc),
 * import_heading, import_intro, malaysia_tips (title, desc) - prescription checklist,
 * faq_heading, faq_items (q, a), cta_heading, cta_description, shop/contact button text + link.
 * The legacy "emoji" sub field is ignored.
 */

$f = static function ($name) {
    return trim((string) get_sub_field($name));
};
$rows = static function ($name, $key) {
    return array_values(array_filter((array) get_sub_field($name), static function ($row) use ($key) {
        return trim((string) ($row[$key] ?? '')) !== '';
    }));
};
$doses    = $rows('dosage_cards', 'dose');
$products = $rows('product_cards', 'name');
$timing   = $rows('timing_items', 'time');
$checks   = $rows('malaysia_tips', 'title');
$faqs     = $rows('faq_items', 'q');
$title    = $f('page_title') ?: get_the_title();
?>
<section class="border-b border-border bg-primary-softer" data-testid="dosage-guide">
    <div class="kmg-container pb-16 pt-12 md:pb-20 md:pt-16">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <div class="mt-8 grid gap-12 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7">
                <?php if ($f('page_badge') !== '') : ?><p class="kmg-eyebrow"><?php echo esc_html($f('page_badge')); ?></p><?php endif; ?>
                <h1 class="kmg-display mt-4"><?php echo esc_html($title); ?></h1>
                <?php if ($f('page_subtitle') !== '') : ?><p class="kmg-lead mt-6"><?php echo esc_html($f('page_subtitle')); ?></p><?php endif; ?>
            </div>
            <?php if ($f('disclaimer') !== '') : ?>
                <aside class="rounded-[1.75rem] border border-border bg-white p-8 lg:col-span-5">
                    <?php if ($f('disclaimer_label') !== '') : ?><p class="flex items-center gap-2 font-heading text-[17px] font-bold text-ink"><?php echo waklert_render_card_icon(['icon_preset' => 'medical_rx_clarity', 'size_class' => 'h-5 w-5 text-primary']); ?><?php echo esc_html($f('disclaimer_label')); ?></p><?php endif; ?>
                    <p class="mt-3 text-[16px] leading-relaxed text-muted-foreground"><?php echo esc_html($f('disclaimer')); ?></p>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($doses) : ?>
<section class="kmg-section">
    <div class="kmg-container">
        <?php kmg_section_head(['title' => $f('guide_heading'), 'lead' => $f('guide_intro'), 'align' => 'left']); ?>
        <div class="grid gap-6 md:grid-cols-2 <?php echo ['', 'lg:grid-cols-1', 'lg:grid-cols-2', 'lg:grid-cols-3', 'lg:grid-cols-4'][min(4, count($doses))]; ?>">
            <?php foreach ($doses as $dose) : ?>
                <div class="kmg-card">
                    <p class="kmg-num text-[2.5rem] leading-none"><?php echo esc_html($dose['dose']); ?></p>
                    <?php if (!empty($dose['label'])) : ?><p class="mt-4 font-heading text-[1.25rem] font-bold text-ink"><?php echo esc_html($dose['label']); ?></p><?php endif; ?>
                    <?php if (!empty($dose['desc'])) : ?><p class="kmg-body mt-2"><?php echo esc_html($dose['desc']); ?></p><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($products) : ?>
<section class="kmg-section kmg-section-tint">
    <div class="kmg-container">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <?php kmg_section_head(['title' => $f('products_heading'), 'align' => 'left', 'class' => '!mb-0']); ?>
            <?php kmg_button($f('products_button_text'), $f('products_button_link'), 'outline', 'shrink-0'); ?>
        </div>
        <div class="mt-12 grid gap-4 md:grid-cols-2">
            <?php foreach ($products as $p) : $link = trim((string) ($p['link'] ?? '')); ?>
                <<?php echo $link ? 'a href="' . esc_url($link) . '"' : 'div'; ?> class="group flex items-start justify-between gap-6 rounded-[1.75rem] bg-white p-8 ring-1 ring-border transition-shadow hover:ring-primary">
                    <span>
                        <span class="block font-heading text-[1.375rem] font-bold tracking-normal text-ink group-hover:text-primary"><?php echo esc_html($p['name']); ?></span>
                        <?php if (!empty($p['use'])) : ?><span class="kmg-body mt-2 block"><?php echo esc_html($p['use']); ?></span><?php endif; ?>
                        <span class="mt-4 flex flex-wrap gap-2">
                            <?php foreach (['dose', 'duration'] as $k) : if (!empty($p[$k])) : ?><span class="kmg-chip"><?php echo esc_html($p[$k]); ?></span><?php endif; endforeach; ?>
                        </span>
                    </span>
                    <?php if ($link) : ?><?php echo kmg_glyph('arrow-up-right', 'h-5 w-5 shrink-0 text-muted-foreground group-hover:text-primary'); ?><?php endif; ?>
                </<?php echo $link ? 'a' : 'div'; ?>>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($timing || $checks) : ?>
<section class="kmg-section">
    <div class="kmg-container grid gap-16 lg:grid-cols-2 lg:gap-20">
        <?php if ($timing) : ?>
            <div>
                <?php kmg_section_head(['title' => $f('timing_heading'), 'lead' => $f('timing_intro'), 'align' => 'left', 'class' => '!mb-10']); ?>
                <ol class="space-y-6">
                    <?php foreach ($timing as $item) : ?>
                        <li class="border-t border-border pt-6">
                            <p class="font-heading text-[1.25rem] font-bold text-ink"><?php echo esc_html($item['time']); ?></p>
                            <?php if (!empty($item['desc'])) : ?><p class="kmg-body mt-2"><?php echo esc_html($item['desc']); ?></p><?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        <?php endif; ?>
        <?php if ($checks) : ?>
            <div class="rounded-[2rem] bg-ink p-10 md:p-12">
                <?php if ($f('import_heading') !== '') : ?><h2 class="kmg-h3 !text-[1.875rem] !text-white"><?php echo esc_html($f('import_heading')); ?></h2><?php endif; ?>
                <?php if ($f('import_intro') !== '') : ?><p class="mt-4 text-[17px] text-white/70"><?php echo esc_html($f('import_intro')); ?></p><?php endif; ?>
                <ul class="mt-8 space-y-6">
                    <?php foreach ($checks as $check) : ?>
                        <li class="flex gap-4">
                            <span class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white/10 text-primary-light"><?php echo kmg_glyph('check', 'h-4 w-4'); ?></span>
                            <span>
                                <span class="block font-semibold text-white"><?php echo esc_html($check['title']); ?></span>
                                <?php if (!empty($check['desc'])) : ?><span class="mt-1 block text-[16px] text-white/65"><?php echo esc_html($check['desc']); ?></span><?php endif; ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($faqs) : ?>
<section class="kmg-section kmg-section-tint">
    <div class="kmg-container-narrow">
        <?php kmg_section_head(['title' => $f('faq_heading'), 'align' => 'left', 'class' => '!mb-10']); ?>
        <?php foreach ($faqs as $item) : ?>
            <details class="kmg-accordion group border-b border-border first-of-type:border-t">
                <summary class="flex items-start justify-between gap-6 py-6">
                    <h3 class="font-heading text-[1.25rem] font-bold leading-snug text-ink group-hover:text-primary"><?php echo esc_html($item['q']); ?></h3>
                    <span class="kmg-accordion-icon mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-primary"><?php echo kmg_glyph('plus', 'h-4 w-4'); ?></span>
                </summary>
                <div class="faq-answer prose max-w-none pb-8 pr-14 text-[17px] text-muted-foreground"><?php echo wp_kses_post(wpautop((string) ($item['a'] ?? ''))); ?></div>
            </details>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($f('cta_heading') !== '') : ?>
<section class="kmg-section-sm">
    <div class="kmg-container">
        <div class="rounded-[2.5rem] bg-primary px-8 py-16 text-center md:px-16 md:py-20">
            <h2 class="kmg-h2 mx-auto max-w-3xl !text-white"><?php echo esc_html($f('cta_heading')); ?></h2>
            <?php if ($f('cta_description') !== '') : ?><p class="kmg-lead mx-auto mt-6 max-w-2xl !text-white/80"><?php echo esc_html($f('cta_description')); ?></p><?php endif; ?>
            <div class="mt-10 flex flex-wrap justify-center gap-3">
                <?php kmg_button($f('shop_button_text'), $f('shop_button_link'), 'light'); ?>
                <?php kmg_button($f('contact_button_text'), $f('contact_button_link'), 'ghost-light', '', false); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
