<?php
  /* Template Name: Trang Tin Tức */
    get_header();
    $list = get_field('banner_list', get_the_ID());
    $title = get_field('banner_title', get_the_ID());
    $content = get_field('banner_content', get_the_ID());
    $right_sidebar = get_field('news_sidebar', 'option');
?>
<?php
    the_module('heading', ['title' => $title, 'description'=> $content]);
?>
<section class="page-news">
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-news__inner">
                        <?php
                        $post_per_page = get_field('post_per_page', get_the_ID());
                        $arr = [];
                        global $post;
                        $pageCurent = get_query_var('paged');
                        $secondary = new WP_Query( array(
                            'post_type'     => 'post',
                            'posts_per_page'=> $post_per_page,
                            'post__not_in' => $arr,
                            'paged' => $pageCurent
                        ) );
                        if( $secondary->have_posts() ) : ?>
                            <div class="page-news__list">
                                <?php
                                while( $secondary->have_posts() ) : $secondary->the_post();
                                    $thumbnailUrl = get_the_post_thumbnail_url($post->ID, 'large');
                                    $thumbnailID = get_post_thumbnail_id($post->ID);
                                    $alt = get_post_meta($thumbnailID, '_wp_attachment_image_alt', true);
                                    $content = get_the_content($post->ID);

                                    $save_post = $post;
                                    $post = get_post($post->ID);
                                    setup_postdata( $post ); // hello
                                    $output = get_the_excerpt();
                                    $post = $save_post;
                                    ?>
                                    <div class="page-news__item">
                                        <div class="page-news__item--inner">
                                            <a href="<?php the_permalink(); ?>">
                                                <figure class="page-news__image lazy" data-src="<?= $thumbnailUrl ?>"></figure>
                                            </a>
                                            <div class="page-news__intro">
                                                <h3 class="page-news__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                <span class="page-news__date">Cập nhật: <?php the_time('d/m/Y'); ?></span>
                                                <div><?= $output ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile;
                                    wp_reset_postdata();
                                ?>


                            </div>
                            <div class="pagination">
                                <?php
                                if($secondary->max_num_pages > 1) {
                                }
                                echo paginate_links(array(
                                    'total' => $secondary->max_num_pages,
                                    'prev_text'          => __('<<'),
                                    'next_text'          => __('>>')
                                ));?>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
        </div>
    </div>
    </div>
</section>
<?php
get_footer();
?>


