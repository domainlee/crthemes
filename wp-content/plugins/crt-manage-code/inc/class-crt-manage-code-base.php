<?php
defined('ABSPATH') or die('Sorry guys!');
/**
 * @class CRT_Manage_Code_Base
 */
class CRT_Manage_Code_Base {
    public static $_instance = '';

    public function __construct() {
        if ( ! defined( 'CRTHEMES_PRODUCT_ENV' ) ) {
            define( 'CRTHEMES_PRODUCT_ENV', 'production' ); // dev or production
        }

        $this->includes();
        global $table_crtheme_manage_codes;
        $table_crtheme_manage_codes = new CRT_DB_CODE();

        add_action('admin_menu', array($this, 'crt_manage_admin_menu'));
        add_action('init', array($this, 'crt_manage_languages'));

        add_action( 'woocommerce_order_status_completed', array( $this , 'crt_manage_code_create_code_after_payment'));
        add_action( 'woocommerce_order_status_processing', array( $this , 'crt_manage_code_create_code_after_payment'));

        add_action( 'crt_manage_woocommerce_thankyou', array( $this , 'add_purchase_code_to_summary' ) );
        add_filter( 'woocommerce_get_order_item_totals', array( $this, 'insert_custom_line_order_item_totals' ), 10, 3 );
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
        require_once dirname( __FILE__, 2 ) . '/inc/class-crt-manage-code-db.php';
        require_once dirname( __FILE__, 2 ) . '/inc/class-crt-manage-code-custom-wp-list.php';
    }

    public function crt_manage_admin_menu()
    {
        add_menu_page(__('CRThemes Code', 'crt_manage_code'), __('CRThemes Code', 'crt_manage_code'), 'activate_plugins', 'crthemes-code', array($this, 'crt_manage_code_page_handler'));
        add_submenu_page('crthemes-code', __('Add new', 'crt_manage_code'), __('Add new', 'crt_manage_code'), 'activate_plugins', 'code_form', array($this, 'crt_manage_code_form_page_handler'));
    }

