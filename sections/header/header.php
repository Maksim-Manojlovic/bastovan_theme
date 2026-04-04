<?php
/**
 * Section: Header
 * Poziva se iz header.php u rootu teme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$telefon = bastovan_get_contact( 'telefon' );
?>

<header class="site-header" role="banner" id="site-header">
  <div class="container site-header__inner">

    <!-- LOGO -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
       class="site-logo"
       rel="home"
       aria-label="<?php bloginfo( 'name' ); ?> — početna">

      <img src="http://localhost:10089/wp-content/uploads/2026/03/Gradski-bastovan-logo-02-scaled.webp"
           alt="<?php bloginfo( 'name' ); ?>"
           class="site-logo__img" />

    </a>

    <!-- NAVIGACIJA -->
    <?php if ( has_nav_menu( 'primary-menu' ) ) : ?>
      <nav class="site-nav" id="site-nav" aria-label="Glavna navigacija">
        <?php wp_nav_menu( [
          'theme_location' => 'primary-menu',
    'menu_class'     => 'site-nav__list',
    'container'      => false,
    'walker'         => new Bastovan_Nav_Walker(),

        ] ); ?>
      </nav>
    <?php endif; ?>

    <!-- CTA -->
    <div class="site-header__cta">
      <?php if ( $telefon ) : ?>
        <a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', $telefon ) ); ?>"
           class="btn btn--green btn--sm">
          📞 <?php echo esc_html( $telefon ); ?>
        </a>
      <?php else : ?>
        <a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>"
           class="btn btn--green btn--sm">
          Kontaktirajte nas
        </a>
      <?php endif; ?>
    </div>

    <!-- MOBILE TOGGLE -->
    <button class="site-header__toggle"
            id="nav-toggle"
            aria-expanded="false"
            aria-controls="site-nav"
            aria-label="Otvori meni">
      <span class="toggle-icon toggle-open" aria-hidden="true">☰</span>
      <span class="toggle-icon toggle-close" aria-hidden="true">✕</span>
    </button>

  </div>
</header>
