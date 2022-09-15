<?php
    $footer_tool = get_field('footer_tool', 'options');
    $footer_social = get_field('footer_social', 'options');

    $form_title = get_sub_field('form_title');
    $is_home = get_sub_field('is_home');
    $form_intro = get_sub_field('form_intro');
    $bg = $is_home ? get_template_directory_uri() . '/img/bg_contact.jpg':null;

?>
<section class="form lazy <?= $is_home ? 'form-home':'form-page' ?>" data-src="<?= $bg ?>" id="lien-he">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form__inner">
                    <h2 class="form__headline"><?= $form_title ?></h2>
                    <div class="form__intro">
                        <?= $form_intro ?>
                    </div>
                    <form class="form-contact" method="post" enctype="multipart/form-data" data-parsley-validate novalidate>
                        <div class="form-contact__row">
                            <input class="contact__name" type="text" name="name" placeholder="Tên của bạn" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"/>
                        </div>
                        <div class="form-contact__row">
                            <input class="contact__email" type="text" name="email" placeholder="Địa chỉ email của bạn" data-parsley-type="email" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"/>
                        </div>
                        <div class="form-contact__row">
                            <input class="contact__service" type="text" name="address" placeholder="Dịch vụ bạn quan tâm" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"/>
                            <input class="contact__phone" type="text" name="phone" placeholder="Số điện thoại của bạn" data-parsley-type="digits" data-parsley-length="[10,11]" required="required" data-parsley-error-message="Không được để trống"/>
                        </div>


                        <button class="form_submit contact__btn-submit" type="submit">Đăng ký tư vấn</button>
                        <input type="hidden" class="contact__redirect" value="<?= site_url() ?>" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>