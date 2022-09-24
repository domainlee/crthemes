<?php
    $profilo_headline = get_sub_field('profilo_headline');
    $profilo_tabs = get_sub_field('profilo_tabs');
?>
<section class="profilo py-6">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="heading__default to-top"><?= $profilo_headline ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php foreach ($profilo_tabs as $t): ?>
                    <div class="profilo__list to-top">
                        <?php
                        foreach ($t['profilo_list'] as $post):
                            $title = $post->post_title;
                            $link = get_permalink($post->ID);
                            $thumbnailId = get_post_thumbnail_id($post->ID);
                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];

                            $save_post = $post;
                            $post = get_post($post->ID);
                            setup_postdata( $post ); // hello
                            $output = get_the_excerpt();
                            $post = $save_post;
                            $post_price = get_field('post_price', $post->ID);
                            $post_ground = get_field('post_ground', $post->ID);
                            $post_bedrooms = get_field('post_bedrooms', $post->ID);
                            $images = get_field('post_images', $post->ID);
                            $term = get_the_terms($post->ID, 'category');
                            $term_link = get_term_link($term[0]->term_id);
                            ?>
                            <div class="profilo__item">
                                <div class="profilo__item--inner">
                                    <a href="<?= $link ?>">
                                        <figure class="profilo__image ratio ratio-50 bg-cover bg-no-repeat bg-center lazy" data-src="<?= $img ?>" title="<?= $title ?>"></figure>
                                    </a>
                                    <h3 class="profilo__title mb-4"><a href="<?= $link ?>"><?= $title; ?></a></h3>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <?php echo edd_price($post->ID); ?>
                                        <div>
                                            <a class="profilo__cart" href="<?= site_url().'/checkout?edd_action=add_to_cart&download_id='.$post->ID; ?>">Purchase <i class="icofont-cart"></i></a>
                                            <a class="profilo__live-view" href="">Live Preview</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
