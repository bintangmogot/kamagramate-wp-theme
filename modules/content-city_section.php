<?php
/**
 * Module: city_section
 * Fields: theme_style (navy | light), tag, title, description,
 * cities_list (city_name, has_checkmark), quick_cities (one city per line,
 * optional trailing check mark).
 */

$cities = [];
$quick  = trim((string) get_sub_field('quick_cities'));
if ($quick !== '') {
    foreach (preg_split('/\r\n|\r|\n/', $quick) as $line) {
        $name = trim(str_replace(["\u{2713}", '[x]'], '', $line));
        if ($name !== '') {
            $cities[] = ['name' => $name, 'check' => $name !== trim($line)];
        }
    }
} elseif (have_rows('cities_list')) {
    while (have_rows('cities_list')) {
        the_row();
        $name = trim(str_replace("\u{2713}", '', (string) get_sub_field('city_name')));
        if ($name !== '') {
            $cities[] = ['name' => $name, 'check' => (bool) get_sub_field('has_checkmark')];
        }
    }
}

$title = trim((string) get_sub_field('title'));
if (!$cities && $title === '') {
    return;
}
$dark = get_sub_field('theme_style') !== 'light';
?>
<section class="kmg-section <?php echo $dark ? 'kmg-section-dark' : 'kmg-section-tint'; ?>" data-testid="city-section">
    <div class="kmg-container grid gap-14 lg:grid-cols-12 lg:items-center lg:gap-20">
        <div class="lg:col-span-5">
            <?php kmg_section_head([
                'eyebrow' => get_sub_field('tag'),
                'title'   => $title,
                'lead'    => get_sub_field('description'),
                'align'   => 'left',
                'dark'    => $dark,
                'class'   => '!mb-0',
            ]); ?>
        </div>

        <?php if ($cities) : ?>
        <ul class="flex flex-wrap gap-3 lg:col-span-7">
            <?php foreach ($cities as $city) : ?>
                <li class="inline-flex items-center gap-2 rounded-full border px-5 py-3 text-[16px] font-semibold <?php echo $dark ? 'border-white/15 text-white' : 'border-border bg-white text-ink'; ?>">
                    <?php if ($city['check']) : ?><?php echo kmg_glyph('check', 'h-4 w-4 ' . ($dark ? 'text-primary-light' : 'text-primary')); ?><?php endif; ?>
                    <?php echo esc_html($city['name']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</section>
