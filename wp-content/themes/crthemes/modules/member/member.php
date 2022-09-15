<?php
    $member_heading = get_sub_field('member_heading');
    $member_intro = get_sub_field('member_intro');
    $member_list = get_sub_field('member_list');
?>
<section class="member">
    <div class="container">
        <div class="row">
            <?php if($member_heading): ?>
            <div class="col-md-12">
                <h2 class="member__heading wp2"><?= $member_heading ?></h2>
                <div class="member__intro wp2"><?= $member_intro ?></div>
            </div>
            <?php endif; ?>
            <div class="col-md-12">
                <div class="member__inner wp3 member__js owl-carousel owl-theme">
                    <?php foreach($member_list as $i): ?>
                        <div class="member__item">
                            <div class="member__item--inner">
                                <figure class="member__image owl-lazy" data-src="<?= $i['member_avata']['sizes']['base-small'] ?>"></figure>
                                <h3 class="member__name"><?= $i['member_name'] ?></h3>
                                <div class="member__role"><?= $i['member_role'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>