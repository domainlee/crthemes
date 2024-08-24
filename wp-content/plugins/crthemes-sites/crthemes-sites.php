<?php
/**
 * Plugin Name: CRThemes Management Sites
 * Plugin URI: https://crthemes.com
 * Description: CRThemes managerment sites
 * Author: Domainlee
 * Version: 0.0.1
 * Domain Path: /languages/
 * Text Domain: crthemes-management-site
 * Author URI: https://crthemes.com
 */
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
define( 'CRTHEMES_MANAGEMENT_SITE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CRTHEMES_MANAGEMENT_SITE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'CRTHEMES_MANAGEMENT_SITE_PLUGIN_FILE' ) ) {
    define( 'CRTHEMES_MANAGEMENT_SITE_PLUGIN_FILE', __FILE__ );
}

if (function_exists('is_plugin_active')) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

require_once 'includes/class-crt-manage-site.php';
add_action('plugins_loaded', array('CRT_Manage_SITE_Base', 'instance'));