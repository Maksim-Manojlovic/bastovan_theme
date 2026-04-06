<?php
/**
 * Section: Usluge stranica
 */

$primarne = get_posts( [
    'post_type'      => 'usluga',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'tax_query'      => [ [
        'taxonomy' => 'tip-usluge',
        'field'    => 'slug',
        'terms'    => 'primarne-usluge',
    ] ],
    'orderby' => 'menu_order',
    'order'   => 'ASC',
] );

$sekundarne = get_posts( [
    'post_type'      => 'usluga',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'tax_query'      => [ [
        'taxonomy' => 'tip-usluge',
        'field'    => 'slug',
        'terms'    => 'dodatne-usluge',
    ] ],
    'orderby' => 'menu_order',
    'order'   => 'ASC',
] );
?>

<main class="usluge-page">

  <!-- HERO -->
  <section class="usluge-hero">
    <div class="usluge-hero__bg" aria-hidden="true"></div>
    <div class="usluge-hero__overlay" aria-hidden="true"></div>
    <div class="container usluge-hero__inner">
      <div class="text-eyebrow" style="color: rgba(255,255,255,0.7);">Šta radimo</div>
      <h1 class="usluge-hero__title">
        Kompletno sređivanje i<br><span>održavanje zelenih površina</span>
      </h1>
      <p class="usluge-hero__sub">
        Profesionalna briga o vašem dvorištu — od planiranja do održavanja.
      </p>
      <a href="#primarne" class="btn btn--white">Pogledajte usluge ↓</a>
    </div>
  </section>

  <!-- PRIMARNE USLUGE -->
  <?php if ( ! empty( $primarne ) ) : ?>
  <section class="usluge-primarne section" id="primarne">
    <div class="container">

      <div class="stack-sm usluge-primarne__header">
        <div class="text-eyebrow">Primarne usluge</div>
        <h2 class="heading-lg">Osnova svakog urednog dvorišta</h2>
      </div>

      <div class="usluge-lista">
        <?php foreach ( $primarne as $index => $usluga ) :
          $ikonica   = get_post_meta( $usluga->ID, '_bastovan_ikonica', true ) ?: '🌿';
          $slika_id  = get_post_meta( $usluga->ID, '_bastovan_slika', true );
          $slika_url = $slika_id ? wp_get_attachment_image_url( $slika_id, 'large' ) : '';
          $opis      = get_post_meta( $usluga->ID, '_bastovan_opis', true );
          $cena_od   = get_post_meta( $usluga->ID, '_bastovan_cena_od', true );
          $cena_do   = get_post_meta( $usluga->ID, '_bastovan_cena_do', true );
          $trajanje  = get_post_meta( $usluga->ID, '_bastovan_trajanje', true );
          $stavke    = get_post_meta( $usluga->ID, '_bastovan_stavke', true );
          $stavke    = $stavke ? json_decode( $stavke, true ) : [];
          $levo      = $index % 2 === 0;
        ?>
        <div class="usluga-row <?php echo $levo ? 'usluga-row--levo' : 'usluga-row--desno'; ?>" id="<?php echo esc_attr( $usluga->post_name ); ?>">

          <div class="usluga-row__visual">
            <?php if ( $slika_url ) : ?>
              <img
                src="<?php echo esc_url( $slika_url ); ?>"
                alt="<?php echo esc_attr( $usluga->post_title ); ?> — uređivanje dvorišta Beograd"
                loading="lazy"
                decoding="async"
                class="usluga-row__slika"
              >
            <?php else : ?>
              <div class="usluga-row__placeholder">
                <span class="usluga-row__emoji"><?php echo esc_html( $ikonica ); ?></span>
              </div>
            <?php endif; ?>
          </div>

          <div class="usluga-row__content stack-md">
            <div class="usluga-row__icon"><?php echo esc_html( $ikonica ); ?></div>
            <h3 class="heading-md"><?php echo esc_html( $usluga->post_title ); ?></h3>

            <?php if ( $opis ) : ?>
              <div class="text-lead"><?php echo wp_kses_post( $opis ); ?></div>
            <?php endif; ?>

            <?php if ( $trajanje || $cena_od ) : ?>
            <div class="usluga-row__meta">
              <?php if ( $trajanje ) : ?>
                <span class="usluga-meta-tag">⏱ <?php echo esc_html( $trajanje ); ?></span>
              <?php endif; ?>
              <?php if ( $cena_od ) : ?>
                <span class="usluga-meta-tag">
                  💰 od <?php echo esc_html( number_format( $cena_od, 0, ',', '.' ) ); ?> RSD
                  <?php if ( $cena_do ) : ?>
                    — <?php echo esc_html( number_format( $cena_do, 0, ',', '.' ) ); ?> RSD
                  <?php endif; ?>
                </span>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <a href="/#kalkulator" class="btn btn--green">Izračunaj cenu →</a>

            <?php if ( ! empty( $stavke ) ) : ?>
            <div class="usluga-accordion">
              <button class="usluga-accordion__btn" type="button" aria-expanded="false">
                Više o usluzi
                <span class="usluga-accordion__arrow">↓</span>
              </button>
              <div class="usluga-accordion__panel" hidden>
                <table class="usluga-stavke">
                  <?php foreach ( $stavke as $stavka ) : ?>
                  <tr class="usluga-stavke__row">
                    <td class="usluga-stavke__naziv">
                      <?php echo esc_html( $stavka['naziv'] ); ?>
                    </td>
                    <td class="usluga-stavke__cena">
                      <?php echo esc_html( $stavka['cena'] ); ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </div>
            </div>
            <?php endif; ?>

          </div>

        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>
  <?php endif; ?>

  <!-- SEKUNDARNE USLUGE -->
  <?php if ( ! empty( $sekundarne ) ) : ?>
  <section class="usluge-sekundarne section" id="sekundarne">
    <div class="container">

      <div class="stack-sm usluge-sekundarne__header">
        <div class="text-eyebrow">Dodatne usluge</div>
        <h2 class="heading-lg">Sve što vaše dvorište još može da dobije</h2>
      </div>

      <div class="usluge-grid">
        <?php foreach ( $sekundarne as $usluga ) :
          $ikonica  = get_post_meta( $usluga->ID, '_bastovan_ikonica', true ) ?: '✨';
          $opis     = get_post_meta( $usluga->ID, '_bastovan_opis', true );
          $cena_od  = get_post_meta( $usluga->ID, '_bastovan_cena_od', true );
          $trajanje = get_post_meta( $usluga->ID, '_bastovan_trajanje', true );
          $stavke   = get_post_meta( $usluga->ID, '_bastovan_stavke', true );
          $stavke   = $stavke ? json_decode( $stavke, true ) : [];
        ?>
        <div class="usluga-card">
          <div class="usluga-card__icon"><?php echo esc_html( $ikonica ); ?></div>
          <h3 class="usluga-card__naziv"><?php echo esc_html( $usluga->post_title ); ?></h3>

          <?php if ( $opis ) : ?>
            <div class="usluga-card__opis"><?php echo wp_kses_post( $opis ); ?></div>
          <?php endif; ?>

          <?php if ( $trajanje || $cena_od ) : ?>
          <div class="usluga-card__meta">
            <?php if ( $trajanje ) : ?>
              <span class="usluga-meta-tag">⏱ <?php echo esc_html( $trajanje ); ?></span>
            <?php endif; ?>
            <?php if ( $cena_od ) : ?>
              <span class="usluga-meta-tag">💰 od <?php echo esc_html( number_format( $cena_od, 0, ',', '.' ) ); ?> RSD</span>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <?php if ( ! empty( $stavke ) ) : ?>
          <div class="usluga-accordion usluga-accordion--sm">
            <button class="usluga-accordion__btn" type="button" aria-expanded="false">
              Podkategorije <span class="usluga-accordion__arrow">↓</span>
            </button>
            <div class="usluga-accordion__panel" hidden>
              <table class="usluga-stavke">
                <?php foreach ( $stavke as $stavka ) : ?>
                <tr class="usluga-stavke__row">
                  <td class="usluga-stavke__naziv">
                    <?php echo esc_html( $stavka['naziv'] ); ?>
                  </td>
                  <td class="usluga-stavke__cena">
                    <?php echo esc_html( $stavka['cena'] ); ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </table>
            </div>
          </div>
          <?php endif; ?>

        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>
  <?php endif; ?>

  <!-- PAKETI — placeholder -->
  <section class="usluge-paketi section">
    <div class="container">
      <div class="stack-sm" style="text-align:center;">
        <div class="text-eyebrow">Paketi održavanja</div>
        <h2 class="heading-lg">Dugoročni paketi uskoro</h2>
        <p class="text-lead">Pripremamo posebne pakete za redovno održavanje.<br>Kontaktirajte nas za više informacija.</p>
        <a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="btn btn--green">Kontaktirajte nas</a>
      </div>
    </div>
  </section>

</main>