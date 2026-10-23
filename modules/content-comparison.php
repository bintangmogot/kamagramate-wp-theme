<?php
/**
 * Module: comparison
 * Fields: tag, heading, description, column labels (col_feature_label,
 * col_1_label, col_1_badge, col_2_label, col_3_label), footnote,
 * rows (feature, modafinil, coffee, energy). Column 1 is highlighted.
 */

$rows = [];
if (have_rows('rows')) {
    while (have_rows('rows')) {
        the_row();
        $feature = trim((string) get_sub_field('feature'));
        if ($feature !== '') {
            $rows[] = [$feature, (string) get_sub_field('modafinil'), (string) get_sub_field('coffee'), (string) get_sub_field('energy')];
        }
    }
}
if (!$rows) {
    return;
}

$labels = [
    trim((string) get_sub_field('col_feature_label')),
    trim((string) get_sub_field('col_1_label')),
    trim((string) get_sub_field('col_2_label')),
    trim((string) get_sub_field('col_3_label')),
];
$badge    = trim((string) get_sub_field('col_1_badge'));
$footnote = trim((string) get_sub_field('footnote'));
$has_col  = [true, true, $labels[2] !== '' || array_filter(array_column($rows, 2)), $labels[3] !== '' || array_filter(array_column($rows, 3))];
?>
<section class="kmg-section" data-testid="comparison">
    <div class="kmg-container">
        <?php kmg_section_head([
            'eyebrow' => get_sub_field('tag'),
            'title'   => get_sub_field('heading'),
            'lead'    => get_sub_field('description'),
        ]); ?>

        <div class="-mx-5 overflow-x-auto px-5 md:mx-0 md:px-0">
            <table class="w-full min-w-[640px] border-separate border-spacing-0 text-left">
                <thead>
                    <tr>
                        <th scope="col" class="w-[34%] pb-6 pr-6 align-bottom text-[13px] font-semibold uppercase tracking-[0.14em] text-muted-foreground"><?php echo esc_html($labels[0]); ?></th>
                        <th scope="col" class="w-[26%] rounded-t-3xl bg-primary px-6 pb-6 pt-7 text-center align-bottom text-white">
                            <?php if ($badge !== '') : ?><span class="mb-2 block text-[12px] font-semibold uppercase tracking-[0.14em] text-white/70"><?php echo esc_html($badge); ?></span><?php endif; ?>
                            <span class="font-heading text-xl font-bold text-white"><?php echo esc_html($labels[1]); ?></span>
                        </th>
                        <?php foreach ([2, 3] as $c) : if (!$has_col[$c]) { continue; } ?>
                            <th scope="col" class="px-6 pb-6 text-center align-bottom font-heading text-lg font-bold text-ink"><?php echo esc_html($labels[$c]); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="text-[16px]">
                    <?php foreach ($rows as $i => $row) : $last = $i === count($rows) - 1; ?>
                        <tr>
                            <th scope="row" class="border-t border-border py-6 pr-6 font-semibold text-ink"><?php echo esc_html($row[0]); ?></th>
                            <td class="<?php echo $last ? 'rounded-b-3xl ' : ''; ?>border-t border-white/15 bg-primary px-6 py-6 text-center font-semibold text-white"><?php echo esc_html($row[1]); ?></td>
                            <?php foreach ([2, 3] as $c) : if (!$has_col[$c]) { continue; } ?>
                                <td class="border-t border-border px-6 py-6 text-center text-muted-foreground"><?php echo esc_html($row[$c]); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($footnote !== '') : ?>
            <p class="mx-auto mt-10 max-w-2xl text-center text-[15px] text-muted-foreground"><?php echo esc_html($footnote); ?></p>
        <?php endif; ?>
    </div>
</section>
