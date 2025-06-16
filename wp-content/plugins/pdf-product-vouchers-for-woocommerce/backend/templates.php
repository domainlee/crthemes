<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Settings_Templates{
	function __construct(){
		add_action("yeeaddons_product_vouchers_options",array($this,"add_form"));
		add_action( 'woocommerce_admin_process_product_object', array($this,"woocommerce_admin_process_product_object") );
	}
	function woocommerce_admin_process_product_object($product){
		if ( isset( $_POST['_yeepdf_voucher_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yeepdf_voucher_nonce'] ) ) , '_yeepdf_voucher_nonce' ) ) {
			if(isset($_POST['_yeepdf_product_vouchers_templates'])){
				$form_data_forms = map_deep( $_POST['_yeepdf_product_vouchers_templates'], 'sanitize_text_field' );
				$form_data_forms = map_deep( $form_data_forms, 'wp_unslash' );
				$product->update_meta_data( '_yeepdf_product_vouchers_templates', $form_data_forms);
			}
		}
	}
	function add_form(){
		global $post, $product_object;
		$list_tempates = array();
        $args = array(
            'numberposts' => -1,
            'post_type'   => 'yeepdf'
        );
        $post_list = get_posts( $args );
        foreach ( $post_list as $post ) {
            $list_tempates[$post->ID] = $post->post_title;
        }
		?>
		<div class="pdf_vou_voucher_tab pdf_vou_voucher_tab_template hidden">
			<div class="pdf_vou_voucher_forms_container">
				<div class="pdf_vou_voucher_template_container_data yeepdf-admin-table-sort">
				<?php
				$forms_fields = $product_object->get_meta( '_yeepdf_product_vouchers_templates', true );
				if(!is_array($forms_fields) || count($forms_fields)<1){
					$template_id = get_option( "yeepdf_voucher_setup" );
					$forms_fields = array(array("template"=>$template_id,"image"=>YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL."frontend/libs/template-default.png","label"=>"Template"));
				}
				$i=0;
				foreach($forms_fields as $field){
					$template ="";
					$image ="";
					$label ="";
					if(isset($field["template"])){
						$template = $field["template"];
					}
					if(isset($field["image"])){
						$image = $field["image"];
					}
					if(isset($field["label"])){
						$label = $field["label"];
					}
				?>
				<div class="wc-metaboxes ui-sortable yeepdf-admin-table">
					<div class="woocommerce_attribute wc-metabox postbox closed" rel="0">
						<h3>
							<div class="handlediv" title="Click to toggle"></div>
							<div class="tips sort" data-tip="Drag and drop to set admin attribute order"></div>
							<a href="#" class="remove_row delete">Remove</a>
							<strong class="attribute_name placeholder"><?php echo esc_html($label) ?></strong>
						</h3>
						<div class="woocommerce_attribute_data wc-metabox-content ">
							<table cellpadding="0" cellspacing="0">
										<tr>
											<td>
												<label><?php esc_html_e("Template","pdf-product-vouchers-for-woocommerce") ?></label>
												<select class="yeepdf_attribute_name" name="_yeepdf_product_vouchers_templates[<?php echo esc_attr($i) ?>][template]">
													<?php foreach ($list_tempates as $id => $title) {
														?>
														<option <?php selected($id,$template) ?> value="<?php echo esc_attr($id) ?>"><?php echo esc_html($title) ?></option>
														<?php
													} ?>
												</select>
											</td>
											<td>
												<label><?php esc_html_e("Image URL","pdf-product-vouchers-for-woocommerce") ?></label>
												<input type="text" class="yeepdf_attribute_name" name="_yeepdf_product_vouchers_templates[<?php echo esc_attr($i) ?>][image]" value="<?php echo esc_url( $image) ?>" placeholder="">
											</td>
										</tr>
										<tr>
											<td class="attribute_name">
												<label><?php esc_html_e("Label","pdf-product-vouchers-for-woocommerce") ?>:</label>
																<input type="text" class="yeepdf_attribute_name" name="_yeepdf_product_vouchers_templates[<?php echo esc_attr($i) ?>][label]" value="<?php echo esc_attr( $label) ?>">
											</td>
											<td rowspan="3">
											</td>
										</tr>
									</table>
						</div>
					</div>
				</div>
				<?php 
				$i++;
				} ?>
				</div>
				<div class="form-field">
					<div class="hidden" id="_yeepdf_product_vouchers_templates_data">
						<div class="wc-metaboxes ui-sortable">
							<div class="woocommerce_attribute wc-metabox postbox open" rel="0">
								<h3>
									<div class="handlediv" title="Click to toggle"></div>
									<div class="tips sort" data-tip="Drag and drop to set admin attribute order"></div>
									<a href="#" class="remove_row delete"><?php esc_html_e( "Remove", "pdf-product-vouchers-for-woocommerce") ?></a>
									<strong class="attribute_name placeholder"><?php esc_html_e("Label","pdf-product-vouchers-for-woocommerce") ?></strong>
								</h3>
								<div class="woocommerce_attribute_data wc-metabox-content">
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td>
												<label><?php esc_html_e("Template","pdf-product-vouchers-for-woocommerce") ?></label>
												<select class="yeepdf_attribute_name yeepdf_attribute_name_update" name="yeepdf_template_change_name[template]">
													<?php 
													$forms_fields_demo = $forms_fields[0];
													foreach ($list_tempates as $id => $title) {
														?>
														<option <?php selected($id,$forms_fields_demo["template"]) ?> value="<?php echo esc_attr($id) ?>"><?php echo esc_html($title) ?></option>
														<?php
													} ?>
												</select>
											</td>
											<td>
												<label><?php esc_html_e("Image URL","pdf-product-vouchers-for-woocommerce") ?></label>
												<input type="text" class="yeepdf_attribute_name yeepdf_attribute_name_update" name="yeepdf_template_change_name[image]" value="<?php echo esc_url($forms_fields_demo["image"]) ?>" placeholder="">
											</td>
										</tr>
										<tr>
											<td class="attribute_name">
												<label><?php esc_html_e("Label","pdf-product-vouchers-for-woocommerce") ?>:</label>
																<input type="text" class="yeepdf_attribute_name yeepdf_attribute_name_update" name="yeepdf_template_change_name[label]" value="<?php echo esc_html($forms_fields_demo["label"]) ?>">
											</td>
											<td rowspan="3">
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<a href="#" class="yeepdf_voucher_add_template button button-primary"><?php esc_html_e("Add template","pdf-product-vouchers-for-woocommerce") ?></a>
				</div>
			</div>
		</div>
		<?php
	}
}
new Yeeaddons_Woo_PDF_Product_Settings_Templates;