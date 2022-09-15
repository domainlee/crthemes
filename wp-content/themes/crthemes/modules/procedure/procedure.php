<?php
    $heading = get_sub_field('procedure_heading');
    $procedure_list = get_sub_field('procedure_list');
    $procedure_image = get_sub_field('procedure_image');
    $bg = get_template_directory_uri() . '/img/bc.png';
?>
<section class="procedure">
    <div class="procedure__bg lazy" data-src="<?= $bg ?>"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="procedure__inner">
                    <h3 class="procedure__heading wp1"><?= $heading ?></h3>
                    <ul class="procedure__list">
                        <?php $c = 0; foreach($procedure_list as $l): $c++; ?>
                            <li class="wp<?= $c ?>"><i class="procedure__icon" style="background-image: url('<?= $l['procedure_icon']['url'] ?>')"></i><span><?= $l['procedure_label'] ?></span><?= $l['procedure_content'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>