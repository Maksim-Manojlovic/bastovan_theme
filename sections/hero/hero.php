<?php
/**
 * Hero Section
 * Reuse: get_template_part('sections/hero/hero', null, $args)
 */

$args = $args ?? [];

$theme_uri = get_template_directory_uri();


$title    = $args['title']    ?? 'Profesionalno uređivanje <br><span>i održavanje dvorišta</span><br>Beograd';
$subtitle = $args['subtitle'] ?? 'Brza, pouzdana i pristupačna usluga za vaše savršeno dvorište. Prepustite košenje, orezivanje i čišćenje nama.';
$cta_url  = $args['cta_url']  ?? '#kalkulator';
$cta_text = $args['cta_text'] ?? '🌿 Zatražite besplatnu procenu';
// $img      = $args['img']      ?? $theme_uri . '/assets/images/hero-dvoriste.jpg';

$tel = bastovan_get_contact( 'telefon' ) ?: '+381110000000';
?>

<section class="hero" id="pocetna">

  <div class="hero__bg" aria-hidden="true">
    <!-- <img
      src="<?php echo esc_url( $img ); ?>"
      alt="Uređeno dvorište — Bastovanstvo Beograd"
      loading="eager"
      fetchpriority="high"
      decoding="async"
    > -->
  </div>

  <div class="hero__grain" aria-hidden="true"></div>

  <canvas class="hero__canvas" id="hero-canvas" aria-hidden="true"></canvas>

  <div class="hero__inner container">

    

    <h1 class="hero__title">
      <?php echo wp_kses( $title, [ 'br' => [], 'span' => [] ] ); ?>
    </h1>

    <p class="hero__sub">
      <?php echo esc_html( $subtitle ); ?>
    </p>

    <div class="hero__actions">
      <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--white">
        <?php echo esc_html( $cta_text ); ?>
      </a>
      <a href="#usluge" class="btn btn--outline">
        Naše usluge →
      </a>
    </div>

    

</section>