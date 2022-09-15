<?php
    $partner_headline = get_sub_field('partner_headline');
    $partner_list = get_sub_field('partner_list');
?>

<section class="partner" id="doi-tac">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="partner__headline"><?= $partner_headline ?></h2>
                <div class="partner__list">
                    <?php foreach ($partner_list as $l): ?>
                    <div class="partner__item">
                        <a href="<?= $l['partner_url'] ?>" target="_blank">
                            <figure class="lazy partner__image" data-src="<?= $l['partner_image']['url'] ?>"></figure>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
