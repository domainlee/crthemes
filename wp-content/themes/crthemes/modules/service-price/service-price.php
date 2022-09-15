<?php
    $service_price_headline = get_sub_field('service_price_headline');
    $service_price_list = get_sub_field('service_price_list');
?>
<section class="service-price">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="service-price__inner">
                    <div class="service-2__sub"><?= $service_price_headline ?></div>
                    <div class="service-price__list">
                        <?php foreach ($service_price_list as $l): ?>
                        <div class="service-price__item">
                            <figure class="lazy service-price__image" data-src="<?= $l['service_price_image']['url'] ?>"></figure>
                            <h3 class="service-price__title"><?= $l['service_price_title'] ?></h3>
                            <div class="service-price__intro"><?= $l['service_price_intro'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>