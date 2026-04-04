<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'after_setup_theme', 'bastovan_register_menus' );

function bastovan_register_menus() {

    register_nav_menus([
        'primary-menu' => __( 'Glavni meni', 'bastovan-tema' ),
        'footer-menu'  => __( 'Footer meni', 'bastovan-tema' ),
        'legal-menu'   => __( 'Pravni linkovi', 'bastovan-tema' ),
    ]);

}