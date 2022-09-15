<?php
$footer_tool = get_field('footer_tool', 'options');
$footer_social = get_field('footer_social', 'options');

$service_form_title = get_sub_field('service_form_title');
$service_form_intro = get_sub_field('service_form_intro');
$service_form_image = get_sub_field('service_form_image');
$service_form_logo = get_sub_field('service_form_logo');

?>
<section class="service-form">
    <div class="service-form__image lazy" data-src="<?= $service_form_image['url'] ?>"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="service-form__inner">
                    <div class="service-form__right">
                        <h2 class="service-form__headline"><?= $service_form_title ?></h2>
                        <div class="service-form__intro"><?= $service_form_intro ?></div>
                        <form class="form-contact" method="post" enctype="multipart/form-data" data-parsley-validate novalidate>
                            <input class="contact__name" type="text" name="name" placeholder="Tên của bạn" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"/>
                            <input class="contact__email" type="text" name="email" placeholder="Địa chỉ email của bạn" data-parsley-type="email" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"/>
                            <textarea class="contact__content" name="message" rows="4" placeholder="Nội dung" required="required" data-parsley-error-message="Không được để trống"></textarea>
                            <button class="form_submit contact__btn-submit" type="submit">Gửi</button>
                            <input type="hidden" class="contact__redirect" value="<?= site_url() ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="footer-service">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <figure class="lazy form__logo" data-src="<?= $service_form_logo['url'] ?>"></figure>
                <div class="footer-service__list">
                    <?php foreach ($footer_tool as $c): ?>
                        <div class="form__contact--item">
                            <strong><?= $c['tool_title'] ?></strong>
                            <span><?= $c['tool_value'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="footer-service__social">
                    <?php foreach ($footer_social as $c): ?>
                        <div class="form__social--item">
                            <a href="<?= $c['footer_social_url'] ?>">
                                <figure class="social--image lazy" data-src="<?= $c['footer_social_image']['url'] ?>"></figure>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>