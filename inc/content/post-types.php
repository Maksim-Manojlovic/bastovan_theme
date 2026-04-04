<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'bastovan_register_post_types' );

function bastovan_register_post_types() {

    register_post_type( 'projekat', [
        'labels' => [
            'name'               => __( 'Projekti', 'bastovan' ),
            'singular_name'      => __( 'Projekat', 'bastovan' ),
            'add_new'            => __( 'Dodaj novi', 'bastovan' ),
            'add_new_item'       => __( 'Dodaj novi projekat', 'bastovan' ),
            'edit_item'          => __( 'Uredi projekat', 'bastovan' ),
            'new_item'           => __( 'Novi projekat', 'bastovan' ),
            'view_item'          => __( 'Pogledaj projekat', 'bastovan' ),
            'search_items'       => __( 'Pretraži projekte', 'bastovan' ),
            'not_found'          => __( 'Nema pronađenih projekata', 'bastovan' ),
            'not_found_in_trash' => __( 'Nema projekata u korpi', 'bastovan' ),
        ],
        'public'        => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-images-alt2',
        'menu_position' => 5,
        'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'has_archive'   => false,
        'rewrite'       => [ 'slug' => 'projekti' ],
        'show_in_rest'  => true,
    ] );

    register_post_type( 'usluga', [
    'labels' => [
        'name'               => __( 'Usluge', 'bastovan' ),
        'singular_name'      => __( 'Usluga', 'bastovan' ),
        'add_new'            => __( 'Dodaj novu', 'bastovan' ),
        'add_new_item'       => __( 'Dodaj novu uslugu', 'bastovan' ),
        'edit_item'          => __( 'Uredi uslugu', 'bastovan' ),
        'new_item'           => __( 'Nova usluga', 'bastovan' ),
        'view_item'          => __( 'Pogledaj uslugu', 'bastovan' ),
        'search_items'       => __( 'Pretraži usluge', 'bastovan' ),
        'not_found'          => __( 'Nema pronađenih usluga', 'bastovan' ),
        'not_found_in_trash' => __( 'Nema usluga u korpi', 'bastovan' ),
    ],
    'public'        => true,
    'show_in_menu'  => true,
    'menu_icon'     => 'dashicons-hammer',
    'menu_position' => 6,
    'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
    'has_archive'   => false,
    'rewrite'       => [ 'slug' => 'usluge' ],
    'show_in_rest'  => true,
] );

}