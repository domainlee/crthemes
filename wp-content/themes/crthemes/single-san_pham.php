<?php
get_header();
session_start();
$recent_old = $_SESSION['recent_views'];

if(isset($recent_old)) {
    if(array_key_exists(get_the_ID(), $recent_old)) {
        unset($recent_old[get_the_ID()]);
    }
    $_SESSION['recent_views'] = [get_the_ID() => get_the_ID()] + $recent_old;
} else {
    $_SESSION['recent_views'] = [get_the_ID() => get_the_ID()];
}
?>
<section class="product-single">
    <div class="product-single__breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__title">
                        <?php my_breadcrumb() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-single__article">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-single__list">
                        <?php if (have_posts()): while (have_posts()) :
                            the_post(); ?>
                            <?php
                            $title = get_the_title();
                            $link = get_permalink(get_the_ID());
                            $images = get_field('product_images', get_the_ID());
                            $price = get_number(get_field('post_detail_price', get_the_ID()));
                            $product_intro = get_field('product_intro', get_the_ID());
                            $product_quantity = get_field('product_quantity', get_the_ID());
                            $product_size = get_field('product_size',get_the_ID());
                            $product_color = get_field('product_color',get_the_ID());
                            $product_status = get_field('product_status',get_the_ID());
                            $product_sale = get_field('product_info_sale',get_the_ID());
                            $thumbnailId = get_post_thumbnail_id(get_the_ID());
                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                            $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
                            ?>
                            <div class="product-single__item">
                                <div class="product-single__image">
                                    <?php if (!empty($images)): ?>
                                        <ul class="product-single__slider owl-carousel owl-theme">
                                            <?php foreach ($images as $img): ?>
                                                <li>
                                                    <figure class="lazy product__lazy-image"
                                                            data-src="<?= $img['url'] ?>"></figure>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <div id="imgZoom">
                                        <div id="zoomSlide">
                                            <a href="#" id="prevSlideZ"></a>
                                            <a href="#" id="nextSlideZ"></a>
                                            <ul id="listImgZoom_2">
                                                <?php
                                                if (count($images)) {
                                                    foreach ($images as $img) {
                                                        ?>
                                                        <li data-src="<?= $img['url'] ?>">
                                                            <img class='cloudzoom-gallery' src="<?= $img['url'] ?>"
                                                                 data-cloudzoom="useZoom: '.cloudzoom', image: '<?= $img['url'] ?>', zoomImage: '<?= $img['url'] ?>'">
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <div id="zoomer">
                                            <img id="z" width="100%" class="cloudzoom" src="<?= $images[0]['url'] ?>"
                                                 data-cloudzoom="
                                     zoomImage: '<?= $images[0]['url'] ?>',
                                     animationTime: 50,
                                     easeTime: 0,
                                     easing: 0,
                                     zoomFlyOut: false,
                                     zoomWidth: 300,
                                     zoomHeight: 300"/>
                                        </div>
                                    </div>
                                    <?php
                                    $tag = wp_get_post_terms(get_the_ID(),'tag_sanpham');
                                    if (!empty($tag)):
                                    ?>
                                    <ul class="tag">
                                        <?php
                                        foreach ($tag as $t):
                                            $tag = $t->name;
                                            $tag_link = get_term_link($t->term_id);
                                            ?>
                                            <li class="tag__item">
                                                <a href="<?= $tag_link; ?>"><?= $tag ?> </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                </div>
                                <div class="product-single__content">
                                    <h2 class="product-single__title"><?= $title ?></h2>
                                    <?php if (!empty($product_intro)): ?>
                                        <div class="product-single__info">
                                            <?= $product_intro; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($price)): ?>
                                        <p class="product-single__price"><?= $price ? '<span>' . number_format($price) . ' đ</span>' : null ?></p>
                                    <?php else: ?>
                                        <p class="product-single__price">
                                            <a href="" class="btn btn-success">Vui lòng iên hệ ngay để được giá ưu đãi</a>
                                        </p>
                                    <?php endif; ?>
                                    <?php if (!empty($product_size)): ?>
                                    <div class="product-single__size">
                                        <p>Size</p>
                                        <div class="product-single__size--list">
                                            <select>
                                                <option value="0">Chọn Size</option>
                                                <?php
                                                foreach ($product_size as $item):?>
                                                    <option class="product-single__size--item">
                                                        <?= $item['label']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($product_color)): ?>
                                    <div class="product-single__color">
                                        <p>Màu sắc</p>
                                        <div class="product-single__color--list">
                                            <select>
                                                <option value="0">Chọn màu sắc</option>
                                                <?php
                                                foreach ($product_color as $item):?>
                                                    <option class="product-single__color--item">
                                                        <?= $item['label']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="product-single__status">
                                        <?php if ($product_quantity > 0): ?>
                                        <span>Tình trạng: </span><?= $product_status ? $product_status : 'Còn hàng' ?>
                                        <?php else: ?>
                                        <span>Tình trạng: </span><?= 'Hết hàng' ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($product_sale)): ?>
                                    <div class="product-single__sale">
                                        <div class="product-single__sale--head">
                                            Khuyến mại và lợi ích
                                        </div>
                                        <div class="product-single__sale--content">
                                            <?= $product_sale; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php endif; ?>
                        <?php
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-single__relations">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-relations__inner">
                        <?php
                        $cs = [];
                        $termProduct = wp_get_object_terms(get_the_ID(), 'danhmuc_sanpham');
                        if(!empty($termProduct)) {
                            foreach($termProduct as $c) {
                                $cs[] = array(
                                    'taxonomy' => 'danhmuc_sanpham',
                                    'field' => 'slug',
                                    'terms' => $c->slug,
                                );
                            }
                        }
                        global $post;
                        $productRelation = new WP_Query( array(
                            'post_type'     => 'san_pham',
                            'posts_per_page'=> 4,
                            'tax_query' => array(
                                'relation' => 'OR',
                                $cs,
                            ),
                            'post__not_in' => array(get_the_ID())
                        ) );
                        if( $productRelation->have_posts() ) : ?>
                            <div class="product-relation">
                                <h2 class="product-relation__title"><span>Có thể bạn sẽ thích</span></h2>
                                <div class="product-relation__list">
                                    <?php
                                    while( $productRelation->have_posts() ) : $productRelation->the_post();
                                        $title = get_the_title();
                                        $link = get_permalink(get_the_ID());

                                        $images = get_field('product_images', get_the_ID());
                                        $code = get_field('product_code', get_the_ID());
                                        $price = get_number(get_field('post_detail_price', get_the_ID()));
                                        $price_sale = get_number(get_field('post_detail_price_sales', get_the_ID()));
                                        $product_intro = get_field('post_detail_intro', get_the_ID());

                                        $thumbnailId = get_post_thumbnail_id(get_the_ID());
                                        $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
                                        $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
                                        $hot_product = get_field('hot_product', $post->ID);
                                        ?>
                                        <div class="module-product__item product-relations__item">
                                            <div class="module-product__item--inner product-relations__item--inner">
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
                                    <?php endwhile;
                                    wp_reset_query();
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-single__comment">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-comment__inner">
                        <div class="product-comment__headline">
                            <h2>Đánh giá sản phẩm</h2>
                        </div>
                        <div class="product-comment__content">
                            <div class="fb-comments" data-href="<?php the_permalink(); ?>" xid="<?php the_ID(); ?> data-numposts="20" width="100%" data-colorscheme="light" data-version="v2.3"></div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
?>