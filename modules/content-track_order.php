<?php
/**
 * Module: track_order
 * Fields: badge_text, heading, description, intro_callout_p1/p2, form_heading,
 * form_instructions, input_placeholder, track_btn_text, redirect_notice,
 * how_works_heading, how_works_steps (title, description), help_heading,
 * help_description, help_btn_text, help_btn_url, trust_cards (card_type, icon_image, title, description).
 * The form opens the official Australia Post tracking page for the entered number.
 */

$f = static function ($name) {
    return trim((string) get_sub_field($name));
};
$heading  = $f('heading') ?: get_the_title();
$tracking = isset($_GET['id']) ? sanitize_text_field(wp_unslash($_GET['id'])) : '';
$steps    = array_values(array_filter((array) get_sub_field('how_works_steps'), static function ($row) {
    return trim((string) ($row['title'] ?? '')) !== '';
}));
$cards    = array_values(array_filter((array) get_sub_field('trust_cards'), static function ($row) {
    return trim((string) ($row['title'] ?? '')) !== '';
}));
$card_icons = ['shipping' => 'express_shipping', 'support' => 'live_chat_support', 'payment' => 'secure_lock'];
?>
<section class="border-b border-border bg-primary-softer" data-testid="track-order">
    <div class="kmg-container pb-16 pt-12 md:pb-20 md:pt-16">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <div class="mt-8 grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-6">
                <?php if ($f('badge_text') !== '') : ?><p class="kmg-eyebrow"><?php echo esc_html($f('badge_text')); ?></p><?php endif; ?>
                <h1 class="kmg-display mt-4"><?php echo esc_html($heading); ?></h1>
                <?php if ($f('description') !== '') : ?><p class="kmg-lead mt-6"><?php echo esc_html($f('description')); ?></p><?php endif; ?>
                <?php foreach (array_filter([$f('intro_callout_p1'), $f('intro_callout_p2')]) as $para) : ?>
                    <p class="kmg-body mt-4"><?php echo esc_html($para); ?></p>
                <?php endforeach; ?>
            </div>

            <div class="lg:col-span-6">
                <div class="rounded-[2rem] bg-white p-8 ring-1 ring-border md:p-10">
                    <?php if ($f('form_heading') !== '') : ?><h2 class="kmg-h3"><?php echo esc_html($f('form_heading')); ?></h2><?php endif; ?>
                    <?php if ($f('form_instructions') !== '') : ?><p class="kmg-body mt-3"><?php echo esc_html($f('form_instructions')); ?></p><?php endif; ?>
                    <form id="auspost-tracking-form" action="https://auspost.com.au/mypost/track/search" method="GET" target="_blank" rel="noopener noreferrer" class="mt-8 space-y-3">
                        <label for="auspost-tracking-input" class="sr-only"><?php echo esc_html($f('form_heading')); ?></label>
                        <input type="text" id="auspost-tracking-input" name="id" value="<?php echo esc_attr($tracking); ?>" placeholder="<?php echo esc_attr($f('input_placeholder')); ?>" autocomplete="off" spellcheck="false" class="kmg-input font-mono tracking-wider">
                        <button type="submit" id="auspost-tracking-btn" class="kmg-btn kmg-btn-primary w-full"><?php echo esc_html($f('track_btn_text')); ?><?php echo kmg_glyph('arrow-up-right'); ?></button>
                    </form>
                    <?php if ($f('redirect_notice') !== '') : ?><p class="mt-5 text-[14px] text-muted-foreground"><?php echo esc_html($f('redirect_notice')); ?></p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($steps) : ?>
<section class="kmg-section">
    <div class="kmg-container">
        <?php kmg_section_head(['title' => $f('how_works_heading')]); ?>
        <ol class="grid gap-6 md:grid-cols-3 md:gap-8">
            <?php foreach ($steps as $i => $step) : ?>
                <li class="kmg-card">
                    <p class="kmg-num text-[2.5rem] leading-none opacity-25"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></p>
                    <h3 class="kmg-h3 mt-8"><?php echo esc_html(preg_replace('/^\d+\.\s*/', '', $step['title'])); ?></h3>
                    <?php if (!empty($step['description'])) : ?><p class="kmg-body mt-3"><?php echo esc_html($step['description']); ?></p><?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
<?php endif; ?>

<?php if ($cards || $f('help_heading') !== '') : ?>
<section class="kmg-section kmg-section-tint">
    <div class="kmg-container grid gap-6 lg:grid-cols-4">
        <?php foreach ($cards as $card) : ?>
            <div class="kmg-card">
                <span class="kmg-icon"><?php echo waklert_render_card_icon(['icon_image' => $card['icon_image'] ?? null, 'icon_preset' => $card_icons[$card['card_type'] ?? ''] ?? 'auto', 'title' => $card['title'], 'size_class' => 'h-7 w-7']); ?></span>
                <h3 class="kmg-h3 mt-6"><?php echo esc_html($card['title']); ?></h3>
                <?php if (!empty($card['description'])) : ?><p class="kmg-body mt-3"><?php echo esc_html($card['description']); ?></p><?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if ($f('help_heading') !== '') : ?>
            <div class="flex flex-col rounded-[1.75rem] bg-ink p-8 md:p-10">
                <h3 class="kmg-h3 !text-white"><?php echo esc_html($f('help_heading')); ?></h3>
                <?php if ($f('help_description') !== '') : ?><p class="mt-3 text-[17px] leading-relaxed text-white/70"><?php echo esc_html($f('help_description')); ?></p><?php endif; ?>
                <div class="mt-auto pt-8"><?php kmg_button($f('help_btn_text'), $f('help_btn_url'), 'light', 'kmg-btn-sm'); ?></div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<script>
(function () {
    const form = document.getElementById('auspost-tracking-form');
    const input = document.getElementById('auspost-tracking-input');
    if (!form || !input) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const clean = (input.value || '').replace(/[\s-]/g, '');
        const url = clean ? 'https://auspost.com.au/mypost/track/details/' + encodeURIComponent(clean) : 'https://auspost.com.au/mypost/track/';
        window.open(url, '_blank', 'noopener,noreferrer');
    });
})();
</script>
