<?php
/**
 * Module: privacy_policy
 * Fields: title, subtitle, badges (badge), sections (title, content).
 */

get_template_part('template-parts/policy-layout', null, [
    'title'    => get_sub_field('title'),
    'intro'    => get_sub_field('subtitle'),
    'badges'   => array_column((array) get_sub_field('badges'), 'badge'),
    'sections' => get_sub_field('sections'),
]);
