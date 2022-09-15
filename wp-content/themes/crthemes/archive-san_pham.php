<?php
    get_header();
?>

<div class="category-product">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="category-product__search">
                    <div class="category-product__search--label">
                        Tìm theo
                    </div>
                    <div>
                        Thương hiệu <i class="fa fa-angle-down"></i>
                    </div>
                    <div>
                        Mức giá <i class="fa fa-angle-down"></i>
                    </div>
                    <div class="category-product__search--type">
                        Sản phẩm <i class="fa fa-angle-down"></i>
                    </div>
                </div>
                <?php
                get_template_part('loop-product');
                get_template_part('pagination');
                ?>
            </div>
        </div>
    </div>
</div>

<?php
    get_footer();
?>


