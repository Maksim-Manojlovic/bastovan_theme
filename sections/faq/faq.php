<?php
/**
 * Section: FAQ
 * Reuse: get_template_part('sections/faq/faq')
 */

$pitanja = [
  [
    'pitanje' => 'Koliko košta uređivanje dvorišta u Beogradu?',
    'odgovor' => 'Cena uređivanja dvorišta u Beogradu zavisi od veličine površine, vrste radova i stanja terena. Osnovne usluge poput košenja trave su pristupačne, dok kompleksniji radovi poput planiranja dvorišta ili orezivanja drveća zahtevaju detaljniju procenu. Nudimo besplatnu procenu na terenu kako bismo vam dali tačnu cenu.',
  ],
  [
    'pitanje' => 'Da li nudite besplatnu procenu dvorišta?',
    'odgovor' => 'Da, nudimo besplatan izlazak na teren u Beogradu. Na licu mesta procenjujemo stanje dvorišta i predlažemo najbolje rešenje za uređivanje i održavanje.',
  ],
  [
    'pitanje' => 'Koje usluge održavanja dvorišta nudite?',
    'odgovor' => 'Nudimo kompletne usluge uređivanja i održavanja dvorišta u Beogradu: košenje trave, orezivanje žive ograde i drveća, uklanjanje korova, planiranje dvorišta, pranje i čišćenje staza. Sve usluge prilagođavamo vašim potrebama.',
  ],
  [
    'pitanje' => 'Koliko često treba kositi travu?',
    'odgovor' => 'Košenje trave se preporučuje jednom nedeljno u sezoni rasta (proleće i leto), dok je u jesen i zimu ređe potrebno. Redovno održavanje travnjaka doprinosi zdravijem i lepšem dvorištu.',
  ],
  [
    'pitanje' => 'Da li radite održavanje dvorišta za firme i stambene zgrade?',
    'odgovor' => 'Da, pružamo usluge održavanja dvorišta za privatne kuće, firme i stambene zajednice u Beogradu. Nudimo i redovno mesečno održavanje.',
  ],
  [
    'pitanje' => 'Da li uklanjate i odnosite otpad nakon radova?',
    'odgovor' => 'Da, nakon svih radova (košenje, orezivanje, čišćenje) uklanjamo i odnosimo sav biljni otpad kako bi vaše dvorište ostalo potpuno uredno.',
  ],
  [
    'pitanje' => 'Koliko traje uređivanje dvorišta?',
    'odgovor' => 'Trajanje radova zavisi od veličine dvorišta i vrste usluge. Manji radovi poput košenja mogu biti završeni za nekoliko sati, dok kompleksniji projekti mogu trajati više dana.',
  ],
  [
    'pitanje' => 'Da li radite hitne intervencije (zaraslo dvorište)?',
    'odgovor' => 'Da, radimo i sređivanje zapuštenih i zaraslih dvorišta u Beogradu. U takvim slučajevima pravimo plan rada i brzo vraćamo dvorište u uredno stanje.',
  ],
  [
    'pitanje' => 'Da li koristite profesionalnu opremu?',
    'odgovor' => 'Da, koristimo profesionalnu opremu za košenje, orezivanje i čišćenje kako bismo obezbedili kvalitetan i dugotrajan rezultat.',
  ],
  [
    'pitanje' => 'Kako da zakažem uređivanje dvorišta?',
    'odgovor' => 'Možete nas kontaktirati telefonom ili putem sajta. Takođe, možete koristiti naš kalkulator cena i zatražiti besplatnu procenu.',
  ],
];
?>

<section class="faq section" id="faq">
  <div class="container">

    <div class="faq__header stack-sm">
      <div class="text-eyebrow">Česta pitanja</div>
      <h2 class="heading-lg">Sve što vas zanima o<br>uređivanju dvorišta</h2>
      <p class="text-lead">Odgovori na najčešća pitanja naših klijenata u Beogradu.</p>
    </div>

    <div class="faq__list" itemscope itemtype="https://schema.org/FAQPage">

      <?php foreach ( $pitanja as $i => $item ) : ?>
      <div class="faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">

        <button
          class="faq__question<?php echo $i === 0 ? ' faq__question--open' : ''; ?>"
          aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>"
          aria-controls="faq-answer-<?php echo $i; ?>"
          itemprop="name"
        >
          <span><?php echo esc_html( $item['pitanje'] ); ?></span>
          <span class="faq__icon" aria-hidden="true"></span>
        </button>

        <div
          class="faq__answer<?php echo $i === 0 ? ' faq__answer--open' : ''; ?>"
          id="faq-answer-<?php echo $i; ?>"
          itemscope
          itemprop="acceptedAnswer"
          itemtype="https://schema.org/Answer"
        >
          <p itemprop="text"><?php echo esc_html( $item['odgovor'] ); ?></p>
        </div>

      </div>
      <?php endforeach; ?>

    </div>

    <div class="faq__cta">
      <p class="faq__cta-text">Niste pronašli odgovor? Kontaktirajte nas direktno.</p>
      <a href="#kontakt" class="btn btn--primary">Pošaljite upit →</a>
    </div>

  </div>
</section>
