<?php
  /* Template Name: Trang Tuyển Dụng */
    get_header();
?>
<?php
    $slider = get_field('news_slider', get_the_ID());
    $right_sidebar = get_field('news_sidebar', 'option');
    $list = get_field('banner_list', get_the_ID());
    $title = get_field('banner_title', get_the_ID());
    $content = get_field('banner_content', get_the_ID());
?>

<?php
    the_module('hero-slider', ['list' => $list, 'title' => $title, 'content' => $content]);
?>

<div class="container page-recruitment" >
    <h1 class="page-recruitment__hero--headline">Các vị trí tuyển dụng</h1>
    <?php
    $post_per_page = get_field('post_per_page', get_the_ID());
    global $post;
    $pageCurent = get_query_var('paged');
    $secondary = new WP_Query( array(
        'post_type'     => 'recruitment',
        'posts_per_page'=> 8,
        'paged' => $pageCurent
    ) );
    if( $secondary->have_posts() ) : ?>
        <div class="page-recruitment__list">
            <?php
            while( $secondary->have_posts() ) : $secondary->the_post();
                $thumb = get_template_directory_uri() . '/image/req-1.png';

                $thumbnailUrl = get_the_post_thumbnail_url($post->ID) ? get_the_post_thumbnail_url($post->ID):$thumb;
                $thumbnailID = get_post_thumbnail_id($post->ID);

                $alt = get_post_meta($thumbnailID, '_wp_attachment_image_alt', true);
                $content = get_the_content($post->ID);

                $expiration_date = get_field('recruitment_expiration_date', $post->ID);
                $working_time = get_field('recruitment_working_time', $post->ID);
                $salary = get_field('recruitment_salary', $post->ID);

                $save_post = $post;
                $post = get_post($post->ID);
                setup_postdata( $post ); // hello
                $output = get_the_excerpt();
                $post = $save_post;
                ?>
                <div class="page-recruitment__item">
                    <div class="page-recruitment__item--inner">
                        <div>
                            <a href="<?php the_permalink(); ?>">
                                <figure class="page-recruitment__image lazy" data-src="<?= $thumbnailUrl ?>"></figure>
                            </a>
                        </div>
                        <div>
                            <h3 class="page-recruitment__item--title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="page-recruitment__item--row"><strong>Hình thức làm việc: </strong> <?= $working_time ?></div>
                            <div class="page-recruitment__item--row"><strong>Mức thu nhập: </strong> <?= $salary ?></div>
                            <div class="page-recruitment__item--row"><strong>Hạn nộp hồ sơ: </strong><?= $expiration_date ?></div>
                            <a class="page-recruitment__item--detail" href="<?php the_permalink(); ?>">Chi tiết <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();
            ?>
        </div>

        <div class="news__paging">
            <?php
            if($secondary->max_num_pages > 1) {
            }
            echo paginate_links(array(
                'total' => $secondary->max_num_pages,
                'prev_text'          => __(''),
                'next_text'          => __('')
            ));?>
        </div>
        <?php
    endif;
    ?>

</div>

<?php
get_footer();
?>


