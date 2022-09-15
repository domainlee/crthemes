<?php get_header();  ?>

<section>
        <div class="container ">
            <div class="product-single__breadcrumb">
                <?php my_breadcrumb() ?>
            </div>
            <div class="row page__row">
                <div class="page__left">
                    <h1 class="page__title"><?php my_breadcrumb2() ?></h1>
                        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <?php the_content(); ?>
                                <?php comments_template( '', true ); // Remove if you don't want comments ?>
                            </article>
                        <?php endwhile; ?>
                        <?php else: ?>
                            <article>
                                <h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
                            </article>
                        <?php endif; ?>
                </div>
                <?php
                    get_template_part('news_sidebar');
                ?>
            </div>
        </div>
</section>


<?php get_footer(); ?>
