<?php
/* Template Name: Trang Chi Tiết Dịch Vụ */
get_header();
?>
<?php
if( have_rows('service_detail_page') ):
    while ( have_rows('service_detail_page') ) : the_row();
        $type = get_row_layout();
        switch ($type) {
            case "layout_heading":
                the_module('heading', ['title' => get_sub_field('heading_title') ? get_sub_field('heading_title'):get_the_title(), 'description' => get_sub_field('heading_intro')]);
                break;
            case "layout_service_block":
                the_module('service-block');
                break;
            case "layout_strategy3":
                the_module('strategy3');
                break;
            case "layout_strategy4":
                the_module('strategy4');
                break;
            case "layout_case_study":
                the_module('case-study');
                break;
            case "layout_form":
                the_module('form');
                break;
            case "layout_cooperate":
                the_module('cooperate');
                break;
            case "layout_cooperate_two":
                the_module('cooperate-two');
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