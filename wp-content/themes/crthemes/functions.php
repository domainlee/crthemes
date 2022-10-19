<?php
/*
 *  Author: Mienlv1790 | fb/mienlv1790
 *  URL: dweb.vn
 */

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

include_once( __DIR__ . '/lib/class-theme-init.php' );
require_once( __DIR__ . '/lib/modules.php' );
require_once( __DIR__ . '/lib/widget.php' );
include_once( __DIR__ . '/lib/payment.php' );

require_once( __DIR__ . '/lib/payment/config.php');

//update_option('siteurl','http://localhost/cooken');
//update_option('home','http://localhost/cooken');

//require_once( __DIR__ . '/template-variable.php.php');
//echo $nameFull;die;

//update_option('siteurl','https://kientrucnoithatcaocap.vn');
//update_option('home','https://kientrucnoithatcaocap.vn');

if (!isset($content_width))
{
    $content_width = 900;
}

function removeSigns($text) {
    if(!$text) {
        return "";
    }
    $vnSigns = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'd' => 'đ',
        'D' => 'Đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'E' => 'É|É|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ'
    );
    foreach($vnSigns as $unsign => $signs) {
        $text = preg_replace("/($signs)/", $unsign, $text);
    }
    return $text;
}

function slugify($text, $toLower = true) {
    if (empty($text)) {
        return '';
    }
    $text = trim(removeSigns($text));
    $text = preg_replace('/[^a-zA-Z0-9\s.?!-]/', '', $text);
    $text = str_replace(array(' - ', ' ', '&', '--'), '-', $text);

    if($toLower) {
        $text = strtolower($text);
    }
    return $text;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');

//    remove_image_size( 'large' );
//    remove_image_size( 'medium' );
//    remove_image_size( 'small' );

//    add_image_size('large', 700, '', true); // Large Thumbnail
//    add_image_size('medium', 250, '', true); // Medium Thumbnail
//    add_image_size('small', 120, '', true); // Small Thumbnail
//    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
	'default-color' => 'FFF',
	'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
	'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
	'header-text'			=> false,
	'default-text-color'		=> '000',
	'width'				=> 1000,
	'height'			=> 198,
	'random-default'		=> false,
	'wp-head-callback'		=> $wphead_cb,
	'admin-head-callback'		=> $adminhead_cb,
	'admin-preview-callback'	=> $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

function my_breadcrumb($id = null) {
    echo '<ul>';
    if (!is_home()) {
        echo '<a href="';
        echo home_url();
        echo '">';
        echo 'Trang chủ';
        echo "</a>";
        if (is_category() || is_single() || is_tax()) {
            if (is_single()) {
                global $post,$wp_query;

//                $terms = get_the_terms( $post->ID, 'danhmuc_sanpham' );
//
//                if ( ! empty( $terms ) ) {
//                    if ( function_exists( 'wp_list_sort' ) ) {
//                        $terms = wp_list_sort( $terms, 'term_id', 'DESC' );
//                    } else {
//                        usort( $terms, '_usort_terms_by_ID' );
//                    }
//                    $category_object = apply_filters( 'dweb_cpt_product_post_type_link_product_cat', $terms[0], $terms, $post );
//                    $category_object = get_term( $category_object, 'danhmuc_sanpham' );
//                    $product_cat     = '<a href="'.get_term_link($category_object->term_id).'">'.$category_object->name.'</a>';
//                    if ( $category_object->parent ) {
//                        $ancestors = get_ancestors( $category_object->term_id, 'danhmuc_sanpham' );
//                        foreach ( $ancestors as $ancestor ) {
//                            $ancestor_object = get_term( $ancestor, 'danhmuc_sanpham' );
//                            $product_cat     = '<a href="'.get_term_link($ancestor_object->term_id).'">'.$ancestor_object->name.'</a>' . $product_cat;
//                        }
//                    }
//                } else {
//                    $product_cat = '';
//                }
//
//                echo $product_cat;



                $post_type = get_post_type();

                // If it is a custom post type display name and link
                if($post_type != 'post') {

                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);
                    /*echo '<a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a>';*/

                }

                // Get post category info
                $category = get_the_category();

                if(!empty($category)) {

                    // Get last category post is in
                    $last_category = end(array_values($category));

                    // Get parent any categories and create array
                    $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                    $cat_parents = explode(',',$get_cat_parents);

                    // Loop through parent categories and store in variable $cat_display
                    $cat_display = '';
                    foreach($cat_parents as $parents) {
                        $cat_display .= $parents;
                    }

                }


                // Check if the post is in a category
                if(!empty($last_category)) {
                    echo $cat_display;
                    echo '<a class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
                    // Else if post is in a custom taxonomy
                } else if(!empty($cat_id)) {
                    echo '<a class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
                } else {
                    echo '<a class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
                }
            }
            if (is_category()) {
                $cat = get_category_parents(get_query_var('cat'), true, '');
                // remove last &gt;
                echo preg_replace('/&gt;\s$|&gt;$/', '', $cat);
            }
            if (is_tax()) {
                $term = get_term_by("slug", get_query_var("term"), get_query_var("taxonomy") );
                $tmpTerm = $term;
                $tmpCrumbs = array();
                while ($tmpTerm->parent > 0){
                    $tmpTerm = get_term($tmpTerm->parent, get_query_var("taxonomy"));
                    $crumb = '<a href="' . get_term_link($tmpTerm, get_query_var('taxonomy')) . '">' . $tmpTerm->name . '</a>';
                    array_push($tmpCrumbs, $crumb);
                }
                echo implode('', array_reverse($tmpCrumbs));
                echo '<a href="' . get_term_link($tmpTerm, get_query_var('taxonomy')) . '">' . $term->name . '</a>';
            }
        } elseif (is_page()) {
            if ($id != null) {
                $an = get_post_ancestors($id);
                if(isset($an['0'])) {
                    $parent = '<a href="' . get_permalink($an['0']) . '">' . ucwords(get_the_title($an['0'])) . '</a>';
                    echo !is_null($parent) ? $parent . "" : null;
                }
                $parent = get_the_title($id);
                $parent = '<a href="' . get_permalink($id) . '">' . ucwords($parent) . '</a>';
                echo !is_null($parent) ? $parent . "" : null;
            }
            echo '<a href="' . get_permalink(get_the_ID()) . '">';
            ucwords(the_title());
            echo '</a>';
        }
    }
    echo '</ul>';
}

