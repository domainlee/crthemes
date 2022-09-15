<?php
$news_headline = get_sub_field('news_headline');
$news_list = get_sub_field('news_list');
if (!empty($news_list)):
?>
<section class="news">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="news__inner">
                    <h2 class="news__headline"><?= $news_headline; ?></h2>
                    <div class="news__list">
                        <?php foreach ($news_list as $post):
                            $title = $post->post_title;
                            $link = get_permalink($post->ID);
                            $thumbnailId = get_post_thumbnail_id($post->ID);
                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];

                            $save_post = $post;
                            $post = get_post($post->ID);
                            setup_postdata( $post ); // hello
                            $output = get_the_excerpt();
                            $post = $save_post;

                            ?>
                        <div class="news__item wp3">
                            <div class="news__img">
                                <a href="<?= $link; ?>">
                                    <figure class="post-new__img lazy" data-src="<?= $img; ?>"></figure>
                                </a>
                            </div>
                            <div class="news__details">
                                <h3 class="news__title"><a href="<?= $link; ?>"><?= $title; ?></a></h3>
                                <div class="news__date">
                                    <i class="fa fa-clock-o"></i>
                                    <?php the_time('d/m/Y'); ?>
                                </div>
                                <div class="news__intro">
                                    <?= $output ?>
                                </div>
                                <div class="news__url">
                                    <a href="<?= $link; ?>">Đọc thêm <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>