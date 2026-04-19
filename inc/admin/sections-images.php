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

    // Ukloni dupli "Bastovan" submenu koji WP automatski dodaje
    remove_submenu_page( 'bastovan-panel', 'bastovan-panel' );
} );

// ─── ENQUEUE MEDIA ───────────────────────────────────────────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( strpos( $hook, 'bastovan-services-images' ) === false ) return;
    wp_enqueue_media();
    wp_add_inline_script( 'jquery', bastovan_media_picker_js() );
} );

function bastovan_media_picker_js(): string {
    return <<<JS
jQuery(function($){
    $(document).on('click', '.bastovan-pick', function(e){
        e.preventDefault();
        var btn    = $(this);
        var wrap   = btn.closest('.bastovan-img-field');
        var frame  = wp.media({ title: 'Izaberi sliku', multiple: false, library: { type: 'image' } });
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

    $keys = [
        'services_img_bg', 'services_img_featured',
        'services_img_kosenje_i', 'services_img_kosenje_d',
        'services_img_orez_i',    'services_img_orez_d',
        'services_img_korov_i',   'services_img_korov_d',
        'services_img_pranje_i',  'services_img_pranje_d',
    ];

    $data = [];
    foreach ( $keys as $key ) {
        $data[ $key ] = absint( $_POST[ $key ] ?? 0 );
    }

    update_option( 'bastovan_services_images', $data );

    wp_redirect( admin_url( 'admin.php?page=bastovan-services-images&saved=1' ) );
    exit;
} );

// ─── PAGE ────────────────────────────────────────────────────
function bastovan_services_images_page(): void {
    $data = get_option( 'bastovan_services_images', [] );

    $fields = [
        'services_img_bg'        => 'Pozadina sekcije',
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
            <div class="notice notice-success is-dismissible"><p>Slike sačuvane.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <?php wp_nonce_field( 'bastovan_services_images_nonce' ); ?>
            <input type="hidden" name="action" value="bastovan_save_services_images">

            <table class="form-table" role="presentation">
                <?php foreach ( $fields as $key => $label ) :
                    $id      = absint( $data[ $key ] ?? 0 );
                    $src     = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
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
                <input type="submit" class="button-primary" value="Sačuvaj slike">
            </p>
        </form>
    </div>
    <?php
}
