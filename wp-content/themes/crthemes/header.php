<?php
session_start();
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<?php
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
<head>
    <?php
    $script_first_head = get_field('script_first_head', 'options', false);
    if (!empty($script_first_head)):
        echo $script_first_head;
    endif; ?>

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta property="fb:app_id" content="210041499841167" />
    <title><?php bloginfo('name'); ?><?php if (wp_title('', false)) { echo ' | ';} ?><?php wp_title(''); ?></title>

    <link href="<?= !empty($favicon['url']) ? $favicon['url'] : $logoHeader; ?>" rel="shortcut icon">
    <link href="<?= !empty($favicon['url']) ? $favicon['url'] : $logoHeader; ?>" rel="apple-touch-icon-precomposed">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">

    <?php wp_head(); ?>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.3&appId=210041499841167&autoLogAppEvents=1"></script>
    <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>


</head>
<body <?php body_class(); ?>>
<?php
$script_in_body = get_field('script_in_body', 'options', false);
if (!empty($script_in_body)):
    echo $script_in_body;
endif; ?>

<header class="head">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="head__inner d-flex justify-content-between align-items-center">
                    <div class="head__logo">
                        <a href="<?= site_url(); ?>">
                            <img src="<?= $logoHeader; ?>" alt="<?php bloginfo('name'); ?>">
                        </a>
                    </div>
                    <div class="head__nav d-none d-md-flex">
                        <?php if (has_nav_menu('header-primary')) {
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'header-primary',
                                    'container_class' => 'header__menu',
                                    'menu_class' => 'header__navigation'
                                )
                            );
                        } ?>
                    </div>
                    <div class="head__search d-none">
                        <?= get_search_form(); ?>
                    </div>
                    <div class="nav-mobile d-block d-md-none">
                        <a class="toggle-menu">
                            <span class="fa-navicon__custom"><i></i></span>
                        </a>
                        <div class="nav-mobile__menu nav__mobile">
                            <?php if (has_nav_menu('header-primary')) {
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'header-primary',
                                        'container_class' => 'header__menu--mobile',
                                        'menu_class' => 'header__navigation--mobile'
                                    )
                                );
                            } ?>
                            <div class="nav-mobile__search">
                                <?= get_search_form(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div id="preloader">
    <div class="loader_line"></div>
</div>



