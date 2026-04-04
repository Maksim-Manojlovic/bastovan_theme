<?php
/**
 * page.php — Template za obicne stranice
 */
get_header();
?>

<main class="site-main" id="main-content">
    <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class(); ?>>
            <div class="container section">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
