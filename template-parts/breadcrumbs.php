<?php
/**
 * Breadcrumb trail: Home (site name) / ancestors / current title.
 * Labels come from page titles, product categories and the site name.
 */

$crumbs = [['label' => get_bloginfo('name'), 'url' => home_url('/')]];

if (function_exists('is_product') && is_product()) {
    $shop_id = wc_get_page_id('shop');
    if ($shop_id > 0) {
        $crumbs[] = ['label' => get_the_title($shop_id), 'url' => get_permalink($shop_id)];
    }
    $terms = get_the_terms(get_the_ID(), 'product_cat');
    if ($terms && !is_wp_error($terms)) {
        $crumbs[] = ['label' => $terms[0]->name, 'url' => get_term_link($terms[0])];
    }
} elseif (function_exists('is_product_category') && is_product_category()) {
    $shop_id = wc_get_page_id('shop');
    if ($shop_id > 0) {
        $crumbs[] = ['label' => get_the_title($shop_id), 'url' => get_permalink($shop_id)];
    }
} elseif (is_singular('post')) {
    $blog_id = (int) get_option('page_for_posts');
    if ($blog_id) {
        $crumbs[] = ['label' => get_the_title($blog_id), 'url' => get_permalink($blog_id)];
    }
} elseif (is_page()) {
    foreach (array_reverse(get_post_ancestors(get_the_ID())) as $ancestor) {
        $crumbs[] = ['label' => get_the_title($ancestor), 'url' => get_permalink($ancestor)];
    }
}

if (is_singular()) {
    $current = get_the_title();
} elseif (is_tax() || is_category() || is_tag()) {
    $current = single_term_title('', false);
} elseif (function_exists('is_shop') && is_shop()) {
    $current = get_the_title(wc_get_page_id('shop'));
} else {
    $current = wp_get_document_title();
}
?>
<nav aria-label="Breadcrumb" class="text-[14px] text-muted-foreground">
    <ol class="flex flex-wrap items-center gap-2">
        <?php foreach ($crumbs as $crumb) : ?>
            <li class="flex items-center gap-2">
                <a href="<?php echo esc_url($crumb['url']); ?>" class="hover:text-primary"><?php echo esc_html($crumb['label']); ?></a>
                <span aria-hidden="true" class="text-border">/</span>
            </li>
        <?php endforeach; ?>
        <li aria-current="page" class="font-medium text-ink"><?php echo esc_html($current); ?></li>
    </ol>
</nav>
