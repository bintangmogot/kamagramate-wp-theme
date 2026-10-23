<?php
/**
 * Module: blog_section (also used by blog_grid)
 * Fields: eyebrow, title, subtitle, selected_posts, posts_count, show_view_all,
 * button_text, button_url. Without selected posts the latest posts are shown.
 */

$selected = array_filter((array) get_sub_field('selected_posts'));
$count    = (int) get_sub_field('posts_count') ?: 3;

if ($selected) {
    $articles = array_filter(array_map(static function ($p) {
        return is_numeric($p) ? get_post((int) $p) : $p;
    }, $selected));
} else {
    $articles = get_posts([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'ignore_sticky_posts' => true,
    ]);
}
if (!$articles) {
    return;
}

$button_text = get_sub_field('show_view_all') ? trim((string) get_sub_field('button_text')) : '';
$button_url  = trim((string) get_sub_field('button_url'));
?>
<section class="kmg-section" data-testid="blog-section">
    <div class="kmg-container">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <?php kmg_section_head([
                'eyebrow' => get_sub_field('eyebrow'),
                'title'   => get_sub_field('title'),
                'lead'    => get_sub_field('subtitle'),
                'align'   => 'left',
                'class'   => '!mb-0',
            ]); ?>
            <?php if ($button_text !== '' && $button_url !== '') : ?>
                <a href="<?php echo esc_url($button_url); ?>" class="kmg-link shrink-0 text-[17px]"><?php echo esc_html($button_text); ?><?php echo kmg_glyph('arrow-right'); ?></a>
            <?php endif; ?>
        </div>

        <div class="mt-14 grid gap-x-8 gap-y-14 md:grid-cols-2 lg:grid-cols-3 md:mt-16">
            <?php foreach ($articles as $article) {
                get_template_part('template-parts/post-card', null, ['post' => $article]);
            } ?>
        </div>
    </div>
</section>