function my_breadcrumb2($id = null) {
    /*echo '<ul>';
    echo '<a href="';
    echo home_url();
    echo '">';
    echo 'Trang chủ';
    echo "</a>";*/
    if (is_category() || is_single() || is_tax()) {
        if (is_single()) {
            global $post,$wp_query;

            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a>';

            }

            // Get post category info
            $category = get_the_category();

            if(!empty($category)) {

                // Get last category post is in
                $last_category = end(array_values($category));

                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);

                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= $parents;
                }

            }


            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<a class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
                // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                echo '<a class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
            } else {
                echo '<a class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
            }
        }
        if (is_category()) {
            $cat = get_category_parents(get_query_var('cat'), true, '');
            // remove last &gt;
            echo preg_replace('/&gt;\s$|&gt;$/', '', $cat);
        }
        if (is_tax()) {
            $tag = single_tag_title('', false);
            $tag = get_tag_id($tag);
            $term = get_term_parents($tag, get_query_var('taxonomy'), true, '');
            // remove last &gt;
            echo preg_replace('/&gt;\s$|&gt;$/', '', $term);
        }
    } elseif (is_page()) {
        if ($id != null) {
            $an = get_post_ancestors($id);
            if(isset($an['0'])) {
                $parent = '<a href="' . get_permalink($an['0']) . '">' . ucwords(get_the_title($an['0'])) . '</a>';
                echo !is_null($parent) ? $parent . "" : null;
            }
            $parent = get_the_title($id);
            $parent = '<a href="' . get_permalink($id) . '">' . ucwords($parent) . '</a>';
            echo !is_null($parent) ? $parent . "" : null;
        }
        echo '<a href="' . get_permalink(get_the_ID()) . '">';
        ucwords(the_title());
        echo '</a>';
    }
    echo '</ul>';
}


function wpa_cpt_tags( $query ) {
    if ( $query->is_tag() && $query->is_main_query() ) {
        $query->set( 'post_type', array( 'post', 'du-an', 'bai-viet' ) );
    }
    if ( $query->is_search() && $query->is_main_query() ) {
        $query->set( 'post_type', array( 'post' ) );
    }
}
add_action( 'pre_get_posts', 'wpa_cpt_tags' );

if ( function_exists('acf_add_options_page')) {
    acf_add_options_page( array (
        'page_title' 	=> 'Tuỳ chỉnh khác',
        'menu_title'	=> 'Tuỳ chỉnh khác',
        'menu_slug' 	=> 'theme-options',
        'capability'	=> 'administrator',
        'redirect'		=> false
    ) );
}

// HTML5 Blank navigation
function html5blank_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

    }
}

// Load HTML5 Blank conditional scripts
function html5blank_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}

function dweb_scripts()
{
    wp_register_script('main', get_template_directory_uri() . '/assets/build/js/main.bundle.js', array('jquery'), '1.0.1', true); // Custom scripts
    wp_enqueue_script('main'); // Enqueue it!
}

