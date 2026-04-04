<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Contact helper
 */

function bastovan_get_contact( $type = 'telefon' ) {

    $allowed = [ 'telefon', 'email', 'adresa', 'instagram', 'facebook' ];

    if ( ! in_array( $type, $allowed ) ) {
        return '';
    }

    return get_option( 'bastovan_' . $type, '' );

}


/**
 * SVG icon helper
 */

function bastovan_icon( $name, $class = '' ) {

    $icons = [

    'phone' => '<svg viewBox="0 0 24 24" width="20" height="20"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V20a1 1 0 01-1 1C9.61 21 3 14.39 3 6a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.45.57 3.58a1 1 0 01-.24 1.01l-2.21 2.2z"/></svg>',

    'menu' => '<svg viewBox="0 0 24 24" width="24" height="24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>',

    'close' => '<svg viewBox="0 0 24 24" width="24" height="24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>',

    'mail' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',

    'message' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',

    'arrow-right' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',

    'check' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 11 4 16"/></svg>',

    'location' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',

];

    if ( ! isset($icons[$name]) ) {
        return '';
    }

    $class_attr = $class ? ' class="'.esc_attr($class).'"' : '';

    return '<span'.$class_attr.'>'.$icons[$name].'</span>';

}


add_filter( 'render_block_core/template-part', 'bastovan_override_template_parts', 10, 2 );

function bastovan_override_template_parts( $block_content, $parsed_block ) {

    if ( isset( $parsed_block['attrs']['slug'] ) ) {

        if ( $parsed_block['attrs']['slug'] === 'header' ) {
            ob_start();
            get_template_part( 'header' );
            return ob_get_clean();
        }

        if ( $parsed_block['attrs']['slug'] === 'footer' ) {
            ob_start();
            get_template_part( 'footer' );
            return ob_get_clean();
        }
    }

    return $block_content;
}