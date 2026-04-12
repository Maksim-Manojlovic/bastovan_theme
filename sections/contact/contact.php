<?php
/**
 * Section: Kontakt stranica
 * Reuse: get_template_part('sections/contact-page/contact-page')
 */

$telefon = bastovan_get_contact( 'telefon' );
$email   = bastovan_get_contact( 'email' );
?>

<section class="contact section-lg" id="kontakt">
  <div class="container">

    <div class="contact__header stack-sm">
      <div class="text-eyebrow">Kontakt</div>
      <h1 class="heading-xl">Pošaljite upit za<br>uređenje dvorišta</h1>
      <p class="text-lead contact__lead">
        Odgovaramo u roku od 24–48h.<br>
        Opišite dvorište i dobićete okvirnu ponudu.
      </p>
      <div class="contact__info">
        <?php if ( $email ) : ?>
        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="contact__info-item">
          <span class="contact__info-label">Email</span>
          <span class="contact__info-value"><?php echo esc_html( $email ); ?></span>
        </a>
        <?php endif; ?>
        <?php if ( $telefon ) : ?>
        <a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', $telefon ) ); ?>" class="contact__info-item">
          <span class="contact__info-label">Telefon</span>
          <span class="contact__info-value"><?php echo esc_html( $telefon ); ?></span>
        </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="contact__form-wrap">
      <?php echo do_shortcode( '[wpforms id="81"]' ); ?>
    </div>

    

  </div>
</section>