<?php
    $list;
    $title;
    $content;
?>

<section class="hero">
    <?php if($list): ?>
    <div class="hero__slider">
        <div class="hero__js owl-carousel owl-theme">
            <?php foreach ($list as $b): ?>
                <div>
                    <a href="" target="_blank">
                        <figure class="hero__image ratio ratio-16x9 m-0 owl-lazy" data-src="<?= $b['banner_image']['url'] ?>">
                            <div class="hero__content">
                                <h3 class="to-top"><?= $b['banner_heading'] ?></h3>
                            </div>
                        </figure>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</section>

