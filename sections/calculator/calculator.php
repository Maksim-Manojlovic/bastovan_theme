<?php
/**
 * Section: Kalkulator cena
 * Reuse: get_template_part('sections/calculator')
 */
?>

<section class="calc section" id="kalkulator">
  <div class="container">

    <div class="calc__header">
      <div>
        <div class="text-eyebrow">Odmah i besplatno</div>
        <h2 class="heading-lg">Izračunajte cenu<br>vaše usluge</h2>
      </div>

      <p class="text-lead calc__desc">
        Unesite podatke i dobijte okvirnu cenu za samo par sekundi. Bez obaveza.
      </p>
    </div>

    <div class="calc__embed">
      <?php echo do_shortcode('[bastovanstvo_kalkulator]'); ?>
    </div>

  </div>
</section>