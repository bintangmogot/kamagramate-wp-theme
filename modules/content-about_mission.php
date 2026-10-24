<?php
/**
 * Module: about_mission
 * Fields: mission_heading, mission_desc, mission_icon_preset, mission_icon_image,
 * vision_heading, vision_desc, vision_icon_preset, vision_icon_image.
 */

$cards = [];
foreach (['mission', 'vision'] as $key) {
    $heading = trim((string) get_sub_field($key . '_heading'));
    if ($heading === '') {
        continue;
    }
    $cards[] = [
        'heading' => $heading,
        'desc'    => trim((string) get_sub_field($key . '_desc')),
        'icon'    => waklert_render_card_icon([
            'icon_image'  => get_sub_field($key . '_icon_image'),
            'icon_preset' => (string) (get_sub_field($key . '_icon_preset') ?: 'auto'),
            'title'       => $heading,
            'desc'        => (string) get_sub_field($key . '_desc'),
            'size_class'  => 'h-7 w-7',
        ]),
    ];
}
if (!$cards) {
    return;
}
?>
<section class="kmg-section" data-testid="about-mission">
    <div class="kmg-container grid gap-6 md:grid-cols-2 md:gap-8">
        <?php foreach ($cards as $i => $card) : ?>
            <div class="flex flex-col rounded-[2rem] p-10 md:p-14 <?php echo $i === 0 ? 'bg-ink text-white' : 'bg-primary-soft'; ?>">
                <span class="kmg-icon <?php echo $i === 0 ? '!bg-white/10 !text-primary-light' : '!bg-white'; ?>"><?php echo $card['icon']; ?></span>
                <h2 class="mt-10 font-heading text-[clamp(1.75rem,1.4rem+1.4vw,2.5rem)] font-bold leading-tight tracking-normal <?php echo $i === 0 ? 'text-white' : 'text-ink'; ?>"><?php echo esc_html($card['heading']); ?></h2>
                <?php if ($card['desc'] !== '') : ?>
                    <p class="mt-5 text-[18px] leading-relaxed <?php echo $i === 0 ? 'text-white/70' : 'text-muted-foreground'; ?>"><?php echo esc_html($card['desc']); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
