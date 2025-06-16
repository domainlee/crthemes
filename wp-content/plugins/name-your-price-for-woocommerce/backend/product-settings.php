<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_Name_Price_Product_Settings{
	function __construct(){
		add_action( "woocommerce_product_options_pricing", array($this,"form_settings"),10 );
		add_filter( "product_type_options", array($this,"product_type_options") );
		add_action( 'admin_enqueue_scripts', array($this,"load_admin_style") );
		add_action( 'woocommerce_admin_process_product_object', array($this,"woocommerce_admin_process_product_object") );
	}
	function woocommerce_admin_process_product_object($product){
		if ( isset( $_POST['_yee_pyn_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yee_pyn_nonce'] ) ) , '_yee_pyn_nonce' ) ) {
			if( isset($_POST['_yee_price_name']) ) {
				$title = isset( $_POST['_yee_name_price_title'] ) ?  sanitize_text_field( wp_unslash( $_POST['_yee_name_price_title'] ) )  : '';
				$product->update_meta_data( '_yee_name_price_title', $title );
				$product->update_meta_data( '_yee_price_name', "yes" );
				$product->set_sale_price( '' );
				$suggested = isset( $_POST['_yee_name_price_suggested'] ) ? wc_format_decimal( sanitize_text_field( wp_unslash( $_POST['_yee_name_price_suggested'] ) ) ) : '';
				$product->update_meta_data( '_yee_name_price_suggested', $suggested ); 
				$suggested = isset( $_POST['_yee_name_price_suggested'] ) ? wc_format_decimal( sanitize_text_field( wp_unslash( $_POST['_yee_name_price_suggested'] ) ) ) : '';
				$product->update_meta_data( '_yee_name_price_suggested', $suggested ); 
				$default = isset( $_POST['_yee_name_price_default'] ) ? wc_format_decimal( sanitize_text_field( wp_unslash( $_POST['_yee_name_price_default'] ) ) ) : '';
				$product->update_meta_data( '_yee_name_price_default', $default ); 
				$min = isset( $_POST['_yee_name_price_min'] ) ? wc_format_decimal( sanitize_text_field( wp_unslash( $_POST['_yee_name_price_min'] ) ) ) : '';
				$product->update_meta_data( '_yee_name_price_min', $min );
				$text_min = isset( $_POST['_yee_name_price_min_text'] ) ?  sanitize_text_field( wp_unslash( $_POST['_yee_name_price_min_text'] ) )  : '';
				$product->update_meta_data( '_yee_name_price_min_text', $text_min );
				$max = isset( $_POST['_yee_name_price_max'] ) ? wc_format_decimal( sanitize_text_field( wp_unslash( $_POST['_yee_name_price_max'] ) ) ) : '';
				$product->update_meta_data( '_yee_name_price_max', $max );
				$add_text_shop = isset( $_POST['_yee_name_price_add_cart_shop'] ) ? sanitize_text_field( wp_unslash( $_POST['_yee_name_price_add_cart_shop'] ) ) : '';
				$custom_values = isset( $_POST['_yee_name_price_add_custom_values'] ) ? sanitize_textarea_field( wp_unslash( $_POST['_yee_name_price_add_custom_values'] ) ) : '';
				$enable_select = isset( $_POST['_yee_name_price_add_enable_custom_value'] ) ? "yes":"no";
				$product->update_meta_data( '_yee_name_price_add_cart_shop', $add_text_shop );
				$product->update_meta_data( '_yee_name_price_add_custom_values', $custom_values );
				$product->update_meta_data( '_yee_name_price_add_enable_custom_value', $enable_select );
				$main_price = 0;
				if($suggested == ""){
					$suggested = 0;
				}
				if($default == ""){
					$default = 0;
				}
				if( $default == 0 && $min > $default ) {
					$error_notice = esc_html__( 'The suggested price must be higher than the minimum for Name Your Price products. Please review your prices.', "name-your-price-for-woocommerce" );
					WC_Admin_Meta_Boxes::add_error( $error_notice );
				}else{
					$main_price = $default;
				}
				$product->set_price( $main_price );
				$product->set_regular_price( $main_price );
				if ( $product->is_type( 'subscription' ) ) {
					$product->update_meta_data( '_subscription_price', $main_price );
				}
				if ( isset( $_POST['_variable_billing'] ) && $product->is_type( 'subscription' ) ) {
					$product->update_meta_data( '_variable_billing', 'yes' );
				} else {
					$product->delete_meta_data( '_variable_billing' );
				}
			}else{
				$product->update_meta_data( '_yee_price_name', "no" );
			}
		}
	}
	function load_admin_style(){
		wp_enqueue_script( 'yeeaddons-name-your-price', YEEADDONS_WOO_NAME_PRICE_PLUGIN_URL . 'backend/js/name-your-price.js', false, time());
	}
	function product_type_options($options){
		$options["yee_price_name"]   = array(
			'id'            => '_yee_price_name',
			'wrapper_class' => '_show_if_simple',
			'label'         => __( 'Name Your Price', "name-your-price-for-woocommerce" ),
			'description'   => __( 'Enable Name Your Price', "name-your-price-for-woocommerce" ),
			'default'       => 'no',
		);
		return $options;
	}
	function form_settings(){
		global $post, $product_object;
		$title = $product_object->get_meta( '_yee_name_price_title', true );
		if(!$title){
			$title = "Suggested price $";
		}
		$min_text = $product_object->get_meta( '_yee_name_price_min_text', true );
		$enable_custom_value = $product_object->get_meta( '_yee_name_price_add_enable_custom_value', true );
		$custom_values = $product_object->get_meta( '_yee_name_price_add_custom_values', true );
		$add_cart_text_s = $product_object->get_meta( '_yee_name_price_add_cart_shop', true );
		if(!$min_text){
			$min_text = "Minimum price: [min]";
		}
		if(!$add_cart_text_s){
			$add_cart_text_s = "Choose price";
		}
		?>
		<div class="show_if_yee_price_name options_group">
			<?php wp_nonce_field("_yee_pyn_nonce","_yee_pyn_nonce") ?>
		<p><strong><?php esc_html_e( "Name Your Price","name-your-price-for-woocommerce" ) ?></strong></p>
		<?php
		woocommerce_wp_text_input(
			array(
				'id'          => '_yee_name_price_title',
				'value'       => $title,
				'label'       => __( 'Title', "name-your-price-for-woocommerce" ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => '_yee_name_price_default',
				'value'       => $product_object->get_meta( '_yee_name_price_default', true ),
				'label'       => __( 'Default', "name-your-price-for-woocommerce" ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => '_yee_name_price_min',
				'value'       => $product_object->get_meta( '_yee_name_price_min', true ),
				'label'       => __( 'Min Price', "name-your-price-for-woocommerce" ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => '_yee_name_price_min_text',
				'value'       => $min_text,
				'label'       => __( 'Text min Price', "name-your-price-for-woocommerce" ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => '_yee_name_price_max',
				'value'       => $product_object->get_meta( '_yee_name_price_max', true ),
				'label'       => __( 'Max Price', "name-your-price-for-woocommerce" ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => '_yee_name_price_add_cart_shop',
				'value'       => $add_cart_text_s,
				'label'       => __( 'Add to Cart Button Text for Shop', "name-your-price-for-woocommerce" ),
			)
		);
		echo '<hr>';
		woocommerce_wp_textarea_input(
			array(
					'id'          => '_yee_name_price_add_custom_values',
					'value'       => $custom_values,
					'label'       => __( 'Select custom value', "name-your-price-for-woocommerce" ),
				)
		);
		woocommerce_wp_checkbox(
			array(
					'id'          => '_yee_name_price_add_enable_custom_value',
					'value'       => $enable_custom_value,
					'label'       => __( 'Enable Custom price after select', "name-your-price-for-woocommerce" ),
				)
		);
		?>
	</div>
		<?php
	}
}
new Yeeaddons_Woo_Name_Price_Product_Settings;