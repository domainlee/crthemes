<?php
    $client_heading = get_sub_field('client_heading');
    $client_list = get_sub_field('client_list');
?>
<section class="client">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="client__heading to-top"><?= $client_heading ?></h2>
            </div>
        </div>
    </div>
    <div class="client__row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="client__list client__js owl-carousel owl-theme to-top">
                        <?php foreach ($client_list as $l): ?>
                        <div class="client__item">
                            <div class="client__left">
                                <div class="client__image">
                                    <div class="square position-absolute left-top"><div></div></div>
                                    <div class="square position-absolute right-top"><div></div></div>
                                    <figure class="ratio ratio-1x1 owl-lazy" data-src="<?= $l['client_image']['url'] ?>"></figure>
                                    <div class="square position-absolute left-bottom"><div></div></div>
                                    <div class="square position-absolute right-bottom"><div></div></div>
                                </div>
                            </div>
                            <div class="client__right">
                                <div class="client__content">
                                    <div class="client__rate"><i></i><i></i><i></i><i></i><i></i></div>
                                    <h3 class="client__title"><?= $l['client_name'] ?></h3>
                                    <div class="client__intro"><?= $l['client_intro'] ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

