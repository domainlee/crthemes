<?php
/**
 * Created by PhpStorm.
 * User: mienle
 * Date: 1/13/18
 * Time: 12:11 AM
 */
$option_slider = get_sub_field('product_slider');
?>
<section class="module-product">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="module-product__inner">
                    <?php
                        $title_module = get_sub_field('product_title');
                    ?>
                    <div class="module-product__headline headline__default">
                        <h2>
                            <?= $title_module ?>
                        </h2>
                    </div>
                    <div class="module-product__list module-product__carousel owl-carousel owl-theme">
                        <?php
                            $product_list = get_sub_field('product_list');
                            if(!empty($product_list)):
                            foreach($product_list as $post):
                            $title = get_the_title($post->ID);
                            $link = get_permalink($post->ID);
                            $images = get_field('product_images', $post->ID);
                            $price = get_number(get_field('post_detail_price', $post->ID));
                            $thumbnailId = get_post_thumbnail_id($post->ID);
                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                            $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
                            $hot_product = get_field('hot_product', $post->ID);
                        ?>
                                <div class="module-product__item">
                                    <div class="module-product__item--inner">
                                        <a class="module-product__link" href="<?= $link ?>">
                                            <figure class="module-product__image lazy" data-src="<?= $img ? $img:$images[0]['url'] ?>" style=""></figure>
                                        </a>
                                        <h3 class="module-product__title"><a href="<?= $link ?>"><?= $title ?></a></h3>
                                        <div class="module-product__price-cart">
                                        <?php
                                            if(!empty($price)) {
                                                $price_format = number_format($price);
                                            }
                                         ?>
                                            <div class="module-product__price"> <div class="pice"> Giá: </div> <?= $price ? '<span>'.number_format($price) : '0 VNĐ' ?></div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                    endforeach;
                            endif;

                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>