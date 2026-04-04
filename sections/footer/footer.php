<?php
/**
 * Footer — Globalni footer
 * Poziva se sa get_footer()
 */

$telefon   = bastovan_get_contact( 'telefon' );
$email     = bastovan_get_contact( 'email' );
$adresa    = bastovan_get_contact( 'adresa' );
$instagram = bastovan_get_contact( 'instagram' );
$facebook  = bastovan_get_contact( 'facebook' );
$year      = date( 'Y' );
$site_name = get_bloginfo( 'name' );
?>

<footer class="footer" role="contentinfo" id="kontakt">
  <div class="container">

    <!-- CTA Banner -->
    <div class="footer__cta">
      <div>
        <h2 class="footer__cta-heading">Zakažite sređivanje<br>dvorišta već danas.</h2>
        <p class="footer__cta-sub">Besplatan izlazak na teren · Bez skrivenih troškova</p>
      </div>
      <div class="footer__cta-actions">
        <a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', $telefon ?: '+381110000000' ) ); ?>"
           class="btn btn--white">📞 Pozovite odmah</a>
        <a href="<?php echo esc_url( home_url( '/kalkulator' ) ); ?>"
           class="btn btn--ghost">Izračunaj cenu</a>
      </div>
    </div>

    <!-- Footer grid -->
    <div class="footer__grid">

      <!-- Brend -->
      <div class="footer__brand">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__logo">
          <img src="https://gradskibastovan.rs/wp-content/uploads/2026/03/Gradski-bastovan-logo-01-scaled.webp"
               alt="Gradski Baštovan" class="footer__logo-icon">
          <span class="footer__logo-text"><?php echo esc_html( $site_name ); ?></span>
        </a>
        <p class="footer__desc">
          Profesionalno uređivanje i održavanje dvorišta na teritoriji Beograda.
          Vaša bašta u sigurnim rukama.
        </p>
        <?php if ( $facebook || $instagram ) : ?>
          <div class="footer__social">
            <?php if ( $facebook ) : ?>
              <a href="<?php echo esc_url( $facebook ); ?>" class="footer__social-link"
                 target="_blank" rel="noopener noreferrer" aria-label="Facebook">fb</a>
            <?php endif; ?>
            <?php if ( $instagram ) : ?>
              <a href="<?php echo esc_url( $instagram ); ?>" class="footer__social-link"
                 target="_blank" rel="noopener noreferrer" aria-label="Instagram">ig</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Usluge -->
      <div>
        <span class="footer__col-title">Usluge</span>
        <?php
        $footer_usluge = new WP_Query( [
          'post_type'      => 'usluga',
          'posts_per_page' => 4,
          'meta_key'       => '_bastovan_istaknuto',
          'meta_value'     => '1',
          'orderby'        => 'menu_order',
          'order'          => 'ASC',
        ] );
        if ( $footer_usluge->have_posts() ) : ?>
          <ul class="footer__links">
            <?php while ( $footer_usluge->have_posts() ) : $footer_usluge->the_post(); ?>
              <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; wp_reset_postdata(); ?>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Navigacija -->
      <div>
        <span class="footer__col-title">Navigacija</span>
        <?php wp_nav_menu( [
          'theme_location' => 'footer-menu',
          'menu_class'     => 'footer__links',
          'container'      => false,
          'depth'          => 1,
          'fallback_cb'    => false,
        ] ); ?>
      </div>

      <!-- Kontakt -->
      <div>
        <span class="footer__col-title">Kontakt</span>
        <?php if ( $telefon ) : ?>
          <div class="footer__contact-item">
            <div class="footer__contact-icon">📞</div>
            <div>
              <span class="footer__contact-label">Telefon</span>
              <span class="footer__contact-val">
                <a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', $telefon ) ); ?>">
                  <?php echo esc_html( $telefon ); ?>
                </a>
              </span>
            </div>
          </div>
        <?php endif; ?>
        <?php if ( $email ) : ?>
          <div class="footer__contact-item">
            <div class="footer__contact-icon">✉️</div>
            <div>
              <span class="footer__contact-label">Email</span>
              <span class="footer__contact-val">
                <a href="mailto:<?php echo esc_attr( $email ); ?>">
                  <?php echo esc_html( $email ); ?>
                </a>
              </span>
            </div>
          </div>
        <?php endif; ?>
        <div class="footer__contact-item">
          <div class="footer__contact-icon">🕐</div>
          <div>
            <span class="footer__contact-label">Radno vreme</span>
            <span class="footer__contact-val">Pon–Sub: 07:00–20:00<br>Ned: Po dogovoru</span><br>
            <span class="footer__contact-val">Broj telefona: </span><br>
            <span class="footer__contact-val">Email adresa:</span>
          </div>
        </div>
        <?php if ( $adresa ) : ?>
          <div class="footer__contact-item">
            <div class="footer__contact-icon">📍</div>
            <div>
              <span class="footer__contact-label">Lokacija</span>
              <span class="footer__contact-val"><?php echo esc_html( $adresa ); ?></span>
            </div>
          </div>
        <?php endif; ?>
      </div>

    </div><!-- /footer__grid -->

    <!-- Bottom bar -->
    <div class="footer__bottom">
      <span class="footer__copy">
        &copy; <?php echo esc_html( $year ); ?> <?php echo esc_html( $site_name ); ?>. Sva prava zadržana.
      </span>
      <nav class="footer__legal" aria-label="Pravni linkovi">
        <?php wp_nav_menu( [
          'theme_location' => 'legal-menu',
          'menu_class'     => 'footer__legal-list',
          'container'      => false,
          'depth'          => 1,
          'fallback_cb'    => false,
        ] ); ?>
      </nav>
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>