<?php
/**
 * Module: terms_contact
 * Fields: heading, description, btn_label, whatsapp_number.
 */

$heading = trim((string) get_sub_field('heading'));
if ($heading === '') {
    return;
}
?>
<section class="kmg-section-sm">
    <div class="kmg-container-narrow">
        <div class="rounded-[2rem] bg-primary-softer p-8 text-center md:p-14">
            <h2 class="kmg-h3"><?php echo esc_html($heading); ?></h2>
            <?php if (get_sub_field('description')) : ?><p class="kmg-body mx-auto mt-3 max-w-xl"><?php echo esc_html(get_sub_field('description')); ?></p><?php endif; ?>
            <div class="mt-8 flex justify-center"><?php kmg_button((string) get_sub_field('btn_label'), kmg_chat_url((string) get_sub_field('whatsapp_number')), 'primary'); ?></div>
        </div>
    </div>
</section>
