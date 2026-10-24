<?php
/**
 * Module: trust_strip
 * Fields: style (light | dark), badges (icon_preset, icon_image, icon_svg, label, desc).
 */

$badges = [];
if (have_rows('badges')) {
    while (have_rows('badges')) {
        the_row();
        $label = trim((string) get_sub_field('label'));
        if ($label === '') {
            continue;
        }
        $badges[] = [
            'label' => $label,
            'desc'  => trim((string) get_sub_field('desc')),
            'icon'  => waklert_render_card_icon([
                'icon_image'  => get_sub_field('icon_image'),
                'icon_preset' => (string) (get_sub_field('icon_preset') ?: 'auto'),
                'icon_svg'    => (string) get_sub_field('icon_svg'),
                'title'       => $label,
                'desc'        => (string) get_sub_field('desc'),
                'size_class'  => 'h-7 w-7',
            ]),
        ];
    }
}
if (!$badges) {
    return;
}
$dark = get_sub_field('style') === 'dark';
$cols = count($badges) >= 4 ? 'lg:grid-cols-4' : (count($badges) === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-2');
?>
<?php if ($dark) : ?>
<section class="bg-ink text-white" data-testid="trust-strip">
    <ul class="kmg-container grid grid-cols-2 gap-x-6 gap-y-10 py-12 md:py-14 <?php echo esc_attr($cols); ?>">
        <?php foreach ($badges as $badge) : ?>
            <li class="flex flex-col items-center text-center">
                <span class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-white/80 text-white md:h-[72px] md:w-[72px]"><?php echo $badge['icon']; ?></span>
                <span class="mt-4 block font-heading text-[19px] font-semibold text-primary-light md:text-[21px]"><?php echo esc_html($badge['label']); ?></span>
                <?php if ($badge['desc'] !== '') : ?>
                    <span class="mt-1 block text-[15px] leading-snug text-white/65"><?php echo esc_html($badge['desc']); ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<?php else : ?>
<section class="border-y border-border bg-white" data-testid="trust-strip">
    <ul class="kmg-container grid grid-cols-1 gap-10 py-14 sm:grid-cols-2 md:py-16 <?php echo esc_attr($cols); ?>">
        <?php foreach ($badges as $badge) : ?>
            <li class="flex items-start gap-5">
                <span class="kmg-icon"><?php echo $badge['icon']; ?></span>
                <span>
                    <span class="block font-heading text-[19px] font-bold text-ink"><?php echo esc_html($badge['label']); ?></span>
                    <?php if ($badge['desc'] !== '') : ?>
                        <span class="mt-1 block text-[16px] leading-relaxed text-muted-foreground"><?php echo esc_html($badge['desc']); ?></span>
                    <?php endif; ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>
