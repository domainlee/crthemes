<?php
    $feature_heading = get_sub_field('feature_heading');
    $feature_sub = get_sub_field('feature_sub');
    $list_features = get_sub_field('list_features');
?>

<section class="feature py-6">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <h2 class="feature__heading heading__default"><?= $feature_heading ?></h2>
                <div class="feature__sub sub__default"><?= $feature_sub ?></div>
                <div class="feature__list">
                    <?php if($list_features): ?>
                        <?php foreach ($list_features as $v): ?>
                            <div class="feature__item">
                                <div class="feature__icon"><?= $v['feature_icon'] ?></div>
                                <h3 class="feature__title"><?= $v['feature_title'] ?></h3>
                                <div class="feature__intro"><?= $v['feature_intro'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
