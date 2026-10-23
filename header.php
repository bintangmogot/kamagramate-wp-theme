<?php
/**
 * Site header: announcement bar, main bar (logo, nav, search, phone, cart),
 * optional category row and the mobile drawer. All copy from Theme Settings.
 */

$opt = function ($name) {
    return function_exists('get_field') ? trim((string) get_field($name, 'option')) : '';
};

$ga_id          = $opt('ga_measurement_id');
$topbar_left    = $opt('topbar_left');
$topbar_center  = $opt('topbar_center');
$topbar_code    = $opt('topbar_promo_code');
$raw_phone      = $opt('header_phone_number') ?: $opt('whatsapp_number');
$phone_display  = $raw_phone !== '' ? kmg_phone_display($raw_phone) : '';
$phone_label    = $opt('header_phone_label');
$phone_text     = $opt('whatsapp_button_text');
$phone_url      = $opt('whatsapp_button_url');
$search_hint    = $opt('header_search_placeholder');
$search_button  = $opt('header_search_button');

// Phone button: explicit URL from settings wins, otherwise dial the number.
if ($phone_text === '' || stripos($phone_text, 'whatsapp') !== false) {
    $phone_text = $phone_display;
}
if ($phone_url !== '' && stripos($phone_url, 'wa.me') === false) {
    $phone_href = preg_match('#^(tel:|sms:|mailto:|https?://|/|\#)#i', $phone_url) ? $phone_url : kmg_phone_href($phone_url);
} else {
    $phone_href = kmg_phone_href($raw_phone);
}

$help = [
    'eyebrow' => $opt('drawer_help_eyebrow'),
    'title'   => $opt('drawer_help_title'),
    'text'    => $opt('drawer_help_text'),
    'link'    => $opt('drawer_help_link_text'),
    'url'     => $opt('drawer_help_link_url'),
];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="overflow-x-hidden scroll-smooth">
<head>
    <?php if ($ga_id !== '') : ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo esc_js($ga_id); ?>');
    </script>
    <?php endif; ?>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php if (is_single() || is_page()) :
        global $post;
        $og_image = get_the_post_thumbnail_url($post->ID, 'full') ?: get_template_directory_uri() . '/screenshot.jpg';
        ?>
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>" />
        <meta property="og:description" content="<?php echo esc_attr(get_the_excerpt()); ?>" />
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>" />
        <meta property="og:image" content="<?php echo esc_url($og_image); ?>" />
        <meta property="og:type" content="article" />
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta name="twitter:description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
        <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
    <?php else : ?>
        <meta property="og:title" content="<?php echo esc_attr(get_bloginfo('name')); ?>" />
        <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>" />
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>" />
        <meta property="og:type" content="website" />
    <?php endif; ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-background text-foreground antialiased selection:bg-primary-soft selection:text-primary-dark'); ?>>
<?php wp_body_open(); ?>
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100000] focus:rounded-full focus:bg-ink focus:px-5 focus:py-3 focus:text-white"><?php echo esc_html(get_bloginfo('name')); ?></a>

