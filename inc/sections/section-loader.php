<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bastovan_section( $name, $args = [], $no_cache = false ) {

    $base_path = get_template_directory();

    $php = $base_path . "/sections/{$name}/{$name}.php";

    if ( ! file_exists( $php ) ) {
        return;
    }

    if ( ! $no_cache ) {

        $cache_key = 'bastovan_section_' . $name;

        if ( ! empty( $args ) ) {
            $cache_key .= '_' . md5( json_encode( $args ) );
        }

        $cached = get_transient( $cache_key );

        if ( $cached !== false ) {
            echo $cached;
            return;
        }
    }

    ob_start();

    if ( ! empty( $args ) ) {
        extract( $args );
    }

    include $php;

    $output = ob_get_clean();

    if ( ! $no_cache ) {
        set_transient( $cache_key, $output, HOUR_IN_SECONDS );
    }

    echo $output;
}

function bastovan_sections( $sections = [] ) {

    if ( empty( $sections ) ) return;

    foreach ( $sections as $section ) {
        if ( ! empty( $section ) ) {
            bastovan_section( $section );
        }
    }
}