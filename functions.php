<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme constants
 */

define( 'BASTOVAN_VERSION', '1.1.0' );
define( 'BASTOVAN_THEME_DIR', get_template_directory() );
define( 'BASTOVAN_THEME_URI', get_template_directory_uri() );


/**
 * Load theme modules
 */

require_once BASTOVAN_THEME_DIR . '/inc/core/loader.php';



// BRISANJE KES MEMORIJE


add_action('init', function(){
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_bastovan_section_%'");
});


// Briši section cache kada se tema ažurira
add_action( 'switch_theme', function() {
    global $wpdb;
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_bastovan_section_%'" );
} );