<?php if ($topbar_left !== '' || $topbar_center !== '' || $phone_display !== '') : ?>
<div class="bg-ink text-white" data-testid="trust-bar">
    <div class="kmg-container flex min-h-10 items-center justify-between gap-4 py-2 text-[13px] font-medium">
        <p class="min-w-0 truncate text-white/75"><?php echo esc_html($topbar_left); ?></p>

        <?php if ($topbar_center !== '' || $topbar_code !== '') : ?>
        <p class="hidden items-center gap-2.5 md:flex">
            <span><?php echo esc_html($topbar_center); ?></span>
            <?php if ($topbar_code !== '') : ?>
                <span class="rounded-md bg-white/10 px-2 py-0.5 font-mono text-[12px] font-semibold tracking-wider text-white"><?php echo esc_html($topbar_code); ?></span>
            <?php endif; ?>
        </p>
        <?php endif; ?>

        <?php if ($phone_display !== '') : ?>
        <a href="<?php echo esc_attr(kmg_phone_href($raw_phone)); ?>" class="inline-flex shrink-0 items-center gap-2 text-white/75 transition-colors hover:text-white">
            <?php echo kmg_glyph('phone', 'h-3.5 w-3.5'); ?>
            <span class="whitespace-nowrap"><?php echo esc_html($phone_display); ?></span>
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<header class="sticky top-0 z-50 w-full border-b border-border bg-white/90 backdrop-blur-lg">
    <div class="kmg-container flex h-[76px] items-center gap-4 lg:h-[88px] lg:gap-8">

        <button type="button" id="mobile-menu-open" aria-label="<?php echo esc_attr__('Open menu', 'kamagramate'); ?>" class="-ml-2 inline-flex h-11 w-11 items-center justify-center rounded-full text-ink hover:bg-primary-soft lg:hidden">
            <?php echo kmg_glyph('menu', 'h-6 w-6'); ?>
        </button>

        <div class="flex-1 lg:flex-none">
            <?php waklert_the_logo('h-9 sm:h-10 w-auto max-w-[200px] object-contain', 'header'); ?>
        </div>

        <nav class="desktop-nav hidden flex-1 justify-center lg:flex" aria-label="<?php echo esc_attr__('Primary', 'kamagramate'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex items-center gap-1',
                'fallback_cb'    => false,
                'walker'         => new Waklert_Desktop_Nav_Walker(),
            ]);
            ?>
        </nav>

        <div class="flex items-center gap-1 sm:gap-2">
            <?php if ($search_hint !== '') : ?>
            <button type="button" data-kmg-search-toggle aria-expanded="false" aria-controls="kmg-search" aria-label="<?php echo esc_attr($search_hint); ?>" class="inline-flex h-11 w-11 items-center justify-center rounded-full text-ink transition-colors hover:bg-primary-soft">
                <?php echo kmg_glyph('search', 'h-[22px] w-[22px]'); ?>
            </button>
            <?php endif; ?>

            <?php if ($phone_text !== '' && $phone_href !== '') : ?>
            <a href="<?php echo esc_attr($phone_href); ?>" class="hidden items-center gap-3 rounded-full py-1.5 pl-1.5 pr-5 transition-colors hover:bg-primary-softer xl:inline-flex">
                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-primary text-white"><?php echo kmg_glyph('phone', 'h-[18px] w-[18px]'); ?></span>
                <span class="leading-tight">
                    <?php if ($phone_label !== '') : ?><span class="block text-[12px] font-medium text-muted-foreground"><?php echo esc_html($phone_label); ?></span><?php endif; ?>
                    <span class="block font-heading text-[17px] font-bold tracking-tight text-ink"><?php echo esc_html($phone_text); ?></span>
                </span>
            </a>
            <?php endif; ?>

            <?php echo do_shortcode('[xoo_wsc_cart]'); ?>
        </div>
    </div>

    <?php if ($search_hint !== '') : ?>
    <div id="kmg-search" class="hidden border-t border-border bg-white">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="kmg-container flex items-center gap-3 py-5">
            <label for="kmg-search-input" class="sr-only"><?php echo esc_html($search_hint); ?></label>
            <div class="relative flex-1">
                <span class="pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-muted-foreground"><?php echo kmg_glyph('search', 'h-5 w-5'); ?></span>
                <input id="kmg-search-input" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_attr($search_hint); ?>" class="kmg-input pl-14 text-[17px]" autocomplete="off">
                <input type="hidden" name="post_type" value="product">
            </div>
            <?php if ($search_button !== '') : ?>
                <button type="submit" class="kmg-btn kmg-btn-primary hidden sm:inline-flex"><?php echo esc_html($search_button); ?></button>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>

    <?php if (has_nav_menu('category_nav')) : ?>
    <nav class="hidden border-t border-border lg:block" aria-label="<?php echo esc_attr__('Categories', 'kamagramate'); ?>">
        <?php
        wp_nav_menu([
            'theme_location' => 'category_nav',
            'container'      => false,
            'depth'          => 1,
            'menu_class'     => 'kmg-container flex h-12 items-center gap-7 text-[14px] font-medium text-muted-foreground [&_a:hover]:text-primary [&_.current-menu-item_a]:text-primary',
            'fallback_cb'    => false,
        ]);
        ?>
    </nav>
    <?php endif; ?>
</header>

<div id="mobile-menu-overlay" class="fixed inset-0 z-[99998] hidden bg-ink/50 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

<div id="mobile-menu-drawer" class="fixed inset-y-0 left-0 z-[99999] flex h-[100dvh] w-[90%] max-w-sm -translate-x-full flex-col overflow-y-auto bg-white transition-transform duration-300 ease-out lg:hidden">
    <div class="flex h-[76px] shrink-0 items-center justify-between border-b border-border px-6">
        <?php waklert_the_logo('h-9 w-auto max-w-[180px] object-contain', 'mobile'); ?>
        <button type="button" id="mobile-menu-close" aria-label="<?php echo esc_attr__('Close menu', 'kamagramate'); ?>" class="-mr-2 inline-flex h-11 w-11 items-center justify-center rounded-full text-ink hover:bg-primary-soft">
            <?php echo kmg_glyph('close', 'h-6 w-6'); ?>
        </button>
    </div>

    <nav class="mobile-nav flex-1 px-6 py-2" aria-label="<?php echo esc_attr__('Mobile', 'kamagramate'); ?>">
        <?php
        wp_nav_menu([
            'theme_location' => has_nav_menu('mobile') ? 'mobile' : 'primary',
            'container'      => false,
            'menu_class'     => 'flex flex-col',
            'fallback_cb'    => false,
            'walker'         => new Waklert_Mobile_Nav_Walker(),
        ]);
        ?>
    </nav>

    <div class="shrink-0 space-y-4 p-6">
        <?php if ($phone_text !== '' && $phone_href !== '') : ?>
            <a href="<?php echo esc_attr($phone_href); ?>" class="kmg-btn kmg-btn-primary w-full"><?php echo kmg_glyph('phone'); ?><span><?php echo esc_html($phone_text); ?></span></a>
        <?php endif; ?>

        <?php if ($help['title'] !== '' || $help['text'] !== '') : ?>
        <div class="rounded-3xl bg-primary-softer p-6">
            <?php if ($help['eyebrow'] !== '') : ?><p class="kmg-eyebrow"><?php echo esc_html($help['eyebrow']); ?></p><?php endif; ?>
            <?php if ($help['title'] !== '') : ?><p class="mt-2 font-heading text-xl font-bold text-ink"><?php echo esc_html($help['title']); ?></p><?php endif; ?>
            <?php if ($help['text'] !== '') : ?><p class="mt-2 text-[15px] leading-relaxed text-muted-foreground"><?php echo esc_html($help['text']); ?></p><?php endif; ?>
            <?php if ($help['link'] !== '' && $help['url'] !== '') : ?>
                <a href="<?php echo esc_url($help['url']); ?>" class="kmg-link mt-4"><?php echo esc_html($help['link']); ?><?php echo kmg_glyph('arrow-right'); ?></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
