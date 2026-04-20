<?php
/**
 * Hero Section
 * Reuse: get_template_part('sections/hero/hero')
 */

$_hero    = array_merge( bastovan_hero_defaults(), get_option( 'bastovan_hero', [] ) );
$bg_url   = wp_get_attachment_image_url( $_hero['img_bg'] ?? 0, 'full' );

$title     = $_hero['title'];
$subtitle  = esc_html( $_hero['subtitle'] );
$cta_text  = esc_html( $_hero['cta_text'] );
$cta_url   = esc_url( $_hero['cta_url'] );
$btn2_text = esc_html( $_hero['btn2_text'] );
$btn2_url  = esc_url( $_hero['btn2_url'] );

$tel = bastovan_get_contact( 'telefon' ) ?: '+381110000000';
?>

<section class="hero" id="pocetna">

  <div class="hero__bg" aria-hidden="true">
    <?php if ( $bg_url ) : ?>
    <img
      src="<?php echo esc_url( $bg_url ); ?>"
      alt=""
      loading="eager"
      fetchpriority="high"
      decoding="async"
    >
    <?php endif; ?>
  </div>

  <div class="hero__grain" aria-hidden="true"></div>

  <canvas class="hero__canvas" id="hero-canvas" aria-hidden="true"></canvas>

  <div class="hero__inner container">

    <h1 class="hero__title">
      <?php echo wp_kses( $title, [ 'br' => [], 'span' => [] ] ); ?>
    </h1>

    <p class="hero__sub">
      <?php echo $subtitle; ?>
    </p>

    <div class="hero__actions">
      <a href="<?php echo $cta_url; ?>" class="btn btn--white">
        <?php echo $cta_text; ?>
      </a>
      <a href="<?php echo $btn2_url; ?>" class="btn btn--outline">
        <?php echo $btn2_text; ?>
      </a>
    </div>

</section>
