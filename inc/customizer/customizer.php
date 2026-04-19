<?php

if ( ! defined('ABSPATH') ) exit;

add_action( 'customize_register', function( WP_Customize_Manager $wp_customize ) {

    $wp_customize->add_panel( 'bastovan_sekcije', [
        'title'    => 'Slike sekcija',
        'priority' => 30,
    ] );

    // ─── SERVICES SECTION ────────────────────────────────────
    $wp_customize->add_section( 'bastovan_services', [
        'title' => 'Usluge — slike',
        'panel' => 'bastovan_sekcije',
    ] );

    $images = [
        'services_img_bg'         => 'Pozadina sekcije',
        'services_img_featured'   => 'Featured kartica — fotografija',
        'services_img_kosenje_i'  => 'Košenje — ikonica',
        'services_img_kosenje_d'  => 'Košenje — ilustracija',
        'services_img_orez_i'     => 'Orezivanje — ikonica',
        'services_img_orez_d'     => 'Orezivanje — ilustracija',
        'services_img_korov_i'    => 'Korov — ikonica',
        'services_img_korov_d'    => 'Korov — ilustracija',
        'services_img_pranje_i'   => 'Pranje — ikonica',
        'services_img_pranje_d'   => 'Pranje — ilustracija',
    ];

    foreach ( $images as $key => $label ) {
        $wp_customize->add_setting( $key, [
            'default'           => '',
            'sanitize_callback' => 'absint',
        ] );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $key, [
            'label'     => $label,
            'section'   => 'bastovan_services',
            'mime_type' => 'image',
        ] ) );
    }

} );
