<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'bastovan_register_taxonomies' );

function bastovan_register_taxonomies() {

    // Tip usluge — vezuje projekte za usluge
    register_taxonomy( 'tip-usluge', [ 'projekat', 'usluga' ], [
        'labels' => [
            'name'              => __( 'Tipovi usluga', 'bastovan' ),
            'singular_name'     => __( 'Tip usluge', 'bastovan' ),
            'search_items'      => __( 'Pretraži tipove', 'bastovan' ),
            'all_items'         => __( 'Svi tipovi', 'bastovan' ),
            'edit_item'         => __( 'Uredi tip', 'bastovan' ),
            'add_new_item'      => __( 'Dodaj novi tip', 'bastovan' ),
            'not_found'         => __( 'Nema pronađenih tipova', 'bastovan' ),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'tip-usluge' ],
    ] );
}