    public function crt_manage_code_page_handler()
    {

        global $wpdb;

        $table = new Custom_Table_Code_List_Table();
        $table->prepare_items();

        $message = '';
        if ('delete' === $table->current_action()) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'crt_manage_code'), $_REQUEST['id']) . '</p></div>';
        }
        ?>
        <div class="wrap">

            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php _e('Code', 'crt_manage_code')?> <a class="add-new-h2"
                                                      href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=code_form');?>"><?php _e('Add new', 'crt_manage_code')?></a>
            </h2>
            <?php echo $message; ?>

            <form id="persons-table" method="GET">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                <?php $table->display() ?>
            </form>

        </div>
        <?php
    }

    public function crt_manage_code_form_page_handler()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'crtheme_manage_codes'; // do not forget about tables prefix

        $message = '';
        $notice = '';

        // this is default $item which will be used for new records
        $default = array(
            'id' => 0,
            'name' => '',
            'email' => '',
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
                        $message = __('Item was successfully saved', 'crt_manage_code');
                    } else {
                        $notice = __('There was an error while saving item', 'crt_manage_code');
                    }
                } else {
                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                    if ($result) {
                        $message = __('Item was successfully updated', 'crt_manage_code');
                    } else {
                        $notice = __('There was an error while updating item', 'crt_manage_code');
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
                    $notice = __('Item not found', 'crt_manage_code');
                }
            }
        }

        // here we adding our custom meta box
        add_meta_box('persons_form_meta_box', 'Code detail', array($this, 'crt_manage_persons_form_meta_box_handler'), 'domain', 'normal', 'default');

        ?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php _e('Code', 'crt_manage_code')?> <a class="add-new-h2"
                                                      href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=themes-code');?>"><?php _e('back to list', 'crt_manage_code')?></a>
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
                            <input type="submit" value="<?php _e('Save', 'crt_manage_code')?>" id="submit" class="button-primary" name="submit">
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
                    <label for="name"><?php _e('Name', 'crt_manage_code')?></label>
                </th>
                <td>
                    <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
                           size="50" class="code" placeholder="<?php _e('Your name', 'crt_manage_code')?>" required>
                </td>
            </tr>
            <tr class="form-field">
                <th valign="top" scope="row">
                    <label for="email"><?php _e('E-Mail', 'crt_manage_code')?></label>
                </th>
                <td>
                    <input id="email" name="email" type="email" style="width: 95%" value="<?php echo esc_attr($item['email'])?>"
                           size="50" class="code" placeholder="<?php _e('Your E-Mail', 'crt_manage_code')?>" required>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }

    public function crt_manage_validate_person($item)
    {
        $messages = array();
        global $wpdb;
        $table_name = $wpdb->prefix . 'crtheme_manage_codes'; // do not forget about tables prefix
        $item_result = $wpdb->get_row($wpdb->prepare("SELECT id, name FROM " . $table_name . " WHERE name = '".$item['name']."' ORDER BY id ASC"), 'ARRAY_A');

        if (empty($item['name'])) $messages[] = __('Name is required', 'crt_manage_code');
        if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'crt_manage_code');
        if(!empty($item_result) && $item_result['id'] != $item['id']) $messages[] = __('Domain is exist', 'crt_manage_code');

        if (empty($messages)) return true;
        return implode('<br />', $messages);
    }

    public function crt_manage_languages()
    {
        load_plugin_textdomain('crt_manage_code', false, dirname(plugin_basename(__FILE__)));
    }

    public function crt_manage_code_create_code_after_payment( $order_id) {
        $order = wc_get_order( $order_id );
        $order_email = $order->get_billing_email();
        $items = $order->get_items();
        foreach ( $items as $item ) {
            $product_id = $item->get_variation_id();

            if($product_id) {
                $product = wc_get_product($product_id);
                $sku = $product->get_sku();
                $date = date("Y-m-d");
                $code = md5($sku . $order_email . $date);

                global $table_crtheme_manage_codes;
                $data = array(
                    'code' => $code,
                    'name_service' => $sku,
                    'date' => date("Y-m-d"),
                    'order_id' => $order_id,
                    'status' => $table_crtheme_manage_codes::STATUS_INACTIVE,
                );
                $table_crtheme_manage_codes->create($data);
            }

            $theme_virtual = get_field('theme_virtual', $item->get_product_id());
            if($theme_virtual) {
                $product_id = $item->get_product_id();
                $product = wc_get_product($product_id);
                $sku = $product->get_sku();
                $sku_str_int = $this->crt_manage_code_random_string(mb_strlen($sku));
                $code = base64_encode($sku_str_int . '_' . $sku);
                global $table_crtheme_manage_codes;
                $data = array(
                    'code' => $code,
                    'name_service' => $sku,
                    'date' => date("Y-m-d"),
                    'order_id' => $order_id,
                    'status' => $table_crtheme_manage_codes::STATUS_INACTIVE,
                );
                $table_crtheme_manage_codes->create($data);
            }
        }
    }

    public function add_purchase_code_to_summary($order_id){
        global $table_crtheme_manage_codes;
        $code = $table_crtheme_manage_codes->get_code_order_id($order_id);
        if($code['code']):
        ?>
        <li class="woocommerce-order-overview__order order">
            <?php esc_html_e( 'License Code: ', 'woocommerce' ); ?><strong><?php echo $code['code'];  ?></strong>
        </li>
        <?php
        endif;
    }

    public function insert_custom_line_order_item_totals( $total_rows, $order, $tax_display ){
        if( is_wc_endpoint_url() ) return $total_rows;

        global $table_crtheme_manage_codes;
        $code = $table_crtheme_manage_codes->get_code_order_id($order->get_id());
        $code_label = 'License Code';
        if( empty($code['code']) ) return $total_rows;
        $new_total_rows  = array();
        foreach( $total_rows as $key => $value ){
            if( ! empty($code['code']) ) {
                $new_total_rows['crt_manage_email_code'] = array(
                    'label' => $code_label,
                    'value' => $code['code'],
                );
            }
            $new_total_rows[$key] = $total_rows[$key];
        }

        return sizeof($new_total_rows) > 0 ? $new_total_rows : $total_rows;
    }

    public function crt_manage_code_random_string($str_int = 10) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*';
        $strings = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $str_int; $i++) {
            $n = rand(0, $alphaLength);
            $strings[] = $alphabet[$n];
        }
        return implode($strings);
    }



}