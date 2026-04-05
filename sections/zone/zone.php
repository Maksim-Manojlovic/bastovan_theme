<?php
/**
 * Section: Zone dolaska
 * Reuse: get_template_part('sections/zone/zone')
 */

$zone = [
  [
    'broj'    => '01',
    'naziv'   => 'Blizina',
    'opis'    => 'do 7 km',
    'cena'    => '+1.500 RSD',
    'akcent'  => '#cee07d',
    'opstine' => [ 'Bezanijska kosa', 'Novi Beograd', 'Zemun', 'Surčin' ],
  ],
  [
    'broj'    => '02',
    'naziv'   => 'Srednja blizina',
    'opis'    => '7 – 15 km',
    'cena'    => '+1.800 RSD',
    'akcent'  => '#8ecfaa',
    'opstine' => [ 'Čukarica', 'Savski venac', 'Stari grad', 'Rakovica', 'Voždovac', 'Zvezdara', 'Palilula', 'Vračar' ],
  ],
  [
    'broj'    => '03',
    'naziv'   => 'Udaljene opštine',
    'opis'    => '15 – 30 km',
    'cena'    => '+2.300 RSD',
    'akcent'  => '#f0b429',
    'opstine' => [ 'Grocka', 'Barajevo', 'Obrenovac', 'Sopot' ],
  ],
  [
    'broj'    => '04',
    'naziv'   => 'Najudaljenije opštine',
    'opis'    => 'preko 30 km',
    'cena'    => '+2.600 RSD',
    'akcent'  => '#e07a5f',
    'opstine' => [ 'Mladenovac', 'Lazarevac' ],
  ],
];
?>

<section class="zone section" id="zone">
  <div class="container">

    <div class="zone__header stack-sm">
      <div class="text-eyebrow zone__eyebrow">Pokrivenost</div>
      <h2 class="heading-lg zone__heading">Dolazimo do vas —<br>gde god u Beogradu</h2>
      <p class="zone__lead">Transparentne cene dolaska bez iznenađenja.<br>Pronađite svoju opštinu i saznajte cenu odmah.</p>
    </div>

    <div class="zone__list">

      <?php foreach ( $zone as $i => $z ) : ?>
      <div
        class="zone__item"
        style="--zone-akcent: <?php echo esc_attr( $z['akcent'] ); ?>;"
      >

        <button
          class="zone__trigger"
          aria-expanded="false"
          aria-controls="zone-panel-<?php echo $i; ?>"
        >
          <div class="zone__meta">
            <span class="zone__num"><?php echo esc_html( $z['broj'] ); ?></span>
            <div class="zone__info">
              <span class="zone__name"><?php echo esc_html( $z['naziv'] ); ?></span>
              <span class="zone__range"><?php echo esc_html( $z['opis'] ); ?></span>
            </div>
          </div>

          <div class="zone__right">
            <span class="zone__price"><?php echo esc_html( $z['cena'] ); ?></span>
            <span class="zone__arrow" aria-hidden="true">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M2 5l5 5 5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </div>
        </button>

        <div class="zone__panel" id="zone-panel-<?php echo $i; ?>">
          <div class="zone__chips">
            <?php foreach ( $z['opstine'] as $opstina ) : ?>
            <span class="zone__chip">
              <svg class="zone__chip-icon" width="10" height="12" viewBox="0 0 10 12" fill="none">
                <path d="M5 0C2.8 0 1 1.8 1 4c0 3 4 8 4 8s4-5 4-8c0-2.2-1.8-4-4-4zm0 5.5C4.2 5.5 3.5 4.8 3.5 4S4.2 2.5 5 2.5 6.5 3.2 6.5 4 5.8 5.5 5 5.5z" fill="currentColor"/>
              </svg>
              <?php echo esc_html( $opstina ); ?>
            </span>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
      <?php endforeach; ?>

    </div>

    <div class="zone__footer">
      <p class="zone__footer-text">Niste sigurni koja je vaša zona? Kalkulator to obračunava automatski.</p>
      <a href="#kalkulator" class="btn btn--outline">Izračunaj cenu →</a>
    </div>

  </div>
</section>
