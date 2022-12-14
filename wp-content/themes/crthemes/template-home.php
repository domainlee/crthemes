<?php
  /* Template Name: Home Page */
    get_header();
?>

<?php
if( have_rows('home_page') ):
    while ( have_rows('home_page') ) : the_row();
        $type = get_row_layout();
        switch ($type) {
            case "layout_banner":
                the_module('hero-slider', ['image' => get_sub_field('banner_image'), 'title' => get_sub_field('banner_title'), 'intro' => get_sub_field('banner_intro'), 'url' => get_sub_field('banner_url')]);
                break;
            case "layout_feature":
                the_module('feature');
                break;
            case "layout_profilo":
                the_module('profilo');
                break;
            case "layout_faq":
                the_module('faq');
                break;
            case "layout_gallery";
                the_module('gallery', ['gallery_list' => get_sub_field('gallery_list'), 'title' => get_sub_field('gallery_heading'), 'button_text' => get_sub_field('gallery_button_text'), 'button_url' => get_sub_field('gallery_button_url')]);
                break;
            case "layout_service":
                the_module('service', ['service_list'=> get_sub_field('service_list'), 'service_heading'=> get_sub_field('service_heading')]);
                break;
            case "layout_client";
                the_module('client');
                break;
            case "layout_news";
                the_module('news');
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


