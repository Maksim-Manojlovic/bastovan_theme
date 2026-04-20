<?php

if ( ! defined('ABSPATH') ) exit;

// ─── SUBMENU ─────────────────────────────────────────────────
add_action( 'admin_menu', function () {
    add_submenu_page(
        'bastovan-panel',
        'Hero',
        'Hero',
        'manage_options',
        'bastovan-hero',
        'bastovan_hero_page'
    );
} );

// ─── ENQUEUE ─────────────────────────────────────────────────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( strpos( $hook, 'bastovan-hero' ) === false ) return;
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_add_inline_script( 'wp-color-picker', bastovan_admin_js() );
} );

// ─── SAVE ────────────────────────────────────────────────────
add_action( 'admin_post_bastovan_save_hero', function () {
    check_admin_referer( 'bastovan_hero_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die();

    $allowed_html = [ 'br' => [], 'span' => [], 'strong' => [], 'em' => [] ];

    update_option( 'bastovan_hero', [
        'img_bg'    => absint( $_POST['img_bg'] ?? 0 ),
        'title'     => wp_kses( wp_unslash( $_POST['title'] ?? '' ), $allowed_html ),
        'subtitle'  => sanitize_text_field( $_POST['subtitle'] ?? '' ),
        'cta_text'  => sanitize_text_field( $_POST['cta_text'] ?? '' ),
        'cta_url'   => sanitize_text_field( $_POST['cta_url'] ?? '' ),
        'btn2_text' => sanitize_text_field( $_POST['btn2_text'] ?? '' ),
        'btn2_url'  => sanitize_text_field( $_POST['btn2_url'] ?? '' ),
    ] );

    wp_redirect( admin_url( 'admin.php?page=bastovan-hero&saved=1' ) );
    exit;
} );

// ─── DEFAULTS ────────────────────────────────────────────────
function bastovan_hero_defaults(): array {
    return [
        'img_bg'    => 0,
        'title'     => 'Profesionalno uređivanje <br><span>i održavanje dvorišta</span><br>Beograd',
        'subtitle'  => 'Brza, pouzdana i pristupačna usluga za vaše savršeno dvorište. Prepustite košenje, orezivanje i čišćenje nama.',
        'cta_text'  => '🌿 Zatražite besplatnu procenu',
        'cta_url'   => '#kalkulator',
        'btn2_text' => 'Naše usluge →',
        'btn2_url'  => '#usluge',
    ];
}

// ─── PAGE ────────────────────────────────────────────────────
function bastovan_hero_page(): void {
    $d    = array_merge( bastovan_hero_defaults(), get_option( 'bastovan_hero', [] ) );
    $bg_id  = absint( $d['img_bg'] );
    $bg_src = $bg_id ? wp_get_attachment_image_url( $bg_id, 'medium' ) : '';
    ?>
    <div class="wrap">
        <h1>Hero</h1>

        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Podešavanja sačuvana.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <?php wp_nonce_field( 'bastovan_hero_nonce' ); ?>
            <input type="hidden" name="action" value="bastovan_save_hero">

            <!-- SLIKA -->
            <h2 style="margin-top:1.5em;padding-bottom:6px;border-bottom:1px solid #ddd;">Pozadinska slika</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th style="width:180px;"><label>Pozadinska slika</label></th>
                    <td>
                        <div class="bastovan-img-field" style="display:flex;align-items:center;gap:12px;">
                            <input type="hidden" name="img_bg" value="<?php echo $bg_id ?: ''; ?>">
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

            <!-- TEKSTOVI -->
            <h2 style="margin-top:2em;padding-bottom:6px;border-bottom:1px solid #ddd;">Tekstovi</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th style="width:180px;"><label for="title">Naslov</label></th>
                    <td>
                        <textarea id="title" name="title" rows="3" class="large-text"><?php echo esc_textarea( $d['title'] ); ?></textarea>
                        <p class="description">Dozvoljeni tagovi: &lt;br&gt;, &lt;span&gt;, &lt;strong&gt;, &lt;em&gt;. Span dobija akcent boju.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="subtitle">Podnaslov</label></th>
                    <td><textarea id="subtitle" name="subtitle" rows="2" class="large-text"><?php echo esc_textarea( $d['subtitle'] ); ?></textarea></td>
                </tr>
            </table>

            <!-- DUGMAD -->
            <h2 style="margin-top:2em;padding-bottom:6px;border-bottom:1px solid #ddd;">Dugmad</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th style="width:180px;"><label for="cta_text">Dugme 1 — tekst</label></th>
                    <td><input type="text" id="cta_text" name="cta_text" value="<?php echo esc_attr( $d['cta_text'] ); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th><label for="cta_url">Dugme 1 — URL</label></th>
                    <td><input type="text" id="cta_url" name="cta_url" value="<?php echo esc_attr( $d['cta_url'] ); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th><label for="btn2_text">Dugme 2 — tekst</label></th>
                    <td><input type="text" id="btn2_text" name="btn2_text" value="<?php echo esc_attr( $d['btn2_text'] ); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th><label for="btn2_url">Dugme 2 — URL</label></th>
                    <td><input type="text" id="btn2_url" name="btn2_url" value="<?php echo esc_attr( $d['btn2_url'] ); ?>" class="large-text"></td>
                </tr>
            </table>

            <p class="submit" style="margin-top:2em;">
                <input type="submit" class="button-primary" value="Sačuvaj">
            </p>
        </form>
    </div>
    <?php
}
