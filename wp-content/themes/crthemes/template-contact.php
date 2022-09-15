<?php
/* Template Name: Trang Liên hệ */
get_header();
$id = get_the_ID();
$thumbnailId = get_post_thumbnail_id($id);
$img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
?>
<div class="single-header">
    <figure class="ratio lazy" data-src="<?= $img ?>"></figure>
</div>

<?php if (have_posts()): while (have_posts()) : the_post(); ?>
    <section class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3 class="contact__heading"><?= get_the_title() ?></h3>
                    <div class="">
                        <?= get_the_content() ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <h3 class="contact__heading">Gửi tin nhắn cho chúng tôi</h3>
                    <form class="form-contact" method="post" enctype="multipart/form-data" data-parsley-validate novalidate>
                        <div class="form-contact__row">
                            <input class="contact__name" type="text" name="name" placeholder="Tên của bạn" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"/>
                            <input class="contact__email" type="text" name="email" placeholder="Địa chỉ email của bạn" data-parsley-type="email" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"/>
                        </div>
                        <div class="form-contact__row">
                            <input class="contact__phone" type="text" name="phone" placeholder="Số điện thoại của bạn" data-parsley-type="digits" data-parsley-length="[10,11]" required="required" data-parsley-error-message="Không được để trống"/>
                        </div>
                        <div class="form-contact__row">
                            <textarea class="contact__content" type="text" name="address" placeholder="Nội dung" required="required" data-parsley-minlength="5" data-parsley-error-message="Không được để trống"></textarea>
                        </div>
                        <button class="form_submit contact__btn-submit" type="submit">Đăng ký tư vấn</button>
                        <input type="hidden" class="contact__redirect" value="<?= site_url() ?>" />
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php endwhile; ?>
<?php endif; ?>
<?php
get_footer();
?>


