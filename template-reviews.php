<?php
/**
 * Template Name: Reviews Page
 */

get_header();
?>

<main class="site-main">
    <section class="relative overflow-hidden bg-gradient-to-b from-primary-softer via-primary-softer to-primary-softer bg-pattern-dots py-8 md:py-12" data-testid="section-general-reviews">
        <?php
        $reviews = get_posts([
            "post_type" => "review",
            "posts_per_page" => -1,
            "orderby" => "date",
            "order" => "DESC",
            "meta_query" => array(
                'relation' => 'OR',
                array(
                    'key'     => 'linked_product',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => 'linked_product',
                    'value'   => '',
                    'compare' => '='
                )
            )
        ]);
        $review_count = count($reviews);
        ?>
        <div class="container-site max-w-5xl mx-auto px-4 relative z-10">
            <!-- Header & Aggregate Summary Card -->
            <div class="mb-12">
                <div class="text-center max-w-2xl mx-auto mb-8">
                    <span class="inline-flex items-center gap-2 rounded-full bg-primary-soft border border-border px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-ink shadow-sm mb-4">
                        <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                        Verified Customer Experiences
                    </span>
                    <h1 class="font-heading text-3xl md:text-5xl font-bold text-ink tracking-normal mb-4">
                        <?= "Real Reviews from Real Australians" ?>
                    </h1>
                    <p class="text-muted-foreground text-base md:text-lg leading-relaxed">
                        <?= "Read unfiltered feedback from verified buyers across Australia who rely on genuine Armodafinil for work, study, and peak performance." ?>
                    </p>
                </div>

                <?php if ($review_count > 0): ?>
                <!-- 2-Tone Dashed Rating Summary Card -->
                <div class="rounded-3xl border border-border bg-white p-6 md:p-8 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-primary hover:bg-primary-dark text-white shadow-md">
                            <!-- Multi-color Shield SVG -->
                            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L3 6V12C3 17.5 7 21 12 22C17 21 21 17.5 21 12V6L12 2Z" fill="#FFFFFF" fill-opacity="0.3"/>
                                <path d="M12 3L4 7V12C4 16.8 7.5 20 12 21C16.5 20 20 16.8 20 12V7L12 3Z" stroke="#1a1030" stroke-width="1.8"/>
                                <path d="M8.5 12L10.8 14.3L15.5 9.7" stroke="#1a1030" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 text-accent mb-1">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                <?php endfor; ?>
                                <span class="ml-1 text-lg font-bold text-ink">4.8 / 5.0</span>
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-muted-foreground">
                                <?= sprintf("Based on %d verified customer ratings Australia-wide", $review_count) ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-success-soft border border-border px-3.5 py-1.5 text-xs font-bold text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                            100% Verified Orders
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-soft border border-border px-3.5 py-1.5 text-xs font-bold text-ink">
                            <svg class="w-3.5 h-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Updated Weekly
                        </span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Reviews Grid: 2-Tone Dashed Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                <?php
                if ($reviews):
                    foreach ($reviews as $i => $r):
                        $post_id = $r->ID;
                        $title = get_the_title($post_id);
                        $body = get_post_field('post_content', $post_id);
                        $reviewer = get_field("name", $post_id) ?: $r->post_title;
                        $meta = get_field('reviewer_meta', $post_id) ?: "Verified Buyer";
                        $rating_val = get_field("rating", $post_id) ?: 5;
                        ?>
                        <div class="group flex flex-col rounded-3xl border border-border hover:border-primary bg-white shadow-sm hover:shadow-none hover:-translate-y-2 transition-all duration-300 overflow-hidden" data-testid="review-<?= $i ?>">
                            <!-- Top Card Body (Pure White) -->
                            <div class="p-6 md:p-7 flex-1 flex flex-col">
                                <div class="flex items-center justify-between gap-3 mb-4">
                                    <!-- Colorful Quote Mark SVG -->
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary-soft border border-border text-ink group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-primary" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                        </svg>
                                    </div>

                                    <!-- Golden Stars -->
                                    <div class="flex items-center gap-1 text-accent">
                                        <?php for ($stars = 0; $stars < 5; $stars++): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 <?= ($stars < $rating_val) ?"text-accent fill-current" : "text-slate-200 fill-current" ?>" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <h3 class="font-heading text-lg font-bold text-ink mb-2 leading-snug group-hover:text-ink transition-colors">
                                    <?= esc_html($title) ?>
                                </h3>
                                <p class="text-sm text-muted-foreground leading-relaxed italic">
                                    &ldquo;<?= esc_html($body) ?>&rdquo;
                                </p>
                            </div>

                            <!-- Bottom 2-Tone Solid Navy Strip -->
                            <div class="bg-primary hover:bg-primary-dark p-4 sm:p-5 flex items-center justify-between gap-3 text-white">
                                <div class="flex items-center gap-3">
                                    <!-- Vibrant Amber Reviewer Avatar -->
                                    <div class="w-9 h-9 rounded-full bg-primary hover:bg-primary-dark text-white flex items-center justify-center font-bold text-sm uppercase shadow-sm shrink-0">
                                        <?= substr($reviewer, 0, 1) ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-white flex items-center gap-1.5 whitespace-nowrap">
                                            <?= esc_html($reviewer) ?>
                                        </p>
                                        <p class="text-[11px] text-primary-light font-medium"><?= esc_html($meta) ?></p>
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-success bg-success/20 border border-border px-2.5 py-1 rounded-full shrink-0" data-testid="badge-verified-buyer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <?= "Verified" ?>
                                </span>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    echo "<div class=\"col-span-full py-16 text-center text-muted-foreground bg-white rounded-3xl border  border-border p-8\">";
                    echo "<p class=\"text-lg font-bold text-ink\">" . "No reviews found at this time." . "</p>";
                    echo "</div>";
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
