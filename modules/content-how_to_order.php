<?php
/**
 * Module: how_to_order
 * Fields: tag, heading, description, steps (icon_preset, icon_image, icon_svg, title, desc).
 */

$steps = [];
if (have_rows('steps')) {
    while (have_rows('steps')) {
        the_row();
        $title = trim((string) get_sub_field('title'));
        if ($title === '') {
            continue;
        }
        $steps[] = [
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
if (!$steps) {
    return;
}
?>
<section class="kmg-section kmg-section-tint" data-testid="how-to-order">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('tag'),
            'title'   => get_sub_field('heading'),
            'lead'    => get_sub_field('description'),
        ]); ?>

        <ol class="mx-auto grid max-w-5xl gap-5 <?php echo count($steps) >= 3 ? 'md:grid-cols-3' : 'md:grid-cols-2'; ?>">
            <?php foreach ($steps as $i => $step) : ?>
                <li class="relative rounded-2xl bg-white p-8 text-center shadow-[0_14px_28px_-20px_rgba(34,18,79,0.45)]">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border-2 border-primary font-heading text-[1.375rem] font-bold text-primary"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                    <span class="kmg-icon mx-auto mt-6 !h-12 !w-12 !rounded-xl"><?php echo $step['icon']; ?></span>
                    <h3 class="mt-5 font-heading text-[1.5rem] font-bold leading-tight text-ink"><?php echo esc_html($step['title']); ?></h3>
                    <?php if ($step['desc'] !== '') : ?>
                        <p class="kmg-body mt-2"><?php echo esc_html($step['desc']); ?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
