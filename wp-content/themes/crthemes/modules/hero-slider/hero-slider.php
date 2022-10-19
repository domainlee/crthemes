<?php
    $image;
    $title;
    $intro;
    $url;
?>

<section class="hero">
    <div class="container-xl">
        <div class="row py-6 align-items-center">
            <div class="col-5">
                <h1 class="hero__heading"><?= $title ?></h1>
                <p class="hero__intro"><?= $intro ?></p>
                <div class="hero__button">
                    <a href="<?= $url ? $url:'#' ?>">Learn More <i class="icofont-arrow-down"></i></a>
                </div>
            </div>
            <div class="col-6 offset-1">
                <figure class="hero__image ratio ratio-1x1 bg-contain bg-center bg-no-repeat lazy" data-src="<?= $image['url'] ?>"></figure>
            </div>
        </div>
    </div>
</section>

