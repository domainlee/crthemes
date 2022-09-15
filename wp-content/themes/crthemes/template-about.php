<?php
  /* Template Name: Trang Giới Thiệu */
    get_header();
?>

<?php
if( have_rows('about_page') ):
    while ( have_rows('about_page') ) : the_row();
        the_module('page-about-header', ['title' => get_the_title(), 'content' => get_the_content()]);
        $type = get_row_layout();
        switch ($type) {
            case "layout_gallery";
                the_module('gallery', ['gallery_list' => get_sub_field('gallery_list'), 'title' => get_sub_field('gallery_heading')]);
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


