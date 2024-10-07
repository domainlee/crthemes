<?php
/**
 * Plugin Name: CRThemes Management Code
 * Plugin URI: https://crthemes.com
 * Description: CRThemes managerment Code
 * Author: Domainlee
 * Version: 0.0.1
 * Domain Path: /languages/
 * Text Domain: crthemes-management-code
 * Author URI: https://crthemes.com
 */
global $crt_manage_code_db_version;
$crt_manage_code_db_version = '1.1'; // version changed from 1.0 to 1.1
define( 'CRTheme_Manage_Site_Code_PLUGIN_FILE', __FILE__);
require_once 'inc/class-crt-manage-code-base.php';
add_action('plugins_loaded', array('CRT_Manage_Code_Base', 'instance'));

add_action('plugins_loaded', 'crt_manage_code_update_db_check');
register_activation_hook(CRTheme_Manage_Site_Code_PLUGIN_FILE, 'crt_manage_code_install');
register_activation_hook(CRTheme_Manage_Site_Code_PLUGIN_FILE, 'crt_manage_code_install_data');

function crt_manage_code_install()
{
    global $wpdb;
    global $crt_manage_code_db_version;

    $table_name = $wpdb->prefix . 'crtheme_manage_codes'; // do not forget about tables prefix
    $sql = "CREATE TABLE " . $table_name . " (
              id int(11) NOT NULL AUTO_INCREMENT,
              code VARCHAR(300) NOT NULL,
              order_id VARCHAR(300) NOT NULL,
              name_service VARCHAR(300) NOT NULL,
              date date NOT NULL,
              active_date date NULL,
              status int(3) NOT NULL,
              PRIMARY KEY  (id)
            );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // save current database version for later use (on upgrade)
    add_option('crt_manage_code_db_version', $crt_manage_code_db_version);

    $installed_ver = get_option('crt_manage_code_db_version');
    if ($installed_ver != $crt_manage_code_db_version) {
        $sql = "CREATE TABLE " . $table_name . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          code VARCHAR(300) NOT NULL,
          order_id VARCHAR(300) NOT NULL,
          name_service VARCHAR(300) NOT NULL,
          date date NOT NULL,
          active_date date NULL,
          status int(3) NOT NULL,
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('crt_manage_code_db_version', $crt_manage_code_db_version);
    }
}


function crt_manage_code_install_data()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'crtheme_manage_codes'; // do not forget about tables prefix

    $wpdb->insert($table_name, array(
        'name' => 'Alex',
    ));
}

function crt_manage_code_update_db_check()
{
    global $crt_manage_code_db_version;
    if (get_site_option('crt_manage_code_db_version') != $crt_manage_code_db_version) {
        crt_manage_code_install();
    }
}