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

        <!-- Tip level -->
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

        <!-- Usluga level — po tipu -->
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

      <?php if ( $projekti_query->have_posts() ) : ?>
      <div class="projekti__lista stack-xl">

        <?php $i = 0; while ( $projekti_query->have_posts() ) : $projekti_query->the_post(); ?>

        <?php
          $datum           = get_post_meta( get_the_ID(), '_bastovan_datum', true );
          $lokacija        = get_post_meta( get_the_ID(), '_bastovan_lokacija', true );
          $galerija        = get_post_meta( get_the_ID(), '_bastovan_galerija', true );
          $izabrane_usluge = get_post_meta( get_the_ID(), '_bastovan_usluge', true );
          $slike_ids       = $galerija ? explode( ',', $galerija ) : [];
          $datum_fmt       = $datum ? date_i18n( 'd.m.Y', strtotime( $datum ) ) : '';

          $naslov_parts = array_filter( [ get_the_title(), $lokacija ] );
          $naslov       = implode( ', ', $naslov_parts );

          $slugovi  = wp_get_post_terms( get_the_ID(), 'tip-usluge', [ 'fields' => 'slugs' ] );
          $data_tip = implode( ' ', $slugovi );

          $slika_pre_id    = get_post_meta( get_the_ID(), '_bastovan_slika_pre',   true );
          $slika_posle_id  = get_post_meta( get_the_ID(), '_bastovan_slika_posle', true );
          $slika_pre_url   = $slika_pre_id   ? wp_get_attachment_image_url( $slika_pre_id,   'large' ) : '';
          $slika_posle_url = $slika_posle_id ? wp_get_attachment_image_url( $slika_posle_id, 'large' ) : '';
        ?>

        <article class="projekat"
                 data-tip="<?php echo esc_attr( $data_tip ); ?>"
                 data-usluge="<?php echo esc_attr( $izabrane_usluge ?: '' ); ?>">

          <div class="projekat__header">
            <div>
              <h2 class="projekat__naslov heading-lg">
                <?php echo esc_html( $naslov ); ?>
              </h2>
              <?php if ( $datum_fmt ) : ?>
                <span class="projekat__datum text-eyebrow">
                  <?php echo esc_html( $datum_fmt ); ?>
                </span>
              <?php endif; ?>
            </div>
            <?php if ( get_the_excerpt() ) : ?>
              <p class="projekat__opis text-lead">
                <?php echo esc_html( get_the_excerpt() ); ?>
              </p>
            <?php endif; ?>
          </div>

          <div class="projekat__media">

            <?php if ( $slika_pre_url || $slika_posle_url ) : ?>
            <div class="projekat__media-split">
              <div class="gallery__card projekat__gallery-card">

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
            <div class="projekat__media-gallery">
              <div class="projekat__scroll-wrap">
                <div class="projekat__track" id="track-<?php echo $i; ?>">
                  <?php foreach ( $slike_ids as $slika_id ) :
                    $slika_url = wp_get_attachment_image_url( trim( $slika_id ), 'large' );
                    $slika_alt = get_post_meta( trim( $slika_id ), '_wp_attachment_image_alt', true );
                    if ( ! $slika_url ) continue;
                  ?>
                  <div class="projekat__slika">
                    <img
                      src="<?php echo esc_url( $slika_url ); ?>"
                      alt="<?php echo esc_attr( $slika_alt ?: get_the_title() ); ?>"
                      loading="lazy"
                      decoding="async"
                    >
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="projekat__controls">
                <button class="projekat__btn"
                        data-track="track-<?php echo $i; ?>"
                        data-dir="prev"
                        aria-label="Prethodna slika">←</button>
                <button class="projekat__btn"
                        data-track="track-<?php echo $i; ?>"
                        data-dir="next"
                        aria-label="Sledeća slika">→</button>
              </div>
            </div>
            <?php endif; ?>

          </div>

        </article>

        <?php $i++; endwhile; wp_reset_postdata(); ?>

      </div>

      <?php else : ?>
      <div class="galerija-prazna">
        <p class="text-lead">Projekti uskoro...</p>
      </div>
      <?php endif; ?>

    </div>
  </section>
</main>