<?php
defined('ABSPATH') or die('Sorry guys!');
/**
 * @class CRT_Manage_Site_Base
 */
class CRT_Manage_Site_Base {
    public static $_instance = '';

    public function __construct() {
        $this->includes();
        global $table_crtheme_manage_sites;
        $table_crtheme_manage_sites = new CRT_DB();

        add_action('admin_menu', array($this, 'crt_manage_admin_menu'));
        add_action('init', array($this, 'crt_manage_languages'));
    }

    public static function instance() {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function includes() {
        if (!class_exists('WP_List_Table')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }
        require_once dirname( __FILE__, 2 ) . '/inc/class-crt-manage-site-db.php';
        require_once dirname( __FILE__, 2 ) . '/inc/class-crt-manage-site-custom-wp-list.php';
        require_once dirname( __FILE__, 2 ) . '/inc/class-crt-manage-site-register.php';
    }

    public function crt_manage_admin_menu()
    {
        add_menu_page(__('CRThemes', 'crt_manage'), __('CRThemes', 'crt_manage'), 'activate_plugins', 'crthemes', array($this, 'crt_manage_persons_page_handler'));
        add_submenu_page('crthemes', __('Add new', 'crt_manage'), __('Add new', 'crt_manage'), 'activate_plugins', 'domains_form', array($this, 'crt_manage_persons_form_page_handler'));
    }

    public function crt_manage_persons_page_handler()
    {
        global $wpdb;

        $table = new Custom_Table_Example_List_Table();
        $table->prepare_items();

        $message = '';
        if ('delete' === $table->current_action()) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'crt_manage'), count($_REQUEST['id'])) . '</p></div>';
        }
        ?>
        <div class="wrap">

            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php _e('Domain', 'crt_manage')?> <a class="add-new-h2"
                                                      href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=domains_form');?>"><?php _e('Add new', 'crt_manage')?></a>
            </h2>
            <?php echo $message; ?>

            <form id="persons-table" method="GET">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                <?php $table->display() ?>
            </form>

        </div>
        <?php
    }

    public function crt_manage_persons_form_page_handler()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'crtheme_manage_sites'; // do not forget about tables prefix

        $message = '';
        $notice = '';

        // this is default $item which will be used for new records
        $default = array(
            'id' => 0,
            'name' => '',
            'email' => '',
            'age' => null,
        );

        // here we are verifying does this request is post back and have correct nonce
        if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
            // combine our default item with request params
            $item = shortcode_atts($default, $_REQUEST);
            // validate data, and if all ok save item to database
            // if id is zero insert otherwise update
            $item_valid = $this->crt_manage_validate_person($item);
            if ($item_valid === true) {
                if ($item['id'] == 0) {
                    $result = $wpdb->insert($table_name, $item);
                    $item['id'] = $wpdb->insert_id;
                    if ($result) {
                        $message = __('Item was successfully saved', 'crt_manage');
                    } else {
                        $notice = __('There was an error while saving item', 'crt_manage');
                    }
                } else {
                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                    if ($result) {
                        $message = __('Item was successfully updated', 'crt_manage');
                    } else {
                        $notice = __('There was an error while updating item', 'crt_manage');
                    }
                }
            } else {
                // if $item_valid not true it contains error message(s)
                $notice = $item_valid;
            }
        }
        else {
            // if this is not post back we load item to edit or give new one to create
            $item = $default;
            if (isset($_REQUEST['id'])) {
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
                if (!$item) {
                    $item = $default;
                    $notice = __('Item not found', 'crt_manage');
                }
            }
        }

        // here we adding our custom meta box
        add_meta_box('persons_form_meta_box', 'Domain detail', array($this, 'crt_manage_persons_form_meta_box_handler'), 'domain', 'normal', 'default');

        ?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php _e('Domain', 'crt_manage')?> <a class="add-new-h2"
                                                      href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=domains');?>"><?php _e('back to list', 'crt_manage')?></a>
            </h2>

            <?php if (!empty($notice)): ?>
                <div id="notice" class="error"><p><?php echo $notice ?></p></div>
            <?php endif;?>
            <?php if (!empty($message)): ?>
                <div id="message" class="updated"><p><?php echo $message ?></p></div>
            <?php endif;?>

            <form id="form" method="POST">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
                <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
                <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

                <div class="metabox-holder" id="poststuff">
                    <div id="post-body">
                        <div id="post-body-content">
                            <?php /* And here we call our custom meta box */ ?>
                            <?php do_meta_boxes('domain', 'normal', $item); ?>
                            <input type="submit" value="<?php _e('Save', 'crt_manage')?>" id="submit" class="button-primary" name="submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    public function crt_manage_persons_form_meta_box_handler($item)
    {
        ?>

        <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
            <tbody>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="name"><?php _e('Name', 'crt_manage')?></label>
                </th>
                <td>
                    <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
                           size="50" class="code" placeholder="<?php _e('Your name', 'crt_manage')?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="email"><?php _e('E-Mail', 'crt_manage')?></label>
                </th>
                <td>
                    <input id="email" name="email" type="email" style="width: 95%" value="<?php echo esc_attr($item['email'])?>"
                           size="50" class="code" placeholder="<?php _e('Your E-Mail', 'crt_manage')?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="age"><?php _e('Age', 'crt_manage')?></label>
                </th>
                <td>
                    <input id="age" name="age" type="number" style="width: 95%" value="<?php echo esc_attr($item['age'])?>"
                           size="50" class="code" placeholder="<?php _e('Your age', 'crt_manage')?>" required>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }

    public function crt_manage_validate_person($item)
    {
        $messages = array();

        if (empty($item['name'])) $messages[] = __('Name is required', 'crt_manage');
        if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'crt_manage');
        if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'crt_manage');
        //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
        //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
        //...

        if (empty($messages)) return true;
        return implode('<br />', $messages);
    }

    public function crt_manage_languages()
    {
        load_plugin_textdomain('crt_manage', false, dirname(plugin_basename(__FILE__)));
    }

}