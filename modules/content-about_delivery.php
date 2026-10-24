<?php
/**
 * Module: about_delivery
 * Fields: heading, desc, list_items (icon_preset, icon_image, icon_svg, title, desc).
 */

$items = [];
foreach ((array) get_sub_field('list_items') as $row) {
    $title = trim((string) ($row['title'] ?? ''));
    if ($title === '') {
        continue;
    }
    $items[] = [
        'title' => $title,
        'desc'  => trim((string) ($row['desc'] ?? '')),
        'icon'  => waklert_render_card_icon([
            'icon_image'  => $row['icon_image'] ?? null,
            'icon_preset' => (string) (($row['icon_preset'] ?? '') ?: 'auto'),
            'icon_svg'    => (string) ($row['icon_svg'] ?? ''),
            'title'       => $title,
            'desc'        => (string) ($row['desc'] ?? ''),
            'size_class'  => 'h-7 w-7',
        ]),
    ];
}
if (!$items && !get_sub_field('heading')) {
    return;
}
?>
<section class="kmg-section" data-testid="about-delivery">
    <div class="kmg-container grid gap-14 lg:grid-cols-12 lg:gap-20">
        <div class="lg:col-span-5">
            <?php kmg_section_head(['title' => get_sub_field('heading'), 'lead' => get_sub_field('desc'), 'align' => 'left', 'class' => '!mb-0']); ?>
        </div>
        <ul class="space-y-10 lg:col-span-7">
            <?php foreach ($items as $item) : ?>
                <li class="flex gap-6 border-t border-border pt-10 first:border-t-0 first:pt-0">
                    <span class="kmg-icon"><?php echo $item['icon']; ?></span>
                    <span>
                        <span class="block font-heading text-[1.375rem] font-bold tracking-normal text-ink"><?php echo esc_html($item['title']); ?></span>
                        <?php if ($item['desc'] !== '') : ?><span class="kmg-body mt-2 block"><?php echo esc_html($item['desc']); ?></span><?php endif; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