// Load HTML5 Blank styles
function html5blank_styles()
{
    wp_register_style('html5blank', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('html5blank'); // Enqueue it!

    wp_register_style('main', get_template_directory_uri() . '/assets/build/css/main.min.css', array(), '1.1', 'all');
    wp_enqueue_style('main'); // Enqueue it!
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Tin tức', 'html5blank'),
        'description' => __('Sidebar các trang tin tức', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Sản phẩm', 'html5blank'),
        'description' => __('Sidebar các trang sản phẩm', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text'          => __('«'),
        'next_text'          => __('»'),
    ));
}


// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('', 'html5blank') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
//add_action('init', 'add_options_page'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'html5blank_conditional_scripts'); // Add Conditional Page Scripts
add_action('wp_print_scripts', 'dweb_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
//add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
//add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called HTML5-Blank
function create_post_type_html5()
{
    register_taxonomy_for_object_type('category', 'html5-blank'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'html5-blank');
    register_post_type('html5-blank', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Banner', 'html5blank'), // Rename these to suit
            'singular_name' => __('Banner', 'html5blank'),
            'add_new' => __('Thêm mới Banner', 'html5blank'),
            'add_new_item' => __('Thêm mới Banner', 'html5blank'),
            'edit' => __('Edit Banner', 'html5blank'),
            'edit_item' => __('Edit Banner', 'html5blank'),
            'new_item' => __('Thêm mới Banner', 'html5blank'),
            'view' => __('Xem Banner', 'html5blank'),
            'view_item' => __('Xem Banner', 'html5blank'),
            'search_items' => __('Tìm', 'html5blank'),
            'not_found' => __('Không tồn tại banner nào.', 'html5blank'),
            'not_found_in_trash' => __('Không tồn tại.', 'html5blank')
        ),
        'slug' => 'danh-muc-banner',

        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

function wpdocs_my_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
        <div>
            <input type="text" placeholder="Tìm kiếm  123..." value="' . get_search_query() . '" name="s" id="s" />
        </div>
        <div>
            <button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
        </div>
    </form>';
    return $form;
}

add_action( 'wp_ajax_nopriv_order', 'order' );
add_action( 'wp_ajax_order', 'order' );

function order()
{
    $orderId = $_POST['orderId'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    $list_product = $_POST['data'];
    $redirect = $_POST['redirect'] == site_url() ? 0:1;

    $post = array(
        'post_title'	=> $name,
        'post_content'	=> '',
        'post_status'	=> 'publish',
        'post_type' 	=> 'order-custom'
    );
    $my_post_id = wp_insert_post($post);

    $repeater_field = 'order_list';
    $repeater_key = 'field_5443d4e2dd4e4';
    $sub_field_product = 'order_name_product';
    $sub_field_key_product = 'field_5443d4e2d13dd5';
    $sub_field_material = 'order_material';
    $sub_field_key_material = 'field_5443d4e2d62a4';
    $sub_field_weight = 'order_weight';
    $sub_field_key_weight = 'field_5443d4e2d62a4';
    $sub_field_color = 'order_color';
    $sub_field_key_color = 'field_5443d4e25dqd7c';
    $sub_field_quantity = 'order_quantity';
    $sub_field_key_quantity = 'field_5443d4e2d73f66';
    $sub_field_price = 'order_price';
    $sub_field_key_price = 'field_5443d4e2a45ab';

    $count = count($list_product);
    if ($count) {

        add_post_meta($my_post_id, $repeater_field, $count, true);
        add_post_meta($my_post_id, '_'.$repeater_field, $repeater_key, true);
        $c = 0;
        $price_total = 0;
        foreach($list_product as $v) {
            $d = $c++;

            $sub_product = $repeater_field.'_'.$d.'_'.$sub_field_product;
            add_post_meta($my_post_id, $sub_product, $v['name'], false);
            add_post_meta($my_post_id, '_'.$sub_product, $sub_field_key_product, false);

            $sub_material = $repeater_field.'_'.$d.'_'.$sub_field_material;
            add_post_meta($my_post_id, $sub_material, $v['material'], false);
            add_post_meta($my_post_id, '_'.$sub_material, $sub_field_key_material, false);

            $sub_weight = $repeater_field.'_'.$d.'_'.$sub_field_weight;
            add_post_meta($my_post_id, $sub_weight, $v['weight'], false);
            add_post_meta($my_post_id, '_'.$sub_weight, $sub_field_key_weight, false);

            $sub_color = $repeater_field.'_'.$d.'_'.$sub_field_color;
            add_post_meta($my_post_id, $sub_color, $v['color'], false);
            add_post_meta($my_post_id, '_'.$sub_color, $sub_field_key_color, false);

            $sub_quantity = $repeater_field.'_'.$d.'_'.$sub_field_quantity;
            add_post_meta($my_post_id, $sub_quantity, $v['count'], false);
            add_post_meta($my_post_id, '_'.$sub_quantity, $sub_field_key_quantity, false);

            $sub_price = $repeater_field.'_'.$d.'_'.$sub_field_price;
            add_post_meta($my_post_id, $sub_price, !empty($v['price']) ? number_format($v['price']):number_format($v['price']), false);
            add_post_meta($my_post_id, '_'.$sub_price, $sub_field_key_price, false);

            $price_total += (!empty($v['price']) ? get_number($v['price']):get_number($v['price'])) * $v['count'];
        }
    }
    add_post_meta($my_post_id, 'order_price_total', number_format($price_total).' đ', true);
    add_post_meta($my_post_id, 'order_code', $orderId, true);
    add_post_meta($my_post_id, 'order_client', $name, true);
    add_post_meta($my_post_id, 'order_phone', $phone, true);
    add_post_meta($my_post_id, 'order_email', $email, true);
    add_post_meta($my_post_id, 'order_address', $address, true);
    add_post_meta($my_post_id, 'order_payment', $payment, true);
    add_post_meta($my_post_id, 'order_product_price', number_format($price_total), true);


    $vnp_TmnCode = "CALISTA1"; //Mã website tại VNPAY
    $vnp_HashSecret = "WMCOLKEDKBUZYFOLFFDDCKEGBRXGEISN"; //Chuỗi bí mật
    $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = "http://theme.com/calista/payment";

    $vnp_TxnRef = $orderId; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
    $vnp_OrderInfo = 'Don hang '. $orderId;
    $vnp_OrderType = 'topup';
    $vnp_Amount = $price_total * 100;
    $vnp_Locale = 'vn';
    $vnp_BankCode = $_POST['bank_code'];
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    $inputData = array(
        "vnp_Version" => "2.0.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
    );

    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . $key . "=" . $value;
        } else {
            $hashdata .= $key . "=" . $value;
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
        $vnp_Url .= 'vnp_SecureHashType=MD5&vnp_SecureHash=' . $vnpSecureHash;
    }

    echo json_encode([
            'code' => 1,
            'mes' => 'Tạo đơn hàng thành công',
            'redirect' => $redirect,
            'urlRedirect' => $vnp_Url,
    ]);

    exit();
};

add_action( 'wp_ajax_nopriv_contact', 'contact' );
add_action( 'wp_ajax_contact', 'contact' );

function contact()
{
//    $vnp_tmnCode = get_field('vnp_tmncode', 'options');
//    echo '1';
//    print_r($vnp_tmnCode);die;

    $title = $_POST['title'];
    $content = $_POST['content'] . $title;

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $service = $_POST['service'];
    $content = $_POST['content'];
//    $address = $_POST['address'];

    $post = array(
        'post_title'	=> $name .' - '.$phone,
        'post_content'	=> '',
        'post_status'	=> 'publish',
        'post_type' 	=> 'contact'
    );
    $my_post_id = wp_insert_post($post);

    add_post_meta($my_post_id, 'contact_name', $name, true);
    add_post_meta($my_post_id, 'contact_phone', $phone, true);
    add_post_meta($my_post_id, 'contact_email', $email, true);
    add_post_meta($my_post_id, 'contact_service', $service, true);
    add_post_meta($my_post_id, 'contact_content', $content, true);
//    add_post_meta($my_post_id, 'contact_address', $address, true);

    echo json_encode([
        'code' => 1,
        'mes' => 'Thành công',
    ]);
    exit();
}

add_action( 'wp_ajax_nopriv_order_custom', 'order_custom' );
add_action( 'wp_ajax_order_custom', 'order_custom' );

function order_custom()
{
    $name = $_POST['name'];
    $birthday = $_POST['birthday'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $product = $_POST['product'];
    $price = $_POST['price'];
    $material = $_POST['material'];
    $color = $_POST['color'];
    $description = $_POST['description'];

    $post = array(
        'post_title'	=> $name. '/ '.$phone,
        'post_status'	=> 'publish',
        'post_type' 	=> 'order-other'
    );
    $my_post_id = wp_insert_post($post);

    add_post_meta($my_post_id, 'order_custom_name', $name, true);
    add_post_meta($my_post_id, 'order_custom_birthday', $birthday, true);
    add_post_meta($my_post_id, 'order_custom_phone', $phone, true);
    add_post_meta($my_post_id, 'order_custom_email', $email, true);
    add_post_meta($my_post_id, 'order_custom_address', $address, true);
    add_post_meta($my_post_id, 'order_custom_product', $product, true);
    add_post_meta($my_post_id, 'order_custom_price', $price, true);
    add_post_meta($my_post_id, 'order_custom_material', $material, true);
    add_post_meta($my_post_id, 'order_custom_color', $color, true);
    add_post_meta($my_post_id, 'order_custom_description', $description, true);

    echo json_encode([
        'code' => 1,
        'mes' => 'Thành công',
    ]);
    exit();
}


function get_number($string) {
    return (int)str_replace('.', '', $string);
}

//function san_pham_func() {
//    $labels = array(
//        'name'                  => _x( 'Sản phẩm', 'Post Type General Name', 'DWEB' ),
//        'singular_name'         => _x( 'Sản phẩm', 'Post Type Singular Name', 'DWEB' ),
//        'menu_name'             => __( 'Sản phẩm', 'DWEB' ),
//        'name_admin_bar'        => __( 'Sản phẩm', 'DWEB' ),
//        'archives'              => __( 'Item Archives', 'DWEB' ),
//        'attributes'            => __( 'Item Attributes', 'DWEB' ),
//        'parent_item_colon'     => __( 'Parent Item:', 'DWEB' ),
//        'all_items'             => __( 'Tất cả sản phẩm', 'DWEB' ),
//        'add_new_item'          => __( 'Add New Item', 'DWEB' ),
//        'add_new'               => __( 'Thêm sản phẩm', 'DWEB' ),
//        'new_item'              => __( 'New Item', 'DWEB' ),
//        'edit_item'             => __( 'Edit Item', 'DWEB' ),
//        'update_item'           => __( 'Update Item', 'DWEB' ),
//        'view_item'             => __( 'View Item', 'DWEB' ),
//        'view_items'            => __( 'View Items', 'DWEB' ),
//        'search_items'          => __( 'Search Item', 'DWEB' ),
//        'not_found'             => __( 'Not found', 'DWEB' ),
//        'not_found_in_trash'    => __( 'Not found in Trash', 'DWEB' ),
//        'featured_image'        => __( 'Featured Image', 'DWEB' ),
//        'set_featured_image'    => __( 'Set featured image', 'DWEB' ),
//        'remove_featured_image' => __( 'Remove featured image', 'DWEB' ),
//        'use_featured_image'    => __( 'Use as featured image', 'DWEB' ),
//        'uploaded_to_this_item' => __( 'Uploaded to this item', 'DWEB' ),
//        'items_list'            => __( 'Items list', 'DWEB' ),
//        'items_list_navigation' => __( 'Items list navigation', 'DWEB' ),
//        'filter_items_list'     => __( 'Filter items list', 'DWEB' ),
//    );
//    $rewrite = array(
//        'slug'                  => _x('product/%danhmuc_sanpham%','slug', 'DWEB'), //Slug của trang chi tiết sản phẩm
//        'with_front'            => true,
//        'pages'                 => true,
//        'feeds'                 => true,
//    );
//    $args = array(
//        'label'                 => __( 'Sản phẩm', 'DWEB' ),
//        'description'           => __( 'Post Type Description', 'DWEB' ),
//        'labels'                => $labels,
//        'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', ),
//        'taxonomies'            => array( 'danhmuc_sanpham' ),
//        'hierarchical'          => false,
//        'public'                => true,
//        'show_ui'               => true,
//        'show_in_menu'          => true,
//        'menu_position'         => 20,
//        'menu_icon'             => 'dashicons-products',
//        'show_in_admin_bar'     => true,
//        'show_in_nav_menus'     => true,
//        'can_export'            => true,
//        'has_archive'           => 'product', //Đường dẫn của archive
//        'exclude_from_search'   => false,
//        'publicly_queryable'    => true,
//        'rewrite'               => $rewrite,
//        'capability_type'       => 'page',
//    );
//    register_post_type( 'san_pham', $args );
//}
//add_action( 'init', 'san_pham_func', 0 );
//function danhmuc_sanpham_func() {
//    $labels = array(
//        'name'                       => _x( 'Danh mục', 'Taxonomy General Name', 'DWEB' ),
//        'singular_name'              => _x( 'Danh mục', 'Taxonomy Singular Name', 'DWEB' ),
//        'menu_name'                  => __( 'Danh mục', 'DWEB' ),
//        'all_items'                  => __( 'All Items', 'DWEB' ),
//        'parent_item'                => __( 'Parent Item', 'DWEB' ),
//        'parent_item_colon'          => __( 'Parent Item:', 'DWEB' ),
//        'new_item_name'              => __( 'New Item Name', 'DWEB' ),
//        'add_new_item'               => __( 'Add New Item', 'DWEB' ),
//        'edit_item'                  => __( 'Edit Item', 'DWEB' ),
//        'update_item'                => __( 'Update Item', 'DWEB' ),
//        'view_item'                  => __( 'View Item', 'DWEB' ),
//        'separate_items_with_commas' => __( 'Separate items with commas', 'DWEB' ),
//        'add_or_remove_items'        => __( 'Add or remove items', 'DWEB' ),
//        'choose_from_most_used'      => __( 'Choose from the most used', 'DWEB' ),
//        'popular_items'              => __( 'Popular Items', 'DWEB' ),
//        'search_items'               => __( 'Search Items', 'DWEB' ),
//        'not_found'                  => __( 'Not Found', 'DWEB' ),
//        'no_terms'                   => __( 'No items', 'DWEB' ),
//        'items_list'                 => __( 'Items list', 'DWEB' ),
//        'items_list_navigation'      => __( 'Items list navigation', 'DWEB' ),
//    );
//    $rewrite = array(
//        'slug'                       => _x('product','slug', 'DWEB'),
//        'with_front'                 => true,
//        'hierarchical'               => true,
//    );
//    $args = array(
//        'labels'                     => $labels,
//        'hierarchical'               => true,
//        'public'                     => true,
//        'show_ui'                    => true,
//        'show_admin_column'          => true,
//        'show_in_nav_menus'          => true,
//        'show_tagcloud'              => true,
//        'rewrite'                    => $rewrite,
//    );
//    register_taxonomy( 'danhmuc_sanpham', array( 'san_pham' ), $args );
//}
//add_action( 'init', 'danhmuc_sanpham_func', 0 );
//
//function thuonghieu_sanpham_func() {
//    $labels = array(
//        'name'                       => _x( 'Thương hiệu', 'Taxonomy General Name', 'DWEB' ),
//        'singular_name'              => _x( 'Thương hiệu', 'Taxonomy Singular Name', 'DWEB' ),
//        'menu_name'                  => __( 'Thương hiệu', 'DWEB' ),
//        'all_items'                  => __( 'All Items', 'DWEB' ),
//        'parent_item'                => __( 'Parent Item', 'DWEB' ),
//        'parent_item_colon'          => __( 'Parent Item:', 'DWEB' ),
//        'new_item_name'              => __( 'New Item Name', 'DWEB' ),
//        'add_new_item'               => __( 'Add New Item', 'DWEB' ),
//        'edit_item'                  => __( 'Edit Item', 'DWEB' ),
//        'update_item'                => __( 'Update Item', 'DWEB' ),
//        'view_item'                  => __( 'View Item', 'DWEB' ),
//        'separate_items_with_commas' => __( 'Separate items with commas', 'DWEB' ),
//        'add_or_remove_items'        => __( 'Add or remove items', 'DWEB' ),
//        'choose_from_most_used'      => __( 'Choose from the most used', 'DWEB' ),
//        'popular_items'              => __( 'Popular Items', 'DWEB' ),
//        'search_items'               => __( 'Search Items', 'DWEB' ),
//        'not_found'                  => __( 'Not Found', 'DWEB' ),
//        'no_terms'                   => __( 'No items', 'DWEB' ),
//        'items_list'                 => __( 'Items list', 'DWEB' ),
//        'items_list_navigation'      => __( 'Items list navigation', 'DWEB' ),
//    );
//    $rewrite = array(
//        'slug'                       => _x('product','slug', 'DWEB'),
//        'with_front'                 => true,
//        'hierarchical'               => true,
//    );
//    $args = array(
//        'labels'                     => $labels,
//        'hierarchical'               => true,
//        'public'                     => true,
//        'show_ui'                    => true,
//        'show_admin_column'          => true,
//        'show_in_nav_menus'          => true,
//        'show_tagcloud'              => true,
//        'rewrite'                    => $rewrite,
//    );
//    register_taxonomy( 'thuonghieu_sanpham', array( 'san_pham' ), $args );
//}
//add_action( 'init', 'thuonghieu_sanpham_func', 0 );
//
//
//function tag_sanpham_func() {
//    $labels = array(
//        'name'                       => _x( 'Thẻ', 'Taxonomy General Name', 'DWEB' ),
//        'singular_name'              => _x( 'Thẻ', 'Taxonomy Singular Name', 'DWEB' ),
//        'menu_name'                  => __( 'Thẻ', 'DWEB' ),
//        'all_items'                  => __( 'All Items', 'DWEB' ),
//        'parent_item'                => __( 'Parent Item', 'DWEB' ),
//        'parent_item_colon'          => __( 'Parent Item:', 'DWEB' ),
//        'new_item_name'              => __( 'New Item Name', 'DWEB' ),
//        'add_new_item'               => __( 'Add New Item', 'DWEB' ),
//        'edit_item'                  => __( 'Edit Item', 'DWEB' ),
//        'update_item'                => __( 'Update Item', 'DWEB' ),
//        'view_item'                  => __( 'View Item', 'DWEB' ),
//        'separate_items_with_commas' => __( 'Separate items with commas', 'DWEB' ),
//        'add_or_remove_items'        => __( 'Add or remove items', 'DWEB' ),
//        'choose_from_most_used'      => __( 'Choose from the most used', 'DWEB' ),
//        'popular_items'              => __( 'Popular Items', 'DWEB' ),
//        'search_items'               => __( 'Search Items', 'DWEB' ),
//        'not_found'                  => __( 'Not Found', 'DWEB' ),
//        'no_terms'                   => __( 'No items', 'DWEB' ),
//        'items_list'                 => __( 'Items list', 'DWEB' ),
//        'items_list_navigation'      => __( 'Items list navigation', 'DWEB' ),
//    );
//    $rewrite = array(
//        'slug'                       => _x('tags','slug', 'DWEB'),
//        'with_front'                 => true,
//        'hierarchical'               => true,
//    );
//    $args = array(
//        'labels'                     => $labels,
//        'hierarchical'               => false,
//        'public'                     => true,
//        'show_ui'                    => true,
//        'show_admin_column'          => true,
//        'show_in_nav_menus'          => true,
//        'show_tagcloud'              => true,
//        'rewrite'                    => $rewrite,
//    );
//    register_taxonomy( 'tag_sanpham', array( 'san_pham' ), $args );
//}
//add_action( 'init', 'tag_sanpham_func', 0 );
//
//
//function dweb_cpt_product_post_type_link( $permalink, $post ) {
//    if ( 'san_pham' !== $post->post_type ) {
//        return $permalink;
//    }
//    if ( false === strpos( $permalink, '%' ) ) {
//        return $permalink;
//    }
//    $terms = get_the_terms( $post->ID, 'danhmuc_sanpham' );
//
//    if ( ! empty( $terms ) ) {
//        if ( function_exists( 'wp_list_sort' ) ) {
//            $terms = wp_list_sort( $terms, 'term_id', 'DESC' );
//        } else {
//            usort( $terms, '_usort_terms_by_ID' );
//        }
//        $category_object = apply_filters( 'dweb_cpt_product_post_type_link_product_cat', $terms[0], $terms, $post );
//        $category_object = get_term( $category_object, 'danhmuc_sanpham' );
//        $product_cat     = $category_object->slug;
//        if ( $category_object->parent ) {
//            $ancestors = get_ancestors( $category_object->term_id, 'danhmuc_sanpham' );
////            print_r($ancestors);
//            foreach ( $ancestors as $ancestor ) {
//                $ancestor_object = get_term( $ancestor, 'danhmuc_sanpham' );
//                $product_cat     = $ancestor_object->slug . '/' . $product_cat;
//            }
//
//        }
//    } else {
//        $product_cat = _x( 'none-category', 'slug', 'devvn' );
//    }
//
//    $permalink = str_replace( '%danhmuc_sanpham%', $product_cat, $permalink );
//
//    return $permalink;
//}
//add_filter( 'post_type_link', 'dweb_cpt_product_post_type_link', 10, 2 );
//
//function dweb_cpt_product_category_base_same_shop_base( $flash = false ) {
//    $terms = get_terms(array(
//        'taxonomy' => 'danhmuc_sanpham',
//        'hide_empty' => false,
//    ));
//    if ($terms && !is_wp_error($terms)) {
//        $siteurl = esc_url(home_url('/'));
//        foreach ($terms as $term) {
//            $term_slug = $term->slug;
//            $baseterm = str_replace($siteurl, '', get_term_link($term->term_id, 'danhmuc_sanpham'));
//
//            add_rewrite_rule($baseterm . '?$','index.php?danhmuc_sanpham=' . $term_slug,'top');
//            add_rewrite_rule($baseterm . 'page/([0-9]{1,})/?$', 'index.php?danhmuc_sanpham=' . $term_slug . '&paged=$matches[1]','top');
//            add_rewrite_rule($baseterm . '(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?danhmuc_sanpham=' . $term_slug . '&feed=$matches[1]','top');
//        }
//    }
//    if ($flash == true)
//        flush_rewrite_rules(false);
//}
//add_filter( 'init', 'dweb_cpt_product_category_base_same_shop_base');
//
//function na_remove_slug( $post_link, $post, $leavename ) {
//
//    if ( 'san_pham' != $post->post_type ||  'publish' != $post->post_status ) {
//        return $post_link;
//    }
//
//    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
//    return $post_link;
//}
//add_filter( 'post_type_link', 'na_remove_slug', 10, 3 );
//
//function na_parse_request( $query ) {
//
//    if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
//        return;
//    }
//    if ( ! empty( $query->query['name'] ) ) {
//        $query->set( 'post_type', array( 'post', 'san_pham', 'page' ) );
//    }
//}
//add_action( 'pre_get_posts', 'na_parse_request' );
//
//
//function devvn_remove_slug( $post_link, $post ) {
//    if ( !in_array( get_post_type($post), array( 'san_pham' ) ) || 'publish' != $post->post_status ) {
//        return $post_link;
//    }
//    if('san_pham' == $post->post_type){
//        $post_link = str_replace( '/product/', '/', $post_link ); //Thay cua-hang bằng slug hiện tại của bạn
//    } else {
//        $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
//    }
//
//    return $post_link;
//}
//add_filter( 'post_type_link', 'devvn_remove_slug', 10, 2 );
//function devvn_woo_product_rewrite_rules($flash = false) {
//    global $wp_post_types, $wpdb, $wp;
//    $siteLink = esc_url(home_url('/'));
//    foreach ($wp_post_types as $type=>$custom_post) {
//        if($type == 'san_pham'){
//            if ($custom_post->_builtin == false) {
//                $querystr = "SELECT {$wpdb->posts}.post_name, {$wpdb->posts}.ID
//                            FROM {$wpdb->posts}
//                            WHERE {$wpdb->posts}.post_status = 'publish'
//                            AND {$wpdb->posts}.post_type = '{$type}'";
//                $posts = $wpdb->get_results($querystr, OBJECT);
//                foreach ($posts as $post) {
//                    $current_slug = get_permalink($post->ID);
//                    $base_product = str_replace($siteLink,'',$current_slug);
//
//
//                    $currentUrl = array_reverse(explode('/', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'/'));
//                    $baseUrl = array_reverse(explode('/', $base_product));
//
//                    add_rewrite_rule($base_product.'?$', "index.php?{$custom_post->query_var}={$post->post_name}", 'top');
//                    add_rewrite_rule($base_product.'comment-page-([0-9]{1,})/?$', 'index.php?'.$custom_post->query_var.'='.$post->post_name.'&cpage=$matches[1]', 'top');
//                    add_rewrite_rule($base_product.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?'.$custom_post->query_var.'='.$post->post_name.'&feed=$matches[1]','top');
//                }
//            }
//        }
//    }
//    if ($flash == true)
//        flush_rewrite_rules(false);
//}
//add_action('init', 'devvn_woo_product_rewrite_rules');
//function devvn_woo_new_product_post_save($post_id){
//    global $wp_post_types;
//    $post_type = get_post_type($post_id);
//    foreach ($wp_post_types as $type=>$custom_post) {
//        if ($custom_post->_builtin == false && $type == $post_type) {
//            devvn_woo_product_rewrite_rules(true);
//        }
//    }
//}
//add_action('wp_insert_post', 'devvn_woo_new_product_post_save');
//
//add_filter( 'term_link', 'devvn_product_cat_permalink', 10, 3 );
//function devvn_product_cat_permalink( $url, $term, $taxonomy ){
//    switch ($taxonomy):
//        case 'danhmuc_sanpham':
//            $taxonomy_slug = 'product'; //Thay bằng slug hiện tại của bạn. Mặc định là product-category
//            if(strpos($url, $taxonomy_slug) === FALSE) break;
//            $url = str_replace('/' . $taxonomy_slug, '', $url);
//            break;
//        case 'thuonghieu_sanpham':
//            $taxonomy_slug = 'product'; //Thay bằng slug hiện tại của bạn. Mặc định là product-category
//            if(strpos($url, $taxonomy_slug) === FALSE) break;
//            $url = str_replace('/' . $taxonomy_slug, '', $url);
//            break;
//    endswitch;
//    return $url;
//}
//function devvn_product_category_rewrite_rules($flash = false) {
//    $terms = get_terms( array(
//        'taxonomy' => 'danhmuc_sanpham',
//        'post_type' => 'san_pham',
//        'hide_empty' => false,
//    ));
//    if($terms && !is_wp_error($terms)){
//        $siteurl = esc_url(home_url('/'));
//        foreach ($terms as $term) {
//            $term_slug = $term->slug;
//            $baseterm = str_replace($siteurl,'',get_term_link($term->term_id,'danhmuc_sanpham'));
////            $baseterm = rtrim($baseterm, '/');
////            echo $baseterm;
////            echo '<br/>';
//
//            add_rewrite_rule($baseterm.'?$','index.php?danhmuc_sanpham='.$term_slug,'top');
//            add_rewrite_rule($baseterm.'page/([0-9]{1,})/?$', 'index.php?danhmuc_sanpham='.$term_slug.'&paged=$matches[1]','top');
//////            add_rewrite_rule($baseterm.'?price/([0-9a-z-]{1,})?$', 'index.php?danhmuc_sanpham='.$term_slug.'&price=$matches[1]','top');
//////            add_rewrite_rule($baseterm.'?color/([0-9-]{1,})?$', 'index.php?danhmuc_sanpham='.$term_slug.'&color=$matches[1]','top');
//////            add_rewrite_rule($baseterm.'?color/([0-9-]{1,})?$', 'index.php?danhmuc_sanpham='.$term_slug.'&color=$matches[1]','top');
//////            add_rewrite_rule($baseterm.'filter/?price=([0-9a-z-]{1,})&color=([0-9-]{1,})?$', 'index.php?danhmuc_sanpham='.$term_slug.'&price=$matches[1]&color=$matches[2]','top');
////            add_rewrite_rule($baseterm.'filter/([0-9a-z-]{1,})/page/([0-9]{1,})/?$', 'index.php?danhmuc_sanpham='.$term_slug.'&price=$matches[1]&paged=$matches[2]','top');
//            add_rewrite_rule($baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat='.$term_slug.'&feed=$matches[1]','top');
//
//        }
//    }
//    if ($flash == true)
//        flush_rewrite_rules(false);
//}
//add_action('init', 'devvn_product_category_rewrite_rules');
//add_action( 'create_term', 'devvn_new_product_cat_edit_success', 10, 2 );
//function devvn_new_product_cat_edit_success( $term_id, $taxonomy ) {
//    devvn_product_category_rewrite_rules(true);
//}
//function devvn_product_brand_rewrite_rules($flash = false) {
//
//    $terms = get_terms( array(
//        'taxonomy' => 'thuonghieu_sanpham',
//        'post_type' => 'san_pham',
//        'hide_empty' => false,
//    ));
//
//    if($terms && !is_wp_error($terms)){
//        $siteurl = esc_url(home_url('/'));
//        foreach ($terms as $term) {
//            $term_slug = $term->slug;
//            $baseterm = str_replace($siteurl,'',get_term_link($term->term_id,'thuonghieu_sanpham'));
//
//            add_rewrite_rule($baseterm.'?$','index.php?thuonghieu_sanpham='.$term_slug,'top');
//            add_rewrite_rule($baseterm.'page/([0-9]{1,})/?$', 'index.php?thuonghieu_sanpham='.$term_slug.'&paged=$matches[1]','top');
//            add_rewrite_rule($baseterm.'filter/([0-9a-z-]{1,})/([0-9]{1,})/?$', 'index.php?thuonghieu_sanpham='.$term_slug.'&price=$matches[1]','top');
//            add_rewrite_rule($baseterm.'filter/([0-9a-z-]{1,})/page/([0-9]{1,})/?$', 'index.php?thuonghieu_sanpham='.$term_slug.'&price=$matches[1]&paged=$matches[2]','top');
//            add_rewrite_rule($baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat='.$term_slug.'&feed=$matches[1]','top');
//        }
//    }
//
//    if ($flash == true)
//        flush_rewrite_rules(false);
//}
//add_action('init', 'devvn_product_brand_rewrite_rules');
//add_action( 'create_term', 'devvn_new_product_brand_edit_success', 10, 2 );
//function devvn_new_product_brand_edit_success( $term_id, $taxonomy ) {
//    devvn_product_brand_rewrite_rules(true);
//}
//
//
//function devvn_product_tag_rewrite_rules($flash = false) {
//    $terms = get_terms( array(
//        'taxonomy' => 'tag_sanpham',
//        'post_type' => 'san_pham',
//        'hide_empty' => false,
//    ));
//    if($terms && !is_wp_error($terms)){
//        $siteurl = esc_url(home_url('/'));
//        foreach ($terms as $term) {
//            $term_slug = $term->slug;
//            $baseterm = str_replace($siteurl,'',get_term_link($term->term_id,'tag_sanpham'));
//            add_rewrite_rule($baseterm.'?$','index.php?tag_sanpham='.$term_slug,'top');
//            add_rewrite_rule($baseterm.'page/([0-9]{1,})/?$', 'index.php?tag_sanpham='.$term_slug.'&paged=$matches[1]','top');
//            add_rewrite_rule($baseterm.'([0-9a-z-]{1,})?$', 'index.php?tag_sanpham='.$term_slug.'&price=$matches[1]','top');
//            add_rewrite_rule($baseterm.'([0-9a-z-]{1,})/page/([0-9]{1,})/?$', 'index.php?tag_sanpham='.$term_slug.'&price=$matches[1]&paged=$matches[2]','top');
//            add_rewrite_rule($baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat='.$term_slug.'&feed=$matches[1]','top');
//        }
//    }
//    if ($flash == true)
//        flush_rewrite_rules(false);
//}
//add_action('init', 'devvn_product_tag_rewrite_rules');
//add_action( 'create_term', 'devvn_new_product_tag_edit_success', 10, 2 );
//function devvn_new_product_tag_edit_success( $term_id, $taxonomy ) {
//    devvn_product_tag_rewrite_rules(true);
//}
//
//function add_slug( $query_var ) {
//    $query_var[] = 'paged';
//    $query_var[] = 'price';
//    $query_var[] = 'size';
//    $query_var[] = 'color';
//    $query_var[] = 'sort';
//    $query_var[] = 'material';
//    $query_var[] = 'gift';
//    return $query_var;
//}
//add_filter( 'query_vars', 'add_slug' );
//
//add_action( 'pre_get_posts', 'price_search_filter');
//
//function price_search_filter( $query ) {
//    if ((!is_admin() && is_tax('danhmuc_sanpham') && $query->is_main_query()) || (!is_admin() && is_tax('thuonghieu_sanpham') && $query->is_main_query()) ) {
//
//        $price = get_query_var('price');
//        $sort = get_query_var('sort');
//        $size = get_query_var('size');
//        $material = get_query_var('material');
//        $color = get_query_var('color');
//        $gift = get_query_var('gift');
//
//        $priceArray = explode('-',$price);
//        $count = count($priceArray);
//
//        $sortArray = explode('-',$sort);
//        $countSort = count($sortArray);
//
//        if($gift) {
//            $meta_query[] = array(
//                'key' => 'product_gift',
//                'value' => $gift,
//                'compare' => 'LIKE',
//            );
//            $query->set('meta_query', $meta_query);
//        }
//
//        if($color) {
//            $meta_query[] = array(
//                'key' => 'product_color',
//                'value' => $color,
//                'compare' => 'LIKE',
//            );
//            $query->set('meta_query', $meta_query);
//        }
//
//        if($size) {
//            $meta_query[] = array(
//                'key' => 'product_size',
//                'value' => $size,
//                'compare' => 'LIKE',
//            );
//            $query->set('meta_query', $meta_query);
//        }
//
//        if($material) {
//            $meta_query[] = array(
//                'key' => 'product_material',
//                'value' => $material,
//                'compare' => 'LIKE',
//            );
//            $query->set('meta_query', $meta_query);
//        }
//
//        if($count == 1 && (int)$priceArray[0]) {
//            $meta_query[] = array(
//                'key' => 'post_detail_price',
//                'value' => (int)$priceArray[0],
//                'compare' => '>=',
//                'type'      => 'NUMERIC',
//            );
//            $query->set('meta_query', $meta_query);
//        }
//        if($countSort == 1) {
//            if($sortArray[0] == 'minprice') {
//                $query->set('meta_key', 'post_detail_price');
//                $query->set('orderby', 'meta_value_num');
//                $query->set('order', 'ASC');
//            } elseif($sortArray[0] == 'maxprice') {
//                $query->set('meta_key', 'post_detail_price');
//                $query->set('orderby', 'meta_value_num');
//                $query->set('order', 'DESC');
//            }
//            if($sortArray[0] == 'az') {
//                $query->set('orderby', 'title');
//                $query->set('order', 'ASC');
//            } elseif($sortArray[0] == 'za') {
//                $query->set('orderby', 'title');
//                $query->set('order', 'DESC');
//            }
//        }
//        if($priceArray[0] == '0') {
//            $meta_query[] = array(
//                'key' => 'post_detail_price',
//                'value' => $priceArray[1],
//                'compare' => '<=',
//                'type'      => 'NUMERIC',
//            );
//            $query->set('meta_query', $meta_query);
//        } else {
//            $meta_query[] = array(
//                'key' => 'post_detail_price',
//                'value' => $priceArray,
//                'compare' => 'BETWEEN',
//                'type'      => 'NUMERIC',
//            );
//            $query->set('meta_query', $meta_query);
//        }
////        print_r($query);
//    }
//}
//
//function arrayExclude($array, Array $excludeKeys){
//    foreach($array as $key => $value){
//        if(!in_array($key, $excludeKeys)){
//            $return[$key] = $value;
//        }
//    }
//    return $return;
//}
//
//function valueQuery($v = null) {
//    $queryGlobal = $GLOBALS['wp_query']->query;
//    $queryGlobals = arrayExclude($queryGlobal, array('paged', 'danhmuc_sanpham'));
//
//    $i = 0;
//    $url = count($queryGlobals) > 0 ? '':'?';
//    if(!empty($queryGlobals)) {
//        foreach($queryGlobals as $kq => $vq):
//            $i++;
//            $i == 1 ? $kk = '?':$kk = '';
//            if($kq != $v) {
//                $url .= $kk.$kq.'='.$vq.'&';
//            } else {
//                $url .= $kk;
//            }
//        endforeach;
//    }
//    return $url;
//}
//
//function removeQuery($v = null) {
//    $queryGlobal = $GLOBALS['wp_query']->query;
//    $queryGlobals = arrayExclude($queryGlobal, array('paged', 'danhmuc_sanpham'));
//    $i = 0;
//    $url = count($queryGlobals) > 0 ? '':'';
//    if(!empty($queryGlobals)) {
//        foreach($queryGlobals as $kq => $vq):
//            $i++;
//            $i == 1 ? $kk = '?':$kk = '&';
//            if($kq != $v) {
//                $url .= $kk.$kq.'='.$vq;
//            } else {
//                $url .= $kk;
//            }
//        endforeach;
//    }
//    return $url;
//}
//
//function hm_get_template_part( $file, $template_args = array(), $cache_args = array() ) {
//    $template_args = wp_parse_args( $template_args );
//    $cache_args = wp_parse_args( $cache_args );
//    if ( $cache_args ) {
//        foreach ( $template_args as $key => $value ) {
//            if ( is_scalar( $value ) || is_array( $value ) ) {
//                $cache_args[$key] = $value;
//            } else if ( is_object( $value ) && method_exists( $value, 'get_id' ) ) {
//                $cache_args[$key] = call_user_method( 'get_id', $value );
//            }
//        }
//        if ( ( $cache = wp_cache_get( $file, serialize( $cache_args ) ) ) !== false ) {
//            if ( ! empty( $template_args['return'] ) )
//                return $cache;
//            echo $cache;
//            return;
//        }
//    }
//    $file_handle = $file;
//    do_action( 'start_operation', 'hm_template_part::' . $file_handle );
//    if ( file_exists( get_stylesheet_directory() . '/' . $file . '.php' ) )
//        $file = get_stylesheet_directory() . '/' . $file . '.php';
//    elseif ( file_exists( get_template_directory() . '/' . $file . '.php' ) )
//        $file = get_template_directory() . '/' . $file . '.php';
//    ob_start();
//    $return = require( $file );
//    $data = ob_get_clean();
//    do_action( 'end_operation', 'hm_template_part::' . $file_handle );
//    if ( $cache_args ) {
//        wp_cache_set( $file, $data, serialize( $cache_args ), 3600 );
//    }
//    if ( ! empty( $template_args['return'] ) )
//        if ( $return === false )
//            return false;
//        else
//            return $data;
//    echo $data;
//}
//
//
//add_action( 'wp_ajax_nopriv_filter_taxonomy', 'filter_taxonomy' );
//add_action( 'wp_ajax_filter_taxonomy', 'filter_taxonomy' );
//
//function filter_taxonomy()
//{
//    $material = [
//        'pt' => 'PT',
//        'k18' => 'K18',
//        'k14' => 'K14',
//        'k10' => 'K10',
//        'sv' => 'SV',
//    ];
//
//    $price = [
//        '0-1000000' => 'Dưới 1tr',
//        '1000000-2000000' => '1tr-2tr',
//        '2000000-3000000' => '2tr-3tr',
//        '3000000-4000000' => '3tr-4tr',
//        '5000000' => 'Trên 5tr',
//    ];
//
//    $color = [
//        'ffe84f' => 'Vàng vàng',
//        'f7f1d7' => 'Vàng trắng',
//        'ffead8' => 'Vàng hồng',
//        '' => 'Kết hợp màu',
//    ];
//
//    $gift = [
//        'sinh-nhat' => 'Sinh nhật',
//        'le-tinh-nhan' => 'Lễ tình nhân',
//        'ki-niem' => 'Kỉ niệm',
//        'tot-nghiep' => 'Tốt nghiệp',
//        'ngay-phu-nu' => 'Ngày phụ nữ',
//        'du-tiec' => 'Dự tiệc',
//        'cong-so' => 'Công sở',
//        'tu-thuong' => 'Tự thưởng',
//    ];
//
//    $sort = [
//        'az' => 'A -> Z',
//        'za' => 'Z -> A',
//        'minprice' => 'Giá từ thấp tới cao',
//        'maxprice' => 'Giá từ cao tới thấp',
//    ];
//
//    $url = $_POST['url'];
//    $slug = $_POST['slug'];
//
//    $url = ltrim($url, '?');
//    $url = explode('&', $url);
//    if(!empty($url)) {
//
//        $material_query = [];
//        $price_query = [];
//        $color_query = [];
//        $gift_query = [];
//        $sort_query = [];
//
//        foreach ($url as $u) {
//            $url2 = explode('=', $u);
//
//            if($url2[0] == 'material') {
//                if(isset($material[$url2[1]])) {
//                    $material_query = array(
//                        'key' => 'product_material',
//                        'value' => $url2[1],
//                        'compare' => 'LIKE',
//                    );
//                }
//            }
//
//            if($url2[0] == 'price') {
//                $priceArray = explode('-',$url2[1]);
//                $count = count($priceArray);
//                if(isset($price[$url2[1]])) {
//                    if($count == 1 && (int)$priceArray[0]) {
//                        $price_query = array(
//                            'key' => 'post_detail_price',
//                            'value' => (int)$priceArray[0],
//                            'compare' => '>=',
//                            'type'      => 'NUMERIC',
//                        );
//                    } elseif ($priceArray[0] == '0') {
//                        $price_query = array(
//                            'key' => 'post_detail_price',
//                            'value' => $priceArray[1],
//                            'compare' => '<=',
//                            'type'      => 'NUMERIC',
//                        );
//                    } else {
//                        $price_query = array (
//                            'key' => 'post_detail_price',
//                            'value' => $priceArray,
//                            'compare' => 'BETWEEN',
//                            'type'      => 'NUMERIC',
//                        );
//                    }
//
//                }
//            }
//            if($url2[0] == 'gift') {
//                $gift_query = array(
//                    'key' => 'product_gift',
//                    'value' => $url2[1],
//                    'compare' => 'LIKE',
//                );
//            }
//            if($url2[0] == 'sort') {
//                $sortArray = explode('-',$url2[1]);
//                $countSort = count($sortArray);
//
////                if($countSort == 1) {
////                    if($sortArray[0] == 'minprice') {
////                        $query->set('meta_key', 'post_detail_price');
////                        $query->set('orderby', 'meta_value_num');
////                        $query->set('order', 'ASC');
////                    } elseif($sortArray[0] == 'maxprice') {
////                        $query->set('meta_key', 'post_detail_price');
////                        $query->set('orderby', 'meta_value_num');
////                        $query->set('order', 'DESC');
////                    }
////                    if($sortArray[0] == 'az') {
////                        $query->set('orderby', 'title');
////                        $query->set('order', 'ASC');
////                    } elseif($sortArray[0] == 'za') {
////                        $query->set('orderby', 'title');
////                        $query->set('order', 'DESC');
////                    }
////                }
//            }
//            if($url2[0] == 'color') {
//                $color_query = array(
//                    'key' => 'product_color',
//                    'value' => $url2[1],
//                    'compare' => 'LIKE',
//                );
//            }
//        }
//
//        $rd_args = array (
//            'post_type' => 'san_pham',
//            'tax_query' => array(
//                array(
//                    'taxonomy' => 'danhmuc_sanpham',
//                    'field'    => 'slug',
//                    'terms'    => $slug,
//                ),
//            ),
//            'meta_query' => array (
//                'relation' => 'AND',
//                $material_query,
//                $price_query,
//                $gift_query,
////                $color_query
//            )
//        );
//
//
//        $term = get_term_by('slug', $slug, 'danhmuc_sanpham');
//        hm_get_template_part('template-product-filter', ['query_filter' => $rd_args, 'term' => $term]);
//    }
//
//    exit();
//};
//
//add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
//function tsm_filter_post_type_by_taxonomy() {
//    global $typenow;
//    $post_type = 'san_pham'; // change to your post type
//    $taxonomy  = 'danhmuc_sanpham'; // change to your taxonomy
//    if ($typenow == $post_type) {
//        $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
//        $info_taxonomy = get_taxonomy($taxonomy);
//        wp_dropdown_categories(array(
//            'show_option_all' => __("Show All {$info_taxonomy->label}"),
//            'taxonomy'        => $taxonomy,
//            'name'            => $taxonomy,
//            'orderby'         => 'name',
//            'selected'        => $selected,
//            'show_count'      => true,
//            'hide_empty'      => true,
//        ));
//    };
//}
//
//add_filter('parse_query', 'tsm_convert_id_to_term_in_query');
//function tsm_convert_id_to_term_in_query($query) {
//    global $pagenow;
//    $post_type = 'san_pham'; // change to your post type
//    $taxonomy  = 'danhmuc_sanpham'; // change to your taxonomy
//    $q_vars    = &$query->query_vars;
//    if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
//        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
//        $q_vars[$taxonomy] = $term->slug;
//    }
//}


//function fix_slash( $string, $type ) {
//    global $wp_rewrite;
//    if ( $wp_rewrite->use_trailing_slashes == false ) {
//        if ( $type != 'single' && $type != 'category' )
//            return trailingslashit( $string );
//
//        if ( $type == 'single' && ( strpos( $string, '.html/' ) !== false ) )
//            return trailingslashit( $string );
//
//        if ( $type == 'category' && ( strpos( $string, 'category' ) !== false ) ){
//            $aa_g = str_replace( "/category/", "/", $string );
//            return trailingslashit( $aa_g );
//        }
//        if ( $type == 'category' )
//            return trailingslashit( $string );
//    }
//    return $string;
//}
//
//add_filter( 'user_trailingslashit', 'fix_slash', 55, 2 );

?>
