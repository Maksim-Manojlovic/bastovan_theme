<?php
/**
 * Services Section
 * Reuse: get_template_part('sections/services/services')
 */
$img = wp_get_attachment_image_url(221, 'full');
$theme_uri = get_template_directory_uri();
?>

<section class="services section" id="usluge">
  <div class="container">

    <div class="services__header stack-sm">
      <div class="text-eyebrow">Šta nudimo</div>
      <h2 class="heading-lg">Usluge uređivanja i<br>održavanja dvorišta</h2>
    </div>

    <div class="services__grid">

      <!-- Featured kartica -->
      <div class="services__card services__card--featured">
        <div class="services__card-content">
          <div class="services__icon">🗺️</div>
          <h3 class="services__name">Planiranje dvorišta</h3>
          <p class="services__desc">
            Besplatan izlazak na teren. Detaljno planiramo sadnju,
            košenje i ostale radove kako bismo osigurali
            najefikasniji rast biljaka i najbolje rezultate.
          </p>
          <a href="#kontakt" class="services__link">Zakaži besplatnu procenu →</a>
        </div>
        <div class="services__img">
          <img
           src="<?php echo esc_url($img); ?>"
            alt="Planiranje dvorišta"
            loading="lazy"
          >
          <div class="services__img-placeholder">🌱</div>
        </div>
      </div>

      <!-- Košenje -->
      <div class="services__card">
        <div class="services__icon">🌿</div>
        <h3 class="services__name">Košenje trave</h3>
        <p class="services__desc">
          Brzo i pedantno košenje svih površina.
          Travnjak održavamo urednim,
          a pokošenu travu sakupljamo i odnosimo.
        </p>
        <a href="#kalkulator" class="services__link">Izračunaj cenu →</a>
      </div>

      <!-- Orezivanje -->
      <div class="services__card">
        <div class="services__icon">✂️</div>
        <h3 class="services__name">Orezivanje žive ograde i drveća</h3>
        <p class="services__desc">
          Precizno oblikovanje za zdrav rast biljaka
          i estetski savršeno dvorište.
        </p>
        <a href="#kalkulator" class="services__link">Izračunaj cenu →</a>
      </div>

      <!-- Korov -->
      <div class="services__card">
        <div class="services__icon">🌾</div>
        <h3 class="services__name">Uklanjanje korova</h3>
        <p class="services__desc">
          Temeljno čišćenje i efikasno suzbijanje
          neželjenog rastinja iz cvećnjaka,
          staza i travnjaka.
        </p>
        <a href="#kalkulator" class="services__link">Izračunaj cenu →</a>
      </div>

      <!-- Pranje -->
      <div class="services__card">
        <div class="services__icon">💧</div>
        <h3 class="services__name">Pranje i čišćenje staza</h3>
        <p class="services__desc">
          Pranje pod visokim pritiskom —
          uklanjamo mahovinu i prljavštinu
          sa svih tvrdih podloga.
        </p>
        <a href="#kalkulator" class="services__link">Izračunaj cenu →</a>
      </div>

    </div>

    <div class="services__cta">
      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'usluge' ) ) ); ?>"
         class="btn btn--white services__cta-btn">
        Pogledajte sve usluge →
      </a>
    </div>

  </div>
</section>