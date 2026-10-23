<?php
/**
 * Module: marquee_strip
 * Fields: items (icon_preset, text). Items are duplicated once so the loop is seamless.
 */

$items = array_values(array_filter((array) get_sub_field('items'), static function ($row) {
    return trim((string) ($row['text'] ?? '')) !== '';
}));
if (!$items) {
    return;
}
?>
<section class="border-y border-border bg-white py-6" data-testid="marquee-strip">
    <div class="kmg-marquee">
        <ul class="kmg-marquee-track">
            <?php for ($pass = 0; $pass < 2; $pass++) : ?>
                <?php foreach ($items as $item) : ?>
                    <li class="flex shrink-0 items-center gap-3 px-8 text-[15px] font-semibold uppercase tracking-[0.12em] text-ink"<?php echo $pass ? ' aria-hidden="true"' : ''; ?>>
                        <span class="text-primary"><?php echo waklert_render_card_icon(['icon_preset' => $item['icon_preset'] ?? 'no_customs', 'title' => $item['text'], 'size_class' => 'h-5 w-5']); ?></span>
                        <?php echo esc_html($item['text']); ?>
                    </li>
                <?php endforeach; ?>
            <?php endfor; ?>
        </ul>
    </div>
</section>
