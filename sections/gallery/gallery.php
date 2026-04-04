<?php
/**
 * Gallery Section
 * Reuse: get_template_part('sections/gallery/gallery')
 *
 * Podaci se povlače iz custom post type "projekat"
 * i meta polja definisanih u meta-boxes.php
 */

$projekti = get_posts( [
    'post_type'      => 'projekat',
    'posts_per_page' => 10,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

?>

<section class="gallery section" id="galerija">
  <div class="container">

    <div class="gallery__header stack-sm">
      <div class="text-eyebrow">Naši radovi</div>
      <h2 class="heading-lg">Vidite razliku<br>sami</h2>
      <p class="text-lead">Pre i posle naše intervencije — rezultati govore sami za sebe.</p>
    </div>

    <?php if ( $projekti ) : ?>

    <div class="gallery__scroll-wrap">
      <div class="gallery__track" id="gallery-track">

        <?php foreach ( $projekti as $projekat ) :

          $naziv    = get_the_title( $projekat );
          $lokacija = get_post_meta( $projekat->ID, '_bastovan_lokacija', true );

          $slika_pre_id   = get_post_meta( $projekat->ID, '_bastovan_slika_pre',   true );
          $slika_posle_id = get_post_meta( $projekat->ID, '_bastovan_slika_posle', true );

          $slika_pre_url   = $slika_pre_id   ? wp_get_attachment_image_url( $slika_pre_id,   'large' ) : '';
          $slika_posle_url = $slika_posle_id ? wp_get_attachment_image_url( $slika_posle_id, 'large' ) : '';

        ?>
        <div class="gallery__card">

          <div class="gallery__split">

            <!-- PRE: puna širina u pozadini -->
            <div class="gallery__layer gallery__layer--before">
              <?php if ( $slika_pre_url ) : ?>
                <img src="<?php echo esc_url( $slika_pre_url ); ?>"
                     alt="Pre — <?php echo esc_attr( $naziv ); ?>"
                     loading="lazy">
              <?php endif; ?>
              <span class="gallery__label gallery__label--before">Pre</span>
            </div>

            <!-- POSLE: overlay koji se sužava/širi prevlačenjem -->
            <div class="gallery__layer gallery__layer--after">
              <?php if ( $slika_posle_url ) : ?>
                <img src="<?php echo esc_url( $slika_posle_url ); ?>"
                     alt="Posle — <?php echo esc_attr( $naziv ); ?>"
                     loading="lazy">
              <?php endif; ?>
              <span class="gallery__label gallery__label--after">Posle</span>
            </div>

            <!-- Linija i ikonica -->
            <div class="gallery__divider">
              <div class="gallery__split-icon" aria-hidden="true">⟺</div>
            </div>

          </div>

          <div class="gallery__info">
            <div>
              <div class="gallery__info-title"><?php echo esc_html( $naziv ); ?></div>
              <?php if ( $lokacija ) : ?>
              <div class="gallery__info-sub">
                📍 <?php echo esc_html( $lokacija ); ?>
              </div>
              <?php endif; ?>
            </div>
            <div class="gallery__check" aria-hidden="true">✅</div>
          </div>

        </div>
        <?php endforeach; ?>

      </div>
    </div>

    <div class="gallery__controls">
      <button class="gallery__btn" id="gallery-prev" aria-label="Prethodna slika">←</button>
      <button class="gallery__btn" id="gallery-next" aria-label="Sledeća slika">→</button>
    </div>

    <?php else : ?>
      <p style="text-align:center;color:var(--color-muted);">Projekti uskoro...</p>
    <?php endif; ?>

  </div>
</section>