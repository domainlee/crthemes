<?php /* Template Name: Home Page */
get_header();
?>
<main id="site-content">
    <section class="hero section-inner">
            <div class="hero__content">
                <p>
                    We are providing good themes, of quality design, quality clean code, <br/>24/7 support and 30-day refund if you don't like it
                </p>
            </div>
    </section>
    <section id="video-guide" class="section-inner">
        <div class="theme">
            <h2 class="heading-default" data-viewport="custom">Quick setup<br/>Wordpress & Import demo</h2>
            <p class="video-guide__intro">If you don't want to manage complicated hosting, or install Wordpress which takes a lot of time, you can create a website right away following the instructions below.</p>
            <div class="video-player">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/ISM_wpruqNk?si=TPKTt44ML3Hk0prn" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </div>
    </section>
    <section id="my-project" class="section-inner my-project ">
        <div class="theme">
            <h2 class="heading-default" data-viewport="custom">Latest Themes</h2>
            <div class="theme__list">

                <?php
                    global $wp_query;
                    $original_query = $wp_query;
                    $wp_query = null;
                    $args = array('post_type' => 'product', 'posts_per_page'   => -1, 'post__not_in' => array(617));
                    $wp_query = new WP_Query( $args );
                    if ( have_posts() ) :
                        while ( have_posts() ) : the_post();

                            $title = get_the_title();
                            $link = get_permalink(get_the_ID());
                            $thumbnailId = get_post_thumbnail_id(get_the_ID());
                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                            $output = get_the_excerpt(get_the_ID());
                            $url_demo = get_field('demo_url', get_the_ID());
                            $term = get_the_terms(get_the_ID(), 'category');
                            $status = get_post_status(get_the_ID());
				            $url_free_version = get_field('free_version_url', get_the_ID());
                            $theme_name = get_field('theme_name', get_the_ID());
                        ?>
                            <div class="theme__item">
                                <div class="theme__item--inner">
                                    <div class="themes__image">
                                        <a href="<?php echo esc_attr($link) ?>">
                                            <img src="<?php echo esc_attr($img); ?>" alt="<?php echo esc_attr($title) ?>" />
                                        </a>
                                    </div>
                                    <div class="theme__content">
                                        <h3 class="theme__title"><a href="<?php echo esc_attr($link) ?>"><?php echo esc_html($title) ?></a></h3>
                                        <span><?php woocommerce_template_single_price(); ?></span>
                                        <div class="theme__control">
                                            <div class="theme__action">
                                                <?php
                                                    echo '<a href="?add-to-cart=' . get_the_ID() . '&quantity=1" data-quantity="1" data-product_id="' . get_the_ID() . '" class="theme__cart color-accent add_to_cart_button ajax_add_to_cart">Purchase</a>';
                                                ?>
                                                <a class="theme__live-view" target="_blank" href="<?php echo esc_attr($url_demo ? $url_demo:'#'); ?>">Live Preview</a>
                                                <?php if(!empty($theme_name)): ?>
                                                    <a class="theme__action--create-site" href="https://create.crthemes.com/?theme=<?php echo $theme_name; ?>" target="_blank"><span>Or create site with this theme</span>Create Site</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                    else:
                        echo 'no posts found';
                    endif;
                $wp_query = null;
                $wp_query = $original_query;
                wp_reset_postdata();
                ?>

            </div>
        </div>
    </section>

    <section class="question-asweser section-inner">
        <h2 class="heading-default">Q&A</h2>

        <div class="accordion--js faq-list" data-grp-name="faq-list">
            <div class="accrodion active">
                <div class="accrodion-title">
                    <h5>Why does our theme only have one home?</h5>
                </div>
                <div class="accrodion-content">
                    <div class="inner">
                        <p>To ensure page loading speed, the size of option page files will be optimized.</p>
                        <p>So on the theme we should only focus on what the theme needs</p>
                    </div>
                </div>
            </div>

            <div class="accrodion">
                <div class="accrodion-title">
                    <h5>License for themes</h5>
                </div>
                <div class="accrodion-content">
                    <div class="inner">
                        <p>All themes we develop are licensed under GPL or GPLv2</p>
                    </div>
                </div>
            </div>
            <div class="accrodion">
                <div class="accrodion-title">
                    <h5>Secure payment</h5>
                </div>
                <div class="accrodion-content">
                    <div class="inner">
                        <p>Our payment gateway uses Paypal, we do <strong>not save</strong> account numbers or CVC on the server</p>
                        <p>in Paypal can you choose Pay with <strong>Debit</strong> or <strong>Credit Card</strong> on the Paypal page.</p>
                    </div>
                </div>
            </div>
            <div class="accrodion">
                <div class="accrodion-title">
                    <h5>Refund within 30 days</h5>
                </div>
                <div class="accrodion-content">
                    <div class="inner">
                        <p>If you don't like our products, we'll be happy to offer you a full 100% refund within 30 days of purchase with no questions asked.</p>
                    </div>
                </div>
            </div>
        </div>

    </section>
</main>
<?php
get_footer();
