<?php get_header(); ?>

<section class="category news">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="category__heading text-center"><?php echo sprintf( __( 'Chúng tôi tìm thấy %s kết quả ', 'html5blank' ), $wp_query->found_posts ); echo 'cho từ khoá "'. get_search_query().'"'; ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="news__inner">
                    <div class="news__list">
                        <?php
                        if (have_posts()): while (have_posts()) : the_post();
                            $title = get_the_title();
                            $link = get_permalink(get_the_ID());
                            $thumbnailId = get_post_thumbnail_id(get_the_ID());
                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                            $output = get_the_excerpt(get_the_ID());
                            $post_price = get_field('post_price', get_the_ID());
                            $post_ground = get_field('post_ground', get_the_ID());
                            $post_bedrooms = get_field('post_bedrooms', get_the_ID());
                            $images = get_field('post_images', get_the_ID());
                            $term = get_the_terms(get_the_ID(), 'category');
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
                        <?php endwhile; ?>
                        <?php else: ?>
                            <article>
                                <h2><?php _e( 'Không có bài viết nào.', 'html5blank' ); ?></h2>
                            </article>
                        <?php endif; ?>
                    </div>
                </div>
                <?php get_template_part('pagination'); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
