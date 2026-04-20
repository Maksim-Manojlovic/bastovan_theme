<?php

if ( ! defined('ABSPATH') ) exit;

// ─── SUBMENU ─────────────────────────────────────────────────
add_action( 'admin_menu', function () {
    add_submenu_page(
        'bastovan-panel',
        'FAQ',
        'FAQ',
        'manage_options',
        'bastovan-faq',
        'bastovan_faq_page'
    );
} );

// ─── ENQUEUE ─────────────────────────────────────────────────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( strpos( $hook, 'bastovan-faq' ) === false ) return;
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_add_inline_script( 'wp-color-picker', bastovan_admin_js() );
} );

// ─── SAVE ────────────────────────────────────────────────────
add_action( 'admin_post_bastovan_save_faq', function () {
    check_admin_referer( 'bastovan_faq_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die();

    // Slike
    update_option( 'bastovan_faq_images', [
        'faq_img_bg' => absint( $_POST['faq_img_bg'] ?? 0 ),
    ] );

    // Tekstovi
    $txt = [];
    foreach ( [ 'eyebrow', 'lead', 'cta_text', 'cta_btn', 'cta_url' ] as $f ) {
        $txt[ $f ] = sanitize_text_field( $_POST[ $f ] ?? '' );
    }
    $txt['heading'] = sanitize_textarea_field( $_POST['heading'] ?? '' );
    for ( $i = 1; $i <= 10; $i++ ) {
        $txt[ "q{$i}_pitanje" ] = sanitize_text_field( $_POST[ "q{$i}_pitanje" ] ?? '' );
        $txt[ "q{$i}_odgovor" ] = sanitize_textarea_field( $_POST[ "q{$i}_odgovor" ] ?? '' );
    }
    update_option( 'bastovan_faq_texts', $txt );

    wp_redirect( admin_url( 'admin.php?page=bastovan-faq&saved=1' ) );
    exit;
} );

// ─── DEFAULTS ────────────────────────────────────────────────
function bastovan_faq_defaults(): array {
    return [
        'eyebrow' => 'Česta pitanja',
        'heading' => "Sve što vas zanima o\nuređivanju dvorišta",
        'lead'    => 'Odgovori na najčešća pitanja naših klijenata u Beogradu.',
        'cta_text' => 'Niste pronašli odgovor? Kontaktirajte nas direktno.',
        'cta_btn'  => 'Pošaljite upit →',
        'cta_url'  => '#kontakt',
        'q1_pitanje'  => 'Koliko košta uređivanje dvorišta u Beogradu?',
        'q1_odgovor'  => 'Cena uređivanja dvorišta u Beogradu zavisi od veličine površine, vrste radova i stanja terena. Osnovne usluge poput košenja trave su pristupačne, dok kompleksniji radovi poput planiranja dvorišta ili orezivanja drveća zahtevaju detaljniju procenu. Nudimo besplatnu procenu na terenu kako bismo vam dali tačnu cenu.',
        'q2_pitanje'  => 'Da li nudite besplatnu procenu dvorišta?',
        'q2_odgovor'  => 'Da, nudimo besplatan izlazak na teren u Beogradu. Na licu mesta procenjujemo stanje dvorišta i predlažemo najbolje rešenje za uređivanje i održavanje.',
        'q3_pitanje'  => 'Koje usluge održavanja dvorišta nudite?',
        'q3_odgovor'  => 'Nudimo kompletne usluge uređivanja i održavanja dvorišta u Beogradu: košenje trave, orezivanje žive ograde i drveća, uklanjanje korova, planiranje dvorišta, pranje i čišćenje staza. Sve usluge prilagođavamo vašim potrebama.',
        'q4_pitanje'  => 'Koliko često treba kositi travu?',
        'q4_odgovor'  => 'Košenje trave se preporučuje jednom nedeljno u sezoni rasta (proleće i leto), dok je u jesen i zimu ređe potrebno. Redovno održavanje travnjaka doprinosi zdravijem i lepšem dvorištu.',
        'q5_pitanje'  => 'Da li radite održavanje dvorišta za firme i stambene zgrade?',
        'q5_odgovor'  => 'Da, pružamo usluge održavanja dvorišta za privatne kuće, firme i stambene zajednice u Beogradu. Nudimo i redovno mesečno održavanje.',
        'q6_pitanje'  => 'Da li uklanjate i odnosite otpad nakon radova?',
        'q6_odgovor'  => 'Da, nakon svih radova (košenje, orezivanje, čišćenje) uklanjamo i odnosimo sav biljni otpad kako bi vaše dvorište ostalo potpuno uredno.',
        'q7_pitanje'  => 'Koliko traje uređivanje dvorišta?',
        'q7_odgovor'  => 'Trajanje radova zavisi od veličine dvorišta i vrste usluge. Manji radovi poput košenja mogu biti završeni za nekoliko sati, dok kompleksniji projekti mogu trajati više dana.',
        'q8_pitanje'  => 'Da li radite hitne intervencije (zaraslo dvorište)?',
        'q8_odgovor'  => 'Da, radimo i sređivanje zapuštenih i zaraslih dvorišta u Beogradu. U takvim slučajevima pravimo plan rada i brzo vraćamo dvorište u uredno stanje.',
        'q9_pitanje'  => 'Da li koristite profesionalnu opremu?',
        'q9_odgovor'  => 'Da, koristimo profesionalnu opremu za košenje, orezivanje i čišćenje kako bismo obezbedili kvalitetan i dugotrajan rezultat.',
        'q10_pitanje' => 'Kako da zakažem uređivanje dvorišta?',
        'q10_odgovor' => 'Možete nas kontaktirati telefonom ili putem sajta. Takođe, možete koristiti naš kalkulator cena i zatražiti besplatnu procenu.',
    ];
}

// ─── PAGE ────────────────────────────────────────────────────
function bastovan_faq_page(): void {
    $img_data = get_option( 'bastovan_faq_images', [] );
    $bg_id    = absint( $img_data['faq_img_bg'] ?? 0 );
    $bg_src   = $bg_id ? wp_get_attachment_image_url( $bg_id, 'medium' ) : '';

    $d = array_merge( bastovan_faq_defaults(), get_option( 'bastovan_faq_texts', [] ) );
    ?>
    <div class="wrap">
        <h1>FAQ</h1>

        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Podešavanja sačuvana.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <?php wp_nonce_field( 'bastovan_faq_nonce' ); ?>
            <input type="hidden" name="action" value="bastovan_save_faq">

            <!-- SLIKE -->
            <h2 style="margin-top:1.5em;padding-bottom:6px;border-bottom:1px solid #ddd;">Pozadinska slika</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th style="width:180px;"><label>Pozadinska slika</label></th>
                    <td>
                        <div class="bastovan-img-field" style="display:flex;align-items:center;gap:12px;">
                            <input type="hidden" name="faq_img_bg" value="<?php echo $bg_id ?: ''; ?>">
                            <div class="bastovan-preview">
                                <?php if ( $bg_src ) : ?>
                                    <img src="<?php echo esc_url( $bg_src ); ?>" style="max-width:160px;max-height:100px;border-radius:4px;">
                                <?php endif; ?>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                <button type="button" class="button bastovan-pick">Izaberi sliku</button>
                                <button type="button" class="button bastovan-remove" <?php echo $bg_id ? '' : 'style="display:none"'; ?>>Ukloni</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- ZAGLAVLJE -->
            <h2 style="margin-top:2em;padding-bottom:6px;border-bottom:1px solid #ddd;">Zaglavlje sekcije</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th style="width:180px;"><label for="eyebrow">Eyebrow tekst</label></th>
                    <td><input type="text" id="eyebrow" name="eyebrow" value="<?php echo esc_attr( $d['eyebrow'] ); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th><label for="heading">Naslov</label></th>
                    <td><textarea id="heading" name="heading" rows="2" class="large-text"><?php echo esc_textarea( $d['heading'] ); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="lead">Lead tekst</label></th>
                    <td><input type="text" id="lead" name="lead" value="<?php echo esc_attr( $d['lead'] ); ?>" class="large-text"></td>
                </tr>
            </table>

            <!-- PITANJA -->
            <?php for ( $i = 1; $i <= 10; $i++ ) : ?>
            <h2 style="margin-top:2em;padding-bottom:6px;border-bottom:1px solid #ddd;">Pitanje <?php echo $i; ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th style="width:180px;"><label for="q<?php echo $i; ?>_pitanje">Pitanje</label></th>
                    <td><input type="text" id="q<?php echo $i; ?>_pitanje" name="q<?php echo $i; ?>_pitanje" value="<?php echo esc_attr( $d[ "q{$i}_pitanje" ] ); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th><label for="q<?php echo $i; ?>_odgovor">Odgovor</label></th>
                    <td><textarea id="q<?php echo $i; ?>_odgovor" name="q<?php echo $i; ?>_odgovor" rows="3" class="large-text"><?php echo esc_textarea( $d[ "q{$i}_odgovor" ] ); ?></textarea></td>
                </tr>
            </table>
            <?php endfor; ?>

            <!-- CTA -->
            <h2 style="margin-top:2em;padding-bottom:6px;border-bottom:1px solid #ddd;">CTA</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th style="width:180px;"><label for="cta_text">Tekst iznad dugmeta</label></th>
                    <td><input type="text" id="cta_text" name="cta_text" value="<?php echo esc_attr( $d['cta_text'] ); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th><label for="cta_btn">Tekst dugmeta</label></th>
                    <td><input type="text" id="cta_btn" name="cta_btn" value="<?php echo esc_attr( $d['cta_btn'] ); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th><label for="cta_url">URL dugmeta</label></th>
                    <td><input type="text" id="cta_url" name="cta_url" value="<?php echo esc_attr( $d['cta_url'] ); ?>" class="large-text"></td>
                </tr>
            </table>

            <p class="submit" style="margin-top:2em;">
                <input type="submit" class="button-primary" value="Sačuvaj">
            </p>
        </form>
    </div>
    <?php
}
