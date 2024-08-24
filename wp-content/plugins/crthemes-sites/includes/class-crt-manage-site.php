<?php
defined('ABSPATH') or die('Sorry guys!');
/**
 * @class CRT_Manage_SITE_Base
 */
class CRT_Manage_SITE_Base {
    public static $_instance = '';

    public function __construct() {
        if (!class_exists('WP_List_Table')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }
        $this->includes();
        register_activation_hook( CRTHEMES_MANAGEMENT_SITE_PLUGIN_FILE, array($this, 'loaded_active') );
        add_action("booknow_booknow-appointments_add_new",array($this,"form_main"));
        add_action('admin_menu', array( $this, 'my_add_menu_items'));
    }

    public static function instance() {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function includes() {
        require_once dirname( __FILE__, 2 ) . '/includes/class-crt-manage-site-list-table.php';
        require_once dirname( __FILE__, 2 ) . '/includes/list_table.php';
        require_once dirname( __FILE__, 2 ) . '/includes/edit_post.php';
    }

    public function loaded_active()
    {
        echo '123';
    }

    public function my_add_menu_items()
    {
        add_menu_page(
            'CRT Domains',
            'CRT Domains',
            'activate_plugins',
            'crthemes_management_domain',
            array( $this, 'crtheme_management_list_init' ),
        );
    }

    public function form_main()
    {
        //preview
        if (isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"]) && $_GET["id"] > 0 && isset($_GET['type']) && $_GET['type'] == "view") {
            echo 'view';
        } else {
            echo 'add new';
        }

    }

    public function crtheme_management_list_init()
    {
        // Creating an instance
        $table = new CRThemes_Manage_Site_List_Table();

        echo '<div class="wrap"><h2>Domains<a class="add-new-h2" href="?page=' . $_REQUEST['page'] . '&action=add">Add New</a></h2>';
        // Prepare table
        $table->prepare_items();
        // Display table
        $table->display();
        echo '</div>';
    }
}