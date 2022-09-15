<?php //if (have_posts()): while (have_posts()) : the_post(); ?>
<!---->
<!--    --><?php
//        $option_post = get_field('post_detail', get_the_ID());
//
//    ?>
<!--	<article class="search-results__item" id="post---><?php //the_ID(); ?><!--">-->
<!---->
<!--        <div class="search-results__left">-->
<!--            --><?php
//                if ( has_post_thumbnail()) : // Check if thumbnail exists
//                $thumbnailId = get_post_thumbnail_id(get_the_ID());
//                $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
//                $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
//            ?>
<!--                <a href="--><?php //the_permalink(); ?><!--" title="--><?php //the_title(); ?><!--">-->
<!--                    <figure class="lazy search-results__image" data-src="--><?//= $img ?><!--"></figure>-->
<!--                </a>-->
<!--            --><?php //endif; ?>
<!--        </div>-->
<!--        <div class="search-results__right">-->
<!--            <h2 class="search-results__title">-->
<!--                <a href="--><?php //the_permalink(); ?><!--" title="--><?php //the_title(); ?><!--">--><?php //the_title(); ?><!--</a><span class="search-results__label">--><?//= $option_post ? ' - Sản phẩm':' - Tin tức' ?><!--</span>-->
<!--            </h2>-->
<!--            <div class="search-results__date">-->
<!--                <span class="date">--><?php //the_time('F j, Y'); ?><!-- --><?php //the_time('g:i a'); ?><!--</span>-->
<!--                <span class="author">--><?php //_e( 'Tác giả: ', 'html5blank' ); ?><!-- --><?php //the_author_posts_link(); ?><!--</span>-->
<!--            </div>-->
<!--            <div class="search-results__intro">-->
<!--                --><?php //html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>
<!--            </div>-->
<!--        </div>-->
<!--	</article>-->
<!---->
<?php //endwhile; ?>
<!---->
<?php //else: ?>
<!--	<article class="cart-null">-->
<!--		<h2>--><?php //_e( 'Sorry, nothing to display.', 'html5blank' ); ?><!--</h2>-->
<!--	</article>-->
<?php //endif; ?>
