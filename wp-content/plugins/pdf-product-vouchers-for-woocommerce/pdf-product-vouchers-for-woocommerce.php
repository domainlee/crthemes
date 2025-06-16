<?php
/*
Plugin Name: PDF Product Vouchers for WooCommerce
Description: Create, manage, and send customized PDF vouchers for WooCommerce products.
Author: add-ons.org
Version: 1.0.4
Requires Plugins: woocommerce
Author URI: https://add-ons.org/
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
define( 'YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
register_activation_hook( __FILE__, 'yeeaddons_pdf_voucher_activation' );
function yeeaddons_pdf_voucher_activation(){
    if(get_option( "yeepdf_voucher_setup") == ""){
        $string = file_get_contents(YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/demo/voucher.json");
        $my_template = array(
            'post_title'    => "Voucher Default",
            'post_content'  => "",
            'post_status'   => 'publish',
            'post_type'     => 'yeepdf'
        );
        $id_template = wp_insert_post( $my_template );
        add_post_meta($id_template,"data_email",$string);
        add_post_meta($id_template,"_builder_pdf_settings_font_family",'dejavusans');
        $pdfs = array("dpi"=>96,"size"=>"200,90","orientation"=>"P","show_page"=>"");
        add_post_meta($id_template,"_builder_pdf_settings",$pdfs);
        update_option( "yeepdf_voucher_setup",$id_template );
    }
}
function yeeaddons_Checkout_Uploads_Init(){
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/settings.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/product-settings.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/voucher-codes.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/forms.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/templates.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/coupon.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/pdf.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/name-your-price.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."backend/emails/index.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."frontend/index.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."frontend/my-acount.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."frontend/coupon.php";
    include YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH."frontend/cart.php";
}
add_action( 'woocommerce_loaded', 'yeeaddons_Checkout_Uploads_Init', 10, 1 );
add_action( 'plugins_loaded', "yeeaddons_setup_pdf_woo_load_plugin" );
function yeeaddons_setup_pdf_woo_load_plugin(){
    if ( ! did_action( 'yeepdf_woocommerce/loaded' ) ) {
        add_action( 'admin_notices', "yeeaddons_setup_pdf_woo_load_plugin_fail_load" );
        return;
    } 
}
function yeeaddons_setup_pdf_woo_load_plugin_fail_load() {
    $screen = get_current_screen();
    if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
        return;
    }
    $plugin = 'pdf-for-woocommerce/pdf-for-woocommerce.php';
    if ( yeeaddons_setup_pdf_woo_load_plugin_is_yeemail_installed() ) {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
        $message = '<h3>' . esc_html__( 'You\'re not using PDF for WooCommerce yet!', 'yeemail-for-wpforms' ) . '</h3>';
        $message .= '<p>' . esc_html__( 'Activate the PDF for WooCommerce plugin to start using all of PDF Product Vouchers plugin’s features.', 'yeemail-for-wpforms' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Now', 'yeemail-for-wpforms' ) ) . '</p>';
    } else {
        if ( ! current_user_can( 'install_plugins' ) ) {
            return;
        }
        $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=pdf-for-woocommerce' ), 'install-plugin_yeemail' );
        $message = '<h3>' . esc_html__( 'PDF Product Vouchers plugin requires installing the YeeMail plugin', 'yeemail-for-wpforms' ) . '</h3>';
        $message .= '<p>' . esc_html__( 'Install and activate the PDF for WooCommerce to access all the Pro features.', 'yeemail-for-wpforms' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Now', 'yeemail-for-wpforms' ) ) . '</p>';
    }
    yeeaddons_setup_pdf_woo_load_plugin_print_error( $message );
}
function yeeaddons_setup_pdf_woo_load_plugin_print_error( $message ) {
    if ( ! $message ) {
        return;
    }
    // PHPCS - $message should not be escaped
    echo '<div class="error">' . $message . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
function yeeaddons_setup_pdf_woo_load_plugin_is_yeemail_installed() {
    $file_path = 'pdf-for-woocommerce/pdf-for-woocommerce.php';
    $installed_plugins = get_plugins();
    return isset( $installed_plugins[ $file_path ] );
}