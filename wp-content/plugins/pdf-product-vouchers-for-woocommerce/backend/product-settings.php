<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Settings{
	function __construct(){
		add_action('woocommerce_product_data_panels', array($this, 'product_write_panel'));
		add_filter('woocommerce_product_data_tabs', array($this,"woocommerce_product_data_tabs"));
		add_action( 'admin_enqueue_scripts', array($this,"load_admin_style") );
		add_action( 'woocommerce_admin_process_product_object', array($this,"woocommerce_admin_process_product_object") );
		add_filter( "product_type_options", array($this,"product_type_options") );
	}
	function product_type_options($options){
		$options["yee_voucher"]   = array(
			'id'            => '_yee_voucher',
			'wrapper_class' => '_show_if_simple',
			'label'         => __( 'Voucher Product', "name-your-price-for-woocommerce" ),
			'description'   => __( 'Enable voucher product', "name-your-price-for-woocommerce" ),
			'default'       => 'no',
		);
		return $options;
	}
	function woocommerce_admin_process_product_object($product){
		if ( isset( $_POST['_yeepdf_voucher_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yeepdf_voucher_nonce'] ) ) , '_yeepdf_voucher_nonce' ) ) {
			if(isset($_POST['_yee_voucher'])){
				$product->update_meta_data( '_yee_voucher', "yes");
				$form_data = map_deep( $_POST['_yeepdf_product_vouchers'], 'sanitize_text_field' );
				$form_data = map_deep( $form_data, 'wp_unslash' );
				$product->update_meta_data( '_yeepdf_product_vouchers', $form_data);
			}else{
				$product->update_meta_data( '_yee_voucher', "no");
			}
		}
	}
	function load_admin_style(){
		wp_enqueue_style( 'yeeaddons_product_vouchers', YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL . 'backend/css/pdf-vouchers.css', array("wp-jquery-ui-dialog"), time());
		wp_enqueue_script( 'yeeaddons_product_vouchers', YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL."backend/js/pdf-vouchers.js",array("jquery","jquery-ui-sortable","jquery-ui-dialog"),time() );
	}
	function woocommerce_product_data_tabs($tabs){
		$tabs['yee_voucher_tab'] = array(
        'label'    => __( 'PDF Vouchers', "pdf-product-vouchers-for-woocommerce" ),
	        'target'   => 'yeepdf-product-voucher',
	        'class'    => array( '_show_if_yee_voucher' ),
	    );
	    return $tabs;
	}
	function product_save_data($post_id, $post){
		update_post_meta($post_id, 'pdf_template_selection_is_required', 1);
	}
	function is_nyp_installed() {
		$file_path = 'name-your-price-for-woocommerce/name-your-price-for-woocommerce.php';
		$installed_plugins = get_plugins();
		return isset( $installed_plugins[ $file_path ] );
	}
	function product_write_panel(){
		global $post, $product_object;
		$post_id = apply_filters( 'yeepdf_edit_product_id', $post->ID, $post );
		$product  = wc_get_product($post_id);
		$is_variable = (is_object($product) && ($product->is_type('variable') || $product->is_type('variation'))) ? 1 : 0;
		$woo_vou_tab_options = array(
			'general' 			=> esc_html__( 'General', "pdf-product-vouchers-for-woocommerce" ),
			'forms'			=> esc_html__( 'Recipient Fields', "pdf-product-vouchers-for-woocommerce" ),
			'template'			=> esc_html__( 'Voucher Templates', "pdf-product-vouchers-for-woocommerce" ),
			'coupon'			=> esc_html__( 'Coupons', "pdf-product-vouchers-for-woocommerce" ),
		);
		if(!$this->is_nyp_installed()){
			$woo_vou_tab_options["nameyourprice"] = esc_html__( 'Name Your Price', "pdf-product-vouchers-for-woocommerce" );
		}
		?>
		<div id="yeepdf-product-voucher" class="panel wc-metaboxes-wrapper panel tabs-content hide-all">
			<?php 
				wp_nonce_field("_yeepdf_voucher_nonce","_yeepdf_voucher_nonce"); 
				$atts = $product_object->get_meta( '_yeepdf_product_vouchers', true );
				$meta_data = array(
					"reddem_online"=>"yes",
					"partial_redemption"=>"no",
					"start_date"=>"",
					"end_date"=>"",
					"delivery"=>"email",
					"preview"=>"no",
					"auto_create"=>"yes",
					"auto_create_cp"=>"yes",
					"codes"=>"",
					"expiration_type"=>"specific_time",
					"voucher_start_date"=>"",
					"voucher_expiration_date"=>"",
					"expiration_days"=>"30",
					"usage"=>"1",
				);
				$meta_data_pdf = shortcode_atts( $meta_data,$atts );
				 ?>
			<div id="yeepdf-product-settings-tab">
				<ul id="yeepdf-product-settings-tab-inner">
				<?php 
				$i = 0;
				foreach ($woo_vou_tab_options as $key => $value) {
					$class ="no-active";
					if($i == 0 ){
						$class ="active";
					}
					if($meta_data_pdf["auto_create"] == "no" && $key == "coupon") {
						$class = "hidden";
					}
					?>
					<li class="<?php echo esc_attr($class) ?> yeepdf-tab-<?php echo esc_attr($key) ?>" data-show="<?php echo esc_attr( $key ) ?>" ><?php echo esc_html($value) ?></li>
					<?php
					$i++;
				}
				?>
				</ul>
			</div>
			<div id="vou_voucher_121" class="wc-metaboxes-wrapper">	
				<div class="pdf_vou_voucher_tab pdf_vou_voucher_tab_general woocommerce_options_panel">
					<div class="options_group">
						<?php 
						//cho phép sự dụng tại website mình và lưu nó vào coupon
						woocommerce_wp_select(
							array(
								'wrapper_class'          => '_yeepdf_product_vouchers-auto_create',
								'id'          => '_yeepdf_product_vouchers[auto_create]',
								'value'       =>  $meta_data_pdf["auto_create"],
								'options'     =>  array("yes"=>"Yes","no"=>"No"),
								'label'       => __("Auto Coupon Generation","pdf-product-vouchers-for-woocommerce"),
								'description' => __( 'This will allow you to use voucher codes on online store.', 'pdf-product-vouchers-for-woocommerce' ),
							)
						);
						woocommerce_wp_select(
							array(
								'id'          => '_yeepdf_product_vouchers[partial_redemption]',
								'value'       => $meta_data_pdf["partial_redemption"],
								'label'       => __(" Enable Partial Redemption","pdf-product-vouchers-for-woocommerce"),
								'options'     =>  array("yes"=>"Yes","no"=>"No"),
								'description' => __( 'Enable Partial Redemption', 'pdf-product-vouchers-for-woocommerce' ),
							)
						);
						?>
						<p class="form-field">
							<label><?php esc_html_e("Product Start Date","pdf-product-vouchers-for-woocommerce") ?></label>
							<input type="datetime-local" name="_yeepdf_product_vouchers[start_date]" id="_yeepdf_product_vouchers[start_date]" value="<?php echo esc_attr($meta_data_pdf["start_date"],) ?>" >
							<span class="description"><?php esc_html_e("If you want to make the product valid for a specific time only, you can enter an start date here.","pdf-product-vouchers-for-woocommerce") ?></span>
						</p>
						<p class="form-field">
							<label><?php esc_html_e("Product End Date","pdf-product-vouchers-for-woocommerce") ?></label>
							<input type="datetime-local" name="_yeepdf_product_vouchers[end_date]" id="_yeepdf_product_vouchers[start_date]" value="<?php echo esc_attr($meta_data_pdf["end_date"],) ?>" >
							<span class="description"><?php esc_html_e("If you want to make the product valid for a specific time only, you can enter an end date here.","pdf-product-vouchers-for-woocommerce") ?></span>
						</p>
						<?php
							woocommerce_wp_select(
								array(
									'id'          => '_yeepdf_product_vouchers[delivery]',
									'value'       => $meta_data_pdf["delivery"],
									'label'       => __("Voucher Delivery","pdf-product-vouchers-for-woocommerce"),
									'description' => '
													<strong>Email</strong>: Customer receives "PDF Voucher" through email. <br>
													<strong>Offline</strong>: You will have to send voucher through physical mode, via post or on-shop.
This setting modifies the global voucher delivery setting and overrides vouchers delivery value.<br>
									',
									'options'     => array(
														"email" => "Email",
														"offline" => "Offline",
													),
								)
							);
							woocommerce_wp_select(
								array(
									'id'          => '_yeepdf_product_vouchers[preview]',
									'value'       => $meta_data_pdf["preview"],
									'label'       => __("Enable Voucher Preview","pdf-product-vouchers-for-woocommerce"),
									'description' => '
													<strong>Yes</strong>: Allow users to preview the voucher on product detail page before placing the order. <br>
													<strong>No</strong>: Disallow users to preview the voucher on product detail page before placing the order. <br>
									',
									'options'     => array(
														"yes" => "Yes",
														"no" => "No",
													),
								)
							);
							woocommerce_wp_select(
								array(
									'wrapper_class'          => '_yeepdf_product_vouchers-auto_create_cp',
									'id'          => '_yeepdf_product_vouchers-auto_create_cp',
									'name'          => '_yeepdf_product_vouchers[auto_create_cp]',
									'value'       =>  $meta_data_pdf["auto_create_cp"],
									'options'     =>  array("yes"=>"Yes","no"=>"No"),
									'label'       => __("Auto Voucher Code Generation","pdf-product-vouchers-for-woocommerce"),
									'desc_tip'    => true,
									'description' => __( 'It will generate a random 8-character code automatically.', 'pdf-product-vouchers-for-woocommerce' ),
								)
							);
							$class_codes ="";
							if($meta_data_pdf["auto_create_cp"] == "yes"){
								$class_codes = "hidden";
							}
							woocommerce_wp_select(
								array(
									'id'          => '_yeepdf_product_vouchers_usage',
									'wrapper_class'          => '_yeepdf_product_vouchers-usage '.$class_codes,
									'name'          => '_yeepdf_product_vouchers[usage]',
									'value'       => $meta_data_pdf["preview"],
									'label'       => __("Usage limits","pdf-product-vouchers-for-woocommerce"),
									'description' => '
													<strong>Unlimited usage</strong>: This code can be reused on each order. <br>
													<strong>1 time</strong>: 1 code can only be used once. <br>
									',
									'options'     => array(
														"0" => "Unlimited usage",
														"1" => "1 time",
													),
								)
							);
							woocommerce_wp_textarea_input(
								array(
									'wrapper_class'          => '_yeepdf_product_vouchers-codes '.$class_codes,
									'id'          => '_yeepdf_product_vouchers-codes',
									'name'          => '_yeepdf_product_vouchers[codes]',
									'value'       =>  $meta_data_pdf["codes"],
									'label'       => __("Voucher Codes","pdf-product-vouchers-for-woocommerce"),
									'desc_tip'    => true,
									'placeholder' => 'E.g: code1, code2, ..',
									'description' => __( 'If you have a list of voucher codes you can copy and paste them into this option. Make sure, that they are comma separated.', 'pdf-product-vouchers-for-woocommerce' ),
								)
							);
							$url_view_voucher = admin_url( 'edit.php?post_type=yeepdf_vc_order&product_id='.$post_id );
							?>
							<p class="form-field">
								<label><?php esc_html_e("Unredeemed Voucher Code","pdf-product-vouchers-for-woocommerce") ?></label>
								<a href="<?php echo esc_url($url_view_voucher) ?>" target="_blank" class="button button-primary"><?php esc_html_e("Unredeemed Voucher Code","pdf-product-vouchers-for-woocommerce") ?></a>
							</p>
							<p class="form-field">
								<label><?php esc_html_e("Redeemed Voucher Code","pdf-product-vouchers-for-woocommerce") ?></label>
								<a href="<?php echo esc_url($url_view_voucher."&type=redeemed") ?>" target="_blank" class="button button-primary"><?php esc_html_e("Redeemed Voucher Code","pdf-product-vouchers-for-woocommerce") ?></a>
							</p>
							<p class="form-field">
								<label><?php esc_html_e("Expired Voucher Code","pdf-product-vouchers-for-woocommerce") ?></label>
								<a href="<?php echo esc_url($url_view_voucher."&type=expired") ?>" target="_blank" class="button button-primary"><?php esc_html_e("Expired Voucher Code","pdf-product-vouchers-for-woocommerce") ?></a>
							</p>
							<?php
							woocommerce_wp_select(
								array(
									'wrapper_class'          => '_yeepdf_product_vouchers-expiration_type',
									'id'          => '_yeepdf_product_vouchers[expiration_type]',
									'value'       =>  $meta_data_pdf["expiration_type"],
									'options'     =>  array(
										"specific_time"=>"Specific Time",
										"purchase"=>"Based on Purchase"),
									'label'       => __("Expiration Date Type","pdf-product-vouchers-for-woocommerce"),
									'description' => __( 'Please select expiration date type either a Specific Time, Based on Purchased voucher date or Based on Recipient Gift Date like after 7 days, 30 days, 365 days etc.', 'pdf-product-vouchers-for-woocommerce' ),
								)
							);
							$class_specific_time ="";
							$class_purchase ="";
							if($meta_data_pdf["expiration_type"] != "specific_time"){
								$class_specific_time = "hidden";
							}
							if($meta_data_pdf["expiration_type"] != "purchase"){
								$class_purchase = "hidden";
							}
							?>
							<p class="form-field field_specific_time <?php echo esc_attr($class_specific_time) ?>">
								<label><?php esc_html_e("Voucher Start Date","pdf-product-vouchers-for-woocommerce") ?></label>
								<input type="datetime-local" name="_yeepdf_product_vouchers[voucher_start_date]" id="_yeepdf_product_vouchers-start_date" value="<?php echo esc_attr($meta_data_pdf["voucher_start_date"],) ?>" >
								<span class="woocommerce-help-tip" tabindex="0" aria-label="<?php esc_html_e("If you want to make the product valid for a specific time only, you can enter an start date here.","pdf-product-vouchers-for-woocommerce") ?>"></span>
							</p>
							<p class="form-field field_specific_time <?php echo esc_attr($class_specific_time) ?>">
							<p class="form-field field_specific_time <?php echo esc_attr($class_specific_time) ?>">
								<label><?php esc_html_e("Voucher Expiration Date","pdf-product-vouchers-for-woocommerce") ?></label>
								<input type="datetime-local" name="_yeepdf_product_vouchers[voucher_expiration_date]" id="_yeepdf_product_vouchers-voucher_expiration_date" value="<?php echo esc_attr($meta_data_pdf["voucher_expiration_date"],) ?>" >
								<span class="woocommerce-help-tip" tabindex="0" aria-label="<?php esc_html_e("If you want to make the product valid for a specific time only, you can enter an start date here.","pdf-product-vouchers-for-woocommerce") ?>"></span>
							</p>
							<?php
							woocommerce_wp_text_input(
								array(
									'wrapper_class'          => '_yeepdf_product_vouchers-expiration_days '.$class_purchase,
									'id'          => '_yeepdf_product_vouchers[expiration_days]',
									'value'       =>  $meta_data_pdf["expiration_days"],
									'label'       => __("Expiration Days","pdf-product-vouchers-for-woocommerce"),
									'description' => __( 'After Purchase', 'pdf-product-vouchers-for-woocommerce' ),
								)
							);
						?>
					</div>
				</div>
				<?php do_action( "yeeaddons_product_vouchers_options", $arg = '' ) ?>
			</div>
		</div>
		<?php
	}
}
new Yeeaddons_Woo_PDF_Product_Settings;