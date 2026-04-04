<?php
/**
 * Section: Kontakt stranica
 * Reuse: get_template_part('sections/contact-page/contact-page')
 */
?>

<section class="contact section-lg" id="kontakt">
  <div class="container">

    <div class="contact__header stack-sm">
      <div class="text-eyebrow">Kontakt</div>
      <h1 class="heading-xl">Pošaljite upit za<br>uređenje dvorišta</h1>
      <p class="text-lead contact__lead">
        Odgovaramo u roku od 24–48h.<br>
        Opišite dvorište i dobićete okvirnu ponudu.<br>
        Email adresa:<br>
        Broj telefona:
      </p>
    </div>

    <div class="contact__form-wrap">
      <?php echo do_shortcode( '[wpforms id="81"]' ); ?>
    </div>

    

  </div>
</section>