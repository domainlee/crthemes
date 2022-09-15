<?php
    get_header();
    $id = get_the_ID();
    $thumbnailId = get_post_thumbnail_id($id);
    $img = wp_get_attachment_image_src($thumbnailId, 'base-small')[0];
?>
<section class="single-header">
    <figure class="ratio lazy" data-src="<?= $img ?>"></figure>
</section>

<?php
    $post_project = get_field('post_project', $id);
    if(!empty($post_project)) {
        get_template_part('single-project');
    } else {
        get_template_part('single-bai-viet');
    }
?>

<?php get_footer(); ?>
