<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'after_setup_theme', 'bastovan_setup' );

function bastovan_setup() {

    load_theme_textdomain( 'bastovan-tema', BASTOVAN_THEME_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ] );

    add_theme_support( 'custom-logo', [
        'height' => 60,
        'width'  => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'automatic-feed-links' );

    add_image_size( 'bastovan-hero', 1920, 800, true );
    add_image_size( 'bastovan-card', 600, 400, true );
}

add_action( 'init', function() {
    remove_post_type_support( 'usluga', 'editor' );
} );