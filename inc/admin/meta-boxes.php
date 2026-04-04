<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ===================================================
   USLUGA META BOX
=================================================== */

add_action( 'add_meta_boxes', 'bastovan_add_usluga_meta_box' );

function bastovan_add_usluga_meta_box() {
    add_meta_box(
        'usluga-detalji',
        __( 'Detalji usluge', 'bastovan' ),
        'bastovan_usluga_meta_box_html',
        'usluga',
        'normal',
        'high'
    );
}

function bastovan_usluga_meta_box_html( $post ) {

    wp_nonce_field( 'bastovan_save_usluga_meta', 'bastovan_usluga_nonce' );

    $ikonica   = get_post_meta( $post->ID, '_bastovan_ikonica', true );
    $cena_od   = get_post_meta( $post->ID, '_bastovan_cena_od', true );
    $cena_do   = get_post_meta( $post->ID, '_bastovan_cena_do', true );
    $trajanje  = get_post_meta( $post->ID, '_bastovan_trajanje', true );
    $istaknuto = get_post_meta( $post->ID, '_bastovan_istaknuto', true );
    $opis      = get_post_meta( $post->ID, '_bastovan_opis', true );
    $stavke    = get_post_meta( $post->ID, '_bastovan_stavke', true );
    $stavke    = $stavke ? json_decode( $stavke, true ) : [];
    ?>
    <table class="form-table">

        <!-- IKONICA -->
        <tr>
            <th><label for="bastovan_ikonica">Ikonica (emoji)</label></th>
            <td>
                <input type="text" id="bastovan_ikonica" name="bastovan_ikonica"
                       value="<?php echo esc_attr( $ikonica ); ?>"
                       placeholder="npr. 🌿" class="small-text">
                <p class="description">Unesite emoji koji predstavlja uslugu.</p>
            </td>
        </tr>

        <!-- OPIS -->
        <tr>
            <th><label for="bastovan_opis">Opis usluge</label></th>
            <td>
                <?php
                wp_editor( $opis, 'bastovan_opis', [
                    'textarea_name' => 'bastovan_opis',
                    'textarea_rows' => 6,
                    'media_buttons' => false,
                    'teeny'         => true,
                    'quicktags'     => false,
                    'tinymce'       => [
                        'toolbar1' => 'bold,italic,bullist,numlist,link',
                        'toolbar2' => '',
                    ],
                ] );
                ?>
            </td>
        </tr>

        <!-- CENA OD -->
        <tr>
            <th><label for="bastovan_cena_od">Cena od (RSD)</label></th>
            <td><input type="number" id="bastovan_cena_od" name="bastovan_cena_od"
                       value="<?php echo esc_attr( $cena_od ); ?>" class="regular-text"></td>
        </tr>

        <!-- CENA DO -->
        <tr>
            <th><label for="bastovan_cena_do">Cena do (RSD)</label></th>
            <td><input type="number" id="bastovan_cena_do" name="bastovan_cena_do"
                       value="<?php echo esc_attr( $cena_do ); ?>" class="regular-text"></td>
        </tr>

        <!-- TRAJANJE -->
        <tr>
            <th><label for="bastovan_trajanje">Trajanje</label></th>
            <td><input type="text" id="bastovan_trajanje" name="bastovan_trajanje"
                       value="<?php echo esc_attr( $trajanje ); ?>"
                       placeholder="npr. 2-4 sata" class="regular-text"></td>
        </tr>

        <!-- ISTAKNUTO -->
        <tr>
            <th>Istaknuta usluga</th>
            <td>
                <input type="checkbox" name="bastovan_istaknuto" value="1"
                       <?php checked( $istaknuto, '1' ); ?>>
                <label>Prikaži kao featured karticu</label>
            </td>
        </tr>

        <!-- STAVKE -->
        <tr>
            <th><label>Podkategorije / Stavke</label></th>
            <td>
                <div id="bastovan-stavke-wrap">
                    <?php foreach ( $stavke as $stavka ) : ?>
                    <div class="bastovan-stavka">
                        <span>⠿</span>
                        <input type="text"
                               name="bastovan_stavke_naziv[]"
                               value="<?php echo esc_attr( $stavka['naziv'] ?? '' ); ?>"
                               placeholder="Naziv (npr. Trimer)"
                               class="regular-text">
                        <input type="text"
                               name="bastovan_stavke_cena[]"
                               value="<?php echo esc_attr( $stavka['cena'] ?? '' ); ?>"
                               placeholder="Cena (npr. 9-15 din/m²)"
                               class="regular-text">
                        <button type="button" class="button bastovan-stavka-remove">✕</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button" id="bastovan-stavka-add">
                    + Dodaj stavku
                </button>
                <p class="description">
                    Svaka stavka ima naziv i cenu. Redosled možete menjati prevlačenjem.
                </p>
            </td>
        </tr>

    </table>
    <?php
}

