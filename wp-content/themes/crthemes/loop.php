<div class="product__list">
<?php if (have_posts()): while (have_posts()) : the_post(); ?>

    <?php
        $option_post = get_field('post_detail', get_the_ID());
    ?>
            <?php
                if ( has_post_thumbnail()) : // Check if thumbnail exists
                $thumbnailId = get_post_thumbnail_id(get_the_ID());
                $thumbnailUrl = get_the_post_thumbnail_url(get_the_ID());
                $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
            ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <figure class="lazy search-results__image" data-src="<?= $img ?>"></figure>
                </a>
                <?php endif; ?>
                <?php
                $title = get_the_title();
                $link = get_permalink(get_the_ID());

                $images = get_field('product_images', get_the_ID());
                $price = get_number(get_field('post_detail_price', get_the_ID()));
                $price_sale = get_number(get_field('post_detail_price_sales', get_the_ID()));
                $product_intro = get_field('post_detail_intro', get_the_ID());

                $thumbnailId = get_post_thumbnail_id(get_the_ID());
                $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
                $content = get_field('content_display_on_hover', get_the_ID());
                $hot_product = get_field('hot_product', $post->ID);
                $post_type = get_post_type(get_the_ID());
        ?>
        <?php if($post_type == 'san_pham'): ?>
        <div class="tab-product__item">
            <div class="tab-product__item--inner">
                <a class="pI" href="<?= $link ?>">
                    <div class="label-top">
                        <?php
                        if(!empty($price_sale)):
                            $percent = (($price - $price_sale) / $price)*100;                                        ?>
                        <div class="product__label--green product__label">
                            <span>-<?= number_format($percent)?> %</span>
                        </div>
                        <?php
                            endif;
                        ?>
                        <?php
                            if(!empty($hot_product)):
                        ?>
                        <div class="product__label--red product__label">
                            <span>HOT</span>
                        </div>
                        <?php endif;
                            ?>
                    </div>
                    <figure class="product_image product_image--two" data-src="<?= $img ?>" style="">
                        <div class="lazy product__image-1" data-src="<?= $img ?>"></div>
                        <?php
                        if(!empty($content)):
                            ?>
                            <div class="tab-product__info">
                                <p><?= $content ?></p>
                            </div>
                        <?php endif;
                        ?>
                    </figure>
                </a>

                <h2 class="tab-product__item--title"><a href="<?= $link ?>"><?= $title ?></a></h2>
                <div class="product__price-cart">
                    <?php if(!empty($price)){
                        $price_format = number_format($price).'đ';
                    }
                        else{
                            $price_format='Liên hệ';
                        }
                     ?>
                        <p class="product-horizontal__price"><?= $price_sale ? number_format($price_sale).' đ'.'<i class="product__price-old">'.$price_format.'</i>':'<span class="product__price-sale">'.$price_format.'</span>' ?></p>
                </div>
            </div>
        </div>
       <?php
       endif; ?>
        <?php if($post_type == 'post'): ?>
        <div class="tab-product__item">
            <div class="tab-product__item--inner">
                <a class="pI" href="<?= $link ?>">
                    <div class="label-top">
                        <?php
                        if(!empty($price_sale)):
                            $percent = (($price - $price_sale) / $price)*100;                                        ?>
                        <div class="product__label--green product__label">
                            <span>-<?= number_format($percent)?> %</span>
                        </div>
                        <?php
                            endif;
                        ?>
                        <?php
                            if(!empty($hot_product)):
                        ?>
                        <div class="product__label--red product__label">
                            <span>HOT</span>
                        </div>
                        <?php endif;
                            ?>
                    </div>
                    <figure class="product_image product_image--two" data-src="<?= $img ?>" style="">
                        <div class="lazy product__image-1" data-src="<?= $img ?>"></div>
                        <?php
                        if(!empty($content)):
                            ?>
                            <div class="tab-product__info">
                                <p><?= $content ?></p>
                            </div>
                        <?php endif;
                        ?>
                    </figure>
                </a>

                <h2 class="tab-product__item--title"><a href="<?= $link ?>"><?= $title ?></a></h2>
                <div class="product__price-cart">
                    Tin tức
                </div>
            </div>
        </div>
       <?php
       endif; ?>

<?php endwhile; ?>

<?php else: ?>

	<!-- article -->
	<article>
		<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>
</div>
