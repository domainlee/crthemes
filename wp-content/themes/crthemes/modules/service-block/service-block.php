<?php
    $service_list = get_sub_field('service_list');
    $heading = get_sub_field('service_block_heading');
?>
<section class="service service-block">
    <div class="container">
        <div class="row">
            <?php if($heading): ?>
            <div class="col-md-12">
                <h2 class="service-block__heading"><?= $heading ?></h2>
            </div>
            <?php endif; ?>
            <div class="col-md-12">
                <?php if($service_list): foreach($service_list as $i): ?>
                <div class="service__item">
                    <div class="service__col">
                        <figure class="service__image lazy" data-src="<?= $i['service_image']['url'] ?>"></figure>
                    </div>
                    <div class="service__col">
                        <h2 class="service__title"><?= $i['service_title'] ?></h2>
                        <div class="service__intro">
                            <?= $i['service_description'] ?>
                        </div>
                        <?php if($i['service_button_text']): ?>
                            <a class="service__button" href="<?= $i['service_url'] ?>"><?= $i['service_button_text'] ? $i['service_button_text']:'Chi tiết'?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</section>
