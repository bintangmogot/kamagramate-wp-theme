<?php
/**
 * Module: category_grid
 * Fields: eyebrow, title, description, categories (product_cat IDs), show_count, count_suffix.
 * Card image: category ACF "top_image", else the WooCommerce category thumbnail.
 */

$term_ids = array_filter(array_map('intval', (array) get_sub_field('categories')));
if (!$term_ids) {
    return;
}
$terms = get_terms(['taxonomy' => 'product_cat', 'include' => $term_ids, 'orderby' => 'include', 'hide_empty' => false]);
if (is_wp_error($terms) || !$terms) {
    return;
}
$show_count = (bool) get_sub_field('show_count');
$suffix     = trim((string) get_sub_field('count_suffix'));
?>
<section class="kmg-section" data-testid="category-grid">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('eyebrow'),
            'title'   => get_sub_field('title'),
            'lead'    => get_sub_field('description'),
        ]); ?>

        <div class="grid grid-cols-2 gap-4 md:gap-6 lg:grid-cols-3">
            <?php foreach ($terms as $term) :
                $image = get_field('top_image', 'product_cat_' . $term->term_id) ?: get_term_meta($term->term_id, 'thumbnail_id', true);
                $img   = kmg_image($image, 'medium_large', ['class' => 'h-full w-full object-contain p-6 transition-transform duration-500 ease-out group-hover:scale-105', 'loading' => 'lazy', 'alt' => '']);
                ?>
                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="group flex flex-col overflow-hidden rounded-[1.75rem] bg-primary-softer p-3 transition-colors hover:bg-primary-soft">
                    <span class="block aspect-[4/3] overflow-hidden rounded-[1.25rem] bg-white"><?php echo $img; ?></span>
                    <span class="flex items-center justify-between gap-3 px-3 pb-3 pt-5">
                        <span>
                            <span class="block font-heading text-[1.25rem] font-bold tracking-tight text-ink md:text-[1.5rem]"><?php echo esc_html($term->name); ?></span>
                            <?php if ($show_count) : ?>
                                <span class="mt-1 block text-[14px] text-muted-foreground"><?php echo esc_html(trim($term->count . ' ' . $suffix)); ?></span>
                            <?php endif; ?>
                        </span>
                        <span class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white text-ink transition-colors group-hover:bg-primary group-hover:text-white sm:flex"><?php echo kmg_glyph('arrow-up-right', 'h-5 w-5'); ?></span>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
