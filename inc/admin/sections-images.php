<?php

if ( ! defined('ABSPATH') ) exit;

// ─── MENU ────────────────────────────────────────────────────
add_action( 'admin_menu', function () {

    add_menu_page(
        'Bastovan',
        'Bastovan',
        'manage_options',
        'bastovan-panel',
        '__return_null',
        'dashicons-admin-appearance',
        25
    );

    add_submenu_page(
        'bastovan-panel',
        'Usluge — slike',
        'Usluge',
        'manage_options',
        'bastovan-services-images',
        'bastovan_services_images_page'
    );

    remove_submenu_page( 'bastovan-panel', 'bastovan-panel' );
} );

// ─── ENQUEUE ─────────────────────────────────────────────────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( strpos( $hook, 'bastovan-services-images' ) === false ) return;
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_add_inline_script( 'wp-color-picker', bastovan_admin_js() );
} );

function bastovan_admin_js(): string {
    return <<<JS
jQuery(function($){

    // Color picker init
    $('.bastovan-color-input').wpColorPicker();

    // Background type toggle
    function toggleBgType() {
        var type = $('input[name="services_bg_type"]:checked').val();
        if ( type === 'image' ) {
            $('#bastovan-bg-image-row').show();
            $('#bastovan-bg-color-row').hide();
        } else {
            $('#bastovan-bg-image-row').hide();
            $('#bastovan-bg-color-row').show();
        }
    }
    $('input[name="services_bg_type"]').on('change', toggleBgType);
    toggleBgType();

    // Media picker
    $(document).on('click', '.bastovan-pick', function(e){
        e.preventDefault();
        var wrap  = $(this).closest('.bastovan-img-field');
        var frame = wp.media({ title: 'Izaberi sliku', multiple: false, library: { type: 'image' } });
        frame.on('select', function(){
            var att = frame.state().get('selection').first().toJSON();
            wrap.find('input[type=hidden]').val(att.id);
            wrap.find('.bastovan-preview').html('<img src="'+att.url+'" style="max-width:160px;max-height:100px;border-radius:4px;">');
            wrap.find('.bastovan-remove').show();
        });
        frame.open();
    });

    $(document).on('click', '.bastovan-remove', function(e){
        e.preventDefault();
        var wrap = $(this).closest('.bastovan-img-field');
        wrap.find('input[type=hidden]').val('');
        wrap.find('.bastovan-preview').html('');
        $(this).hide();
    });
});
JS;
}

