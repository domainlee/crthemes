<?php
/**
 * Created by PhpStorm.
 * User: mienle
 * Date: 3/25/18
 * Time: 10:03 PM
 */
?>

<div class="sidebar-item">
    <div class="sidebar__item">
        <div class="sidebar__head">Danh mục</div>
        <div class="sidebar__content">
            <?php if( has_nav_menu('category') ) {
                wp_nav_menu(
                    array(
                        'theme_location' => 'category',
                        'container_class' => 'sidebar__category',
                        'menu_class' => 'sidebar__category'
                    )
                );
            } ?>
        </div>
    </div>

    <?php
    $sidebar_product_hot = get_field('sidebar_product_hot', 'options');
    if(!empty($sidebar_product_hot)):
        ?>
        <div class="sidebar__item">
            <div class="sidebar__head">Sản phẩm bán chạy</div>
            <div class="sidebar__content">
                <div class="sidebar__product-hot product__list">
                    <?php
                    foreach($sidebar_product_hot as $post):
                        $title = $post->post_title;
                        $link = get_permalink($post->ID);
                        $images = get_field('product_images', $post->ID);
                        $price = get_number(get_field('post_detail_price', $post->ID));
                        $price_sale = get_number(get_field('post_detail_price_sales', $post->ID));
                        $product_intro = get_field('post_detail_intro', $post->ID);

                        $thumbnailId = get_post_thumbnail_id($post->ID);
                        $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                        $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
                        ?>
                        <div class="product__item">
                            <div class="product__inner">
                                <div class="product__left">
                                    <a href="<?= $link ?>">
                                        <figure class="lazy img-ratio__large" data-src="<?= $img ?>"></figure>
                                    </a>
                                </div>
                                <div class="product__content">
                                    <h2 class="product__title"><a href="<?= $link ?>"><?= $title ?></a></h2>
                                    <p class="product-sidebar">
                                        <?= $price_sale ? '<span class="product-sidebar__price">'.number_format($price_sale).' đ</span><span class="product-sidebar__price-sales">'.number_format($price).' đ</span>':'<span class="product-sidebar__price">'.number_format($price).' đ</span>'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
        <?php
    endif;
    ?>

    <?php
    $sidebar_product_view = get_field('sidebar_product_hot_view', 'options');
    if(!empty($sidebar_product_view)):
        ?>
        <div class="sidebar__item">
            <div class="sidebar__head">Nhiều người xem</div>
            <div class="sidebar__content">
                <div class="sidebar__product-hot product__list">
                    <?php
                    foreach($sidebar_product_view as $post):
                        $title = $post->post_title;
                        $link = get_permalink($post->ID);
                        $images = get_field('product_images', $post->ID);
                        $price = get_number(get_field('post_detail_price', $post->ID));
                        $price_sale = get_number(get_field('post_detail_price_sales', $post->ID));
                        $product_intro = get_field('post_detail_intro', $post->ID);

                        $thumbnailId = get_post_thumbnail_id($post->ID);
                        $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                        $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
                        ?>
                        <div class="product__item">
                            <div class="product__inner">
                                <div class="product__left">
                                    <a href="<?= $link ?>">
                                        <figure class="lazy img-ratio__large" data-src="<?= $img ?>"></figure>
                                    </a>
                                </div>
                                <div class="product__content">
                                    <h2 class="product__title"><a href="<?= $link ?>"><?= $title ?></a></h2>
                                    <p class="product-sidebar">
                                        <?= $price_sale ? '<span class="product-sidebar__price">'.number_format($price_sale).' đ</span><span class="product-sidebar__price-sales">'.number_format($price).' đ</span>':'<span class="product-sidebar__price">'.number_format($price).' đ</span>'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
        <?php
    endif;
    ?>

</div>
