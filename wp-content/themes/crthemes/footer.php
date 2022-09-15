<?php
    $footer_social = get_field('footer_social', 'options');
    $footer_about = get_field('footer_about', 'options');
    $footer_about2 = get_field('footer_about2', 'options');

    $logo = get_field('logo', 'options');
    $head_tool = get_field('head_tool2', 'options');
    $favicon = get_field('head_favicon', 'options');
    $logoHeader = '';
    if (!empty($logo['url'])) {
        $logoHeader = $logo['url'];
    } else {
        $logoHeader = get_template_directory_uri() . '/img/shoes/logo.png';
    }
?>

<footer class="footer" >
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer__inner">
                    <div class="footer__col">
                        <div class="footer__logo">
                            <img src="<?= $logoHeader; ?>" alt="">
                        </div>
                    </div>
                    <div class="footer__col">
                        <div class="">
                            <?= $footer_about ?>
                        </div>
                    </div>
                    <div class="footer__col">
                        <h3 class="footer__headline">Liên kết</h3>
                        <div class="footer__social">
                            <?php foreach ($footer_social as $i): ?>
                                <a href="<?= $i['footer_social_url'] ?>">
                                    <i class="<?= $i['footer_social_image'] ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <h3 class="footer__headline">Nhận tư vấn của chúng tôi</h3>
                        <div class="footer__form">
                            <form class="form-contact" method="post" enctype="multipart/form-data" data-parsley-validate novalidate>
                                <div class="form-contact__row">
                                    <input class="contact__name" type="text" name="name" placeholder="Tên của bạn" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"/>
                                </div>
                                <div class="form-contact__row">
                                    <input class="contact__phone" type="text" name="phone" placeholder="Số điện thoại của bạn" data-parsley-type="digits" data-parsley-length="[10,11]" required="required" data-parsley-error-message="Không được để trống"/>
                                </div>
                                <button class="form_submit contact__btn-submit" type="submit">Đăng ký tư vấn</button>
                                <input type="hidden" class="contact__redirect" value="<?= site_url() ?>" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="footer__copywriter">
        © Bản quyền thuộc về Công ty cổ phần tư vấn thiết kế kiến trúc và nội thất A&I
    </div>
</footer>


<?php wp_footer(); ?>

<?php
$footer_script = get_field('script_footer', 'options', false);
if (!empty($footer_script)):
    echo $footer_script;
endif; ?>

</body>

</html>
