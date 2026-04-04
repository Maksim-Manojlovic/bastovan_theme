<?php
/**
 * index.php — Glavni fallback template
 *
 * WordPress FSE koristi HTML template fajlove u /templates/
 * Ovaj fajl je fallback za stare WP verzije i direktan PHP pristup.
 */
get_header();
?>

<main class="site-main" id="main-content">
    <div class="container">
        <div class="section">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <article <?php post_class(); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_excerpt(); ?>
                    </article>
                <?php endwhile; ?>
                <?php the_posts_pagination(); ?>
            <?php else : ?>
                <p><?php _e( 'Nema sadrzaja.', 'bastovan-tema' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
