<?php get_header(); ?>
<main role="main">
    <!-- section -->
    <h2>haa</h2>
    <section>
        <h1><?php _e( 'Archives', 'html5blank' ); ?></h1>

        <?php get_template_part('loop'); ?>

        <?php get_template_part('pagination'); ?>

    </section>
    <!-- /section -->
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
