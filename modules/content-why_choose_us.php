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
        <div class="grid gap-14 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-36">
                    <?php kmg_section_head([
                        'eyebrow' => get_sub_field('tag'),
                        'title'   => get_sub_field('heading'),
                        'lead'    => get_sub_field('description'),
                        'align'   => 'left',
                        'class'   => '!mb-0',
                    ]); ?>
                </div>
            </div>

            <div class="grid gap-x-10 gap-y-14 sm:grid-cols-2 lg:col-span-7">
                <?php foreach ($items as $item) : ?>
                    <div class="border-t border-border pt-8">
                        <span class="kmg-icon"><?php echo $item['icon']; ?></span>
                        <h3 class="kmg-h3 mt-6"><?php echo esc_html($item['title']); ?></h3>
                        <?php if ($item['desc'] !== '') : ?>
                            <p class="kmg-body mt-3"><?php echo esc_html($item['desc']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
