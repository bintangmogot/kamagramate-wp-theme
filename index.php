<?php
/**
 * Blog index, archives and search results.
 *
 * Blog page: renders its ACF modules; the post loop is inserted where a
 * blog_grid / posts_grid module sits (or after page_hero, or at the end).
 * Archives / search: term or query header, then the loop.
 */

get_header();

$blog_page_id = is_home() ? (int) get_option('page_for_posts') : 0;
$has_modules  = $blog_page_id && have_rows('modules', $blog_page_id);

if (!function_exists('modmy_render_blog_loop')) {
    function modmy_render_blog_loop() {
        ?>
        <section id="blog-articles" class="kmg-section" aria-label="Articles">
            <div class="kmg-container">
                <?php if (have_posts()) : ?>
                    <div class="grid gap-x-8 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                        <?php while (have_posts()) {
                            the_post();
                            get_template_part('template-parts/post-card', null, ['post' => get_post()]);
                        } ?>
                    </div>
                    <?php
                    $pages = paginate_links([
                        'type'      => 'array',
                        'prev_text' => kmg_glyph('arrow-left'),
                        'next_text' => kmg_glyph('arrow-right'),
                    ]);
                    if ($pages) : ?>
                        <nav class="mt-20 flex flex-wrap justify-center gap-2" aria-label="Pagination">
                            <?php foreach ($pages as $page) : ?>
                                <span class="[&>*]:flex [&>*]:h-12 [&>*]:min-w-12 [&>*]:items-center [&>*]:justify-center [&>*]:rounded-full [&>*]:border [&>*]:border-border [&>*]:px-4 [&>*]:text-[15px] [&>*]:font-semibold [&>a:hover]:border-ink [&>.current]:border-ink [&>.current]:bg-ink [&>.current]:text-white"><?php echo wp_kses_post($page); ?></span>
                            <?php endforeach; ?>
                        </nav>
                    <?php endif; ?>
                <?php else :
                    $empty = trim((string) get_field('label_no_results', 'option'));
                    if ($empty !== '') : ?>
                        <p class="py-16 text-center text-[18px] text-muted-foreground"><?php echo esc_html($empty); ?></p>
                    <?php endif;
                endif; ?>
            </div>
        </section>
        <?php
    }
}
?>
<main id="main">
<?php
if (is_home() && $has_modules) {
    $rendered = false;
    $layouts  = array_column((array) get_field('modules', $blog_page_id), 'acf_fc_layout');
    $has_grid = in_array('posts_grid', $layouts, true) || in_array('blog_grid', $layouts, true);
    while (have_rows('modules', $blog_page_id)) {
        the_row();
        $layout = get_row_layout();
        if ($layout === 'posts_grid' || $layout === 'blog_grid') {
            modmy_render_blog_loop();
            $rendered = true;
            continue;
        }
        get_template_part('modules/content', $layout);
        if ($layout === 'page_hero' && !$has_grid && !$rendered) {
            modmy_render_blog_loop();
            $rendered = true;
        }
    }
    if (!$rendered) {
        modmy_render_blog_loop();
    }
} else {
    if (is_search()) {
        $title = trim(get_field('label_search_title', 'option') . ' ' . get_search_query());
        $intro = '';
    } elseif (is_archive()) {
        $title = wp_strip_all_tags(get_the_archive_title());
        $intro = get_the_archive_description();
    } else {
        $title = $blog_page_id ? get_the_title($blog_page_id) : get_bloginfo('name');
        $intro = '';
    }
    ?>
    <section class="border-b border-border bg-primary-softer" data-testid="blog-archive-hero">
        <div class="kmg-container pb-16 pt-12 md:pb-20 md:pt-16">
            <?php get_template_part('template-parts/breadcrumbs'); ?>
            <h1 class="kmg-display mt-8 max-w-4xl"><?php echo esc_html($title); ?></h1>
            <?php if ($intro) : ?><div class="kmg-lead mt-6 max-w-2xl"><?php echo wp_kses_post($intro); ?></div><?php endif; ?>
        </div>
    </section>
    <?php
    modmy_render_blog_loop();
}
?>
</main>
<?php
get_footer();
