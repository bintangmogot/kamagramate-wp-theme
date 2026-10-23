<?php
/**
 * Module: terms_of_service
 * Fields: title, subtitle, badges (badge), sections (title, content),
 * contact_heading, contact_description, contact_btn_label, whatsapp_number, hide_contact_callout.
 */

$contact = [];
if (!get_sub_field('hide_contact_callout')) {
    $contact = [
        'heading'      => get_sub_field('contact_heading'),
        'description'  => get_sub_field('contact_description'),
        'button_label' => get_sub_field('contact_btn_label'),
        'button_url'   => kmg_chat_url((string) get_sub_field('whatsapp_number')),
    ];
}

get_template_part('template-parts/policy-layout', null, [
    'title'    => get_sub_field('title'),
    'intro'    => get_sub_field('subtitle'),
    'badges'   => array_column((array) get_sub_field('badges'), 'badge'),
    'sections' => get_sub_field('sections'),
    'contact'  => $contact,
]);
