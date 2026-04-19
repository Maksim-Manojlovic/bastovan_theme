<?php
/**
 * Services Section
 * Reuse: get_template_part('sections/services/services')
 */
$_imgs = get_option( 'bastovan_services_images', [] );
$_txts = get_option( 'bastovan_services_texts',  [] );

// Helper — čita tekst iz opcija, fallback na hardkodovanu vrednost
$tx = fn( string $key, string $fallback ): string => esc_html( $_txts[ $key ] ?? $fallback );
$tx_raw = fn( string $key, string $fallback ): string => $_txts[ $key ] ?? $fallback;

// Slike
$img_featured  = wp_get_attachment_image_url( $_imgs['services_img_featured']  ?? 0, 'full' );
$img_kosenje_i = wp_get_attachment_image_url( $_imgs['services_img_kosenje_i'] ?? 0, 'full' );
$img_kosenje_d = wp_get_attachment_image_url( $_imgs['services_img_kosenje_d'] ?? 0, 'full' );
$img_orez_i    = wp_get_attachment_image_url( $_imgs['services_img_orez_i']    ?? 0, 'full' );
$img_orez_d    = wp_get_attachment_image_url( $_imgs['services_img_orez_d']    ?? 0, 'full' );
$img_korov_i   = wp_get_attachment_image_url( $_imgs['services_img_korov_i']   ?? 0, 'full' );
$img_korov_d   = wp_get_attachment_image_url( $_imgs['services_img_korov_d']   ?? 0, 'full' );
$img_pranje_i  = wp_get_attachment_image_url( $_imgs['services_img_pranje_i']  ?? 0, 'full' );
$img_pranje_d  = wp_get_attachment_image_url( $_imgs['services_img_pranje_d']  ?? 0, 'full' );

// Pozadina sekcije
$bg_type  = $_imgs['services_bg_type']  ?? 'image';
$bg_color = $_imgs['services_bg_color'] ?? '';
$bg_img   = wp_get_attachment_image_url( $_imgs['services_img_bg'] ?? 0, 'full' );

if ( $bg_type === 'color' && $bg_color ) {
    $bg_style = ' style="background-color:' . esc_attr( $bg_color ) . '"';
} elseif ( $bg_img ) {
    $bg_style = ' style="background-image:url(' . esc_url( $bg_img ) . ')"';
} else {
    $bg_style = '';
}
?>

