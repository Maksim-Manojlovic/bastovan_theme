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
        'Usluge',
        'Usluge',
        'manage_options',
        'bastovan-services',
        'bastovan_services_page'
    );

    remove_submenu_page( 'bastovan-panel', 'bastovan-panel' );
} );

// ─── ENQUEUE ─────────────────────────────────────────────────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( strpos( $hook, 'bastovan-services' ) === false ) return;
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
add_action( 'admin_post_bastovan_save_services', function () {
    check_admin_referer( 'bastovan_services_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die();

    // Slike
    $img_keys = [
        'services_img_bg', 'services_img_featured',
        'services_img_kosenje_i', 'services_img_kosenje_d',
        'services_img_orez_i',    'services_img_orez_d',
        'services_img_korov_i',   'services_img_korov_d',
        'services_img_pranje_i',  'services_img_pranje_d',
    ];
    $img_data = [];
    foreach ( $img_keys as $key ) {
        $img_data[ $key ] = absint( $_POST[ $key ] ?? 0 );
    }
    $img_data['services_bg_type']  = in_array( $_POST['services_bg_type'] ?? '', [ 'image', 'color' ] )
                                      ? $_POST['services_bg_type'] : 'image';
    $img_data['services_bg_color'] = sanitize_hex_color( $_POST['services_bg_color'] ?? '' ) ?: '';
    update_option( 'bastovan_services_images', $img_data );

    // Tekstovi
    $txt_fields = [
        'eyebrow', 'heading',
        'featured_title', 'featured_desc', 'featured_link_text', 'featured_link_url',
        'kosenje_title', 'kosenje_desc',
        'orez_title',    'orez_desc',
        'korov_title',   'korov_desc',
        'pranje_title',  'pranje_desc',
        'cta_text',
    ];
    $txt_data = [];
    foreach ( $txt_fields as $f ) {
        $txt_data[ $f ] = sanitize_text_field( $_POST[ $f ] ?? '' );
    }
    foreach ( [ 'featured_desc', 'kosenje_desc', 'orez_desc', 'korov_desc', 'pranje_desc', 'heading' ] as $f ) {
        $txt_data[ $f ] = sanitize_textarea_field( $_POST[ $f ] ?? '' );
    }
    update_option( 'bastovan_services_texts', $txt_data );

    wp_redirect( admin_url( 'admin.php?page=bastovan-services&saved=1' ) );
    exit;
} );

// ─── PAGE ────────────────────────────────────────────────────
function bastovan_services_page(): void {
    $img  = get_option( 'bastovan_services_images', [] );
    $txt  = get_option( 'bastovan_services_texts', [] );
    $bg_type  = $img['services_bg_type']  ?? 'image';
    $bg_color = $img['services_bg_color'] ?? '';

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

    $txt_sections = [
        'Zaglavlje sekcije' => [
            [ 'eyebrow', 'Eyebrow tekst',  'text',     'Šta nudimo' ],
            [ 'heading', 'Naslov sekcije', 'textarea', "Usluge uređivanja i\nodržavanja dvorišta" ],
        ],
        'Featured kartica — Planiranje dvorišta' => [
            [ 'featured_title',     'Naslov',        'text',     'Planiranje dvorišta' ],
            [ 'featured_desc',      'Opis',          'textarea', 'Besplatan izlazak na teren. Detaljno planiramo sadnju, košenje i ostale radove kako bismo osigurali najefikasniji rast biljaka i najbolje rezultate.' ],
            [ 'featured_link_text', 'Tekst dugmeta', 'text',     'Zakaži besplatnu procenu' ],
            [ 'featured_link_url',  'URL dugmeta',   'text',     '#kontakt' ],
        ],
        'Kartica 01 — Košenje trave' => [
            [ 'kosenje_title', 'Naslov', 'text',     'Košenje trave' ],
            [ 'kosenje_desc',  'Opis',   'textarea', 'Brzo i pedantno košenje svih površina. Travnjak održavamo urednim, a pokošenu travu sakupljamo i odnosimo.' ],
        ],
        'Kartica 02 — Orezivanje' => [
            [ 'orez_title', 'Naslov', 'text',     'Orezivanje žive ograde i drveća' ],
            [ 'orez_desc',  'Opis',   'textarea', 'Precizno oblikovanje za zdrav rast biljaka i estetski savršeno dvorište.' ],
        ],
        'Kartica 03 — Korov' => [
            [ 'korov_title', 'Naslov', 'text',     'Uklanjanje korova' ],
            [ 'korov_desc',  'Opis',   'textarea', 'Temeljno čišćenje i efikasno suzbijanje neželjenog rastinja iz cvećnjaka, staza i travnjaka.' ],
        ],
        'Kartica 04 — Pranje staza' => [
            [ 'pranje_title', 'Naslov', 'text',     'Pranje i čišćenje staza' ],
            [ 'pranje_desc',  'Opis',   'textarea', 'Pranje pod visokim pritiskom — uklanjamo mahovinu i prljavštinu sa svih tvrdih podloga.' ],
        ],
        'CTA dugme' => [
            [ 'cta_text', 'Tekst dugmeta', 'text', 'Pogledajte sve usluge' ],
        ],
    ];
    ?>
    <div class="wrap">
        <h1>Usluge</h1>

        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Podešavanja sačuvana.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <?php wp_nonce_field( 'bastovan_services_nonce' ); ?>
            <input type="hidden" name="action" value="bastovan_save_services">

            <!-- POZADINA -->
            <h2 style="margin-top:1.5em;padding-bottom:6px;border-bottom:1px solid #ddd;">Pozadina sekcije</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th>Tip pozadine</th>
                    <td>
                        <fieldset style="display:flex;gap:20px;">
                            <label><input type="radio" name="services_bg_type" value="image" <?php checked( $bg_type, 'image' ); ?>> Slika</label>
                            <label><input type="radio" name="services_bg_type" value="color" <?php checked( $bg_type, 'color' ); ?>> Boja</label>
                        </fieldset>
                    </td>
                </tr>
                <tr id="bastovan-bg-image-row">
                    <th><label>Pozadinska slika</label></th>
                    <td><?php
                        $id  = absint( $img['services_img_bg'] ?? 0 );
                        $src = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
                    ?>
                        <div class="bastovan-img-field" style="display:flex;align-items:center;gap:12px;">
                            <input type="hidden" name="services_img_bg" value="<?php echo $id ?: ''; ?>">
                            <div class="bastovan-preview"><?php if ( $src ) : ?><img src="<?php echo esc_url( $src ); ?>" style="max-width:160px;max-height:100px;border-radius:4px;"><?php endif; ?></div>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <button type="button" class="button bastovan-pick">Izaberi sliku</button>
                                <button type="button" class="button bastovan-remove" <?php echo $id ? '' : 'style="display:none"'; ?>>Ukloni</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr id="bastovan-bg-color-row">
                    <th><label for="services_bg_color">Pozadinska boja</label></th>
                    <td>
                        <input type="text" id="services_bg_color" name="services_bg_color"
                               value="<?php echo esc_attr( $bg_color ); ?>"
                               class="bastovan-color-input" data-default-color="#f5f8f4">
                    </td>
                </tr>
            </table>

            <!-- SLIKE KARTICA -->
            <h2 style="margin-top:2em;padding-bottom:6px;border-bottom:1px solid #ddd;">Slike kartica</h2>
            <table class="form-table" role="presentation">
                <?php foreach ( $img_fields as $key => $label ) :
                    $id  = absint( $img[ $key ] ?? 0 );
                    $src = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
                ?>
                <tr>
                    <th><label><?php echo esc_html( $label ); ?></label></th>
                    <td>
                        <div class="bastovan-img-field" style="display:flex;align-items:center;gap:12px;">
                            <input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo $id ?: ''; ?>">
                            <div class="bastovan-preview"><?php if ( $src ) : ?><img src="<?php echo esc_url( $src ); ?>" style="max-width:160px;max-height:100px;border-radius:4px;"><?php endif; ?></div>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <button type="button" class="button bastovan-pick">Izaberi sliku</button>
                                <button type="button" class="button bastovan-remove" <?php echo $id ? '' : 'style="display:none"'; ?>>Ukloni</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <!-- TEKSTOVI -->
            <?php foreach ( $txt_sections as $heading => $fields ) : ?>
            <h2 style="margin-top:2em;padding-bottom:6px;border-bottom:1px solid #ddd;"><?php echo esc_html( $heading ); ?></h2>
            <table class="form-table" role="presentation">
                <?php foreach ( $fields as [ $key, $label, $type, $fallback ] ) :
                    $val = $txt[ $key ] ?? $fallback;
                ?>
                <tr>
                    <th style="width:180px;"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
                    <td>
                        <?php if ( $type === 'textarea' ) : ?>
                            <textarea id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" rows="3" class="large-text"><?php echo esc_textarea( $val ); ?></textarea>
                        <?php else : ?>
                            <input type="text" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" class="large-text">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endforeach; ?>

            <p class="submit" style="margin-top:2em;">
                <input type="submit" class="button-primary" value="Sačuvaj">
            </p>
        </form>
    </div>
    <?php
}
