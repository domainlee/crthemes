<?php
get_header();
?>
<section class="single-download">
    <div class="container">
        <div class="row">
            <div class="col-12">
                    <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; ?>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
    get_footer();
?>