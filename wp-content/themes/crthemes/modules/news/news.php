<?php
    $news_heading = get_sub_field('news_heading');
    $news_list = get_sub_field('news_list');
    $button_text = get_sub_field('button_text');
    $button_url = get_sub_field('button_url');
?>
<section class="news">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="news__inner">
                    <h2 class="news__headline to-top"><?= $news_heading ?></h2>
                    <div class="news__list to-top">
                        <?php
                            foreach ($news_list as $post):
                            $title = $post->post_title;
                            $link = get_permalink($post->ID);
                            $thumbnailId = get_post_thumbnail_id($post->ID);
                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                            $output = get_the_excerpt($post);
                            $post_price = get_field('post_price', $post->ID);
                            $post_ground = get_field('post_ground', $post->ID);
                            $post_bedrooms = get_field('post_bedrooms', $post->ID);
                            $images = get_field('post_images', $post->ID);
                            $term = get_the_terms($post->ID, 'category');
                            $term_link = get_term_link($term[0]->term_id);
                        ?>
                        <div class="news__item">
                            <a href="<?= $link ?>">
                                <div class="news__image">
                                    <div class="news__image--inner">
                                        <div class="square position-absolute left-top"><div></div></div>
                                        <div class="square position-absolute right-top"><div></div></div>
                                        <figure class="ratio ratio-4x3 lazy" data-src="<?= $img ?>"></figure>
                                        <div class="square position-absolute left-bottom"><div></div></div>
                                        <div class="square position-absolute right-bottom"><div></div></div>
                                    </div>
                                </div>
                                <div class="news__content">
                                    <h3 class="news__title"><?= $title ?></h3>
                                    <div class="news__intro"><?= $output ?></div>
                                    <div class="news__data">
                                        <?php the_time('d/m/Y'); ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="button__default to-top">
                        <a href="<?= $button_url ? $button_url:'#' ?>" target="_blank"><?= $button_text ? $button_text:'Xem Thêm' ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>