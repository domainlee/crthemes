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
global $crt_manage_db_version;
$crt_manage_db_version = '1.1'; // version changed from 1.0 to 1.1
define( 'CRTheme_Manage_Site_PLUGIN_FILE', __FILE__);
require_once 'inc/class-crt-manage-site-base.php';
add_action('plugins_loaded', array('CRT_Manage_Site_Base', 'instance'));
