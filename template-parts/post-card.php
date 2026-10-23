<?php
/**
 * Blog post card. Expects $args['post'] (WP_Post).
 */

$post_obj = $args['post'] ?? null;
if (!$post_obj instanceof WP_Post) {
    return;
}
$post_id    = $post_obj->ID;
$permalink  = get_permalink($post_id);
$categories = array_filter(get_the_category($post_id), static function ($c) {
    return $c->slug !== 'uncategorized';
});
$category   = $categories ? reset($categories)->name : '';
$excerpt    = has_excerpt($post_id) ? get_the_excerpt($post_id) : wp_trim_words(wp_strip_all_tags(strip_shortcodes($post_obj->post_content)), 24, '...');
?>
<article class="group flex flex-col">
    <a href="<?php echo esc_url($permalink); ?>" class="block aspect-[16/10] overflow-hidden rounded-[1.75rem] bg-primary-soft">
        <?php if (has_post_thumbnail($post_id)) : ?>
            <?php echo get_the_post_thumbnail($post_id, 'large', ['class' => 'h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105', 'loading' => 'lazy']); ?>
        <?php else : ?>
            <span class="flex h-full w-full items-center justify-center text-primary"><?php echo waklert_render_card_icon(['icon_preset' => 'medical_rx_clarity', 'size_class' => 'h-12 w-12']); ?></span>
        <?php endif; ?>
    </a>
    <div class="flex flex-1 flex-col pt-6">
        <p class="flex items-center gap-3 text-[14px] text-muted-foreground">
            <?php if ($category !== '') : ?><span class="font-semibold text-primary"><?php echo esc_html($category); ?></span><span aria-hidden="true">/</span><?php endif; ?>
            <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('j M Y', $post_id)); ?></time>
        </p>
        <h3 class="mt-3 font-heading text-[1.5rem] font-bold leading-tight tracking-tight text-ink">
            <a href="<?php echo esc_url($permalink); ?>" class="hover:text-primary"><?php echo esc_html(get_the_title($post_id)); ?></a>
        </h3>
        <?php if ($excerpt !== '') : ?>
            <p class="mt-3 line-clamp-3 text-[16px] leading-relaxed text-muted-foreground"><?php echo esc_html($excerpt); ?></p>
        <?php endif; ?>
    </div>
</article>
