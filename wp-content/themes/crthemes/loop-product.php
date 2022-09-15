<div class="product__list">
<?php if (have_posts()): while (have_posts()) : the_post(); ?>
        <?php
        $title = get_the_title();
        $link = get_permalink(get_the_ID());

        $images = get_field('product_images', get_the_ID());
        $price = get_number(get_field('post_detail_price', get_the_ID()));

        $thumbnailId = get_post_thumbnail_id(get_the_ID());
        $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
        $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
        ?>
        <div class="module-product__item tab-product__item">
        <div class="module-product__item--inner tab-product__item--inner">
            <a class="module-product__link" href="<?= $link ?>">
                <figure class="module-product__image lazy"
                        data-src="<?= $img ? $img : $images[0]['url'] ?>"
                        style=""></figure>
            </a>
            <h3 class="module-product__title"><a
                        href="<?= $link ?>"><?= $title ?></a></h3>
            <div class="module-product__price-cart">
                <?php
                if (!empty($price)) {
                    $price_format = number_format($price);
                }
                ?>
                <div class="module-product__price">
                    <div class="pice"> Giá: </div> <?= $price ? '<span>'.number_format($price) : '0 VNĐ' ?>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
<?php else: ?>
	<article class="cart-null">
        <span class="cart-null__icon"></span>
		<h2><?php _e( 'Không có sản phẩm nào', 'html5blank' ); ?></h2>
	</article>
<?php endif; ?>
</div>
