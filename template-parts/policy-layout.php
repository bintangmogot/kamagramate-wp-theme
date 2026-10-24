<?php
/**
 * Shared long-form layout for policy, terms and legal pages.
 *
 * $args: eyebrow, title, intro, updated, badges (string[]), sections ([title, content]),
 * contact (heading, description, button_label, button_url, email).
 * Labels for "On this page" / "Last updated" come from Theme Settings -> Interface Labels.
 */

$title    = trim((string) ($args['title'] ?? '')) ?: get_the_title();
$sections = array_values(array_filter((array) ($args['sections'] ?? []), static function ($s) {
    return trim((string) ($s['title'] ?? '')) !== '' || trim((string) ($s['content'] ?? '')) !== '';
}));
$badges   = array_values(array_filter(array_map('trim', (array) ($args['badges'] ?? []))));
$contact  = (array) ($args['contact'] ?? []);
$toc_label     = trim((string) get_field('label_on_this_page', 'option'));
$updated_label = trim((string) get_field('label_last_updated', 'option'));
$updated       = trim((string) ($args['updated'] ?? ''));
$is_post       = is_singular('post');
$reading_label = trim((string) get_field('label_reading_time', 'option'));

$ids = [];
foreach ($sections as $i => $section) {
    $ids[$i] = 'section-' . ($i + 1) . '-' . sanitize_title($section['title'] ?? '');
}
?>
<section class="border-b border-border bg-primary-softer">
    <div class="kmg-container pb-16 pt-12 md:pb-20 md:pt-16">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <div class="mt-8 max-w-4xl">
            <?php if (!empty($args['eyebrow'])) : ?><p class="kmg-eyebrow"><?php echo esc_html($args['eyebrow']); ?></p><?php endif; ?>
            <h1 class="kmg-display mt-4"><?php echo esc_html($title); ?></h1>
            <?php if (!empty($args['intro'])) : ?><p class="kmg-lead mt-6 max-w-2xl"><?php echo esc_html($args['intro']); ?></p><?php endif; ?>
            <?php if ($is_post) :
                $category = modmy_get_post_category(get_the_ID());
                $words    = str_word_count(wp_strip_all_tags(implode(' ', array_column($sections, 'content'))));
                ?>
                <p class="mt-8 flex flex-wrap items-center gap-x-3 gap-y-1 text-[16px] text-muted-foreground">
                    <?php if ($category !== '') : ?><span class="font-semibold text-primary"><?php echo esc_html($category); ?></span><span aria-hidden="true">/</span><?php endif; ?>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time>
                    <?php if ($reading_label !== '') : ?><span aria-hidden="true">/</span><span><?php echo esc_html(max(1, (int) ceil($words / 220)) . ' ' . $reading_label); ?></span><?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if ($badges || $updated !== '') : ?>
                <div class="mt-8 flex flex-wrap items-center gap-2">
                    <?php foreach ($badges as $badge) : ?><span class="kmg-chip kmg-chip-outline"><?php echo esc_html($badge); ?></span><?php endforeach; ?>
                    <?php if ($updated !== '') : ?><span class="text-[14px] text-muted-foreground"><?php echo esc_html(trim($updated_label . ' ' . $updated)); ?></span><?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($sections) : ?>
<section class="kmg-section">
    <div class="kmg-container grid gap-14 lg:grid-cols-12 lg:gap-20">
        <aside class="hidden lg:col-span-4 lg:block">
            <div class="sticky top-36">
                <?php if ($toc_label !== '') : ?><p class="kmg-eyebrow"><?php echo esc_html($toc_label); ?></p><?php endif; ?>
                <ol class="mt-5 space-y-1 border-l border-border" data-kmg-toc>
                    <?php foreach ($sections as $i => $section) : if (empty($section['title'])) { continue; } ?>
                        <li><a href="#<?php echo esc_attr($ids[$i]); ?>" class="-ml-px flex gap-3 rounded-r-lg border-l-2 border-transparent py-2 pl-5 pr-3 text-[16px] text-muted-foreground transition-colors hover:border-primary/40 hover:text-ink aria-[current=true]:border-primary aria-[current=true]:bg-primary-softer aria-[current=true]:font-semibold aria-[current=true]:text-primary">
                            <span class="kmg-num text-[13px]"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span><?php echo esc_html($section['title']); ?>
                        </a></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </aside>

        <div class="min-w-0 lg:col-span-8">
            <?php if ($is_post && has_post_thumbnail()) : ?>
                <figure class="mb-12 overflow-hidden rounded-[2rem] bg-primary-soft">
                    <?php the_post_thumbnail('large', ['class' => 'h-auto w-full object-cover', 'loading' => 'eager', 'fetchpriority' => 'high']); ?>
                </figure>
            <?php endif; ?>
            <?php foreach ($sections as $i => $section) : ?>
                <article id="<?php echo esc_attr($ids[$i]); ?>" class="scroll-mt-36 border-t border-border py-12 first:border-t-0 first:pt-0">
                    <?php if (!empty($section['title'])) : ?>
                        <p class="kmg-num text-[15px]"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></p>
                        <h2 class="mt-2 font-heading text-[clamp(1.625rem,1.4rem+0.8vw,2.125rem)] font-bold leading-tight tracking-normal text-ink"><?php echo esc_html($section['title']); ?></h2>
                    <?php endif; ?>
                    <div class="prose mt-6 max-w-none text-[18px]"><?php echo wp_kses_post(wpautop((string) ($section['content'] ?? ''))); ?></div>
                </article>
            <?php endforeach; ?>

            <?php if (!empty($contact['heading'])) : ?>
                <div class="mt-8 rounded-[2rem] bg-primary-softer p-8 md:p-12">
                    <h2 class="kmg-h3"><?php echo esc_html($contact['heading']); ?></h2>
                    <?php if (!empty($contact['description'])) : ?><p class="kmg-body mt-3 max-w-xl"><?php echo esc_html($contact['description']); ?></p><?php endif; ?>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <?php kmg_button((string) ($contact['button_label'] ?? ''), (string) ($contact['button_url'] ?? ''), 'primary'); ?>
                        <?php if (!empty($contact['email'])) : ?>
                            <a href="mailto:<?php echo esc_attr($contact['email']); ?>" class="kmg-btn kmg-btn-outline max-w-full whitespace-normal py-3 text-left [overflow-wrap:anywhere]"><?php echo kmg_glyph('mail'); ?><?php echo esc_html($contact['email']); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