add_action( 'save_post_usluga', 'bastovan_save_usluga_meta' );

function bastovan_save_usluga_meta( $post_id ) {

    if ( ! isset( $_POST['bastovan_usluga_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['bastovan_usluga_nonce'], 'bastovan_save_usluga_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [
        '_bastovan_ikonica'   => 'bastovan_ikonica',
        '_bastovan_cena_od'   => 'bastovan_cena_od',
        '_bastovan_cena_do'   => 'bastovan_cena_do',
        '_bastovan_trajanje'  => 'bastovan_trajanje',
        '_bastovan_istaknuto' => 'bastovan_istaknuto',
    ];

    foreach ( $fields as $meta_key => $post_key ) {
        if ( isset( $_POST[ $post_key ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $post_key ] ) );
        } else {
            delete_post_meta( $post_id, $meta_key );
        }
    }

    // Čuvaj opis
    if ( isset( $_POST['bastovan_opis'] ) ) {
        update_post_meta( $post_id, '_bastovan_opis', wp_kses_post( $_POST['bastovan_opis'] ) );
    }

    // Čuvaj stavke
    if ( isset( $_POST['bastovan_stavke_naziv'] ) ) {
        $nazivi = $_POST['bastovan_stavke_naziv'];
        $cene   = $_POST['bastovan_stavke_cena'] ?? [];
        $stavke = [];

        foreach ( $nazivi as $index => $naziv ) {
            $naziv = wp_kses_post( $naziv );
            $cena  = wp_kses_post( $cene[ $index ] ?? '' );
            if ( $naziv ) {
                $stavke[] = [ 'naziv' => $naziv, 'cena' => $cena ];
            }
        }

        update_post_meta( $post_id, '_bastovan_stavke', wp_json_encode( $stavke, JSON_UNESCAPED_UNICODE ) );
    } else {
        delete_post_meta( $post_id, '_bastovan_stavke' );
    }
}

/* ===================================================
   PROJEKAT META BOX
=================================================== */

add_action( 'add_meta_boxes', 'bastovan_add_projekat_meta_box' );

function bastovan_add_projekat_meta_box() {
    add_meta_box(
        'projekat-detalji',
        __( 'Detalji projekta', 'bastovan' ),
        'bastovan_projekat_meta_box_html',
        'projekat',
        'normal',
        'high'
    );
}

function bastovan_projekat_meta_box_html( $post ) {

    wp_nonce_field( 'bastovan_save_projekat_meta', 'bastovan_projekat_nonce' );

    $lokacija   = get_post_meta( $post->ID, '_bastovan_lokacija', true );
    $datum      = get_post_meta( $post->ID, '_bastovan_datum', true );
    $galerija   = get_post_meta( $post->ID, '_bastovan_galerija', true );
    $slika_pre  = get_post_meta( $post->ID, '_bastovan_slika_pre', true );
    $slika_posle = get_post_meta( $post->ID, '_bastovan_slika_posle', true );
    $izabrane   = get_post_meta( $post->ID, '_bastovan_usluge', true );
    $izabrane   = $izabrane ? explode( ',', $izabrane ) : [];

    $usluge_po_tipu = [];
    $sve_usluge = get_posts( [
        'post_type'      => 'usluga',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ] );

    foreach ( $sve_usluge as $usluga ) {
        $u_tipovi = wp_get_post_terms( $usluga->ID, 'tip-usluge', [ 'fields' => 'slugs' ] );
        foreach ( $u_tipovi as $slug ) {
            $usluge_po_tipu[ $slug ][] = $usluga;
        }
    }

    $tipovi = get_terms( [ 'taxonomy' => 'tip-usluge', 'hide_empty' => false ] );
    ?>
    <table class="form-table">

        <!-- USLUGE -->
        <tr>
            <th><label>Usluge projekta</label></th>
            <td>
                <?php if ( ! empty( $tipovi ) && ! is_wp_error( $tipovi ) ) :
                    foreach ( $tipovi as $tip ) :
                        $tip_usluge  = $usluge_po_tipu[ $tip->slug ] ?? [];
                        $tip_izabran = ! empty( array_filter( $izabrane, function( $id ) use ( $tip_usluge ) {
                            return in_array( (int) $id, array_column( $tip_usluge, 'ID' ) );
                        }));
                ?>
                <div class="bastovan-tip-group">
                    <label style="font-weight:600;display:block;margin-bottom:6px;">
                        <input type="checkbox"
                               class="bastovan-tip-checkbox"
                               data-tip="<?php echo esc_attr( $tip->slug ); ?>"
                               <?php checked( $tip_izabran ); ?>>
                        <?php echo esc_html( $tip->name ); ?>
                    </label>
                    <div class="bastovan-usluge-select"
                         data-tip="<?php echo esc_attr( $tip->slug ); ?>"
                         style="<?php echo $tip_izabran ? '' : 'display:none;'; ?> margin-left:20px;">
                        <select name="bastovan_usluge_<?php echo esc_attr( $tip->slug ); ?>">
                            <option value="">— Izaberi uslugu —</option>
                            <?php foreach ( $tip_usluge as $usluga ) : ?>
                                <option value="<?php echo esc_attr( $usluga->ID ); ?>"
                                    <?php selected( in_array( (string) $usluga->ID, $izabrane ) ); ?>>
                                    <?php echo esc_html( $usluga->post_title ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php endforeach; endif; ?>
                <input type="hidden" id="bastovan_usluge_hidden" name="bastovan_usluge"
                       value="<?php echo esc_attr( implode( ',', $izabrane ) ); ?>">
            </td>
        </tr>

        <!-- LOKACIJA -->
        <tr>
            <th><label for="bastovan_lokacija">Lokacija</label></th>
            <td>
                <input type="text" id="bastovan_lokacija" name="bastovan_lokacija"
                       value="<?php echo esc_attr( $lokacija ); ?>"
                       placeholder="npr. Novi Beograd" class="regular-text">
            </td>
        </tr>

        <!-- DATUM -->
        <tr>
            <th><label for="bastovan_datum">Datum</label></th>
            <td>
                <input type="date" id="bastovan_datum" name="bastovan_datum"
                       value="<?php echo esc_attr( $datum ); ?>" class="regular-text">
            </td>
        </tr>

        <!-- SLIKA PRE -->
        <tr>
            <th><label>Slika PRE</label></th>
            <td>
                <div class="bastovan-single-image-wrap">
                    <?php if ( $slika_pre ) :
                        $url_pre = wp_get_attachment_image_url( $slika_pre, 'medium' );
                        if ( $url_pre ) : ?>
                            <div class="bastovan-image-preview" id="bastovan-pre-preview">
                                <img src="<?php echo esc_url( $url_pre ); ?>"
                                     style="max-width:200px;border-radius:8px;display:block;margin-bottom:8px;">
                                <button type="button" class="button bastovan-image-remove"
                                        data-target="bastovan_slika_pre"
                                        data-preview="bastovan-pre-preview">
                                    ✕ Ukloni
                                </button>
                            </div>
                        <?php endif;
                    else : ?>
                        <div id="bastovan-pre-preview"></div>
                    <?php endif; ?>
                    <input type="hidden" id="bastovan_slika_pre" name="bastovan_slika_pre"
                           value="<?php echo esc_attr( $slika_pre ); ?>">
                    <button type="button" class="button bastovan-image-upload"
                            data-target="bastovan_slika_pre"
                            data-preview="bastovan-pre-preview"
                            style="margin-top:6px;">
                        📷 Izaberi sliku PRE
                    </button>
                </div>
                <p class="description">Slika dvorišta pre intervencije.</p>
            </td>
        </tr>

        <!-- SLIKA POSLE -->
        <tr>
            <th><label>Slika POSLE</label></th>
            <td>
                <div class="bastovan-single-image-wrap">
                    <?php if ( $slika_posle ) :
                        $url_posle = wp_get_attachment_image_url( $slika_posle, 'medium' );
                        if ( $url_posle ) : ?>
                            <div class="bastovan-image-preview" id="bastovan-posle-preview">
                                <img src="<?php echo esc_url( $url_posle ); ?>"
                                     style="max-width:200px;border-radius:8px;display:block;margin-bottom:8px;">
                                <button type="button" class="button bastovan-image-remove"
                                        data-target="bastovan_slika_posle"
                                        data-preview="bastovan-posle-preview">
                                    ✕ Ukloni
                                </button>
                            </div>
                        <?php endif;
                    else : ?>
                        <div id="bastovan-posle-preview"></div>
                    <?php endif; ?>
                    <input type="hidden" id="bastovan_slika_posle" name="bastovan_slika_posle"
                           value="<?php echo esc_attr( $slika_posle ); ?>">
                    <button type="button" class="button bastovan-image-upload"
                            data-target="bastovan_slika_posle"
                            data-preview="bastovan-posle-preview"
                            style="margin-top:6px;">
                        📷 Izaberi sliku POSLE
                    </button>
                </div>
                <p class="description">Slika dvorišta posle intervencije.</p>
            </td>
        </tr>

        <!-- GALERIJA -->
        <tr>
            <th><label>Galerija slika</label></th>
            <td>
                <div id="bastovan-galerija-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
                    <?php if ( $galerija ) :
                        foreach ( explode( ',', $galerija ) as $id ) :
                            $url = wp_get_attachment_image_url( trim( $id ), 'thumbnail' );
                            if ( $url ) : ?>
                                <img src="<?php echo esc_url( $url ); ?>"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:6px;">
                            <?php endif;
                        endforeach;
                    endif; ?>
                </div>
                <input type="hidden" id="bastovan_galerija" name="bastovan_galerija"
                       value="<?php echo esc_attr( $galerija ); ?>">
                <button type="button" class="button" id="bastovan-galerija-btn">
                    Dodaj / uredi slike
                </button>
            </td>
        </tr>

    </table>
    <?php
}

add_action( 'save_post_projekat', 'bastovan_save_projekat_meta' );

function bastovan_save_projekat_meta( $post_id ) {

    if ( ! isset( $_POST['bastovan_projekat_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['bastovan_projekat_nonce'], 'bastovan_save_projekat_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [
        '_bastovan_lokacija'    => 'bastovan_lokacija',
        '_bastovan_datum'       => 'bastovan_datum',
        '_bastovan_galerija'    => 'bastovan_galerija',
        '_bastovan_slika_pre'   => 'bastovan_slika_pre',
        '_bastovan_slika_posle' => 'bastovan_slika_posle',
    ];

    foreach ( $fields as $meta_key => $post_key ) {
        if ( isset( $_POST[ $post_key ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $post_key ] ) );
        } else {
            delete_post_meta( $post_id, $meta_key );
        }
    }

    // Čuvaj izabrane usluge i automatski postavi tip usluge termine
    $sve_tipovi      = get_terms( [ 'taxonomy' => 'tip-usluge', 'hide_empty' => false, 'fields' => 'slugs' ] );
    $izabrane_usluge = [];
    $izabrani_tipovi = [];

    foreach ( $sve_tipovi as $slug ) {
        $key = 'bastovan_usluge_' . $slug;
        if ( ! empty( $_POST[ $key ] ) ) {
            $usluga_id         = intval( $_POST[ $key ] );
            $izabrane_usluge[] = $usluga_id;

            $tip_term = get_term_by( 'slug', $slug, 'tip-usluge' );
            if ( $tip_term ) {
                $izabrani_tipovi[] = $tip_term->term_id;
            }
        }
    }

    update_post_meta( $post_id, '_bastovan_usluge', implode( ',', $izabrane_usluge ) );
    wp_set_post_terms( $post_id, $izabrani_tipovi, 'tip-usluge' );
}