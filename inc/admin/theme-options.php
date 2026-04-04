<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'bastovan_add_theme_options' );

function bastovan_add_theme_options() {

    add_options_page(
        __( 'Bastovan podešavanja', 'bastovan' ),
        __( 'Bastovan', 'bastovan' ),
        'manage_options',
        'bastovan-options',
        'bastovan_theme_options_page'
    );
}

function bastovan_theme_options_page() {

    if ( isset( $_POST['bastovan_save_options'] ) ) {

        check_admin_referer( 'bastovan_options_nonce' );

        $fields = [ 'telefon', 'email', 'adresa', 'instagram', 'facebook' ];

        foreach ( $fields as $field ) {
            update_option(
                'bastovan_' . $field,
                sanitize_text_field( $_POST[ 'bastovan_' . $field ] ?? '' )
            );
        }

        echo '<div class="notice notice-success"><p>Podešavanja sačuvana.</p></div>';
    }

    $telefon   = get_option( 'bastovan_telefon', '' );
    $email     = get_option( 'bastovan_email', '' );
    $adresa    = get_option( 'bastovan_adresa', '' );
    $instagram = get_option( 'bastovan_instagram', '' );
    $facebook  = get_option( 'bastovan_facebook', '' );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Bastovan podešavanja', 'bastovan' ); ?></h1>
        <form method="post">
            <?php wp_nonce_field( 'bastovan_options_nonce' ); ?>
            <table class="form-table">
                <tr>
                    <th><label for="bastovan_telefon">Telefon</label></th>
                    <td><input type="text" id="bastovan_telefon" name="bastovan_telefon"
                               value="<?php echo esc_attr( $telefon ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="bastovan_email">Email</label></th>
                    <td><input type="email" id="bastovan_email" name="bastovan_email"
                               value="<?php echo esc_attr( $email ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="bastovan_adresa">Adresa</label></th>
                    <td><input type="text" id="bastovan_adresa" name="bastovan_adresa"
                               value="<?php echo esc_attr( $adresa ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="bastovan_instagram">Instagram URL</label></th>
                    <td><input type="url" id="bastovan_instagram" name="bastovan_instagram"
                               value="<?php echo esc_attr( $instagram ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="bastovan_facebook">Facebook URL</label></th>
                    <td><input type="url" id="bastovan_facebook" name="bastovan_facebook"
                               value="<?php echo esc_attr( $facebook ); ?>" class="regular-text"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="bastovan_save_options"
                       class="button-primary"
                       value="<?php _e( 'Sačuvaj podešavanja', 'bastovan' ); ?>">
            </p>
        </form>
    </div>
    <?php
}