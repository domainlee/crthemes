<?php
    $profilo_headline = get_sub_field('profilo_headline');
    $profilo_list = get_sub_field('profilo_list');
?>
<section class="profilo">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="profilo__headline to-top"><?= $profilo_headline ?></h2>
            </div>
        </div>
    </div>
    <div class="profilo__row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="profilo__list to-top">
                        <?php
                            $cc = 0;
                            foreach ($profilo_list as $post):
                            $cc++;
                            if($cc < 2){
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
                            $imgs = '';
                            if($images) {
                                $c = 0;
                                foreach ($images as $i) {
                                    $c++;
                                    $imgs .= $i['sizes']['medium']. (count($images) != $c ? ',':'');
                                }
                            }
                            ?>
                            <div class="profilo__col">
                                <div class="profilo__item" data-images="<?= $imgs ?>" data-image-old="<?= $img ?>">
                                    <div class="profilo__item--inner">
                                        <div class="square position-absolute left-top"><div></div></div>
                                        <div class="square position-absolute right-top"><div></div></div>
                                        <a href="<?= $link ?>">
                                            <figure class="profilo__image ratio ratio-2x3 lazy" data-src="<?= $img ?>" title="<?= $title ?>">
                                                <div class="profilo__content">
                                                    <h3 class="profilo__title"><?= $title ?></h3>
                                                    <div class="">
                                                        <?= $post_price || $post_ground || $post_bedrooms || $term[0] ? '<span>Thông tin dự án</span>':null ?>
                                                        <?= $term[0] ? '<p><span>Dự án: </span><strong>'. $term[0]->name .'</strong></p>':null ?>
                                                        <?= $post_price ? '<p><span>Chi phí dự kiến: </span><strong>'. $post_price .'</strong></p>':null ?>
                                                        <?= $post_ground ? '<p><span>Diện tích xây dựng: </span><strong>'. $post_ground .'</strong></p>':null ?>
                                                        <?= $post_bedrooms ? '<p><span>Số phòng ngủ: </span><strong>'. $post_bedrooms .'</strong></p>':null ?>
                                                    </div>
                                                </div>
                                            </figure>
                                        </a>
                                        <div class="square position-absolute left-bottom"><div></div></div>
                                        <div class="square position-absolute right-bottom"><div></div></div>
                                    </div>
                                </div>
                            </div>
                        <?php  } endforeach; ?>

                        <div class="profilo__col">
                        <?php
                        $ccc = 0;
                        foreach ($profilo_list as $post):
                            $ccc++;
                            if($ccc == 2 || $ccc == 3){
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
                                $imgs = '';
                                if($images) {
                                    $c = 0;
                                    foreach ($images as $i) {
                                        $c++;
                                        $imgs .= $i['sizes']['medium']. (count($images) != $c ? ',':'');
                                    }
                                }
                                ?>
                                    <div class="profilo__item" data-images="<?= $imgs ?>" data-image-old="<?= $img ?>">
                                        <div class="profilo__item--inner">
                                            <div class="square position-absolute left-top"><div></div></div>
                                            <div class="square position-absolute right-top"><div></div></div>
                                            <a href="<?= $link ?>">
                                                <figure class="profilo__image ratio ratio-2x3 lazy" data-src="<?= $img ?>" title="<?= $title ?>">
                                                    <div class="profilo__content">
                                                        <h3 class="profilo__title"><?= $title ?></h3>
                                                        <div class="">
                                                            <?= $post_price || $post_ground || $post_bedrooms || $term[0] ? '<span>Thông tin dự án</span>':null ?>
                                                            <?= $term[0] ? '<p><span>Dự án: </span><strong>'. $term[0]->name .'</strong></p>':null ?>
                                                            <?= $post_price ? '<p><span>Chi phí dự kiến: </span><strong>'. $post_price .'</strong></p>':null ?>
                                                            <?= $post_ground ? '<p><span>Diện tích xây dựng: </span><strong>'. $post_ground .'</strong></p>':null ?>
                                                            <?= $post_bedrooms ? '<p><span>Số phòng ngủ: </span><strong>'. $post_bedrooms .'</strong></p>':null ?>
                                                        </div>
                                                    </div>
                                                </figure>
                                            </a>
                                            <div class="square position-absolute left-bottom"><div></div></div>
                                            <div class="square position-absolute right-bottom"><div></div></div>
                                        </div>
                                    </div>
                            <?php  }  endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
