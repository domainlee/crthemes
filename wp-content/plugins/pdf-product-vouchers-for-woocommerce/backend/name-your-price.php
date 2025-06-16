<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Settings_NYP{
	function __construct(){
		add_action("yeeaddons_product_vouchers_options",array($this,"add_form"));
	}
	function add_form(){
		global $post, $product_object;
		 ?>
		<div class="pdf_vou_voucher_tab pdf_vou_voucher_tab_nameyourprice hidden">
		<?php
		$plugin = 'name-your-price-for-woocommerce/name-your-price-for-woocommerce.php';
		if($this->is_nyp_installed()){
			if ( is_plugin_active( $plugin ) ) {
			    //active plugin
			}else{
				if ( current_user_can( 'activate_plugins' ) ) {
	               $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
	               $message = '<p>' . sprintf( '<a href="%s" target="bank" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Now', "pdf-product-vouchers-for-woocommerce" ) ) . '</p>';
	               echo wp_kses_post($message);
	            }
			}
		}else{
			if ( current_user_can( 'install_plugins' ) ) {
				$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=name-your-price-for-woocommerce' ), 'install-plugin_name-your-price-for-woocommerce' );
                $message = '<p>' . sprintf( '<a href="%s" target="bank" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Now', "pdf-product-vouchers-for-woocommerce" ) ) . '</p>';
                echo wp_kses_post($message);
            }
		}
		?>
		</div>
		<?php
	}
	function is_nyp_installed() {
		$file_path = 'name-your-price-for-woocommerce/name-your-price-for-woocommerce.php';
		$installed_plugins = get_plugins();
		return isset( $installed_plugins[ $file_path ] );
	}
}
new Yeeaddons_Woo_PDF_Product_Settings_NYP;