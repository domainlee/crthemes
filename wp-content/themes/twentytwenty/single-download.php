<?php
get_header();

?>
<section class="single-download">
    <div class="section-inner">
        <div class="single-download__inner">
            <div class="single-download__right">
                <div class="single-download__thumbnail">
                    <?php
                    $url_demo = get_field('demo_url', get_the_ID());
                    the_post_thumbnail();
                    $caption = get_the_post_thumbnail_caption();
                    if ( $caption ) {
                    ?>
                        <figcaption class="wp-caption-text"><?php echo wp_kses_post( $caption ); ?></figcaption>
                        <?php
                    }
                    ?>
                    <div>
                        <a class="profilo__live-view" target="_blank" href="<?= $url_demo ? $url_demo:'#' ?>">Live Preview</a>
                    </div>
                </div>
                <h1 class="single-download__title"><?php the_title() ?></h1>
                <?php
                    the_content();
                ?>
            </div>
            <div class="single-download__left">
                <div class="single-download__left--inner">
                    <div class="single-download__left--price">
                        <a class="color-accent profilo__cart" href="<?= site_url().'/checkout?edd_action=add_to_cart&download_id='.get_the_ID(); ?>">Purchase</a>
                        <strong><?php echo edd_price(get_the_ID()); ?></strong>
                    </div>
                    <p class="single-download__left--noti">No support for free themes</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
    get_footer();
?>