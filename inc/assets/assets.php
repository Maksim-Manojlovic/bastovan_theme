<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', 'bastovan_enqueue_assets' );

function bastovan_enqueue_assets() {

    /**
     * 1. BASE
     */
    wp_enqueue_style(
        'bastovan-base',
        BASTOVAN_THEME_URI . '/assets/css/base.css',
        [],
        BASTOVAN_VERSION
    );

    /**
     * 2. HEADER
     */
    wp_enqueue_style(
        'bastovan-header',
        BASTOVAN_THEME_URI . '/sections/header/header.css',
        [ 'bastovan-base' ],
        BASTOVAN_VERSION
    );

    /**
     * 3. SECTIONS
     */
    $sections = [
        'hero',
        'intro',
        'services',
        'gallery',
        'calculator',
        'reviews',
        'contact',
        'galerija',
        'usluge',
        'footer',
    ];

    foreach ( $sections as $section ) {
        $path = BASTOVAN_THEME_DIR . "/sections/{$section}/{$section}.css";
        if ( file_exists( $path ) ) {
            wp_enqueue_style(
                "section-{$section}",
                BASTOVAN_THEME_URI . "/sections/{$section}/{$section}.css",
                [ 'bastovan-base' ],
                BASTOVAN_VERSION
            );
        }
    }

    /**
     * 4. GLOBAL style.css
     */
    wp_enqueue_style(
        'bastovan-style',
        get_stylesheet_uri(),
        [ 'bastovan-base' ],
        BASTOVAN_VERSION
    );

    /**
     * JS
     */
    wp_enqueue_script(
        'bastovan-main',
        BASTOVAN_THEME_URI . '/assets/js/main.js',
        [],
        BASTOVAN_VERSION,
        true
    );

    wp_enqueue_script(
        'three-js',
        'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
        [],
        'r128',
        true
    );

    wp_enqueue_script(
        'bastovan-hero-mower',
        BASTOVAN_THEME_URI . '/assets/js/hero-mower.js',
        [ 'three-js' ],
        BASTOVAN_VERSION,
        true
    );

    wp_enqueue_script(
        'bastovan-gallery',
        BASTOVAN_THEME_URI . '/assets/js/gallery.js',
        [ 'bastovan-main' ],
        BASTOVAN_VERSION,
        true
    );

    wp_enqueue_script(
        'bastovan-animations',
        BASTOVAN_THEME_URI . '/assets/js/animations.js',
        [ 'bastovan-main' ],
        BASTOVAN_VERSION,
        true
    );

    // Kalkulator — samo na stranicama koje ga sadrže
    if ( has_shortcode( get_post()->post_content ?? '', 'bastovan_kalkulator' ) ) {
        wp_enqueue_script(
            'bastovan-calculator',
            BASTOVAN_THEME_URI . '/assets/js/calculator.js',
            [ 'bastovan-main' ],
            BASTOVAN_VERSION,
            true
        );
    }

    wp_localize_script( 'bastovan-main', 'bastovanData', [
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'bastovan-nonce' ),
        'siteUrl'  => get_site_url(),
        'themeUrl' => BASTOVAN_THEME_URI,
    ] );
}


/**
 * CSS loading optimization — preload critical, defer non-critical
 */
add_filter( 'style_loader_tag', 'bastovan_optimize_css_loading', 10, 2 );

function bastovan_optimize_css_loading( $tag, $handle ) {

    // Critical CSS — preload hint so browser fetches them ASAP
    $preload = [ 'bastovan-base', 'bastovan-header', 'section-hero' ];

    // Non-critical CSS — defer so they don't block rendering
    $defer = [
        'section-services',
        'section-intro',
        'section-footer',
        'section-gallery',
        'section-calculator',
        'section-contact',
        'section-reviews',
        'section-galerija',
        'section-usluge',
        'bastovan-style',
    ];

    if ( in_array( $handle, $preload, true ) ) {
        preg_match( "/href='([^']+)'/", $tag, $m );
        if ( ! empty( $m[1] ) ) {
            return "<link rel='preload' as='style' href='{$m[1]}' />\n" . $tag;
        }
    }

    if ( in_array( $handle, $defer, true ) ) {
        $deferred = str_replace( " media='all'", " media='print' onload=\"this.media='all'\"", $tag );
        return $deferred . "<noscript>{$tag}</noscript>\n";
    }

    return $tag;
}


/**
 * Editor styles
 */
add_action( 'after_setup_theme', 'bastovan_editor_styles' );

function bastovan_editor_styles() {
    add_editor_style( 'assets/css/editor.css' );
}


/**
 * Admin assets
 */
add_action( 'admin_enqueue_scripts', function( $hook ) {

    wp_enqueue_script(
        'bastovan-sections-builder',
        BASTOVAN_THEME_URI . '/assets/js/admin/sections-builder.js',
        [ 'jquery' ],
        BASTOVAN_VERSION,
        true
    );

    wp_enqueue_script(
        'bastovan-admin-gallery',
        BASTOVAN_THEME_URI . '/assets/js/admin/admin-gallery.js',
        [ 'jquery', 'jquery-ui-sortable' ],
        BASTOVAN_VERSION,
        true
    );

    wp_enqueue_style(
        'bastovan-admin-sections',
        BASTOVAN_THEME_URI . '/assets/css/admin/admin-sections.css',
        [],
        BASTOVAN_VERSION
    );

    // wp.media — samo na edit stranicama projekta i usluge
    if ( in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) {
        $screen = get_current_screen();
        if ( $screen && in_array( $screen->post_type, [ 'projekat', 'usluga' ] ) ) {
            wp_enqueue_media();
        }
    }

} );
