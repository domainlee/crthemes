<?php
    $maps_headline = get_sub_field('maps_headline');
    $maps_address1 = get_sub_field('maps_address1');
    $maps_address2 = get_sub_field('maps_address2');
    $maps_location1 = get_sub_field('maps_location1');
    $maps_address = get_sub_field('maps_address');
?>
<section class="maps">
    <?= $maps_address ?>
    <div class="maps__wrapper">
        <div class="maps__inner">
            <h2 class="maps__headline"><?= $maps_headline ?></h2>
            <div class="maps__address1"><?= $maps_address1 ?></div>
            <div class="maps__address2"><?= $maps_address2 ?></div>
            <div class="maps__button">
                <a href="<?= $maps_location1 ?>">Chỉ đường</a>
            </div>
        </div>
    </div>
</section>