// ─── SAVE ────────────────────────────────────────────────────
add_action( 'admin_post_bastovan_save_services_images', function () {
    check_admin_referer( 'bastovan_services_images_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die();

    $img_keys = [
        'services_img_bg', 'services_img_featured',
        'services_img_kosenje_i', 'services_img_kosenje_d',
        'services_img_orez_i',    'services_img_orez_d',
        'services_img_korov_i',   'services_img_korov_d',
        'services_img_pranje_i',  'services_img_pranje_d',
    ];

    $data = [];
    foreach ( $img_keys as $key ) {
        $data[ $key ] = absint( $_POST[ $key ] ?? 0 );
    }

    $data['services_bg_type']  = in_array( $_POST['services_bg_type'] ?? '', [ 'image', 'color' ] )
                                  ? $_POST['services_bg_type']
                                  : 'image';
    $data['services_bg_color'] = sanitize_hex_color( $_POST['services_bg_color'] ?? '' ) ?: '';

    update_option( 'bastovan_services_images', $data );

    wp_redirect( admin_url( 'admin.php?page=bastovan-services-images&saved=1' ) );
    exit;
} );

// ─── PAGE ────────────────────────────────────────────────────
function bastovan_services_images_page(): void {
    $data    = get_option( 'bastovan_services_images', [] );
    $bg_type = $data['services_bg_type']  ?? 'image';
    $bg_color = $data['services_bg_color'] ?? '';

    $img_fields = [
        'services_img_featured'  => 'Featured kartica — fotografija',
        'services_img_kosenje_i' => 'Košenje — ikonica',
        'services_img_kosenje_d' => 'Košenje — ilustracija',
        'services_img_orez_i'    => 'Orezivanje — ikonica',
        'services_img_orez_d'    => 'Orezivanje — ilustracija',
        'services_img_korov_i'   => 'Korov — ikonica',
        'services_img_korov_d'   => 'Korov — ilustracija',
        'services_img_pranje_i'  => 'Pranje — ikonica',
        'services_img_pranje_d'  => 'Pranje — ilustracija',
    ];
    ?>
    <div class="wrap">
        <h1>Usluge — slike</h1>

        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Podešavanja sačuvana.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <?php wp_nonce_field( 'bastovan_services_images_nonce' ); ?>
            <input type="hidden" name="action" value="bastovan_save_services_images">

            <table class="form-table" role="presentation">

                <!-- POZADINA SEKCIJE — tip -->
                <tr>
                    <th>Pozadina sekcije</th>
                    <td>
                        <fieldset style="display:flex;gap:20px;">
                            <label>
                                <input type="radio" name="services_bg_type" value="image" <?php checked( $bg_type, 'image' ); ?>>
                                Slika
                            </label>
                            <label>
                                <input type="radio" name="services_bg_type" value="color" <?php checked( $bg_type, 'color' ); ?>>
                                Boja
                            </label>
                        </fieldset>
                    </td>
                </tr>

                <!-- POZADINA — slika -->
                <tr id="bastovan-bg-image-row">
                    <th><label>Pozadinska slika</label></th>
                    <td><?php
                        $id  = absint( $data['services_img_bg'] ?? 0 );
                        $src = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
                    ?>
                        <div class="bastovan-img-field" style="display:flex;align-items:center;gap:12px;">
                            <input type="hidden" name="services_img_bg" value="<?php echo $id ?: ''; ?>">
                            <div class="bastovan-preview">
                                <?php if ( $src ) : ?>
                                    <img src="<?php echo esc_url( $src ); ?>" style="max-width:160px;max-height:100px;border-radius:4px;">
                                <?php endif; ?>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <button type="button" class="button bastovan-pick">Izaberi sliku</button>
                                <button type="button" class="button bastovan-remove" <?php echo $id ? '' : 'style="display:none"'; ?>>Ukloni</button>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- POZADINA — boja -->
                <tr id="bastovan-bg-color-row">
                    <th><label for="services_bg_color">Pozadinska boja</label></th>
                    <td>
                        <input
                            type="text"
                            id="services_bg_color"
                            name="services_bg_color"
                            value="<?php echo esc_attr( $bg_color ); ?>"
                            class="bastovan-color-input"
                            data-default-color="#f5f8f4"
                        >
                    </td>
                </tr>

                <!-- OSTALE SLIKE -->
                <?php foreach ( $img_fields as $key => $label ) :
                    $id  = absint( $data[ $key ] ?? 0 );
                    $src = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
                ?>
                <tr>
                    <th><label><?php echo esc_html( $label ); ?></label></th>
                    <td>
                        <div class="bastovan-img-field" style="display:flex;align-items:center;gap:12px;">
                            <input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo $id ?: ''; ?>">
                            <div class="bastovan-preview">
                                <?php if ( $src ) : ?>
                                    <img src="<?php echo esc_url( $src ); ?>" style="max-width:160px;max-height:100px;border-radius:4px;">
                                <?php endif; ?>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <button type="button" class="button bastovan-pick">Izaberi sliku</button>
                                <button type="button" class="button bastovan-remove" <?php echo $id ? '' : 'style="display:none"'; ?>>Ukloni</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>

            </table>

            <p class="submit">
                <input type="submit" class="button-primary" value="Sačuvaj">
            </p>
        </form>
    </div>
    <?php
}

