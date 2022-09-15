<?php
/* Template Name: Trang Dịch Vụ */
get_header();
?>
<?php
if( have_rows('service_page') ):
    while ( have_rows('service_page') ) : the_row();
        $type = get_row_layout();
        switch ($type) {
            case "layout_banner":
                the_module('hero-slider', ['list' => get_sub_field('banner_list'), 'title' => get_sub_field('banner_title'), 'content' => get_sub_field('banner_content')]);
                break;
            case "layout_service":
                the_module('service');
                break;
            case "layout_strategy2":
                the_module('strategy2');
                break;
            case "layout_form":
                the_module('form');
                break;
            default:
                break;
        }
    endwhile;
endif;
?>
<?php
get_footer();
?>