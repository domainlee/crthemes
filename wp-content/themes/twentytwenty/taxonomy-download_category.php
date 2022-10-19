<?php get_header(); ?>
<section class="item-download" <?php post_class(); ?> id="post-<?php the_ID(); ?>">
    <div class="section-inner">
        <?php get_template_part('loop-themes'); ?>
    </div>
</section>
<?php get_footer(); ?>
