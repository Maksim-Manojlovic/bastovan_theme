<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Kalkulator wrapper shortcode
 */

add_shortcode( 'bastovan_kalkulator', 'bastovan_kalkulator_shortcode' );

function bastovan_kalkulator_shortcode( $atts ) {

    $atts = shortcode_atts([
        'naslov' => 'Izracunaj cenu',
        'podnaslov' => 'Unesite podatke i dobijte okvirnu cenu'
    ], $atts );

    ob_start();
    ?>

    <div class="calculator-wrapper">

        <?php if ( $atts['naslov'] ) : ?>
            <h3 class="calculator-wrapper__title">
                <?php echo esc_html( $atts['naslov'] ); ?>
            </h3>
        <?php endif; ?>

        <?php if ( $atts['podnaslov'] ) : ?>
            <p class="calculator-wrapper__subtitle">
                <?php echo esc_html( $atts['podnaslov'] ); ?>
            </p>
        <?php endif; ?>

        <?php echo do_shortcode('[bastovanstvo_kalkulator]'); ?>

    </div>

    <?php

    return ob_get_clean();

}

/**
 * Button shortcode
 */

add_shortcode( 'bastovan_btn', 'bastovan_btn_shortcode' );

function bastovan_btn_shortcode( $atts ) {

    $atts = shortcode_atts([
        'url' => '#',
        'text' => 'Saznaj vise',
        'stil' => 'primary',
        'size' => '',
        'nova_kartica' => 'ne'
    ], $atts );

    $target = ( $atts['nova_kartica'] === 'da' ) ? ' target="_blank" rel="noopener"' : '';

    $size_class = $atts['size']
        ? ' btn--' . esc_attr($atts['size'])
        : '';

    return sprintf(
        '<a href="%s" class="btn btn--%s%s"%s>%s</a>',
        esc_url($atts['url']),
        esc_attr($atts['stil']),
        $size_class,
        $target,
        esc_html($atts['text'])
    );

}