<?php
get_header();

?>
<section class="single-download">
    <div class="section-inner">
        <div class="single-download__inner">
            <div class="single-download__right">
                <div class="single-download__right--inner">
                    <div class="single-download__thumbnail">
                        <?php
                        $product = wc_get_product( get_the_ID() );
                        $url_demo = get_field('demo_url', get_the_ID());
                        $url_free_version = get_field('free_version_url', get_the_ID());
                        the_post_thumbnail();
                        $caption = get_the_post_thumbnail_caption();
                        if ( $caption ) {
                            ?>
                            <figcaption class="wp-caption-text"><?php echo wp_kses_post( $caption ); ?></figcaption>
                            <?php
                        }
                        ?>
                        <div class="theme__action">
                            <?php if($url_free_version): ?>
                                <a class="theme__cart" target="_blank" href="<?= $url_free_version ? $url_free_version:'#' ?>"><span class="dashicons dashicons-wordpress-alt"></span>Free Version</a>
                            <?php endif; ?>
                            <a class="theme__live-view" target="_blank" href="<?= $url_demo ? $url_demo:'#' ?>">Live Preview</a>
                        </div>
                    </div>
                    <div class="single-download__right--content">
                        <div class="single-download__left--inner">
                            <div class="single-download__left--price">
                                <?php
                                    echo '<a href="?add-to-cart=' . $product->get_id() . '&quantity=1" data-quantity="1" data-product_id="' . $product->get_id() . '" class="theme__cart color-accent add_to_cart_button ajax_add_to_cart">Purchase</a>';
                                ?>
                                <strong><?php woocommerce_template_single_price(); ?></strong>
                            </div>
                            <p class="single-download__left--noti">Pay securely with Paypal</p>
                            <div class="single-download__left--noti">
                                <p><strong>30-day money-back guarantee</strong></p>
                                <p>
                                    Enjoy CRThemes products completely risk-free. If you don't like our products, we'll be happy to offer you a full 100% refund within 30 days of purchase with no questions asked.
                                </p>
                            </div>
                        </div>
                        <h1 class="single-download__title"><?php the_title() ?></h1>
                        <?php
                            the_content();
                        ?>
                    </div>
                </div>
            </div>
            <div class="single-download__left">
                <div class="single-download__left--inner">
                    <div class="single-download__left--price">
                        <?php
                            echo '<a href="?add-to-cart=' . $product->get_id() . '&quantity=1" data-quantity="1" data-product_id="' . $product->get_id() . '" class="theme__cart color-accent add_to_cart_button ajax_add_to_cart">Purchase</a>';
                        ?>
                        <strong><?php woocommerce_template_single_price(); ?></strong>
                    </div>
                    <p class="single-download__left--noti">Pay securely with Paypal</p>
                    <div class="single-download__left--noti">
                        <p><strong>30-day money-back guarantee</strong></p>
                        <p>
                            Enjoy CRThemes products completely risk-free. If you don't like our products, we'll be happy to offer you a full 100% refund within 30 days of purchase with no questions asked.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .single-download__right .single-download__left--inner {
        display: none;
    }
    .single-download__left--price strong p {
        margin: 0;
    }
    a.added_to_cart.wc-forward {
        border: 1px solid;
    }
    .theme__cart.added {
        background-color: #DDD;
        border-color: #DDD;
    }
    @media (max-width:576px) {
        .single-download {
            padding: 0;
        }
        .single-download__right .single-download__left--inner {
            display: block;
            padding: 50px 20px 15px;
        }
        .single-download__inner {
            margin: 0 -15px;
            flex-wrap: wrap;
        }
        .single-download__right, .single-download__left {
            width: 100%;
        }
        .single-download__left {
            display: none;
        }
        .single-download__title {
            font-size: 32px;
            margin: 20px 0 10px;
        }
        .single-download__right ul {
            margin: 0;
        }
        .single-download__right .single-download__thumbnail {
            padding: 0;
        }
    }
</style>
<?php
get_footer();
?>