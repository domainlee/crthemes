<?php get_header();
$img = get_template_directory_uri() . '/img/shoes/404.png';
?>
    <style>
        #post-404 {
            padding: 60px 0;
            text-align: center;
            margin: 0 auto;
        }
        #post-404>h1 {
            font-size: 35px;
            color: #333;
            font-weight: 600;
            letter-spacing: 2px;
        }
        #post-404>h1>p {
            font-size: 25px;
            color: #999;
            letter-spacing: 2px;
            margin-top: 10px;
        }
        #post-404 h2>a {
            color: #fff;
            padding: 10px 45px;
            border: #de1865;
            background: #de1865;
            text-transform: uppercase;
            font-size: 15px;
            font-weight: normal;
            border-radius: 15px;
        }
        .error-404 {
            background-color: #e0e0e0;
            padding: 70px 0;
            margin-bottom: 100px;
        }
        .error-404__inner {
            display: flex;
            justify-content: center;
            max-width: 650px;
            margin: 0 auto;
        }
        .error-404__img {
            width: 80%;
            position: relative;
            background-repeat: no-repeat;
            background-size: contain;
        }
        .error-404__img:before {
            content: '';
            padding-top: 20%;
            display: block;
        }
    </style>
    <section class="error-404">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="error-404__inner">
                        <div class="error-404__img" style="background-image: url('<?= $img; ?>');"></div>
                        <div id="post-404">
                            <h1><?php _e( 'Lỗi 404 <br/> <p>Không tìm thấy trang</p>', 'html5blank' ); ?></h1>
                            <h2>
                                <a href="<?php echo home_url(); ?>"><?php _e( 'Trở lại', 'html5blank' ); ?></a>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php get_footer(); ?>
