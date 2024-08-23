<?php
    $object = get_queried_object();
    $terms = get_terms( array(
        'taxonomy' => 'download_category',
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
            if($t->name !== 'Themes' || $t->slug !== 'themes') {
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
                $url_free_version = get_field('url_version', get_the_ID());
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
                                <div class="theme__control">
                                    <span><?php echo edd_price(get_the_ID()); ?></span>
                                    <div class="theme__action">
                                        <?php if($url_free_version): ?>
                                            <a class="theme__cart" target="_blank" href="<?= $url_free_version ? $url_free_version:'#' ?>"><span class="dashicons dashicons-wordpress-alt"></span>Free Version</a>
                                        <?php endif; ?>
                                        <a class="theme__live-view" target="_blank" href="<?php echo esc_attr($url_demo ? $url_demo:'#'); ?>">Live Preview</a>
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