<section class="services section" id="usluge"<?php echo $bg_style; ?>>
  <div class="container">

    <div class="services__header stack-sm">
      <div class="text-eyebrow"><?php echo $tx( 'eyebrow', 'Šta nudimo' ); ?></div>
      <h2 class="heading-lg"><?php echo nl2br( $tx( 'heading', "Usluge uređivanja i\nodržavanja dvorišta" ) ); ?></h2>
    </div>

    <div class="services__grid">

      <!-- Featured kartica -->
      <div class="services__card services__card--featured">
        <?php if ($img_featured) : ?>
        <div class="services__featured-bg">
          <img src="<?php echo esc_url($img_featured); ?>" alt="" loading="lazy" aria-hidden="true">
        </div>
        <?php endif; ?>
        <div class="services__card-content">
          <div class="services__icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
              <rect x="9" y="3" width="6" height="4" rx="1"/>
              <path d="M9 12h6M9 16h4"/>
            </svg>
          </div>
          <div class="services__accent" aria-hidden="true"></div>
          <h3 class="services__name"><?php echo $tx( 'featured_title', 'Planiranje dvorišta' ); ?></h3>
          <p class="services__desc"><?php echo nl2br( $tx( 'featured_desc', 'Besplatan izlazak na teren. Detaljno planiramo sadnju, košenje i ostale radove kako bismo osigurali najefikasniji rast biljaka i najbolje rezultate.' ) ); ?></p>
          <a href="<?php echo esc_url( $tx_raw( 'featured_link_url', '#kontakt' ) ); ?>" class="btn btn--white">
            <?php echo $tx( 'featured_link_text', 'Zakaži besplatnu procenu' ); ?>
          </a>
        </div>
      </div>

      <!-- Košenje -->
      <div class="services__card services__card--img-icon">
        <span class="services__num" aria-hidden="true">01</span>
        <?php if ($img_kosenje_i) : ?>
        <div class="services__icon services__icon--img">
          <img src="<?php echo esc_url($img_kosenje_i); ?>" alt="" aria-hidden="true">
        </div>
        <?php endif; ?>
        <div class="services__accent" aria-hidden="true"></div>
        <h3 class="services__name"><?php echo $tx( 'kosenje_title', 'Košenje trave' ); ?></h3>
        <p class="services__desc"><?php echo nl2br( $tx( 'kosenje_desc', 'Brzo i pedantno košenje svih površina. Travnjak održavamo urednim, a pokošenu travu sakupljamo i odnosimo.' ) ); ?></p>
        <a href="#kalkulator" class="services__link">Izračunaj cenu →</a>
        <?php if ($img_kosenje_d) : ?>
        <div class="services__card-img" aria-hidden="true">
          <img src="<?php echo esc_url($img_kosenje_d); ?>" alt="">
        </div>
        <?php endif; ?>
      </div>

      <!-- Orezivanje -->
      <div class="services__card services__card--img-icon services__card--split">
        <span class="services__num" aria-hidden="true">02</span>
        <?php if ($img_orez_i) : ?>
        <div class="services__icon services__icon--img">
          <img src="<?php echo esc_url($img_orez_i); ?>" alt="" aria-hidden="true">
        </div>
        <?php endif; ?>
        <div class="services__accent" aria-hidden="true"></div>
        <h3 class="services__name"><?php echo $tx( 'orez_title', 'Orezivanje žive ograde i drveća' ); ?></h3>
        <p class="services__desc"><?php echo nl2br( $tx( 'orez_desc', 'Precizno oblikovanje za zdrav rast biljaka i estetski savršeno dvorište.' ) ); ?></p>
        <a href="#kalkulator" class="services__link">Izračunaj cenu →</a>
        <?php if ($img_orez_d) : ?>
        <div class="services__card-img" aria-hidden="true">
          <img src="<?php echo esc_url($img_orez_d); ?>" alt="">
        </div>
        <?php endif; ?>
      </div>

      <!-- Korov -->
      <div class="services__card services__card--img-icon">
        <span class="services__num" aria-hidden="true">03</span>
        <?php if ($img_korov_i) : ?>
        <div class="services__icon services__icon--img">
          <img src="<?php echo esc_url($img_korov_i); ?>" alt="" aria-hidden="true">
        </div>
        <?php endif; ?>
        <div class="services__accent" aria-hidden="true"></div>
        <h3 class="services__name"><?php echo $tx( 'korov_title', 'Uklanjanje korova' ); ?></h3>
        <p class="services__desc"><?php echo nl2br( $tx( 'korov_desc', 'Temeljno čišćenje i efikasno suzbijanje neželjenog rastinja iz cvećnjaka, staza i travnjaka.' ) ); ?></p>
        <a href="#kalkulator" class="services__link">Izračunaj cenu →</a>
        <?php if ($img_korov_d) : ?>
        <div class="services__card-img" aria-hidden="true">
          <img src="<?php echo esc_url($img_korov_d); ?>" alt="">
        </div>
        <?php endif; ?>
      </div>

      <!-- Pranje -->
      <div class="services__card services__card--img-icon">
        <span class="services__num" aria-hidden="true">04</span>
        <?php if ($img_pranje_i) : ?>
        <div class="services__icon services__icon--img">
          <img src="<?php echo esc_url($img_pranje_i); ?>" alt="" aria-hidden="true">
        </div>
        <?php endif; ?>
        <div class="services__accent" aria-hidden="true"></div>
        <h3 class="services__name"><?php echo $tx( 'pranje_title', 'Pranje i čišćenje staza' ); ?></h3>
        <p class="services__desc"><?php echo nl2br( $tx( 'pranje_desc', 'Pranje pod visokim pritiskom — uklanjamo mahovinu i prljavštinu sa svih tvrdih podloga.' ) ); ?></p>
        <a href="#kalkulator" class="services__link">Izračunaj cenu →</a>
        <?php if ($img_pranje_d) : ?>
        <div class="services__card-img" aria-hidden="true">
          <img src="<?php echo esc_url($img_pranje_d); ?>" alt="">
        </div>
        <?php endif; ?>
      </div>

    </div>

    <div class="services__cta">
      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'usluge' ) ) ); ?>"
         class="btn btn--white services__cta-btn">
        <?php echo $tx( 'cta_text', 'Pogledajte sve usluge' ); ?> →
      </a>
    </div>

  </div>
</section>
