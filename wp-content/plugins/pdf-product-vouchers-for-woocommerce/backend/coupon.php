<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Settings_Coupon{
	function __construct(){
		add_action("yeeaddons_product_vouchers_options",array($this,"add_form"));
		add_action( 'woocommerce_admin_process_product_object', array($this,"woocommerce_admin_process_product_object") );
	}
	function woocommerce_admin_process_product_object($product){
		if ( isset( $_POST['_yeepdf_voucher_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yeepdf_voucher_nonce'] ) ) , '_yeepdf_voucher_nonce' ) ) {
			if(isset($_POST["_yeepdf_product_vouchers_coupon"])){
				$form_data_forms = map_deep( $_POST['_yeepdf_product_vouchers_coupon'], 'sanitize_text_field' );
				$form_data_forms = map_deep( $form_data_forms, 'wp_unslash' );
				$product->update_meta_data( '_yeepdf_product_vouchers_coupon', $form_data_forms);
			}
		}
	}
	function add_form(){
		global $post, $product_object;
		$coupon_id = absint( $post->ID );
		$coupon    = new WC_Coupon( $coupon_id );
		$atts = $product_object->get_meta( '_yeepdf_product_vouchers_coupon', true );
		$meta_data = array(
					"discount_type"				 =>"fixed_cart",
					"coupon_amount_type"		 =>"price",
					"coupon_amount"				 =>"",
					"expiry_date"				 =>"",
					"minimum_amount"			 =>"",
					"maximum_amount"			 =>"",
					'product_ids'                => array(),
            		'exclude_product_ids'        => '',
					'product_categories'         => '',
            		'exclude_product_categories' => '',
				);
		$meta_data_pdf = shortcode_atts( $meta_data,$atts );
		?>
		<div class="pdf_vou_voucher_tab pdf_vou_voucher_tab_coupon woocommerce_options_panel hidden">
			<div class="pdf_vou_voucher_forms_container">
				<div class="pdf_vou_voucher_forms_container_data">
				<?php
				woocommerce_wp_select(
					array(
						'id'          => '_yeepdf_product_vouchers_coupon_discount_type',
						'name'          => '_yeepdf_product_vouchers_coupon[discount_type]',
						'value'       => $meta_data_pdf["discount_type"],
						'label'       => __("Discount type","pdf-product-vouchers-for-woocommerce"),
						'description' => '',
						'options'     => array(
											"percent" => "Percentage discount",
											"fixed_cart" => "Fixed cart discount",
											"fixed_product" => "Fixed product discount",
										),
					)
				);
				?>
				<?php
				if( $meta_data_pdf["discount_type"] == "percent"){
					$class_type = "hidden";
				}else{
					$class_type ="";
				}
				woocommerce_wp_select(
					array(
						'name'          => '_yeepdf_product_vouchers_coupon[coupon_amount_type]',
						'id'          => '_yeepdf_product_vouchers_coupon_coupon_amount_type',
						'value'       => $meta_data_pdf["coupon_amount_type"],
						'label'       => __("Coupon amount type","pdf-product-vouchers-for-woocommerce"),
						'description' => '',
						'wrapper_class' => $class_type,
						'options'     => array(
											"price" => "Price",
											"custom" => "Custom",
										),
					)
				);
				if($meta_data_pdf["coupon_amount_type"] == "custom"){
					$class_coupon_amount = "yeeshow ".$class_type;
				}else{
					$class_coupon_amount = "hidden ".$class_type;
				}
				if( $meta_data_pdf["discount_type"] == "percent"){
					$class_coupon_amount ="yeeshow";
				}
				?>
				<p class="form-field yeepdf_product_vouchers_coupon_amount <?php echo esc_attr($class_coupon_amount) ?>">
					<label><?php esc_html_e("Coupon amount","pdf-product-vouchers-for-woocommerce") ?></label>
					<input id="_yeepdf_product_vouchers_coupon_coupon_amount" type="text" name="_yeepdf_product_vouchers_coupon[coupon_amount]" value="<?php echo esc_attr($meta_data_pdf["coupon_amount"],) ?>" >
				</p>
				<p class="form-field">
					<label><?php esc_html_e("Coupon expiry date","pdf-product-vouchers-for-woocommerce") ?></label>
					<input type="datetime-local" name="_yeepdf_product_vouchers_coupon[expiry_date]" value="<?php echo esc_attr($meta_data_pdf["expiry_date"],) ?>" >
					<span class="woocommerce-help-tip" tabindex="0" aria-label="<?php esc_html_e("The coupon will expire","pdf-product-vouchers-for-woocommerce") ?>"></span>
				</p>
				<p class="form-field">
					<label><?php esc_html_e( 'Products', "pdf-product-vouchers-for-woocommerce" ); ?></label>
					<select class="wc-product-search" multiple="multiple" name="_yeepdf_product_vouchers_coupon[product_ids][]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', "pdf-product-vouchers-for-woocommerce" ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
						<?php
						$product_ids = $meta_data_pdf["product_ids"];
						if(!is_array($product_ids)){
							$product_ids = array();
						}
						foreach ( $product_ids as $product_id ) {
							$product = wc_get_product( $product_id );
							if ( is_object( $product ) ) {
								?>
								<option <?php selected( true, true ) ?> value="<?php echo esc_attr( $product_id )  ?>"><?php echo esc_html( wp_strip_all_tags( $product->get_formatted_name() ) )  ?></option>
								<?php
							}
						}
						?>
					</select> <?php echo wp_kses_post(wc_help_tip( __( 'You can select the products on which you want to use coupon generated by this product.', "pdf-product-vouchers-for-woocommerce" ) )); // WPCS: XSS ok. ?>
				</p>
				<p class="form-field">
					<label><?php esc_html_e( 'Exclude Products', "pdf-product-vouchers-for-woocommerce" ); ?></label>
					<select class="wc-product-search" multiple="multiple" name="_yeepdf_product_vouchers_coupon[exclude_product_ids][]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', "pdf-product-vouchers-for-woocommerce" ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
						<?php
						$product_ids = $meta_data_pdf["exclude_product_ids"];
						if(!is_array($product_ids)){
							$product_ids = array();
						}
						foreach ( $product_ids as $product_id ) {
							$product = wc_get_product( $product_id );
							if ( is_object( $product ) ) {
								?>
								<option <?php selected( true, true ) ?> value="<?php echo esc_attr( $product_id )  ?>"><?php echo esc_html( wp_strip_all_tags( $product->get_formatted_name() ) )  ?></option>
								<?php
							}
						}
						?>
					</select> <?php echo wp_kses_post(wc_help_tip( __( 'Select the products for which the voucher / coupon will not be applied to.', "pdf-product-vouchers-for-woocommerce" ) )); // WPCS: XSS ok. ?>
				</p>
				<p class="form-field">
					<label><?php esc_html_e( 'Categories', "pdf-product-vouchers-for-woocommerce" ); ?></label>
					<select class="wc-category-search" multiple="multiple" name="_yeepdf_product_vouchers_coupon[product_categories][]" data-placeholder="<?php esc_attr_e( 'Search for a category&hellip;', "pdf-product-vouchers-for-woocommerce" ); ?>" data-action="woocommerce_json_search_products_and_variations" >
						<?php
						$categories = $meta_data_pdf["product_categories"];
						if(!is_array($product_ids)){
							$categories = array();
						}
						if ( $categories ) {
							foreach ( $categories as $cat ) {
								?>
								<option <?php selected( $cat->term_id, $meta_data_pdf["product_categories"] ) ?> value="<?php echo esc_attr( $cat->term_id  )  ?>"><?php echo esc_html( $cat->name )  ?></option>
								<?php
							}
						}
						?>
					</select> <?php echo wp_kses_post(wc_help_tip( __( 'You can select the categories on which you want to use coupon generated by this product.', "pdf-product-vouchers-for-woocommerce" ) )); // WPCS: XSS ok. ?>
				</p>
				<p class="form-field">
					<label ><?php esc_html_e( 'Exclude Categories', "pdf-product-vouchers-for-woocommerce" ); ?></label>
					<select class="wc-category-search" multiple="multiple" name="_yeepdf_product_vouchers_coupon[exclude_product_categories][]" data-placeholder="<?php esc_attr_e( 'Search for a category&hellip;', "pdf-product-vouchers-for-woocommerce" ); ?>" data-action="woocommerce_json_search_products_and_variations" >
						<?php
						$categories = $meta_data_pdf["exclude_product_categories"];
						if(!is_array($product_ids)){
							$categories = array();
						}
						if ( $categories ) {
							foreach ( $categories as $cat ) {
								?>
								<option <?php selected( $cat->term_id, $meta_data_pdf["exclude_product_categories"] ) ?> value="<?php echo esc_attr( $cat->term_id  )  ?>"><?php echo esc_html( $cat->name )  ?></option>
								<?php
							}
						}
						?>
					</select> <?php echo wp_kses_post(wc_help_tip( __( 'Select the categories for which the voucher / coupon will not be applied to.', "pdf-product-vouchers-for-woocommerce" ) )); // WPCS: XSS ok. ?>
				</p>
				<?php
				woocommerce_wp_text_input(
					array(
						'id'          => '_yeepdf_product_vouchers_coupon[minimum_amount]',
						'label'       => __( 'Minimum spend', "pdf-product-vouchers-for-woocommerce" ),
						'placeholder' => __( 'No minimum', "pdf-product-vouchers-for-woocommerce" ),
						'description' => __( 'This field allows you to set the minimum spend (subtotal) allowed to use the coupon.', "pdf-product-vouchers-for-woocommerce" ),
						'data_type'   => 'price',
						'desc_tip'    => true,
						'value'       => $meta_data_pdf["minimum_amount"] ,
					)
				);
				// maximum spend.
				woocommerce_wp_text_input(
					array(
						'id'          => '_yeepdf_product_vouchers_coupon[maximum_amount]',
						'label'       => __( 'Maximum spend', "pdf-product-vouchers-for-woocommerce" ),
						'placeholder' => __( 'No maximum', "pdf-product-vouchers-for-woocommerce" ),
						'description' => __( 'This field allows you to set the maximum spend (subtotal) allowed when using the coupon.', "pdf-product-vouchers-for-woocommerce" ),
						'data_type'   => 'price',
						'desc_tip'    => true,
						'value'       => $meta_data_pdf["maximum_amount"] ,
					)
				);
				?>
				</div>
			</div>
		</div>
		<?php
	}
}
new Yeeaddons_Woo_PDF_Product_Settings_Coupon;