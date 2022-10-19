<?php
get_header();
?>
<section class="single-download">
    <div class="section-inner">
        <div class="single-download__inner">
            <div class="single-download__right">
                <div class="single-download__thumbnail">
                    <?php
                    the_post_thumbnail();
                    $caption = get_the_post_thumbnail_caption();
                    if ( $caption ) {
                    ?>
                        <figcaption class="wp-caption-text"><?php echo wp_kses_post( $caption ); ?></figcaption>
                    <?php
                    }
                    ?>
                </div>
                <h1 class="single-download__title"><?php the_title() ?></h1>
                <?php
                    the_content();
                ?>
            </div>
            <div class="single-download__left">
                <span><?php echo edd_price(get_the_ID()); ?></span>
                <a class="profilo__cart" href="<?= site_url().'/checkout?edd_action=add_to_cart&download_id='.get_the_ID(); ?>">Purchase</a>
            </div>
        </div>
    </div>
</section>
<?php
    get_footer();
?>