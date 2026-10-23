<?php
/**
 * Module: support_cta
 * Fields: eyebrow, title, desc, chat_btn_text/url (WhatsApp number or URL),
 * telegram_btn_text/url, sms_btn_text/url, email_btn_text/url, image.
 * Falls back to Theme Settings -> Support CTA image when no image is set.
 */

$title = trim((string) get_sub_field('title'));
if ($title === '') {
    return;
}
$GLOBALS['waklert_support_cta_rendered'] = true;

$buttons = [];
$chat_text = trim((string) get_sub_field('chat_btn_text'));
$chat_url  = kmg_chat_url((string) get_sub_field('chat_btn_url'));
if ($chat_text !== '' && $chat_url !== '') {
    $buttons[] = ['text' => $chat_text, 'url' => $chat_url, 'icon' => 'chat', 'primary' => true];
}
$tg_text = trim((string) get_sub_field('telegram_btn_text'));
$tg_url  = kmg_chat_url((string) (get_sub_field('telegram_btn_url') ?: get_field('telegram_number', 'option')), 'telegram');
if ($tg_text !== '' && $tg_url !== '') {
    $buttons[] = ['text' => $tg_text, 'url' => $tg_url, 'icon' => 'send', 'primary' => false];
}
$sms_text = trim((string) get_sub_field('sms_btn_text'));
$sms_raw  = trim((string) get_sub_field('sms_btn_url'));
if ($sms_text !== '' && $sms_raw !== '') {
    $buttons[] = ['text' => $sms_text, 'url' => stripos($sms_raw, 'sms:') === 0 ? $sms_raw : 'sms:' . preg_replace('/[^\d+]/', '', $sms_raw), 'icon' => 'phone', 'primary' => false];
}
$mail_text = trim((string) get_sub_field('email_btn_text'));
$mail_raw  = trim((string) get_sub_field('email_btn_url')) ?: trim((string) get_field('support_email', 'option'));
if ($mail_text !== '' && $mail_raw !== '') {
    $buttons[] = ['text' => $mail_text, 'url' => preg_match('#^(mailto:|https?://|/)#i', $mail_raw) ? $mail_raw : 'mailto:' . $mail_raw, 'icon' => 'mail', 'primary' => false];
}

$image = get_sub_field('image') ?: get_field('support_cta_image', 'option');
$img   = kmg_image($image, 'large', ['class' => 'h-full w-full object-cover', 'loading' => 'lazy', 'alt' => '']);
?>
<section class="kmg-section-sm" data-testid="support-cta">
    <div class="kmg-container">
        <div class="grid overflow-hidden rounded-[2.5rem] bg-primary text-white <?php echo $img ? 'lg:grid-cols-12' : ''; ?>">
            <div class="p-10 md:p-16 lg:p-20 <?php echo $img ? 'lg:col-span-7' : 'text-center'; ?>">
                <?php if (get_sub_field('eyebrow')) : ?>
                    <p class="kmg-eyebrow !text-white/70"><?php echo esc_html(get_sub_field('eyebrow')); ?></p>
                <?php endif; ?>
                <h2 class="kmg-h2 mt-4 !text-white <?php echo $img ? '' : 'mx-auto max-w-3xl'; ?>"><?php echo esc_html($title); ?></h2>
                <?php if (get_sub_field('desc')) : ?>
                    <p class="kmg-lead mt-6 max-w-xl !text-white/80 <?php echo $img ? '' : 'mx-auto'; ?>"><?php echo esc_html(get_sub_field('desc')); ?></p>
                <?php endif; ?>
                <?php if ($buttons) : ?>
                    <div class="mt-10 flex flex-wrap gap-3 <?php echo $img ? '' : 'justify-center'; ?>">
                        <?php foreach ($buttons as $b) :
                            $ext = preg_match('#^https?://#i', $b['url']); ?>
                            <a href="<?php echo esc_url($b['url'], ['http', 'https', 'mailto', 'sms', 'tel']); ?>" class="kmg-btn <?php echo $b['primary'] ? 'kmg-btn-light' : 'kmg-btn-ghost-light'; ?>"<?php echo $ext ? ' target="_blank" rel="noopener"' : ''; ?>>
                                <?php echo kmg_glyph($b['icon']); ?><span><?php echo esc_html($b['text']); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($img) : ?>
                <div class="relative min-h-[280px] lg:col-span-5"><?php echo $img; ?></div>
            <?php endif; ?>
        </div>
    </div>
</section>
