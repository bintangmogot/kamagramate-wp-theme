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
$cols = count($steps) >= 4 ? 'md:grid-cols-2 lg:grid-cols-4' : 'md:grid-cols-' . count($steps);
?>
<section class="kmg-section kmg-section-tint" data-testid="how-to-order">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('tag'),
            'title'   => get_sub_field('heading'),
            'lead'    => get_sub_field('description'),
        ]); ?>

        <ol class="grid gap-6 <?php echo esc_attr($cols); ?> md:gap-8">
            <?php foreach ($steps as $i => $step) : ?>
                <li class="kmg-card flex flex-col">
                    <div class="flex items-center justify-between">
                        <span class="kmg-icon"><?php echo $step['icon']; ?></span>
                        <span class="kmg-num text-[2.5rem] leading-none opacity-25"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                    </div>
                    <h3 class="kmg-h3 mt-8"><?php echo esc_html($step['title']); ?></h3>
                    <?php if ($step['desc'] !== '') : ?>
                        <p class="kmg-body mt-3"><?php echo esc_html($step['desc']); ?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
