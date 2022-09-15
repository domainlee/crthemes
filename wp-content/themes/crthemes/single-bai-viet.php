<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap');
</style>

<div class="single">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="single__author">
                    <div>
                        <?php
                        $post = get_post();
                        echo get_avatar( $post->post_author );
                        $info = get_user_meta($post->post_author);
                        $nickname = $info['last_name'][0] || $info['first_name'][0] ? $info['last_name'][0].' '.$info['first_name'][0]:$info['nickname'][0];
                        ?>
                    </div>
                    <div>Đăng bởi <?= $nickname ?></div>
                    <div>vào lúc <?php the_time('d/m/Y'); ?></div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="hom-page__inner">
                    <div class="home-page__main">
                        <div class="single-post">
                            <div class="single-post__inner">
                                <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                                    <h2 class="single__heading">
                                        <?= get_the_title() ?>
                                    </h2>
                                    <div class="sigle-post__content">
                                        <?php the_content(); ?>
                                    </div>
                                <?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php
                        $tag = get_the_tags();
                        if (!empty($tag)):
                            ?>
                            <div class="single-post__tag">
                                <div class="news-single__relation_title">Tìm kiếm theo: </div>
                                <?php
                                foreach ($tag as $item):
                                    $term_link = get_term_link($item->term_id);
                                    ?>
                                    <div class="tag__item">
                                        <div class="tag__name">
                                            <a href="<?= $term_link; ?>"><i class="fa fa-search"></i><?= $item->name; ?></a>
                                        </div>
                                    </div>
                                <?php endforeach;
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row relation news mb-3">
            <div class="col-12">
                <?php
                $post_relation = get_field('post_relation');
                ?>
                <div class="news__inner post-content__relation">
                    <div class="news-single__relation_title">Bài viết liên quan</div>
                    <?php if(!empty($post_relation)):
                        ?>
                        <div class="news__list">
                            <?php
                            foreach ($post_relation as $post):
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
                    <?php endif; ?>

                    <?php if (empty($post_relation)): ?>
                        <?php
                        global $post;
                        $secondary = new WP_Query( array(
                            'post_type'     => 'post',
                            'posts_per_page'=> 3,
                            'post__not_in' => array(get_the_ID())
                        ) );
                        if( $secondary->have_posts() ) : ?>
                            <div class="news__list">
                                <?php
                                while( $secondary->have_posts() ) : $secondary->the_post();
                                    $title = get_the_title($post->ID);
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
                                <?php endwhile;
                                wp_reset_postdata();
                                ?>
                            </div>
                        <?php
                        endif;
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>


    </div>
</div>

