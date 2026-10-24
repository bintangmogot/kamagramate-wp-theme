<?php
/**
 * Module: reviews (Reviews page)
 * Fields: eyebrow, heading, description, summary_text (%d = count), verified_label, empty_text.
 * Lists published general reviews (review CPT without a linked product).
 */

$query = new WP_Query([
    'post_type'      => 'review',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => [
        'relation' => 'OR',
        ['key' => 'linked_product', 'compare' => 'NOT EXISTS'],
        ['key' => 'linked_product', 'value' => '', 'compare' => '='],
    ],
]);

$total = (int) $query->found_posts;
$stars = 0;
foreach ($query->posts as $p) {
    $stars += (int) get_post_meta($p->ID, 'rating', true) ?: 5;
}
$average  = $total ? round($stars / $total, 1) : 0;
$summary  = trim((string) get_sub_field('summary_text'));
$verified = trim((string) get_sub_field('verified_label'));
$heading  = trim((string) get_sub_field('heading')) ?: get_the_title();
?>
<section class="border-b border-border bg-primary-softer" data-testid="section-general-reviews">
    <div class="kmg-container pb-16 pt-12 md:pb-20 md:pt-16">
        <?php get_template_part('template-parts/breadcrumbs'); ?>
        <div class="mt-8 grid gap-10 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-8">
                <?php if (get_sub_field('eyebrow')) : ?><p class="kmg-eyebrow"><?php echo esc_html(get_sub_field('eyebrow')); ?></p><?php endif; ?>
                <h1 class="kmg-display mt-4"><?php echo esc_html($heading); ?></h1>
                <?php if (get_sub_field('description')) : ?><p class="kmg-lead mt-6 max-w-2xl"><?php echo esc_html(get_sub_field('description')); ?></p><?php endif; ?>
            </div>
            <?php if ($total) : ?>
                <div class="lg:col-span-4 lg:text-right">
                    <p class="font-heading text-[4.5rem] font-bold leading-none tracking-normal text-ink"><?php echo esc_html(number_format($average, 1)); ?></p>
                    <div class="mt-3 lg:flex lg:justify-end"><?php echo kmg_stars((int) round($average), 'h-6 w-6'); ?></div>
                    <?php if ($summary !== '') : ?><p class="mt-2 text-[15px] text-muted-foreground"><?php echo esc_html(sprintf($summary, $total)); ?></p><?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="kmg-section">
    <div class="kmg-container">
        <?php if ($query->have_posts()) : ?>
            <div class="columns-1 gap-6 md:columns-2 lg:columns-3 [&>*]:mb-6">
                <?php while ($query->have_posts()) : $query->the_post();
                    $rating = (int) get_field('rating') ?: 5;
                    $name   = trim((string) get_field('name')) ?: get_the_title();
                    $meta   = trim((string) get_field('reviewer_meta'));
                    ?>
                    <figure class="kmg-card break-inside-avoid">
                        <div class="flex items-center justify-between gap-3">
                            <?php echo kmg_stars($rating); ?>
                            <?php if ($verified !== '') : ?><span class="inline-flex items-center gap-1 text-[13px] font-semibold text-success" data-testid="badge-verified-buyer"><?php echo kmg_glyph('check', 'h-3.5 w-3.5'); ?><?php echo esc_html($verified); ?></span><?php endif; ?>
                        </div>
                        <p class="mt-5 font-heading text-xl font-bold tracking-normal text-ink"><?php the_title(); ?></p>
                        <blockquote class="mt-3 text-[17px] leading-relaxed text-muted-foreground"><?php echo esc_html(wp_strip_all_tags(get_the_content())); ?></blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-border pt-5 text-[15px]">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-soft font-heading font-bold uppercase text-primary"><?php echo esc_html(mb_substr($name, 0, 1)); ?></span>
                            <span>
                                <span class="block font-semibold text-ink"><?php echo esc_html($name); ?></span>
                                <span class="block text-[14px] text-muted-foreground"><?php echo esc_html(trim($meta . ($meta !== '' ? ' / ' : '') . get_the_date('M Y'))); ?></span>
                            </span>
                        </figcaption>
                    </figure>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php elseif (get_sub_field('empty_text')) : ?>
            <p class="py-16 text-center text-[18px] text-muted-foreground"><?php echo esc_html(get_sub_field('empty_text')); ?></p>
        <?php endif; ?>
    </div>
</section>
