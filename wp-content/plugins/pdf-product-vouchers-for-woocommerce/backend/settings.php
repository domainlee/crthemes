<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Settings_Plugin{
	function __construct(){
		add_filter( 'woocommerce_settings_tabs_array', array($this,"add_settings_tab"), 50 );
		add_action( 'woocommerce_settings_tabs_settings_vouchers', array($this,'settings_vouchers') );
		add_action( 'woocommerce_update_options_settings_vouchers', array( $this, 'update_settings' ) );

	}
	function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_vouchers'] = esc_html__( 'PDF Vouchers', "pdf-customizer-for-woocommerce" );
        return $settings_tabs;
    }
    function settings_vouchers() {
		$options = get_option( 'yeepdf_vouchers', array("show_code"=>"yes") );
		?>
		<h2><?php esc_html_e("PDF Vouchers Settings","pdf-product-vouchers-for-woocommerce") ?></h2>
		<table class="form-table">
			<tr class="">
				<th scope="row" class="titledesc"><?php esc_html_e("Enable Menu voucher in My Adcount","pdf-product-vouchers-for-woocommerce") ?></th>
				<td class="forminp forminp-checkbox ">
					<input <?php checked( isset( $options['menu_voucher'] ) && 'yes' === $options['menu_voucher'] ); ?> type="checkbox" name="yeepdf_vouchers[menu_voucher]" value="yes" id="menu_voucher">	<label for="menu_voucher"><?php esc_html_e("Show Menu voucher in My Adcount","pdf-product-vouchers-for-woocommerce") ?></label>							
				</td>
			</tr>
			<tr class="">
				<th scope="row" class="titledesc"><?php esc_html_e("Show Code Frontend","pdf-product-vouchers-for-woocommerce") ?></th>
				<td class="forminp forminp-checkbox ">
					<input <?php checked( isset( $options['show_code'] ) && 'yes' === $options['show_code'] ); ?> type="checkbox" name="yeepdf_vouchers[show_code]" value="yes" id="show_code">	<label for="show_code"><?php esc_html_e("Show code on frontend when price is 0","pdf-product-vouchers-for-woocommerce") ?></label>							
				</td>
			</tr>
		</table>
		<?php
	}
	function update_settings() {
		if ( isset( $_POST['yeepdf_vouchers'] ) && is_array( $_POST['yeepdf_vouchers'] ) ) {
			$options = array_map( 'sanitize_text_field', wp_unslash( $_POST['yeepdf_vouchers'] ) );
		} else {
			$options = array();
		}
		update_option( 'yeepdf_vouchers', $options );
	}
}
new Yeeaddons_Woo_PDF_Product_Settings_Plugin;