<?php
/**
 * 404. Copy from Theme Settings -> Interface Labels.
 */

get_header();
$label = static function ($name) {
    return trim((string) get_field($name, 'option'));
};
?>
<main id="main" class="kmg-section">
    <div class="kmg-container-narrow py-10 text-center">
        <p class="kmg-num text-[clamp(6rem,4rem+10vw,12rem)] leading-none tracking-tight">404</p>
        <?php if ($label('label_404_title') !== '') : ?><h1 class="kmg-h2 mt-6"><?php echo esc_html($label('label_404_title')); ?></h1><?php endif; ?>
        <?php if ($label('label_404_text') !== '') : ?><p class="kmg-lead mx-auto mt-6 max-w-xl"><?php echo esc_html($label('label_404_text')); ?></p><?php endif; ?>
        <div class="mt-10 flex justify-center"><?php kmg_button($label('label_404_button'), home_url('/'), 'primary'); ?></div>
    </div>
</main>
<?php
get_footer();
