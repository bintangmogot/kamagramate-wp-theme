<?php
/**
 * Single blog post. Posts built from ACF modules render those; otherwise the
 * article layout: header, featured image, intro (ACF "intro" or excerpt), content,
 * then related posts from the same category.
 */

get_header();
?>
<main id="main">
    <?php
    if (have_rows('modules')) {
        while (have_rows('modules')) {
            the_row();
            get_template_part('modules/content', get_row_layout());
        }
    } else {
        while (have_posts()) : the_post();
            $category = modmy_get_post_category(get_the_ID());
            $author   = get_the_author_meta('display_name', (int) get_post_field('post_author', get_the_ID()));
            $words    = str_word_count(wp_strip_all_tags(get_the_content()));
            $reading  = trim((string) get_field('label_reading_time', 'option'));
            $intro    = get_field('intro') ?: (has_excerpt() ? get_the_excerpt() : '');
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="border-b border-border bg-primary-softer" data-testid="single-post-hero">
                    <div class="kmg-container-narrow pb-16 pt-12 md:pb-20 md:pt-16">
                        <?php get_template_part('template-parts/breadcrumbs'); ?>
                        <?php if ($category !== '') : ?><p class="kmg-eyebrow mt-10"><?php echo esc_html($category); ?></p><?php endif; ?>
                        <h1 class="mt-4 font-heading text-[clamp(2.25rem,1.6rem+2.8vw,4rem)] font-extrabold leading-[1.05] tracking-[-0.035em] text-ink"><?php the_title(); ?></h1>
                        <p class="mt-8 flex flex-wrap items-center gap-x-3 gap-y-1 text-[15px] text-muted-foreground">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time>
                            <?php if ($author && strtolower($author) !== 'admin') : ?><span aria-hidden="true">/</span><span><?php echo esc_html($author); ?></span><?php endif; ?>
                            <?php if ($reading !== '') : ?><span aria-hidden="true">/</span><span><?php echo esc_html(max(1, (int) ceil($words / 220)) . ' ' . $reading); ?></span><?php endif; ?>
                        </p>
                    </div>
                </header>

                <div class="kmg-section !pt-12 md:!pt-16">
                    <div class="kmg-container-narrow">
                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="mb-12 overflow-hidden rounded-[2rem] bg-primary-soft"><?php the_post_thumbnail('large', ['class' => 'h-auto w-full object-cover', 'loading' => 'eager']); ?></figure>
                        <?php endif; ?>
                        <?php if (!empty($intro)) : ?>
                            <div class="mb-12 border-l-2 border-primary pl-6 text-[clamp(1.1875rem,1.1rem+0.4vw,1.375rem)] leading-relaxed text-ink"><?php echo wp_kses_post(wpautop($intro)); ?></div>
                        <?php endif; ?>
                        <div class="prose max-w-none text-[18px]"><?php the_content(); ?></div>
                    </div>
                </div>
            </article>

            <?php
            $related_heading = trim((string) get_field('label_related_posts', 'option'));
            $cats = wp_get_post_categories(get_the_ID());
            $related = $related_heading !== '' ? get_posts([
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post__not_in'   => [get_the_ID()],
                'category__in'   => $cats,
                'ignore_sticky_posts' => true,
            ]) : [];
            if ($related) : ?>
                <section class="kmg-section border-t border-border">
                    <div class="kmg-container">
                        <h2 class="kmg-h2 mb-14"><?php echo esc_html($related_heading); ?></h2>
                        <div class="grid gap-x-8 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                            <?php foreach ($related as $rp) { get_template_part('template-parts/post-card', null, ['post' => $rp]); } ?>
                        </div>
                    </div>
                </section>
            <?php endif;
        endwhile;
    }
    ?>
</main>
<?php
get_footer();
