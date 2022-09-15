<?php
    get_header();
?>

<?php
     $save_post = $post;
     $post = get_post($post->ID);
     setup_postdata( $post ); // hello
     $output = get_the_excerpt();
     $post = $save_post;
?>

<?php
    the_module('heading', ['title' => get_the_title()]);
    $expiration_date = get_field('recruitment_expiration_date', $post->ID);
    $working_time = get_field('recruitment_working_time', $post->ID);
    $salary = get_field('recruitment_salary', $post->ID);
?>

<div class="home-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hom-page__inner">
                    <div class="home-page__main home-page__recruitment">
                        <div class="single-post">
                            <div class="single-post__inner">
                                <div class="page-recruitment__item--row page-recruitment__sub"><strong>Hình thức làm việc: </strong> <?= $working_time ?></div>
                                <div class="page-recruitment__item--row page-recruitment__sub"><strong>Mức thu nhập: </strong> <?= $salary ?></div>
                                <div class="page-recruitment__item--row page-recruitment__sub"><strong>Hạn nộp hồ sơ: </strong><?= $expiration_date ?></div>
                                <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                                    <div class="single-post__border">
                                        <hr>
                                    </div>
                                    <div class="sigle-post__content">
                                        <?php the_content(); ?>
                                    </div>
                                <?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
get_footer();
?>


