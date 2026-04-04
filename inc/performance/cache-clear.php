<?php

if ( ! defined('ABSPATH') ) exit;

add_action('save_post', function(){

    global $wpdb;

    $wpdb->query(
        "DELETE FROM $wpdb->options 
         WHERE option_name LIKE '_transient_bastovan_section_%'
         OR option_name LIKE '_transient_timeout_bastovan_section_%'"
    );

});