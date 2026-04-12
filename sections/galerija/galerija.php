<?php
/**
 * Section: Galerija stranica
 * Reuse: get_template_part('sections/galerija/galerija')
 */

$projekti_query = new WP_Query( [
    'post_type'      => 'projekat',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
] );
?>

<main class="galerija-page">
  <section class="galerija-hero section-sm">
    <div class="container">
      <div class="text-eyebrow">Portfolio</div>
      <h1 class="heading-xl">Naši radovi</h1>
      <p class="text-lead">Svaki projekat je priča za sebe — pogledajte šta smo uradili.</p>
    </div>
  </section>

  <section class="galerija-projekti section">
    <div class="container">

      <?php
      $tipovi = get_terms( [ 'taxonomy' => 'tip-usluge', 'hide_empty' => true ] );

      if ( ! empty( $tipovi ) && ! is_wp_error( $tipovi ) ) : ?>
      <div class="galerija-filter">

        <div class="galerija-filter__row">
          <button class="galerija-filter__btn is-active" data-filter="*" data-level="tip">
            Sve
          </button>
          <?php foreach ( $tipovi as $tip ) : ?>
          <button class="galerija-filter__btn"
                  data-filter="<?php echo esc_attr( $tip->slug ); ?>"
                  data-level="tip">
            <?php echo esc_html( $tip->name ); ?>
          </button>
          <?php endforeach; ?>
        </div>

        <?php foreach ( $tipovi as $tip ) :
          $usluge_tipa = get_posts( [
            'post_type'      => 'usluga',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => [ [
              'taxonomy' => 'tip-usluge',
              'field'    => 'slug',
              'terms'    => $tip->slug,
            ] ],
          ] );
          if ( empty( $usluge_tipa ) ) continue;
        ?>
        <div class="galerija-filter__row galerija-filter__row--usluge"
             data-parent="<?php echo esc_attr( $tip->slug ); ?>"
             style="display:none;">
          <span class="galerija-filter__label">
            <?php echo esc_html( $tip->name ); ?>:
          </span>
          <button class="galerija-filter__sub is-active"
                  data-filter="<?php echo esc_attr( $tip->slug ); ?>"
                  data-usluga="*">
            Sve
          </button>
          <?php foreach ( $usluge_tipa as $usluga ) : ?>
          <button class="galerija-filter__sub"
                  data-filter="<?php echo esc_attr( $tip->slug ); ?>"
                  data-usluga="<?php echo esc_attr( $usluga->ID ); ?>">
            <?php echo esc_html( $usluga->post_title ); ?>
          </button>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

      </div>
      <?php endif; ?>

      <?php if ( $projekti_query->have_posts() ) :

        $cards        = [];
        $detail_items = [];
        $i            = 0;

        while ( $projekti_query->have_posts() ) :
          $projekti_query->the_post();

          $post_id         = get_the_ID();
          $datum           = get_post_meta( $post_id, '_bastovan_datum',      true );
          $lokacija        = get_post_meta( $post_id, '_bastovan_lokacija',   true );
          $galerija        = get_post_meta( $post_id, '_bastovan_galerija',   true );
          $izabrane_usluge = get_post_meta( $post_id, '_bastovan_usluge',     true );
          $slika_pre_id    = get_post_meta( $post_id, '_bastovan_slika_pre',  true );
          $slika_posle_id  = get_post_meta( $post_id, '_bastovan_slika_posle',true );

          $slike_ids       = $galerija ? explode( ',', $galerija ) : [];
          $datum_fmt       = $datum ? date_i18n( 'd.m.Y', strtotime( $datum ) ) : '';

          $naslov_parts = array_filter( [ get_the_title(), $lokacija ] );
          $naslov       = implode( ', ', $naslov_parts );

          $slugovi  = wp_get_post_terms( $post_id, 'tip-usluge', [ 'fields' => 'slugs' ] );
          $nazivi   = wp_get_post_terms( $post_id, 'tip-usluge', [ 'fields' => 'names' ] );
          $data_tip = implode( ' ', $slugovi );

          // Thumbnail: first gallery image → after → before
          $thumb_id  = ! empty( $slike_ids ) ? trim( $slike_ids[0] )
                     : ( $slika_posle_id ?: $slika_pre_id );
          $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'bastovan-card' ) : '';

          $slika_pre_url   = $slika_pre_id   ? wp_get_attachment_image_url( $slika_pre_id,   'large' ) : '';
          $slika_posle_url = $slika_posle_id ? wp_get_attachment_image_url( $slika_posle_id, 'large' ) : '';

          // ── CARD ──────────────────────────────────────────────
          ob_start(); ?>
          <article class="projekat-card"
                   data-id="<?php echo esc_attr( $post_id ); ?>"
                   data-tip="<?php echo esc_attr( $data_tip ); ?>"
                   data-usluge="<?php echo esc_attr( $izabrane_usluge ?: '' ); ?>"
                   role="button"
                   tabindex="0"
                   aria-label="<?php echo esc_attr( $naslov ); ?>">

            <div class="projekat-card__img">
              <?php if ( $thumb_url ) : ?>
                <img src="<?php echo esc_url( $thumb_url ); ?>"
                     alt="<?php echo esc_attr( $naslov ); ?>"
                     loading="lazy"
                     decoding="async">
              <?php else : ?>
                <div class="projekat-card__placeholder">🌿</div>
              <?php endif; ?>
            </div>

            <div class="projekat-card__overlay">
              <?php if ( ! empty( $nazivi ) ) : ?>
              <div class="projekat-card__tags">
                <?php foreach ( $nazivi as $naziv ) : ?>
                  <span class="projekat-card__tag"><?php echo esc_html( $naziv ); ?></span>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>

              <div class="projekat-card__info">
                <h3 class="projekat-card__naslov"><?php echo esc_html( $naslov ); ?></h3>
                <?php if ( $datum_fmt ) : ?>
                  <span class="projekat-card__datum"><?php echo esc_html( $datum_fmt ); ?></span>
                <?php endif; ?>
              </div>

              <span class="projekat-card__cta">Pogledaj projekat →</span>
            </div>

          </article>
          <?php $cards[] = ob_get_clean();

          // ── DETAIL (za modal) ──────────────────────────────────
          ob_start(); ?>
          <div class="projekat-detail" id="detail-<?php echo esc_attr( $post_id ); ?>" hidden>

            <div class="pd__header">
              <div>
                <h2 class="pd__naslov heading-lg"><?php echo esc_html( $naslov ); ?></h2>
                <?php if ( $datum_fmt ) : ?>
                  <span class="pd__datum text-eyebrow"><?php echo esc_html( $datum_fmt ); ?></span>
                <?php endif; ?>
              </div>
              <?php if ( get_the_excerpt() ) : ?>
                <p class="pd__opis text-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
              <?php endif; ?>
            </div>

            <?php if ( $slika_pre_url || $slika_posle_url ) : ?>
            <div class="pd__split-wrap">
              <div class="gallery__card pd__split-card">
                <div class="gallery__split">
                  <div class="gallery__layer gallery__layer--before">
                    <?php if ( $slika_pre_url ) : ?>
                      <img src="<?php echo esc_url( $slika_pre_url ); ?>"
                           alt="Pre — <?php echo esc_attr( get_the_title() ); ?>"
                           loading="lazy">
                    <?php endif; ?>
                    <span class="gallery__label gallery__label--before">Pre</span>
                  </div>
                  <div class="gallery__layer gallery__layer--after">
                    <?php if ( $slika_posle_url ) : ?>
                      <img src="<?php echo esc_url( $slika_posle_url ); ?>"
                           alt="Posle — <?php echo esc_attr( get_the_title() ); ?>"
                           loading="lazy">
                    <?php endif; ?>
                    <span class="gallery__label gallery__label--after">Posle</span>
                  </div>
                  <div class="gallery__divider">
                    <div class="gallery__split-icon" aria-hidden="true">⟺</div>
                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <?php if ( ! empty( $slike_ids ) ) : ?>
            <div class="pd__gallery">
              <?php foreach ( $slike_ids as $slika_id ) :
                $slika_url = wp_get_attachment_image_url( trim( $slika_id ), 'large' );
                $slika_alt = get_post_meta( trim( $slika_id ), '_wp_attachment_image_alt', true );
                if ( ! $slika_url ) continue;
              ?>
              <div class="pd__slika">
                <img src="<?php echo esc_url( $slika_url ); ?>"
                     alt="<?php echo esc_attr( $slika_alt ?: $naslov ); ?>"
                     loading="lazy"
                     decoding="async">
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

          </div>
          <?php $detail_items[] = ob_get_clean();

          $i++;
        endwhile;
        wp_reset_postdata();
      ?>

      <!-- GRID -->
      <div class="projekti__grid">
        <?php foreach ( $cards as $card ) echo $card; ?>
        <div class="galerija-empty" id="galerija-empty" hidden>
          <div class="galerija-empty__icon">🌿</div>
          <p class="galerija-empty__text">Nema projekata u ovoj kategoriji</p>
          <p class="galerija-empty__sub">Probajte drugu kategoriju ili pogledajte sve projekte</p>
        </div>
      </div>

      <!-- HIDDEN DETAIL TEMPLATES -->
      <div class="projekat-details" hidden aria-hidden="true">
        <?php foreach ( $detail_items as $detail ) echo $detail; ?>
      </div>

      <?php else : ?>
      <div class="galerija-prazna">
        <p class="text-lead">Projekti uskoro...</p>
      </div>
      <?php endif; ?>

    </div>
  </section>
</main>

<!-- PROJECT MODAL -->
<div class="pd-modal" id="pd-modal" hidden aria-modal="true" role="dialog" aria-label="Detalj projekta">
  <div class="pd-modal__backdrop"></div>
  <div class="pd-modal__dialog">

    <div class="pd-modal__header">
      <button class="pd-modal__nav-btn" id="pd-prev" aria-label="Prethodni projekat">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
          <polyline points="10,2 4,8 10,14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Prethodni</span>
      </button>

      <span class="pd-modal__counter" id="pd-counter"></span>

      <div class="pd-modal__header-right">
        <button class="pd-modal__nav-btn" id="pd-next" aria-label="Sledeći projekat">
          <span>Sledeći</span>
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <polyline points="6,2 12,8 6,14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button class="pd-modal__close" aria-label="Zatvori">
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <line x1="1" y1="1" x2="13" y2="13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <line x1="13" y1="1" x2="1" y2="13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
    </div>

    <div class="pd-modal__body"></div>
  </div>
</div>
