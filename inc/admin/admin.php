<?php

if ( ! defined('ABSPATH') ) exit;

add_action('admin_enqueue_scripts','bastovan_admin_assets');

function bastovan_admin_assets($hook){

    // učitaj samo na page editoru
    if($hook !== 'post.php' && $hook !== 'post-new.php'){
        return;
    }

    wp_enqueue_script(
        'sortable',
        'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js',
        [],
        '1.15',
        true
    );

    wp_enqueue_script(
        'bastovan-admin-sections',
        get_template_directory_uri() . '/assets/js/admin-sections.js',
        ['sortable'],
        BASTOVAN_VERSION,
        true
    );

    wp_enqueue_style(
        'bastovan-admin-sections',
        get_template_directory_uri() . '/assets/css/admin/admin-sections.css',
        [],
        BASTOVAN_VERSION
    );
    wp_enqueue_script(
    'bastovan-admin-gallery',
    get_template_directory_uri() . '/assets/js/admin-gallery.js',
    [ 'jquery', 'media-upload', 'jquery-ui-sortable' ],
    BASTOVAN_VERSION,
    true
);

}