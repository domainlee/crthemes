<?php
$tab_product_headline = get_sub_field('tab_product_headline');
$tab_product_list = get_sub_field('tab_product_list');
if (!empty($tab_product_list)):
    ?>
    <section class="tab-product">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tab-product__inner">
                        <div class="module-product__headline tab-product__headline">
                            <h2>
                                <?= $tab_product_headline; ?>
                            </h2>
                        </div>
                        <div class="tab-product__list">
                            <ul class="nav nav-tabs NavProductOption">
                                <?php
                                $c = 0;
                                foreach ($tab_product_list as $t): $c++; ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= $c == 1 ? 'active' : '' ?>" data-toggle="tab"
                                           href="#tab<?= $c ?>"><?= $t['tab_headline'] ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <?php
                                $c = 0;
                                foreach ($tab_product_list as $t): $c++; ?>
                                    <div id="tab<?= $c ?>"
                                         class="container tab-pane tab <?= $c == 1 ? 'active' : '' ?>"><br>
                                        <?php if (!empty($t['list_product'])): ?>
                                            <div class="product-horizontal">
                                                <?php foreach ($t['list_product'] as $post):
                                                    $title = $post->post_title;
                                                    $link = get_permalink($post->ID);

                                                    $images = get_field('product_images', $post->ID);
                                                    $price = get_number(get_field('post_detail_price', $post->ID));

                                                    $thumbnailId = get_post_thumbnail_id($post->ID);
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
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

