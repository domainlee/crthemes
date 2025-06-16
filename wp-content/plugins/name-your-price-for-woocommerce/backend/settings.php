<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_Name_Price_Admin_Settings{
	function __construct(){
		add_filter( 'woocommerce_get_price_html', array( $this, 'admin_price_html' ), 30, 2 );
		add_filter( 'woocommerce_product_filters', array( $this, 'product_filters' ) );
		add_filter( 'parse_query', array( $this, 'product_filters_query' ) );
	}
	function product_filters($output){
		global $wp_query;
		$startpos = strpos( $output, '<select name="product_type"' );
		if ( false !== $startpos ) {
			$endpos = strpos( $output, '</select>', $startpos );
			if ( false !== $endpos ) {
				$current = isset( $wp_query->query['product_type'] ) ? $wp_query->query['product_type'] : false;
				$option = sprintf(
					'<option value="yeeaddons-name-your-price" %s > %s</option>',
					selected( 'name-your-price', $current, false ),
					__( 'Name Your Price', "name-your-price-for-woocommerce" )
				);
				$output = substr_replace( $output, $option, $endpos, 0 );
			}
		}
		return $output;
	}
	function product_filters_query($query){
		global $typenow;
		if ( 'product' === $typenow ) {
			if ( isset( $query->query_vars['product_type'] ) ) {
				// Subtypes.
				if ( 'yeeaddons-name-your-price' === $query->query_vars['product_type'] ) {
					$query->query_vars['product_type'] = '';
					$query->is_tax                     = false;
					$meta_query                        = array(
						array(
							'key'     => '_yee_price_name',
							'value'   => 'yes',
							'compare' => '=',
						),
					);
					$query->query_vars['meta_query']   = $meta_query;
				}
			}
		}
	}
	function admin_price_html($price, $product ){
		global $woocommerce;
		$check = $product->get_meta( '_yee_price_name', true, 'edit' );
		if($check == "yes"){
			$min = $product->get_meta( '_yee_name_price_min', true, 'edit' );
			if($min != ""){
				$price =esc_html__( "From:", "name-your-price-for-woocommerce")." ".wc_price($min);
			}
		}
		return $price;
	}
}
new Yeeaddons_Woo_Name_Price_Admin_Settings;