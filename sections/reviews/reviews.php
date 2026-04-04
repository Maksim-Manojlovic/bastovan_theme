<?php
/**
 * Section: Recenzije
 * Reuse: get_template_part('sections/reviews/reviews')
 */

$recenzije = [
  [ 'tekst' => 'Đuka i ekipa su uradili fenomenalan posao sa mojim dvorištem. Stigli su na vreme, rade brzo i pedantno, a cena je potpuno fer. Preporučujem svima!', 'ime' => 'Petar P.',               'lokacija' => 'Voždovac · Košenje trave',         'zvezde' => 5 ],
  [ 'tekst' => 'Zakažite ih odmah — nećete se pokajati. Živa ograda izgleda savršeno, a dvorište kao iz kataloga. Definitivno ćemo nastaviti saradnju.',            'ime' => 'Milica J.',              'lokacija' => 'Zemun · Orezivanje ograde',        'zvezde' => 5 ],
  [ 'tekst' => 'Konačno neko ko dođe kada kaže i uradi šta obeća. Staze su čiste kao nikad, a korov koji je godinama bio problem — nestao za sat vremena.',         'ime' => 'Dragan M.',              'lokacija' => 'Čukarica · Čišćenje staza',        'zvezde' => 5 ],
  [ 'tekst' => 'Planiranje bašte koje su predložili bilo je stručno i praktično. Biljke su na pravim mestima i travnjak izgleda odlično svake sezone.',              'ime' => 'Jelena S.',              'lokacija' => 'Palilula · Planiranje bašte',      'zvezde' => 5 ],
  [ 'tekst' => 'Koristimo ih za održavanje zajedničkog dvorišta u zgradi već godinu dana. Uvek tačni, uvek uredni. Stanari su oduševljeni.',                         'ime' => 'Stanarska zajednica B.', 'lokacija' => 'Novi Beograd · Redovno održavanje', 'zvezde' => 5 ],
];
?>

<section class="reviews section" id="recenzije">
  <div class="container">

    <div class="reviews__header stack-sm">
      <div class="text-eyebrow">Google recenzije</div>
      <h2 class="heading-lg">Iskustvo naših klijenata</h2>
      <p class="text-lead">Poverenje gradimo jednim dvorištem u jednom trenutku.</p>
    </div>

    <div class="reviews__track" id="reviews-track">
      <?php foreach ( $recenzije as $r ) :
        $inicijal = mb_strtoupper( mb_substr( $r['ime'], 0, 1 ) );
      ?>
      <div class="reviews__card">

        <div class="reviews__stars">
          <?php for ( $s = 0; $s < $r['zvezde']; $s++ ) : ?>
            <span class="reviews__star">★</span>
          <?php endfor; ?>
        </div>

        <div class="reviews__quote" aria-hidden="true">"</div>

        <p class="reviews__text">"<?php echo esc_html( $r['tekst'] ); ?>"</p>

        <div class="reviews__author">
          <div class="reviews__avatar"><?php echo esc_html( $inicijal ); ?></div>
          <div>
            <span class="reviews__name"><?php echo esc_html( $r['ime'] ); ?></span>
            <span class="reviews__meta"><?php echo esc_html( $r['lokacija'] ); ?></span>
          </div>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

    <div class="reviews__controls">
      <button class="reviews__btn" id="reviews-prev" aria-label="Prethodna recenzija">←</button>
      <button class="reviews__btn" id="reviews-next" aria-label="Sledeća recenzija">→</button>
    </div>

  </div>
</section>