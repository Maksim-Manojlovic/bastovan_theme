<?php
/**
 * Section: FAQ — 2 columns, v2
 * Reuse: get_template_part('sections/faq/faq')
 */
$_faq_imgs  = get_option( 'bastovan_faq_images', [] );
$faq_bg_url = wp_get_attachment_image_url( $_faq_imgs['faq_img_bg'] ?? 0, 'full' );

$_faq_txts = array_merge( bastovan_faq_defaults(), get_option( 'bastovan_faq_texts', [] ) );
$ftx = fn( string $key ): string => esc_html( $_faq_txts[ $key ] ?? '' );
$ftx_raw = fn( string $key ): string => $_faq_txts[ $key ] ?? '';

$pitanja = [];
for ( $i = 1; $i <= 10; $i++ ) {
    $p = $_faq_txts[ "q{$i}_pitanje" ] ?? '';
    $o = $_faq_txts[ "q{$i}_odgovor" ] ?? '';
    if ( $p ) $pitanja[] = [ 'pitanje' => $p, 'odgovor' => $o ];
}

$kolona1 = array_slice( $pitanja, 0, 5 );
$kolona2 = array_slice( $pitanja, 5 );
?>

<section class="faq section" id="faq" itemscope itemtype="https://schema.org/FAQPage"<?php if ( $faq_bg_url ) : ?> style="background-image:url(<?php echo esc_url( $faq_bg_url ); ?>)"<?php endif; ?>>
  <?php if ( $faq_bg_url ) : ?>
  <div class="faq__overlay" aria-hidden="true"></div>
  <?php endif; ?>
  <div class="container">

    <div class="faq__header stack-sm">
      <div class="text-eyebrow"><?php echo $ftx( 'eyebrow' ); ?></div>
      <h2 class="heading-lg"><?php echo nl2br( $ftx( 'heading' ) ); ?></h2>
      <p class="text-lead"><?php echo $ftx( 'lead' ); ?></p>
    </div>

    <div class="faq__grid">

      <?php foreach ( [ $kolona1, $kolona2 ] as $kolona_idx => $kolona ) : ?>
      <div class="faq__col">
        <?php foreach ( $kolona as $j => $item ) :
          $i = $kolona_idx * 5 + $j;
        ?>
        <div class="faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">

          <button
            class="faq__question"
            aria-expanded="false"
            aria-controls="faq-answer-<?php echo $i; ?>"
            itemprop="name"
          >
            <span><?php echo esc_html( $item['pitanje'] ); ?></span>
            <span class="faq__icon" aria-hidden="true"></span>
          </button>

          <div
            class="faq__answer"
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
      <?php endforeach; ?>

    </div>

    <div class="faq__cta">
      <p class="faq__cta-text"><?php echo $ftx( 'cta_text' ); ?></p>
      <a href="<?php echo esc_url( $ftx_raw( 'cta_url' ) ); ?>" class="btn btn--primary"><?php echo $ftx( 'cta_btn' ); ?></a>
    </div>

  </div>
</section>
