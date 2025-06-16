<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_Name_Price_Product_Frontend{
	function __construct(){
		add_action( 'woocommerce_single_variation', array( $this, 'display_form' ), 12 );
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'display_form' ), 9 );
		//add_filter( 'woocommerce_cart_item_price', array( $this, 'add_edit_link_in_cart' ), 10, 3 );
		add_action( 'wp_enqueue_scripts', array($this,"load_style") );
	}
	function load_style(){
		wp_enqueue_script( 'autoNumeric', YEEADDONS_WOO_NAME_PRICE_PLUGIN_URL . 'frontend/js/autoNumeric-1.9.45.js', "jquery","1.9.4.5");
		wp_enqueue_script( 'yeeaddons-name-your-price', YEEADDONS_WOO_NAME_PRICE_PLUGIN_URL . 'frontend/js/name-your-price.js', "jquery", time());
		wp_enqueue_style( 'yeeaddons-name-your-price', YEEADDONS_WOO_NAME_PRICE_PLUGIN_URL . 'frontend/css/name-your-price.css', false, time());
		$translation_array = array(
			"text_price_error" =>__("Please enter a valid ","name-your-price-for-woocommerce"),
			"text_min_error" =>__("Please enter at least ","name-your-price-for-woocommerce"),
			"text_max_error" =>__("Please enter less than or equal to ","name-your-price-for-woocommerce"),
		);
		wp_localize_script('yeeaddons-name-your-price', 'yee_nyp', $translation_array);
	}
	function display_form(){
		global $woocommerce, $product;
		$check = $product->get_meta( '_yee_price_name', true, 'edit' );
		if($check == "yes"){
			$min = $product->get_meta( '_yee_name_price_min', true, 'edit' );
			$max = $product->get_meta( '_yee_name_price_max', true, 'edit' );
			$text_min = $product->get_meta( '_yee_name_price_min_text', true, 'edit' );
			$suggested = $product->get_meta( '_yee_name_price_suggested', true, 'edit' );
			$default = $product->get_meta( '_yee_name_price_default', true, 'edit' );
			if ( isset( $_POST['_yee_pyn_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yee_pyn_nonce'] ) ) , '_yee_pyn_nonce' ) ) {
				if(isset($_REQUEST['yee_nyp_price'])){
					$default = sanitize_text_field( wp_unslash($_REQUEST['yee_nyp_price']) );
				}
			}
			if($suggested != ""){
				$suggested =wc_price( $suggested );
			}
			//update cart
			$cart_key = "";
			if(isset($_REQUEST['yee_nyp_price_update']) && isset($_REQUEST['cart_key'])){
				if ( isset( $_REQUEST['yee_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_REQUEST['yee_nonce'] ) ) , 'yee_nonce' ) ) {
					$default  = sanitize_text_field( wp_unslash($_REQUEST['yee_nyp_price_update']) );
					$cart_key = sanitize_text_field( wp_unslash($_REQUEST['cart_key']) );
				}
			}
			$title = $product->get_meta( '_yee_name_price_title', true, 'edit' );
			if($title != ""){
				$title = str_replace("[suggested]", $suggested, $title);
			}
			$woocommerce_price_thousand_sep = get_option( "woocommerce_price_thousand_sep");
			$woocommerce_price_decimal_sep = get_option( "woocommerce_price_decimal_sep");
			$woocommerce_price_num_decimals = get_option( "woocommerce_price_num_decimals");
			$woocommerce_currency_pos = get_option( "woocommerce_currency_pos");
			$currency = get_woocommerce_currency_symbol();
			switch ($woocommerce_currency_pos) {
				case 'left':
					$woocommerce_currency_pos ="p";
					break;
				case 'right':
					$woocommerce_currency_pos ="s";
					break;
				case '':
					$woocommerce_currency_pos ="p";
					$currency = $currency ." ";
					break;
				case 'right_space':
					$woocommerce_currency_pos ="s";
					$currency = " ".$currency;
					break;
				default:
					// code...
					break;
			}
			?>
			<div class="yee-name-your-price-container">
				<div class="yee-name-your-price-container-content">
					<p>
						<label class="yee-nyp-price-label" for="yee-nyp-price"><?php echo wp_kses_post( $title ); ?></label>
					</p>
					<p class="yee-name-your-price-container-content-input">
						<?php
						$custom_values = $product->get_meta( '_yee_name_price_add_custom_values', true, 'edit' );
						$enable_custom_values = $product->get_meta( '_yee_name_price_add_enable_custom_value', true, 'edit' );
						$custom_values = trim($custom_values);
						$class ="";
						if($custom_values != ""){
							$options = explode("\n", $custom_values);
							$class = "hidden";
							?>
							<select id="yee-nyp-price-select">
								<?php
								$i = 0;
								foreach ($options as $option) {
									if($i == 0 ){
										$default_old = $default;
										if($default == ""){
											$default = $option; 
										}
									}
									$price = strip_tags(wc_price($option));
									?>
									<option <?php selected( $default_old,$option) ?> value="<?php echo esc_attr($option) ?>"><?php echo esc_attr($price) ?></option>
									<?php
									$i++;
								}
								if($enable_custom_values == "yes") {
									?>
									<option value="custom"><?php esc_html_e( "Custom Price", "name-your-price") ?></option>
									<?php
								}
								 ?>
							</select>
							<?php
						}
						?>
						<input data-a-sign="<?php echo esc_attr($currency) ?>" data-a-sep="<?php echo esc_attr($woocommerce_price_thousand_sep) ?>" data-m-de="<?php echo esc_attr($woocommerce_price_num_decimals) ?>" data-dec="<?php echo esc_attr($woocommerce_price_decimal_sep) ?>" data-p-sign="<?php echo esc_attr($woocommerce_currency_pos) ?>" name="yee_nyp_price" data-min="<?php echo esc_attr($min) ?>" data-max="<?php echo esc_attr($max) ?>" id="yee-nyp-price" type="text" class="amount yee-nyp-price <?php echo esc_attr($class) ?>" value="<?php echo esc_attr($default)  ?>" />
						<input type="hidden" name="yeeaddons_cart_key" value="<?php echo esc_attr($cart_key); ?>">
					</p>
					<?php wp_nonce_field("_yee_pyn_nonce","_yee_pyn_nonce") ?>
					<?php if($text_min != ""){
						?>
						<div class="yee-nyp-des <?php echo esc_attr($class) ?>">
							<?php 
							if($min == ""){
								$min = 0;
							}
							$min =wc_price( $min );
							$text_min = str_replace("[min]", $min, $text_min);
							echo wp_kses_post( $text_min ) ?>
						</div>
						<?php
					} ?>
				</div>
				<div class="yee-name-your-price-container-notify hidden">
					<ul class="woocommerce-error">
					</ul>
				</div>
			</div>
			<?php
		}
	}
}
new Yeeaddons_Woo_Name_Price_Product_Frontend;