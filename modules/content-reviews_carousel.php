<?php
/**
 * Module: reviews_carousel
 * Fields: tag, title, description, reviews (relationship to review CPT),
 * button_text, button_url. Review CPT fields: rating, name, reviewer_meta.
 */

$reviews = array_filter((array) get_sub_field('reviews'));
if (!$reviews) {
    return;
}
$track_id = 'kmg-reviews-' . wp_unique_id();
?>
<section class="kmg-section kmg-section-tint overflow-hidden" data-testid="reviews-carousel">
    <div class="kmg-container">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <?php kmg_section_head([
                'eyebrow' => get_sub_field('tag'),
                'title'   => get_sub_field('title'),
                'lead'    => get_sub_field('description'),
                'align'   => 'left',
                'class'   => '!mb-0',
            ]); ?>
            <div class="flex shrink-0 gap-2">
                <button type="button" data-kmg-scroll="<?php echo esc_attr($track_id); ?>" data-dir="-1" class="flex h-12 w-12 items-center justify-center rounded-full border border-border bg-white text-ink hover:border-ink" aria-label="<?php echo esc_attr__('Previous', 'kamagramate'); ?>"><?php echo kmg_glyph('arrow-left'); ?></button>
                <button type="button" data-kmg-scroll="<?php echo esc_attr($track_id); ?>" data-dir="1" class="flex h-12 w-12 items-center justify-center rounded-full border border-border bg-white text-ink hover:border-ink" aria-label="<?php echo esc_attr__('Next', 'kamagramate'); ?>"><?php echo kmg_glyph('arrow-right'); ?></button>
            </div>
        </div>

        <div id="<?php echo esc_attr($track_id); ?>" class="kmg-scroll-x mt-14 pb-2">
            <?php foreach ($reviews as $r) :
                $post_id = is_object($r) ? $r->ID : (int) $r;
                $body    = trim(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));
                $name    = trim((string) get_field('name', $post_id)) ?: get_the_title($post_id);
                $meta    = trim((string) get_field('reviewer_meta', $post_id));
                $rating  = (int) (get_field('rating', $post_id) ?: 5);
                ?>
                <figure class="kmg-card flex w-[85%] flex-col sm:w-[420px]">
                    <?php echo kmg_stars($rating); ?>
                    <p class="mt-5 font-heading text-xl font-bold tracking-tight text-ink"><?php echo esc_html(get_the_title($post_id)); ?></p>
                    <?php if ($body !== '') : ?>
                        <blockquote class="mt-3 text-[17px] leading-relaxed text-muted-foreground"><?php echo esc_html(wp_trim_words($body, 48, '...')); ?></blockquote>
                    <?php endif; ?>
                    <figcaption class="mt-auto pt-8 text-[15px]">
                        <span class="font-semibold text-ink"><?php echo esc_html($name); ?></span>
                        <?php if ($meta !== '') : ?><span class="block text-muted-foreground"><?php echo esc_html($meta); ?></span><?php endif; ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>

        <?php if (get_sub_field('button_text') && get_sub_field('button_url')) : ?>
            <div class="mt-14 flex justify-center"><?php kmg_button((string) get_sub_field('button_text'), (string) get_sub_field('button_url'), 'outline'); ?></div>
        <?php endif; ?>
    </div>
</section>
