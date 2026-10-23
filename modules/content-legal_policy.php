<?php
/**
 * Module: legal_policy
 * Fields: title, eyebrow, intro, updated, sections (title, content),
 * contact_email, contact_heading, contact_description.
 */

get_template_part('template-parts/policy-layout', null, [
    'eyebrow'  => get_sub_field('eyebrow'),
    'title'    => get_sub_field('title'),
    'intro'    => get_sub_field('intro'),
    'updated'  => get_sub_field('updated'),
    'sections' => get_sub_field('sections'),
    'contact'  => [
        'heading'     => get_sub_field('contact_heading'),
        'description' => get_sub_field('contact_description'),
        'email'       => sanitize_email((string) get_sub_field('contact_email')),
    ],
]);
