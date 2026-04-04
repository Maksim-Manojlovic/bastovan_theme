<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Disable emojis
 */

add_action( 'init', 'bastovan_disable_emojis' );

function bastovan_disable_emojis() {

    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

}


/**
 * Clean WP head
 */

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );


/**
 * Custom excerpt
 */

add_filter( 'excerpt_length', 'bastovan_excerpt_length' );

function bastovan_excerpt_length() {
    return 25;
}

add_filter( 'excerpt_more', 'bastovan_excerpt_more' );

function bastovan_excerpt_more() {
    return '...';
}