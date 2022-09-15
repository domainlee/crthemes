<?php
    $title;
    $gallery_list;
    $button_text;
    $button_url;
?>

<section class="gallery">
    <div class="container">
        <div class="row">
            <div class="offset-0 offset-md-2 col-12 col-md-8">
                <h2 class="gallery__heading text-center to-top"><?= $title ?></h2>
                <div class="gallery__js owl-carousel owl-theme to-top">
                    <?php foreach ($gallery_list as $l): ?>
                        <div class="gallery__item">
                            <a href="<?= $l['gallery_url'] ?>">
                                <figure class="ratio ratio-2x3" style="background-image: url(<?= $l['gallery_image']['url'] ?>)">
                                    <div class="gallery__content">
                                        <img src="<?= $l['gallery_logo']['url'] ?>" alt="" />
                                        <div class=""><?= $l['gallery_content'] ?></div>
                                    </div>
                                </figure>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if(!empty($button_text) && !empty($button_url)): ?>
                <div class="gallery__button button__default to-top">
                    <a href="<?= $button_url ? $button_url:'#' ?>" target="_blank"><?= $button_text ? $button_text:'Xem Thêm' ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
