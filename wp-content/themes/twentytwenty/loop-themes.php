<?php
    $object = get_queried_object();
    $terms = get_terms( array(
        'taxonomy' => 'download_category',
        'hide_empty' => false,
    ) );

?>

<h1 class="themes__heading"><?= $object->name .' wordpress themes' ?></h1>
<div class="themes__sub">
    <?php
        foreach ($terms as $t) {
            echo '<h5><a href="'.get_term_link($t->term_id).'">'.$t->name.'</a></h5>';
        }
    ?>
</div>

<div class="themes">
    <div class="">
        <div class="themes__list">
            <?php
            if (have_posts()): while (have_posts()) : the_post();
                $title = get_the_title();
                $link = get_permalink(get_the_ID());
                $thumbnailId = get_post_thumbnail_id(get_the_ID());
                $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                $output = get_the_excerpt(get_the_ID());
                $url_demo = get_field('demo_url', get_the_ID());
//                $post_ground = get_field('post_ground', get_the_ID());
//                $post_bedrooms = get_field('post_bedrooms', get_the_ID());
//                $images = get_field('post_images', get_the_ID());
                $term = get_the_terms(get_the_ID(), 'category');
                $term_link = get_term_link($term[0]->term_id);
                ?>
                <div class="themes__item">
                    <div class="themes__item--inner">
                        <a href="<?= $link ?>">
                            <div class="themes__image">
                                <div class="news__image--inner">
                                    <img src="<?php echo $img; ?>" />
                                </div>
                            </div>
                        </a>
                        <div class="themes__content">
                            <h3 class="themes__title"><a href="<?= $link ?>"><?= $title ?></a></h3>
                            <div class="themes__control">
                                <a class="profilo__cart" href="<?= site_url().'/checkout?edd_action=add_to_cart&download_id='.get_the_ID(); ?>">Purchase <i class="icofont-cart"></i></a>
                                <a class="profilo__live-view" target="_blank" href="<?= $url_demo ? $url_demo:'#' ?>">Live Preview</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php else: ?>
                <article>
                    <h2><?php _e( 'Không có bài viết nào.', 'html5blank' ); ?></h2>
                </article>
            <?php endif; ?>
        </div>
    </div>
    <?php get_template_part( 'template-parts/pagination' ); ?>
</div>
