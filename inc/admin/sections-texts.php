<?php

if ( ! defined('ABSPATH') ) exit;

// ─── HELPERS ─────────────────────────────────────────────────
function bastovan_txt( string $key, string $fallback ): string {
    static $data = null;
    if ( $data === null ) $data = get_option( 'bastovan_services_texts', [] );
    return esc_html( $data[ $key ] ?? $fallback );
}

function bastovan_txt_raw( string $key, string $fallback ): string {
    static $data = null;
    if ( $data === null ) $data = get_option( 'bastovan_services_texts', [] );
    return $data[ $key ] ?? $fallback;
}
