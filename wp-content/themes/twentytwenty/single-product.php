<?php
get_header();

?>
    <section class="single-download">
        <div class="section-inner">
            <div class="single-download__inner">
                <?php if(get_the_ID() == 617): //617  ?>
                    <div class="virtual-host-imported-theme-demo">
                        <?php
                            $product = wc_get_product( get_the_ID() );
                            $url_demo = get_field('demo_url', get_the_ID());
                            $url_free_version = get_field('free_version_url', get_the_ID());
                            the_post_thumbnail();
                            $caption = get_the_post_thumbnail_caption();
                            if ( $caption ) :
                        ?>
                            <figcaption class="wp-caption-text"><?php echo wp_kses_post( $caption ); ?></figcaption>
                            <?php endif; ?>
                        <?php do_action('woocommerce_variable_add_to_cart'); ?>
                    </div>
                <?php else: ?>
                    <div class="single-download__right">
                        <div class="single-download__right--inner">
                            <div class="single-download__thumbnail">
                                <?php
                                $product = wc_get_product( get_the_ID() );
                                $url_demo = get_field('demo_url', get_the_ID());
                                $url_free_version = get_field('free_version_url', get_the_ID());
                                $theme_name = get_field('theme_name', get_the_ID());
                                the_post_thumbnail();
                                $caption = get_the_post_thumbnail_caption();
                                if ( $caption ) {
                                    ?>
                                    <figcaption class="wp-caption-text"><?php echo wp_kses_post( $caption ); ?></figcaption>
                                    <?php
                                }
                                ?>
                                <div class="theme__action">
                                    <a class="theme__live-view" target="_blank" href="<?= $url_demo ? $url_demo:'#' ?>">Live Preview</a>
                                    <?php if($url_free_version): ?>
                                        <a class="theme__free" target="_blank" href="<?= $url_free_version ? $url_free_version:'#' ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M256 8C119.3 8 8 119.2 8 256c0 136.7 111.3 248 248 248s248-111.3 248-248C504 119.2 392.7 8 256 8zM33 256c0-32.3 6.9-63 19.3-90.7l106.4 291.4C84.3 420.5 33 344.2 33 256zm223 223c-21.9 0-43-3.2-63-9.1l66.9-194.4 68.5 187.8c.5 1.1 1 2.1 1.6 3.1-23.1 8.1-48 12.6-74 12.6zm30.7-327.5c13.4-.7 25.5-2.1 25.5-2.1 12-1.4 10.6-19.1-1.4-18.4 0 0-36.1 2.8-59.4 2.8-21.9 0-58.7-2.8-58.7-2.8-12-.7-13.4 17.7-1.4 18.4 0 0 11.4 1.4 23.4 2.1l34.7 95.2L200.6 393l-81.2-241.5c13.4-.7 25.5-2.1 25.5-2.1 12-1.4 10.6-19.1-1.4-18.4 0 0-36.1 2.8-59.4 2.8-4.2 0-9.1-.1-14.4-.3C109.6 73 178.1 33 256 33c58 0 110.9 22.2 150.6 58.5-1-.1-1.9-.2-2.9-.2-21.9 0-37.4 19.1-37.4 39.6 0 18.4 10.6 33.9 21.9 52.3 8.5 14.8 18.4 33.9 18.4 61.5 0 19.1-7.3 41.2-17 72.1l-22.2 74.3-80.7-239.6zm81.4 297.2l68.1-196.9c12.7-31.8 17-57.2 17-79.9 0-8.2-.5-15.8-1.5-22.9 17.4 31.8 27.3 68.2 27.3 107 0 82.3-44.6 154.1-110.9 192.7z"/></svg><div><strong>Free Version</strong><label>Approved by Wordpress - Trust Factor</label></div></a>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($theme_name)): ?>
                                <div class="theme-action__site">
                                    <div class="theme-action__hr-line"><span>Or create site with this theme</span></div>
                                    <div class="theme-action__create-site">
                                        <a href="https://create.crthemes.com/?theme=<?php echo $theme_name; ?>" target="_blank">Create Site</a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="single-download__right--content">
                                <div class="single-download__left--inner">
                                    <div class="single-download__left--price">
                                        <?php
                                            $buy_with_lemon = get_field('buy_with_lemon', $product->get_id());
                                            if(CRT_API_LEMON_IS_ENABLE && $buy_with_lemon) {
                                                echo $buy_with_lemon;
                                            } else {
                                                echo '<a href="?add-to-cart=' . $product->get_id() . '&quantity=1" data-quantity="1" data-product_id="' . $product->get_id() . '" class="theme__cart color-accent add_to_cart_button ajax_add_to_cart">Purchase</a>';
                                            }
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
                                $buy_with_lemon = get_field('buy_with_lemon', $product->get_id());
                                if(CRT_API_LEMON_IS_ENABLE && $buy_with_lemon) {
                                    echo $buy_with_lemon;
                                } else {
                                    echo '<a href="?add-to-cart=' . $product->get_id() . '&quantity=1" data-quantity="1" data-product_id="' . $product->get_id() . '" class="theme__cart color-accent add_to_cart_button ajax_add_to_cart">Purchase</a>';
                                }
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
                <?php endif; ?>
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
        .theme__free {
            font-weight: 600;
            text-decoration: none;
            font-size: 16px;
            margin: 20px 0 0 20px !important;
            color: #666;
            text-align: left;
            display: flex;
            align-items: center;
        }
        .theme__free svg {
		height: 35px;
            width: 35px;
            margin: 0 10px 0 0;
            color: #3858e9;
        }
        .theme__free strong {
            font-size: 14px;
            color: #000;
        }
        .theme__free label {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        @media (max-width:576px) {
            .single-download {
                padding: 0;
                padding-top: 0 !important;
            }
            .single-download .section-inner {
                padding: 0 15px !important;
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
            .single-download__right {
                padding: 0;
            }
            .single-download__right ul {
                margin: 0;
            }
            .single-download__right .single-download__thumbnail {
                padding: 0 0 20px;
            }
            .single-download__right--content {
                padding: 0 20px;
                margin: 30px 0 0;
            }
            .menu-wrapper.section-inner {
                padding: 0;
            }
            button.close-nav-toggle {
                padding: 3.1rem 15px;
            }
            .theme__free label {
                display: none;
            }
            .theme__live-view {
                margin: 20px 5px 0 0;
                flex: 0 0 auto;
            }
        }
        .virtual-host-imported-theme-demo {
            max-width: 600px;
            margin: 0 auto;
        }
        .virtual-host-inner .quantity {
            display: none !important;
        }
        table.variations {
            margin: 20px 0;
            border: none;
        }
        table.variations th, table.variations td {
            border: none !important;
            padding: 0;
        }
        .virtual-host-inner table.variations select {
            margin-right: 0.5rem;
            padding: 8px;
            border-radius: 10px;
            border: 1px solid;
        }
        .single_variation_wrap {
            margin: 0 0 50px;
            display: flex;
            align-items: center;
        }
        .woocommerce-variation.single_variation {
            margin: 0 20px 0 0;
        }
        button.single_add_to_cart_button:hover {
            text-decoration: none !important;
        }
        button.single_add_to_cart_button {
            border: 1px solid #000;
            border-radius: 10px;
            box-shadow: 2px 2px #000;
            background-color: transparent;
            color: #000;
        }
    </style>
<?php
get_footer();
?>