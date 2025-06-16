<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Settings_PDF{
	function __construct(){
		add_filter('yeepdf_shortcodes_woocommerce', array($this,"yeepdf_shortcodes"),60);
		add_filter('yeepdf_shortcodes_woocommerce_action', array($this,"yeepdf_shortcodes_woocommerce_action"),60,3);
		add_filter('yeepdf_custom_sizes', array($this,"yeepdf_custom_sizes"));
	}
	function yeepdf_custom_sizes($size){
		$size["200,90"] = "200x90 Voucher";
		return $size;
	}
	function yeepdf_shortcodes($shortcodes){
		$shortcodes["Voucher"] = array(
            "yeepdf_woo_voucher_id"=>"Voucher ID",
            "yeepdf_woo_voucher_code"=>"Voucher Code",
            "yeepdf_woo_voucher_expires"=>"Voucher Expires",
            "yeepdf_woo_voucher_coupon_code"=>"Voucher Coupon Code",
            "yeepdf_woo_voucher_recipients meta_key='change_name'"=>"Recipient Field",
            );
		return $shortcodes;
	}
	function yeepdf_shortcodes_woocommerce_action($shortcode,$order,$data_atts){
		$show = false;
		$recipients_fields_datas = array();
		if(isset($data_atts["voucher_id"]) && $data_atts["voucher_id"] > 0){
			$show = true;
			$voucher_id = $data_atts["voucher_id"];
			$product_id = get_post_meta($voucher_id,"_product_id",true);
			$expires = get_post_meta($voucher_id,"_expires",true);
	        if($expires == ""){
	            $expires = __("Never Expire","pdf-product-vouchers-for-woocommerce");
	        }
	        $recipients_datas = get_post_meta($voucher_id,"_forms",true);
	        if($recipients_datas != ""){
	        	$_product = wc_get_product($product_id);
	        	$recipients_fields = $_product->get_meta( '_yeepdf_product_vouchers_forms', true );
	        	$recipients_datas = json_decode($recipients_datas,true);
			    if(is_array($recipients_datas) && count($recipients_datas) > 0){
			         foreach($recipients_fields as $field){
		                if(isset($recipients_datas[$field["name"]]["value"])){
		                    $recipients_fields_datas[$field["name"]] = $recipients_datas[$field["name"]]["value"];
		                }else{
		                    $recipients_fields_datas[$field["name"]] ="";
		                }
		            }
				}
	        }
	    }
		switch($shortcode) {
			case "yeepdf_woo_voucher_id":
				if($show){
					return $data_atts["voucher_id"];
				}else{
					return "9999";
				}
				break;
			case "yeepdf_woo_voucher_code":
				if($show){
					return get_post_meta($voucher_id,"_code",true);
				}else{
					return "CODE-DEMO-RAND";
				}
				break;
			case "yeepdf_woo_voucher_expires":
				if($show){
					return $expires;
				}else{
					return "29/02/2050";
				}
				break;
			case "yeepdf_woo_voucher_coupon_code":
				if($show){
					return get_post_meta($voucher_id,"_coupon_code",true);
				}else{
					return "COUPON-CODE-DEMO-RAND";
				}
				break;
			case "yeepdf_woo_voucher_recipients":
				if($show){
					if( isset($recipients_fields_datas[$data_atts["meta_key"]] ) ){
						return $recipients_fields_datas[$data_atts["meta_key"]];
					}else{
						return "";
					}
				}else{
					return "Recipient field ".$data_atts["meta_key"];
				}
				break;
		}
	}
}
new Yeeaddons_Woo_PDF_Product_Settings_PDF;