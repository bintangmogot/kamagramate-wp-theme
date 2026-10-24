<?php
/**
 * Module: why_choose_us
 * Fields: tag, heading, description, audiences (icon_preset, icon_image, icon_svg, title, desc).
 */

$items = [];
if (have_rows('audiences')) {
    while (have_rows('audiences')) {
        the_row();
        $title = trim((string) get_sub_field('title'));
        if ($title === '') {
            continue;
        }
        $items[] = [
            'title' => $title,
            'desc'  => trim((string) get_sub_field('desc')),
            'icon'  => waklert_render_card_icon([
                'icon_image'  => get_sub_field('icon_image'),
                'icon_preset' => (string) (get_sub_field('icon_preset') ?: 'auto'),
                'icon_svg'    => (string) get_sub_field('icon_svg'),
                'title'       => $title,
                'desc'        => (string) get_sub_field('desc'),
                'size_class'  => 'h-7 w-7',
            ]),
        ];
    }
}
if (!$items) {
    return;
}
?>
<section class="kmg-section" data-testid="why-choose-us">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('tag'),
            'title'   => get_sub_field('heading'),
            'lead'    => get_sub_field('description'),
        ]); ?>
        <div class="mx-auto grid max-w-5xl gap-5 md:grid-cols-2">
            <?php foreach ($items as $item) : ?>
                <div class="flex gap-4 rounded-2xl bg-primary-soft p-6 md:p-7">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary text-white"><?php echo kmg_glyph('check', 'h-4 w-4'); ?></span>
                    <div>
                        <h3 class="font-heading text-[1.5rem] font-bold leading-tight text-ink"><?php echo esc_html($item['title']); ?></h3>
                        <?php if ($item['desc'] !== '') : ?>
                            <p class="kmg-body mt-1.5"><?php echo esc_html($item['desc']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
