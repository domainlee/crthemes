<!--<section class="category">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-md-12">-->
<!--                <div class="product-single__breadcrumb">-->
<!--                    --><?php //my_breadcrumb() ?>
<!--                </div>-->
<!--                <div class="category__inner">-->
<!--                    <div class="category__left">-->
<!--                        <div class="category__left--inner">-->
<!--                        <div class="sidebar__item">-->
<!--                            <div class="sidebar__head">Danh mục</div>-->
<!--                            <div class="sidebar__content">-->
<!--                                --><?php //if( has_nav_menu('category') ) {
//                                    wp_nav_menu(
//                                        array(
//                                            'theme_location' => 'category',
//                                            'container_class' => 'sidebar__category',
//                                            'menu_class' => 'sidebar__category'
//                                        )
//                                    );
//                                } ?>
<!--                            </div>-->
<!--                        </div>-->
<!--                        --><?php
//                            $sidebarCategory = get_field('sidebar_product_category', 'options');
//                            if(!empty($sidebarCategory)):
//                        ?>
<!--                        <div class="sidebar__item">-->
<!--                            <div class="sidebar__head">Thương hiệu</div>-->
<!--                            <div class="sidebar__content">-->
<!--                                <ul class="sidebar__brand five-category__list">-->
<!--                                    --><?php
//
//                                        foreach($sidebarCategory as $post):
//                                            $title = $post->name;
//                                            $link = get_term_link($post->term_id);
//                                            $image = get_field('category_image', 'category_'.$post->term_id);
//                                            ?>
<!--                                            <li class="--><?//= empty($slider) ? 'five-category__item':null ?><!--">-->
<!--                                                <div class="four-post__item--inner">-->
<!--                                                    <a href="--><?//= $link ?><!--" title="--><?//= $title ?><!--">-->
<!--                                                        <figure class="four-post__image lazy" data-src="--><?//= $image['url'] ?><!--">-->
<!--                                                            <div>-->
<!--                                                                <h3 class=""><span>--><?//= $title ?><!--</span></h3>-->
<!--                                                            </div>-->
<!--                                                        </figure>-->
<!--                                                    </a>-->
<!--                                                </div>-->
<!--                                            </li>-->
<!--                                            --><?php
//                                        endforeach;
//                                        wp_reset_postdata();
//                                    ?>
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        --><?php
//                            endif;
//                        ?>
<!--                        --><?php
//                            $sidebar_product_hot = get_field('sidebar_product_hot', 'options');
//                            if(!empty($sidebar_product_hot)):
//                        ?>
<!--                        <div class="sidebar__item">-->
<!--                            <div class="sidebar__head">Sản phẩm bán chạy</div>-->
<!--                            <div class="sidebar__content">-->
<!--                                <div class="sidebar__product-hot product__list">-->
<!--                                    --><?php
//                                        foreach($sidebar_product_hot as $post):
//                                            $title = $post->post_title;
//                                            $link = get_permalink($post->ID);
//                                            $images = get_field('product_images', $post->ID);
//                                            $price = get_number(get_field('post_detail_price', $post->ID));
//                                            $price_sale = get_number(get_field('post_detail_price_sales', $post->ID));
//                                            $product_intro = get_field('post_detail_intro', $post->ID);
//
//                                            $thumbnailId = get_post_thumbnail_id($post->ID);
//                                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
//                                            $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
//                                            ?>
<!--                                            <div class="product__item">-->
<!--                                                <div class="product__inner">-->
<!--                                                    <div class="product__left">-->
<!--                                                        <a href="--><?//= $link ?><!--">-->
<!--                                                            <figure class="lazy img-ratio__large" data-src="--><?//= $img ?><!--"></figure>-->
<!--                                                        </a>-->
<!--                                                    </div>-->
<!--                                                    <div class="product__content">-->
<!--                                                        <h2 class="product__title"><a href="--><?//= $link ?><!--">--><?//= $title ?><!--</a></h2>-->
<!--                                                        <p class="product-sidebar">-->
<!--                                                            --><?//= $price_sale ? '<span class="product-sidebar__price">'.number_format($price_sale).' đ</span><span class="product-sidebar__price-sales">'.number_format($price).' đ</span>':'<span class="product-sidebar__price">'.number_format($price).' đ</span>'; ?>
<!--                                                        </p>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            --><?php
//                                        endforeach;
//                                    ?>
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        --><?php
//                            endif;
//                        ?>
<!---->
<!--                        --><?php
//                        $sidebar_product_view = get_field('sidebar_product_hot_view', 'options');
//                        if(!empty($sidebar_product_view)):
//                        ?>
<!--                        <div class="sidebar__item">-->
<!--                            <div class="sidebar__head">Nhiều người xem</div>-->
<!--                            <div class="sidebar__content">-->
<!--                                <div class="sidebar__product-hot product__list">-->
<!--                                    --><?php
//                                    foreach($sidebar_product_view as $post):
//                                        $title = $post->post_title;
//                                        $link = get_permalink($post->ID);
//                                        $images = get_field('product_images', $post->ID);
//                                        $price = get_number(get_field('post_detail_price', $post->ID));
//                                        $price_sale = get_number(get_field('post_detail_price_sales', $post->ID));
//                                        $product_intro = get_field('post_detail_intro', $post->ID);
//
//                                        $thumbnailId = get_post_thumbnail_id($post->ID);
//                                        $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
//                                        $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
//                                        ?>
<!--                                        <div class="product__item">-->
<!--                                            <div class="product__inner">-->
<!--                                                <div class="product__left">-->
<!--                                                    <a href="--><?//= $link ?><!--">-->
<!--                                                        <figure class="lazy img-ratio__large" data-src="--><?//= $img ?><!--"></figure>-->
<!--                                                    </a>-->
<!--                                                </div>-->
<!--                                                <div class="product__content">-->
<!--                                                    <h2 class="product__title"><a href="--><?//= $link ?><!--">--><?//= $title ?><!--</a></h2>-->
<!--                                                    <p class="product-sidebar">-->
<!--                                                        --><?//= $price_sale ? '<span class="product-sidebar__price">'.number_format($price_sale).' đ</span><span class="product-sidebar__price-sales">'.number_format($price).' đ</span>':'<span class="product-sidebar__price">'.number_format($price).' đ</span>'; ?>
<!--                                                    </p>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        --><?php
//                                    endforeach;
//                                    ?>
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                            --><?php
//                        endif;
//                        ?>
<!--                    </div>-->
<!--                    </div>-->
<!--                    <div class="category__right">-->
<!--                        <div class="category__headline">-->
<!--                            <h1>-->
<!--                            --><?php
//                                $cat = get_category_parents(get_query_var('cat'), true, '');
//                                echo $cat;
//                            ?>
<!--                            </h1>-->
<!--                        </div>-->
<!--                        <div class="product__list">-->
<!--                        --><?php
//                        if (have_posts()): while (have_posts()) : the_post();
//
//                            $title = get_the_title();
//                            $link = get_permalink(get_the_ID());
//
//                            $images = get_field('product_images', get_the_ID());
//                            $price = get_number(get_field('post_detail_price', get_the_ID()));
//                            $price_sale = get_number(get_field('post_detail_price_sales', get_the_ID()));
//                            $product_intro = get_field('post_detail_intro', get_the_ID());
//
//                            $thumbnailId = get_post_thumbnail_id(get_the_ID());
//                            $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
//                            $alt = get_post_meta($thumbnailId, '_wp_attachment_image_alt', true);
//
//                            ?>
<!--                            <div class="tab-product__item">-->
<!--                                <div class="tab-product__item--inner">-->
<!--                                    <a class="pI" href="--><?//= $link ?><!--">-->
<!--                                        <figure class="lazy product_image product_image--two" data-src="--><?//= $img ?><!--" style=""></figure>-->
<!--                                    </a>-->
<!--                                    <h2 class="tab-product__item--title"><a href="--><?//= $link ?><!--">--><?//= $title ?><!--</a></h2>-->
<!--                                    <div class="product__price-cart">-->
<!--                                        <p class="product-horizontal__price">--><?//= $price_sale ? number_format($price_sale).' đ'.'<i class="product__price-old">'.number_format($price).' đ</i>':'<span class="product__price-sale">'.number_format($price).' đ</span>' ?><!--</p>-->
<!--                                        <a class="add-cart btnAddCart" data-toggle="modal" data-target="#myModal" data-name="--><?//= $title ?><!--" data-quantity="1" data-id="--><?//= $post->ID ?><!--"><i class="fa fa-shopping-cart"></i></a>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        --><?php //endwhile; ?>
<!--                        --><?php //else: ?>
<!--                            <article style="padding: 0 15px;">-->
<!--                                <h2 style="text-align: center">--><?php //_e( 'Không có bài viết nào.', 'html5blank' ); ?><!--</h2>-->
<!--                            </article>-->
<!--                        --><?php //endif; ?>
<!--                        </div>-->
<!---->
<!--                        --><?php //get_template_part('pagination'); ?>
<!---->
<!--                    </div>-->
<!---->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->