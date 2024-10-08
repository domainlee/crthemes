<?php
    $object = get_queried_object();
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ) );
?>

<h1 class="themes__heading">
    <?php echo $object->name ?>
</h1>
<div class="themes__sub">
    <?php
        foreach ($terms as $t) {
            if($t->name == 'Themes' || $t->slug == 'themes') {
                echo '<h5><a href="'.get_term_link($t->term_id).'">All</a></h5>';
            }
        }
        foreach ($terms as $t) {
            if(($t->name !== 'Themes' || $t->slug !== 'themes') && $t->slug !== 'uncategorized') {
                echo '<h5><a href="'.get_term_link($t->term_id).'">'.$t->name.'</a></h5>';
            }
        }
    ?>
</div>

<div class="theme">
    <div class="">
        <div class="theme__list">
            <?php
            if (have_posts()): while (have_posts()) : the_post();
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
                if($status !== 'private'):
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
            <?php endif; endwhile; ?>
            <?php else: ?>
                <article>
                    <h2><?php _e( 'No themes', 'html5blank' ); ?></h2>
                </article>
            <?php endif; ?>
        </div>
    </div>
    <?php get_template_part( 'template-parts/pagination' ); ?>
</div>
