<?php
    $service_list;
    $service_heading;
?>
<section class="service">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="service__heading text-center to-top"><?= $service_heading ?></h2>
            </div>
        </div>
    </div>
    <?php $c = 0; foreach($service_list as $i): $c++; ?>
    <div class="service__row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="service__item">
                        <div class="service__col service__number to-top">
                            <?= '0'.$c; ?>
                        </div>
                        <div class="service__col service__image to-top">
                            <div class="service__image--inner">
                                <div class="square position-absolute left-top"><div></div></div>
                                <div class="square position-absolute right-top"><div></div></div>
                                <a class="service__button" href="<?= $i['service_url'] ?>"><figure class="ratio ratio-2x3 lazy" data-src="<?= $i['service_image']['url'] ?>"></figure></a>
                                <div class="square position-absolute left-bottom"><div></div></div>
                                <div class="square position-absolute right-bottom"><div></div></div>
                            </div>
                        </div>
                        <div class="service__col service__content to-top">
                            <h2 class="service__title"><a href="<?= $i['service_url'] ?>" target="_blank"><?= $i['service_title'] ?></a></h2>
                            <div class="service__intro">
                                <?= $i['service_intro'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</section>
