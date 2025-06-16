<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_Name_Price_Cart_Frontend{
	function __construct(){
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 3 );
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 11, 2 );
		//set price
		add_filter( 'woocommerce_cart_item_price', array($this,"cart_item_price"),99,3);
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_add_cart_item' ), 5, 3 );
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 10, 2 );
		add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_to_cart_url' ), 10, 2 );
		add_filter( 'woocommerce_product_supports', array( $this, 'supports_ajax_add_to_cart' ), 10, 3 );
	}
	function add_to_cart_text( $text, $product ) {
		$check = $product->get_meta( '_yee_price_name', true, 'edit' );
		if($check == "yes"){
			$text = $product->get_meta( '_yee_name_price_add_cart_shop', true, 'edit' );
		}
		return $text;
	}
	function add_to_cart_url( $url, $product = null ) {
		$check = $product->get_meta( '_yee_price_name', true, 'edit' );
		if($check == "yes"){
			$url = get_permalink( $product->get_id() );
		}
		return $url;
	}
	function supports_ajax_add_to_cart( $ajax, $feature, $product ) {
		$check = $product->get_meta( '_yee_price_name', true, 'edit' );
		if ( 'ajax_add_to_cart' === $feature && $check == "yes") {
			$ajax = false;
		}
		return $ajax;
	}
	function validate_add_cart_item($passed, $product_id, $quantity){
		if ( wc()->is_rest_api_request() ) {
			return $passed;
		}
		if ( isset( $_POST['_yee_pyn_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yee_pyn_nonce'] ) ) , '_yee_pyn_nonce' ) ) {
			if ( isset( $_POST['yee_nyp_price'] )) {
				$price = sanitize_text_field( wp_unslash( $_POST['yee_nyp_price'] ) );
				$price = $this->remove_price_format($price);
				if ( ! is_object( $product_id ) || ! is_a( $product_id, 'WC_Product' ) ) {
					$product = wc_get_product( $product_id );
					$product_title = $product->get_title();
					$min = $product->get_meta( '_yee_name_price_min', true, 'edit' );;
					$max = $product->get_meta( '_yee_name_price_max', true, 'edit' );;
					if ( ! is_numeric( $price ) || is_infinite( $price ) || floatval( $price ) < 0 ) {
						$passed = false;
						$text_valid = __( 'Please enter a valid.', "name-your-price-for-woocommerce" );
						wc_add_notice( $text_valid, 'error' );
					}else{
						if($min !="" && $min >= 0){
							if($min > $price){
								$passed = false;
								$text_error = esc_html__( 'Please enter at least ', "name-your-price-for-woocommerce" );
								$reason = $text_error . wc_price( $min ). ".";
								wc_add_notice( $reason , 'error' );
							}
						}
						if($max !="" && $max > 0){ 
							if($price > $max){
								$passed = false;
								$text_error = esc_html__( 'Please enter less than or equal to .', "name-your-price-for-woocommerce" );
								$reason = $text_error. wc_price( $max ) .".";
								wc_add_notice( $reason, 'error' );
							}
						}
					}
				}
			}
		}
		return $passed;
	}
	public function get_cart_item_from_session( $cart_item, $values ) {
		if ( isset( $values['_yee_nyp_price'] ) ) {
			$cart_item['_yee_nyp_price'] = $values['_yee_nyp_price'];
			$cart_item = $this->set_cart_item( $cart_item );
		}
		return $cart_item;
	}
	function set_cart_item($cart_item){
		$product = $cart_item['data'];
		$price   = $cart_item['_yee_nyp_price'];
		$product->set_price( $price );
		$product->set_sale_price( $price );
		$product->set_regular_price( $price );
		return $cart_item;
	}
	function add_cart_item_data($cart_item_data, $product_id, $variation_id ){
		if ( isset( $_POST['_yee_pyn_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yee_pyn_nonce'] ) ) , '_yee_pyn_nonce' ) ) {
			if(isset($_POST['yee_nyp_price'])) {
				//update cart
				if(isset($_POST['yeeaddons_cart_key']) && $_POST['yeeaddons_cart_key'] != "") {
					$cart_key = sanitize_text_field( wp_unslash( $_POST['yeeaddons_cart_key']));
					if ( WC()->cart->find_product_in_cart( $cart_key ) ) {
						WC()->cart->remove_cart_item( $cart_key );
						add_filter( 'woocommerce_add_to_cart_redirect', array($this,"wc_get_cart_url") );
						add_filter( 'wc_add_to_cart_message_html', array($this,"wc_add_to_cart_message_html") );
					}
				}
				$new_price = sanitize_text_field( wp_unslash( $_POST['yee_nyp_price'] ) );
				$new_price = $this->remove_price_format($new_price);
				$cart_item_data['_yee_nyp_price'] = (float) $new_price;
			}
		}
		return $cart_item_data;
	}
	function wc_add_to_cart_message_html($message ) {
		return esc_html__( 'Cart updated.', "name-your-price-for-woocommerce" );
	}
	function wc_get_cart_url($message ) {
		return wc_get_cart_url();
	}
	function cart_item_price($content, $cart_item, $cart_item_key ){
		$product = $cart_item['data'];
		$check = $product->get_meta( '_yee_price_name', true, 'edit' );
		if($check == "yes"){
			$price = $product->get_price();
			$link_product = $product->get_permalink();
			$nonce = wp_create_nonce( 'yee_nonce' );
			$link =add_query_arg(array("yee_nyp_price_update"=>$price,"cart_key"=>$cart_item_key,"yee_nonce"=>$nonce),$link_product);
			$text = __( 'Edit price', "name-your-price-for-woocommerce" );
			$content = $content ."<br>". '<a class="yeeaddons-edit-cart " href="'.esc_url( $link ).'"><small>'.esc_html($text).'</small></a>';
		}
		return $content;
	}
	function remove_price_format($price){
		$woocommerce_price_thousand_sep = get_option( "woocommerce_price_thousand_sep");
		$woocommerce_price_decimal_sep = get_option( "woocommerce_price_decimal_sep");
		$price = str_replace($woocommerce_price_thousand_sep,"",$price);
		$price = str_replace($woocommerce_price_decimal_sep,".",$price);
		$price = preg_replace('/[^0-9.]/', '', $price);
		return $price;
	}
}
new Yeeaddons_Woo_Name_Price_Cart_Frontend;