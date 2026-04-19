<?php

if ( ! defined('ABSPATH') ) exit;

// ─── SUBMENU ─────────────────────────────────────────────────
add_action( 'admin_menu', function () {
    add_submenu_page(
        'bastovan-panel',
        'Usluge — tekstovi',
        'Usluge tekstovi',
        'manage_options',
        'bastovan-services-texts',
        'bastovan_services_texts_page'
    );
} );

// ─── SAVE ────────────────────────────────────────────────────
add_action( 'admin_post_bastovan_save_services_texts', function () {
    check_admin_referer( 'bastovan_services_texts_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die();

    $fields = [
        // Header
        'eyebrow', 'heading',
        // Featured
        'featured_title', 'featured_desc', 'featured_link_text', 'featured_link_url',
        // Kartice
        'kosenje_title', 'kosenje_desc',
        'orez_title',    'orez_desc',
        'korov_title',   'korov_desc',
        'pranje_title',  'pranje_desc',
        // CTA
        'cta_text',
    ];

    $data = [];
    foreach ( $fields as $f ) {
        $data[ $f ] = sanitize_text_field( $_POST[ $f ] ?? '' );
    }

    // Opisi mogu imati više redova — sanitize textarea
    foreach ( [ 'featured_desc', 'kosenje_desc', 'orez_desc', 'korov_desc', 'pranje_desc', 'heading' ] as $f ) {
        $data[ $f ] = sanitize_textarea_field( $_POST[ $f ] ?? '' );
    }

    update_option( 'bastovan_services_texts', $data );

    wp_redirect( admin_url( 'admin.php?page=bastovan-services-texts&saved=1' ) );
    exit;
} );

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

// ─── PAGE ────────────────────────────────────────────────────
function bastovan_services_texts_page(): void {
    $d = get_option( 'bastovan_services_texts', [] );

    $t = fn( string $key, string $fallback ) => esc_attr( $d[ $key ] ?? $fallback );

    $sections = [
        'Zaglavlje sekcije' => [
            [ 'eyebrow', 'Eyebrow tekst',  'text',     'Šta nudimo' ],
            [ 'heading', 'Naslov sekcije', 'textarea', "Usluge uređivanja i\nodržavanja dvorišta" ],
        ],
        'Featured kartica — Planiranje dvorišta' => [
            [ 'featured_title',     'Naslov',       'text',     'Planiranje dvorišta' ],
            [ 'featured_desc',      'Opis',         'textarea', 'Besplatan izlazak na teren. Detaljno planiramo sadnju, košenje i ostale radove kako bismo osigurali najefikasniji rast biljaka i najbolje rezultate.' ],
            [ 'featured_link_text', 'Tekst dugmeta','text',     'Zakaži besplatnu procenu' ],
            [ 'featured_link_url',  'URL dugmeta',  'text',     '#kontakt' ],
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
        <h1>Usluge — tekstovi</h1>

        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Tekstovi sačuvani.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <?php wp_nonce_field( 'bastovan_services_texts_nonce' ); ?>
            <input type="hidden" name="action" value="bastovan_save_services_texts">

            <?php foreach ( $sections as $heading => $fields ) : ?>
                <h2 style="margin-top:2em;padding-bottom:6px;border-bottom:1px solid #ddd;"><?php echo esc_html( $heading ); ?></h2>
                <table class="form-table" role="presentation">
                    <?php foreach ( $fields as [ $key, $label, $type, $fallback ] ) :
                        $val = $d[ $key ] ?? $fallback;
                    ?>
                    <tr>
                        <th style="width:180px;"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
                        <td>
                            <?php if ( $type === 'textarea' ) : ?>
                                <textarea
                                    id="<?php echo esc_attr( $key ); ?>"
                                    name="<?php echo esc_attr( $key ); ?>"
                                    rows="3"
                                    class="large-text"
                                ><?php echo esc_textarea( $val ); ?></textarea>
                            <?php else : ?>
                                <input
                                    type="text"
                                    id="<?php echo esc_attr( $key ); ?>"
                                    name="<?php echo esc_attr( $key ); ?>"
                                    value="<?php echo esc_attr( $val ); ?>"
                                    class="large-text"
                                >
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>

            <p class="submit" style="margin-top:2em;">
                <input type="submit" class="button-primary" value="Sačuvaj tekstove">
            </p>
        </form>
    </div>
    <?php
}
