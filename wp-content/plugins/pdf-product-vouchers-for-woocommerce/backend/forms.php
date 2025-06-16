<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Settings_Forms{
	function __construct(){
		add_action("yeeaddons_product_vouchers_options",array($this,"add_form"));
		add_action( 'woocommerce_admin_process_product_object', array($this,"woocommerce_admin_process_product_object") );
	}
	function woocommerce_admin_process_product_object($product){
		if ( isset( $_POST['_yeepdf_voucher_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yeepdf_voucher_nonce'] ) ) , '_yeepdf_voucher_nonce' ) ) {
			if(isset($_POST['_yeepdf_product_vouchers_forms'])){
				$sanitized_forms = array();
				foreach ( $_POST['_yeepdf_product_vouchers_forms'] as $index => $field ) {
			        $sanitized_forms[$index] = array(
				            'type'     => isset($field['type'])     ? sanitize_text_field(wp_unslash($field['type']))     : 'text',
				            'label'    => isset($field['label'])    ? sanitize_text_field(wp_unslash($field['label']))    : '',
				            'default'  => isset($field['default'])  ? sanitize_textarea_field(wp_unslash($field['default']))  : '',
				            'name'     => isset($field['name'])     ? sanitize_text_field(wp_unslash($field['name']))     : '',
				            'required' => isset($field['required']) ? "yes"  								  : "",
				        );
				}
				$product->update_meta_data( '_yeepdf_product_vouchers_forms', $sanitized_forms);
			}
		}
	}
	function add_form(){
		global $post, $product_object;
		?>
		<div class="pdf_vou_voucher_tab pdf_vou_voucher_tab_forms hidden">
			<div class="pdf_vou_voucher_forms_container">
				<div class="pdf_vou_voucher_forms_container_data yeepdf-admin-table-sort">
				<?php
				$forms_fields = $product_object->get_meta( '_yeepdf_product_vouchers_forms', true );
				if(!is_array($forms_fields)){
					$forms_fields = array();
				}
				$i=0;
				foreach($forms_fields as $field){
					$label ="";
					$default ="";
					$name ="change_name";
					$type ="text";
					if(isset($field["label"])){
						$label = $field["label"];
					}
					if(isset($field["default"])){
						$default = $field["default"];
					}
					if(isset($field["name"])){
						$name = $field["name"];
					}
					if(isset($field["type"])){
						$type = $field["type"];
					}
				?>
				<div class="wc-metaboxes ui-sortable yeepdf-admin-table">
					<div class="woocommerce_attribute wc-metabox postbox closed" rel="0">
						<h3>
							<div class="handlediv" title="Click to toggle"></div>
							<div class="tips sort" data-tip="Drag and drop to set admin attribute order"></div>
							<a href="#" class="remove_row delete"><?php esc_html_e("Remove","pdf-product-vouchers-for-woocommerce") ?></a>
							<strong class="attribute_name placeholder"><?php echo esc_html($label) ?></strong>
						</h3>
						<div class="woocommerce_attribute_data wc-metabox-content ">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<label><?php esc_html_e("Type","pdf-product-vouchers-for-woocommerce") ?></label>
										<select class="yeepdf_attribute_name" name="_yeepdf_product_vouchers_forms[<?php echo esc_attr($i) ?>][type]">
											<option <?php selected( $type, "text") ?> value="text"><?php esc_html_e("Text","pdf-product-vouchers-for-woocommerce") ?></option>
											<option <?php selected( $type, "email") ?> value="email"><?php esc_html_e("Email","pdf-product-vouchers-for-woocommerce") ?></option>
											<option <?php selected( $type, "number") ?> value="number"><?php esc_html_e("Number","pdf-product-vouchers-for-woocommerce") ?></option>
											<option <?php selected( $type, "date") ?> value="date"><?php esc_html_e("Date","pdf-product-vouchers-for-woocommerce") ?></option>
											<option <?php selected( $type, "textarea") ?> value="textarea"><?php esc_html_e("Textarea","pdf-product-vouchers-for-woocommerce") ?></option>
											<option <?php selected( $type, "select") ?> value="select"><?php esc_html_e("Select","pdf-product-vouchers-for-woocommerce") ?></option>
											<option <?php selected( $type, "radio") ?> value="radio"><?php esc_html_e("Radio","pdf-product-vouchers-for-woocommerce") ?></option>
											<option <?php selected( $type, "checkbox") ?> value="checkbox"><?php esc_html_e("Checkbox","pdf-product-vouchers-for-woocommerce") ?></option>
										</select>
									</td>
									<td>
										<label><?php esc_html_e("Name *","pdf-product-vouchers-for-woocommerce") ?></label>
										<input type="text" class="yeepdf_attribute_name" name="_yeepdf_product_vouchers_forms[<?php echo esc_attr($i) ?>][name]" value="<?php echo esc_attr($name) ?>" placeholder="Required *">
									</td>
								</tr>
								<tr>
									<td class="attribute_name">
										<label><?php esc_html_e("Label","pdf-product-vouchers-for-woocommerce") ?>:</label>
												<input type="text" class="yeepdf_attribute_name" name="_yeepdf_product_vouchers_forms[<?php echo esc_attr($i) ?>][label]" value="<?php echo esc_attr($label) ?>">
									</td>
									<td rowspan="3">
										<label><?php esc_html_e( "Value", "pdf-product-vouchers-for-woocommerce" )?>:</label>
										<textarea name="_yeepdf_product_vouchers_forms[<?php echo esc_attr($i) ?>][default]" cols="5" rows="5"><?php echo esc_attr($default) ?></textarea>
									</td>
								</tr>
								<tr>
									<td>
										<?php 
										$checked ="";
										if(isset($field["required"]) && $field["required"] == "yes"){
											$checked = "checked";
										}
										?>
										<label><input type="checkbox" class="woocommerce_attribute_visible_on_product_page checkbox" <?php echo esc_attr($checked) ?> name="_yeepdf_product_vouchers_forms[<?php echo esc_attr($i) ?>][required]" value="yes"> <?php esc_html_e("Required","pdf-product-vouchers-for-woocommerce") ?></label>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<?php 
				$i++;
				} ?>
				</div>
				<p class="form-field">
					<div class="hidden" id="_yeepdf_product_vouchers_forms_data">
						<div class="wc-metaboxes ui-sortable">
							<div class="woocommerce_attribute wc-metabox postbox open" rel="0">
								<h3>
									<div class="handlediv" title="Click to toggle"></div>
									<div class="tips sort" data-tip="Drag and drop to set admin attribute order"></div>
									<a href="#" class="remove_row delete">Remove</a>
									<strong class="attribute_name placeholder"><?php esc_html_e("Label","pdf-product-vouchers-for-woocommerce") ?></strong>
								</h3>
								<div class="woocommerce_attribute_data wc-metabox-content">
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td>
												<label><?php esc_html_e("Type","pdf-product-vouchers-for-woocommerce") ?></label>
												<select class="yeepdf_attribute_name yeepdf_attribute_name_update" name="yeepdf_change_name[type]">
													<option selected="selected" value="text"><?php esc_html_e("Text","pdf-product-vouchers-for-woocommerce") ?></option>
													<option value="number"><?php esc_html_e("Number","pdf-product-vouchers-for-woocommerce") ?></option>
													<option value="email"><?php esc_html_e("Email","pdf-product-vouchers-for-woocommerce") ?></option>
													<option value="date"><?php esc_html_e("Date","pdf-product-vouchers-for-woocommerce") ?></option>
													<option value="textarea"><?php esc_html_e("Textarea","pdf-product-vouchers-for-woocommerce") ?></option>
													<option value="select"><?php esc_html_e("Select","pdf-product-vouchers-for-woocommerce") ?></option>
													<option value="radio"><?php esc_html_e("Radio","pdf-product-vouchers-for-woocommerce") ?></option>
													<option value="checkbox"><?php esc_html_e("Checkbox","pdf-product-vouchers-for-woocommerce") ?></option>
												</select>
											</td>
											<td>
												<label><?php esc_html_e("Name","pdf-product-vouchers-for-woocommerce") ?></label>
												<input type="text" class="yeepdf_attribute_name yeepdf_attribute_name_update" name="yeepdf_change_name[name]" value="yeepdf_change_rand_name" placeholder="Required *">
											</td>
										</tr>
										<tr>
											<td class="attribute_name">
												<label><?php esc_html_e("Label","pdf-product-vouchers-for-woocommerce") ?>:</label>
																<input type="text" class="yeepdf_attribute_name yeepdf_attribute_name_update" name="yeepdf_change_name[label]" value="Label">
											</td>
											<td rowspan="3">
												<label><?php esc_html_e("Default","pdf-product-vouchers-for-woocommerce") ?>:</label>
												<textarea name="yeepdf_change_name[default]" class ="yeepdf_attribute_name_update" cols="5" rows="5"></textarea>
											</td>
										</tr>
										<tr>
											<td>
												<?php 
												?>
												<label><input type="checkbox" class="woocommerce_attribute_visible_on_product_page checkbox yeepdf_attribute_name_update" name="yeepdf_change_name[required]" value="yes"> <?php esc_html_e("Required","pdf-product-vouchers-for-woocommerce") ?></label>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<a href="#" class="yeepdf_voucher_add_field button button-primary"><?php esc_html_e("Add Field","pdf-product-vouchers-for-woocommerce") ?></a>
				</p>
			</div>
		</div>
		<?php
	}
}
new Yeeaddons_Woo_PDF_Product_Settings_Forms;