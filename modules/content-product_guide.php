<?php
/**
 * Module: Product Guide
 * "Choosing the Right Armodafinil"
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

// The shop page omits the product comparison section per user request.
if (function_exists('is_shop') && is_shop()) {
    return;
}

$heading = get_sub_field('heading') ?: 'Compare Current Armodafinil Listings';
$desc    = get_sub_field('description') ?: 'Start with the product name and stated strength, then open a listing to review details and pack options.';
$guide_items = get_sub_field('guide_items') ?: [];
$is_buyers_guide = is_page('buyers-guide');

if (!$heading && !$desc && !$guide_items) {
    return;
}
?>

<section class="py-8 md:py-12 bg-gradient-to-b from-white via-primary-softer to-white" data-testid="product-guide">
    <div class="container-site <?= $is_buyers_guide ? 'max-w-6xl' : 'max-w-4xl' ?>">
        
        <div class="text-center mb-10 max-w-3xl mx-auto">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-soft border border-border px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-ink">
                <span class="h-1.5 w-1.5 rounded-full bg-primary-soft0 animate-pulse" aria-hidden="true"></span>
                Product Guide
            </span>
            <h2 class="mt-2.5 font-heading text-2xl sm:text-3xl md:text-4xl font-black text-ink"><?= esc_html($heading) ?></h2>
            <?php if ($desc): ?>
                <p class="mx-auto mt-3 max-w-2xl text-sm sm:text-base leading-relaxed text-muted-foreground"><?= esc_html($desc) ?></p>
            <?php endif; ?>
        </div>
        
        <div class="<?= $is_buyers_guide ? 'grid gap-6 sm:grid-cols-2 lg:grid-cols-3' : 'space-y-6' ?>">
            <?php if (have_rows('guide_items')): ?>
                <?php $guide_idx = 0; while (have_rows('guide_items')): the_row(); $guide_idx++; ?>
                <?php
                    $item_title = get_sub_field('title');
                    $item_description = get_sub_field('description');
                    $item_note = get_sub_field('best_for');
                    $item_initial = $item_title ? mb_substr(wp_strip_all_tags($item_title), 0, 1) : '';
                    $product_link = get_sub_field('product_link');
                ?>
                <article class="group flex h-full flex-col justify-between rounded-2xl border border-border bg-white p-6 sm:p-7 shadow-xs transition-all duration-200 hover:border-border hover:shadow-md">
                    <div>
                        <!-- Header with Avatar & Badge -->
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-soft font-heading text-base font-black text-ink shadow-2xs group-hover:scale-105 transition-transform" aria-hidden="true">
                                <?= esc_html($item_initial ?: sprintf('%02d', $guide_idx)) ?>
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-primary-soft border border-border px-2.5 py-0.5 text-[11px] font-bold text-ink">
                                Genuine Formulation
                            </span>
                        </div>

                        <h3 class="font-heading text-lg sm:text-xl font-black text-ink mb-2"><?= esc_html($item_title) ?></h3>
                        
                        <p class="text-sm leading-relaxed text-muted-foreground mb-4"><?= esc_html($item_description) ?></p>

                        <?php if ($item_note): ?>
                        <div class="mb-4">
                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-primary-soft border border-border px-2.5 py-1 text-xs font-semibold text-primary">
                                <span class="font-bold text-primary">Best For:</span> <?= esc_html($item_note) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($product_link): ?>
                    <div class="pt-4 border-t border-border mt-2">
                        <a href="<?= esc_url($product_link) ?>" class="inline-flex items-center justify-between w-full rounded-xl bg-muted hover:bg-primary hover:text-white px-4 py-2.5 text-xs font-bold text-ink transition-all">
                            <span>View Product Details</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

    </div>
</section>
