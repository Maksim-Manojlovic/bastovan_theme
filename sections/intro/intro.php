<?php
/**
 * Intro Section
 * Reuse: get_template_part('sections/intro/intro', null, $args)
 */

$args = $args ?? [];

$theme_uri = get_template_directory_uri();

$img_id = 216; // ID slike u Media Library
$img_url = $args['img'] ?? wp_get_attachment_image_url($img_id, 'full');
$years = $args['years'] ?? '10+';
$title = $args['title'] ?? 'Vaš pouzdan partner<br>za savršeno dvorište';

$tel = bastovan_get_contact( 'telefon' ) ?: '+381110000000';
?>

<section class="intro section" id="o-nama">
  <div class="intro__inner container">

    <div class="intro__visual">

      <div class="intro__circle" aria-hidden="true"></div>

      <div class="intro__img-wrap">
        <img
          src="<?php echo esc_url($img_url); ?>"
    srcset="<?php echo esc_attr( wp_get_attachment_image_srcset($img_id) ); ?>"
    sizes="(max-width: 768px) 100vw, 50vw"
    alt="Naš tim na terenu"
    loading="lazy"
    decoding="async"
        >
      </div>

      <div class="intro__badge">
        <span class="intro__badge-num"><?php echo esc_html( $years ); ?></span>
        <span class="intro__badge-label">godina iskustva</span>
      </div>

    </div>

    <div class="intro__content stack-md">

      <div class="text-eyebrow">O nama</div>

      <h2 class="heading-lg">
        <?php echo wp_kses( $title, [ 'br' => [] ] ); ?>
      </h2>

      <p class="text-lead">
        Uživamo u sređivanju dvorišta, košenju i zajedničkom planiranju vaše savršene bašte.
        Radimo brzo i pedantno, pružajući usluge vlasnicima kuća, stambenim zajednicama i
        firmama na teritoriji Beograda.
      </p>

      <ul class="intro__checklist">
        <li>
          <span class="intro__check-icon">✓</span>
          Besplatan izlazak na teren i procena pre svake intervencije
        </li>
        <li>
          <span class="intro__check-icon">✓</span>
          Vlastita oprema i tim, nema kašnjenja ni iznenađenja
        </li>
        <li>
          <span class="intro__check-icon">✓</span>
          Radimo za privatna domaćinstva, stambene zajednice i firme
        </li>
        <li>
          <span class="intro__check-icon">✓</span>
          Cena se dogovara unapred, nema skrivenih troškova
        </li>
      </ul>

      <a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', $tel ) ); ?>"
         class="btn btn--green">
        📞 Pozovite nas
      </a>

    </div>

  </div>
</section>