<?php
/*
 * Plugin Name: Name Your Price for WooCommerce
 * Description: Allow customers to define the product price. Also useful for accepting user-set donations.
 * Version: 1.0.3
 * WC tested up to: 9.2
 * Requires Plugins: woocommerce
 * Author: addonsorg
 * Author URI: https://add-ons.org/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'YEEADDONS_WOO_NAME_PRICE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'YEEADDONS_WOO_NAME_PRICE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
function Yeeaddons_Name_Price_Init(){
    include YEEADDONS_WOO_NAME_PRICE_PLUGIN_PATH."backend/product-settings.php";
    include YEEADDONS_WOO_NAME_PRICE_PLUGIN_PATH."backend/settings.php";
    include YEEADDONS_WOO_NAME_PRICE_PLUGIN_PATH."frontend/product.php";
    include YEEADDONS_WOO_NAME_PRICE_PLUGIN_PATH."frontend/cart.php";
}
add_action( 'woocommerce_loaded', 'Yeeaddons_Name_Price_Init', 10, 1 );
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
if(!class_exists('Superaddons_List_Addons')) {  
    include YEEADDONS_WOO_NAME_PRICE_PLUGIN_PATH."add-ons.php"; 
}