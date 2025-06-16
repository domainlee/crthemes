<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Voucher_Menu{
		function __construct(){
			add_action( 'init', array($this,'create_posttype') );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ),10,2 );
			add_action( 'save_post_yeepdf_vc_order',array( $this, 'save_metabox' ), 10, 2 );
			add_filter( 'manage_yeepdf_vc_order_posts_columns', array($this,"set_custom_edit_book_columns") );
			add_action( 'manage_yeepdf_vc_order_posts_custom_column' , array($this,"product_custom_column_values"), 10, 2 );
			add_filter( 'views_edit-yeepdf_vc_order', array($this,"views_yeepdf_vc_order") );
			add_filter( 'pre_get_posts', array($this,"custom_main_query") );
			add_filter( 'bulk_actions-edit-yeepdf_vc_order', array($this,"bulk_actions") );
			add_action( 'manage_posts_extra_tablenav',array($this,"form_check_voucher"));
			add_filter('handle_bulk_actions-edit-yeepdf_vc_order', array($this,"handle_custom_bulk_action"), 10, 3);
			add_action('admin_notices', array($this,'custom_bulk_admin_notice'));
			add_action( "wp_ajax_yeepdf_action_redeeem",array($this,"yeepdf_action_redeeem"),10,3);
			add_action( "wp_ajax_yeepdf_action_add_note",array($this,"yeepdf_action_note"),10,3);
			add_action( "wp_ajax_yeepdf_action_delete_note",array($this,"yeepdf_action_delete_note"),10,3);
			add_action( "admin_footer",array($this,"add_form_action_redeeem"),10,2);
			add_action( 'admin_menu', array($this,"yee_add_menu_badge_to_cpt") );
		}
		function yee_count_pending_vouchers() {
			$current_datetime = current_time('mysql');
    		$args = array(
		        'post_type'      => 'yeepdf_vc_order',
		        'post_status'    => 'publish',
		        'posts_per_page' => -1,
		        'meta_query'     => array(
									'relation' => 'AND', 
										array(
											'relation' => 'OR', 
											array(
								                'key'     => '_expires',
								                'value'   => $current_datetime,
								                'compare' => '>', 
								                'type'    => 'DATETIME'
								            ),
								            array(
								                'key'     => '_expires',
								                'value'   => "",
								                'compare' => '=', 
								            ),
										),
										array(
											'relation' => 'OR', 
											array(
								                'key'     => '_redeemed',
								                'compare' => 'NOT EXISTS', 
								            ),
								            array(
								                'key'     => '_redeemed',
								                'value'   => '',
								                'compare' => '=', 
								            )
										)
							        )
		    );
		    $query = new WP_Query($args);
		    return $query->found_posts;
		}
		function yee_add_menu_badge_to_cpt(){
			global $submenu;
			$count = $this->yee_count_pending_vouchers() ;
			if ( $count <= 0 || ! isset( $submenu['woocommerce'] ) ) {
		        return;
		    }
			$label = sprintf(
		        'Voucher Codes <span class="update-plugins count-%1$d"><span class="plugin-count">%1$d</span></span>',
		        $count
		    );
		    foreach ( $submenu['woocommerce'] as $index => $item ) {
		    	//var_dump($item);
		        if ( strpos( $item[2], 'edit.php?post_type=yeepdf_vc_order' ) !== false ) {
		           $submenu['woocommerce'][$index][0] = $label;
		            break;
		        }
		    }
		}
		function add_form_action_redeeem(){
			?>
			<div id="yeepdf-action-redeeem-container" class="hidden">
				<input type="hidden" id="yeepdf_voucher_product_id">
				<input type="hidden" id="yeepdf_voucher_voucher_id">
				<table class="form-table yepdf-voucher-voucher-redeem-table">
					<tr><td colspan="2" class="yeepdf-voucher-code-success"><?php esc_html_e("This voucher code has been bought for ","pdf-product-vouchers-for-woocommerce") ?><strong class="yeepdf_redeem_product_name"></strong>. 
					<?php esc_html_e("If you would like to redeem voucher code, Please click on the redeem button below:","pdf-product-vouchers-for-woocommerce") ?></td></tr>
					<tr>
						<th scope="col" class="woo-vou-field-title"><?php esc_html_e("Voucher Code","pdf-product-vouchers-for-woocommerce") ?></th>
						<td>
							<strong class="yeepdf_redeem_voucher_code"></strong>
						</td>
					</tr>
					<tr>
						<th scope="col" class="woo-vou-field-title"><?php esc_html_e("Price","pdf-product-vouchers-for-woocommerce") ?></th>
						<td>
							<strong class="yeepdf_redeem_price"></strong>
							<input type="hidden" id="yeepdf_voucher_product_amount">
						</td>
					</tr>
					<tr class="hidden yeepdf-ajax-done">
						<th scope="col" class="woo-vou-field-title"><?php esc_html_e("Status","pdf-product-vouchers-for-woocommerce") ?></th>
						<td>
							<div class="yeepdf-ajax-done-message"></div>
						</td>
					</tr>
					<tr class="yeepdf_redeem-btn-ok">
						<th scope="col" class="woo-vou-field-title"></th>
						<td>
							<a data-type="redeem" class="yeepdf-action-redeeem-check button-primary" href="#"><?php esc_html_e("Redeem","pdf-product-vouchers-for-woocommerce") ?>
						</td>
					</tr>
				</table>
			</div>
			<?php
		}
		function yeepdf_action_redeeem(){
	        check_ajax_referer( 'yeepdf_order_voucher', 'security' );
	        if(!isset($_POST['id'])){
	        	die();
	        }	
	        $post_id = sanitize_text_field(wp_unslash($_POST["id"]));
	        if ( ! current_user_can( 'edit_post', $post_id ) ) {
				die();
			}
			$type = sanitize_text_field(wp_unslash($_POST["type"]));
			$price = sanitize_text_field(wp_unslash($_POST["price"]));
			$max_price = get_post_meta($post_id,"_price",true);
			$user_id = get_current_user_id();
			$redeemed_type = "offline";
			$product_id = sanitize_text_field(wp_unslash($_POST["product_id"]));
			if($type == "redeem"){
				if($price <= 0 ){
					wp_send_json(array("status"=>"error","message"=>"Price > 0"));
					die();
				}
				$partial_redemption = get_post_meta($post_id,"_partial_redemption",true);
				$my_post = array(
				'post_title'    => "Redeemed Product ID #".$product_id,
				'post_content'  => "",
				'post_status'   => 'publish',
				'post_author'   => $user_id,
				'post_type' =>  "yeepdf_redeem"
				);
				if($partial_redemption != "yes"){
					$balance = 0;
					$redeemed_method = "Full";
					$redeemed_id = wp_insert_post($my_post);
					//Chỉ sử dụng 1 lần nên dùng hết price
					$price = $max_price;
					$status ="ok";
					$message = esc_html__("You have successfully redeemed and used up all remaining funds.","pdf-product-vouchers-for-woocommerce");
					update_post_meta($post_id, '_redeemed', 'redeemed'); 
				}else{
					//1 phần
					$redeemed_method = "Partial";
					$tota_price_used = 0;
					$redeems = get_posts(array("post_type"=>"yeepdf_redeem","meta_key"=>"_voucher_id","meta_value"=>$post_id,"numberposts"=>-1));
					foreach($redeems as $redeem_data){
						$tota_price_used += get_post_meta($redeem_data->ID,"_redeemed_amount",true);
					}
					$balance = $max_price - $tota_price_used;
					if($balance < $price) {
						$balance = wc_price($balance);
						$status ="error";
						$message = esc_html__("The remaining amount is ","pdf-product-vouchers-for-woocommerce").$balance;
					}elseif($balance == $price){
						$redeemed_id = wp_insert_post($my_post);
						update_post_meta($post_id, '_redeemed', 'redeemed'); 
						$status ="ok";
						$message = esc_html__("You have successfully redeemed and used up all remaining funds.","pdf-product-vouchers-for-woocommerce");
					}
					else{
						$redeemed_id = wp_insert_post($my_post);
						$status ="ok";
						$balance_1 = $balance - $price;
						$message = sprintf( esc_html__("You have used %s and have %s left.","pdf-product-vouchers-for-woocommerce"),$price,$balance_1);
						if($balance_1 <= 0){
							update_post_meta($post_id, '_redeemed', 'redeemed'); 
						}
					}
				}
				if(isset($redeemed_id) && $redeemed_id > 0){
					update_post_meta( $redeemed_id, '_redeemed_amount', $price );
					update_post_meta( $redeemed_id, '_redeemed_type', $redeemed_type );
					update_post_meta( $redeemed_id, '_voucher_id', $post_id );
				}
			}else{
				update_post_meta($post_id, '_redeemed', ''); 
				$status ="ok";
				$message = esc_html__("Undeemed success","pdf-product-vouchers-for-woocommerce");
			}
			if($status == "ok"){
				$user_info = get_userdata($user_id);
				if ($user_info) {
				    $user_info = $user_info->display_name;
				}
				$datas_redeem = array(
					"voucher_id"=>$post_id,
					"redeemed_amount"=>wc_price($price),
					"redeemed_id"=>$redeemed_id,
					"redeemed_type"=>$redeemed_type,
					"redeemed_balance"=>wc_price($balance),
					"redeemed_method"=>$redeemed_method,
					"user_redeem"=>$user_info,
					"date"=>current_time( get_option('date_format') . ' ' . get_option('time_format') ),
				);
				do_action( 'yeepdf_redeem_email_notification', $datas_redeem);
			}
			//wp_mail("nhtfacebook@gmail.com","dd",$status);
			wp_send_json(array("status"=>$status,"message"=>$message));
			die();
	   }
	   function yeepdf_action_note(){
	        check_ajax_referer( 'yeepdf_order_voucher', 'security' );
	        if(!isset($_POST['id'])){
	        	die();
	        }	
	        $post_id = sanitize_text_field(wp_unslash($_POST["id"]));
	        if ( ! current_user_can( 'edit_post', $post_id ) ) {
				die();
			}
			$note = sanitize_textarea_field(wp_unslash($_POST["note"]));
			$current_timestamp = current_time('timestamp');
			$current_user = wp_get_current_user();
			$my_post = array(
				'post_title'    => "Note ".$current_timestamp,
				'post_content'  => "",
				'post_status'   => 'private',
				'post_author'   => $current_user->ID,
				'post_type' =>  "yeepdf_vc_notes",
				'post_content' => $note
				);
			$note_id =wp_insert_post($my_post);
			update_post_meta( $note_id, '_voucher_id', $post_id );
			wp_send_json(array("status"=>"ok","id"=>$note_id));
			die();
	   }
	   function yeepdf_action_delete_note(){
	        check_ajax_referer( 'yeepdf_order_voucher', 'security' );
	        if(!isset($_POST['id'])){
	        	die();
	        }	
	        $post_id = sanitize_text_field(wp_unslash($_POST["id"]));
	        if ( ! current_user_can( 'edit_post', $post_id ) ) {
				die();
			}
			wp_delete_post($post_id);
			die();
	   }
		function handle_custom_bulk_action($redirect_url, $action, $post_ids) {
		    if ($action === 'redeemed') {
		        foreach ($post_ids as $post_id) {
		        	$current_datetime = current_time('mysql'); 
							update_post_meta($post_id, '_redeemed_date', $current_datetime); 
		          update_post_meta($post_id, '_redeemed', 'redeemed'); 
		        }
		        $redirect_url = add_query_arg('bulk_redeemed', count($post_ids), $redirect_url);
		    }
		    if ($action === 'unredeemed') {
		        foreach ($post_ids as $post_id) {
		            update_post_meta($post_id, '_redeemed', ''); 
		        }
		        $redirect_url = add_query_arg('bulk_unredeemed', count($post_ids), $redirect_url);
		    }
		    return $redirect_url;
		}
		function custom_bulk_admin_notice(){
			if (!empty($_GET['bulk_redeemed'])) {
		        $count = sanitize_text_field(intval($_GET['bulk_redeemed']));
		        ?>
		        <div class='updated notice'><p><?php echo esc_html($count) ?> <?php esc_html_e(" Redeemed Codes","pdf-product-vouchers-for-woocommerce") ?></p></div>
		        <?php
		    }
		    if (!empty($_GET['bulk_unredeemed'])) {
		        $count = sanitize_text_field(intval($_GET['bulk_unredeemed']));
		        ?>
		        <div class='updated notice'><p><?php echo esc_html($count) ?> <?php esc_html_e(" Unredeemed Codes","pdf-product-vouchers-for-woocommerce") ?></p></div>
		        <?php
		    }
		}
		function form_check_voucher($which){
			global $typenow;
			if ($typenow === 'yeepdf_vc_order' && $which === 'top') {
				$codes = "";
				if(isset($_GET["check_voucher_codes"])){
					$codes = sanitize_textarea_field(wp_unslash($_GET["check_voucher_codes"]));
				}
		        ?>
		        <div class="alignleft actions">
		        	<?php wp_nonce_field("yeepdf_order_voucher","yeepdf_order_voucher") ?>
		        	<textarea class="check_voucher_codes" name="check_voucher_codes" placeholder="<?php esc_attr_e("code1, code2, ...","pdf-product-vouchers-for-woocommerce") ?>" ><?php echo esc_attr($codes) ?></textarea>
		            <input type="submit" name="check_code" class="button" value=" <?php esc_html_e('Check codes', "pdf-product-vouchers-for-woocommerce"); ?>">
		        </div>
		        <?php
		    }
		}
		function bulk_actions($actions){
			$actions["redeemed"] = esc_html__("Redeemed","pdf-product-vouchers-for-woocommerce");
			$actions["unredeemed"] = esc_html__("Unredeemed","pdf-product-vouchers-for-woocommerce");
			return $actions;
		}
		function custom_main_query($query){
			if ( is_admin() && $query->is_main_query() ) {
				if (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == "yeepdf_vc_order") {
					if ( $query->is_search() && !isset($_GET['check_code']) ) {
						//seach voucher
						$meta_value = sanitize_text_field(get_query_var('s'));
						$query->set('meta_query', array(
				            'relation' => 'OR',
				            array(
				                'key'     => '_code',
				                'value'   => $meta_value,
                				'compare' => 'LIKE'
				            ),
				            array(
				                'key'     => '_coupon_code',
				                'value'   => $meta_value,
                				'compare' => 'LIKE'
				            ),
				            array(
				                'key'     => '_forms',
				                'value'   => $meta_value,
                				'compare' => 'LIKE'
				            ),
				            array(
				                'key'     => '_product_id',
				                'value'   => $meta_value,
                				'compare' => '='
				            ),
				            array(
				                'key'     => '_order_id',
				                'value'   => $meta_value,
                				'compare' => '='
				            ),
				        ));
				        $query->set('s', '');
					}elseif ( isset($_GET['check_voucher_codes']) && $_GET['check_voucher_codes'] !="") {
						// check code
						$query->set('s', '');
						$code = sanitize_textarea_field(wp_unslash($_GET["check_voucher_codes"]));
						$code = trim($code);
						$codes = explode(",", $code);
						$uppercasecodes = array_map('strtoupper', $codes);
						$uppercasecodes =  array_map('trim', $uppercasecodes);
						$olowecasecodes = array_map('strtolower', $uppercasecodes);
						$codes = array_merge($uppercasecodes,$olowecasecodes);
						$query->set('meta_query', array(
				            array(
				                'key'     => '_code',
				                'value'   => $codes,
                				'compare' => 'IN'
				            ),
				        ));
					}else{
						$query->set('s', '');
						$type = "";
						$current_datetime = current_time('mysql'); 
						if(isset($_GET["type"])){
							$type = sanitize_text_field(wp_unslash($_GET['type']));
						}
						if($type == ""){
							//unredeem
							//remove expired
							if(isset($_GET["product_id"])){
								$product_id = sanitize_text_field(wp_unslash($_GET['product_id']));
								$query->set('meta_query', array(
									'relation' => 'AND', 
										array(
											'relation' => 'OR',
											array(
								                'key'     => '_expires',
								                'value'   => $current_datetime,
								                'compare' => '>', 
								                'type'    => 'DATETIME'
								            ),
								            array(
								                'key'     => '_expires',
								                'value'   => "",
								                'compare' => '=', 
								            ),
										),
							            array(
							                'key'     => '_product_id',
							                'value'   => $product_id,
							                'compare' => '=', 
							            ),
							        ));
							}else{
								//default
								$query->set('meta_query', array(
									'relation' => 'AND', 
										array(
											'relation' => 'OR', 
											array(
								                'key'     => '_expires',
								                'value'   => $current_datetime,
								                'compare' => '>', 
								                'type'    => 'DATETIME'
								            ),
								            array(
								                'key'     => '_expires',
								                'value'   => "",
								                'compare' => '=', 
								            ),
										),
										array(
											'relation' => 'OR', 
											array(
								                'key'     => '_redeemed',
								                'compare' => 'NOT EXISTS', 
								            ),
								            array(
								                'key'     => '_redeemed',
								                'value'   => '',
								                'compare' => '=', 
								            )
										)
							        ));
							}
						} elseif($type == "redeemed"){
							if(isset($_GET["product_id"])){
								$product_id = sanitize_text_field(wp_unslash($_GET['product_id']));
								$query->set('meta_query', array(
									'relation' => 'AND', 
						            	array(
							                'key'   => '_redeemed', 
							                'value' => $type,    
							                'compare' => '='    
							            ),
							            array(
							                'key'     => '_product_id',
							                'value'   => $product_id,
							                'compare' => '=', 
							            ),
							        ));
							}else{
								$query->set('meta_query', array(
									'relation' => 'AND', 
						            	array(
							                'key'   => '_redeemed', 
							                'value' => $type,    
							                'compare' => '='    
							            ),
							        ));
							}
						} elseif ($type == "expired") {
							//list expired and 
							if(isset($_GET["product_id"])){
								$product_id = sanitize_text_field(wp_unslash($_GET['product_id']));
								$query->set('meta_query', array(
						        	'relation' => 'AND', 
						            array(
						                'key'     => '_expires',
						                'value'   => $current_datetime,
						                'compare' => '<', 
						                'type'    => 'DATETIME'
						            ),
						            array(
						                'key'   => '_redeemed', 
						                'value' => '',    
						                'compare' => '='    
						            ),
						            array(
							                'key'     => '_product_id',
							                'value'   => $product_id,
							                'compare' => '=', 
							            ),
						        ));
							}else{
								$query->set('meta_query', array(
						        	'relation' => 'AND', 
						            array(
						                'key'     => '_expires',
						                'value'   => $current_datetime,
						                'compare' => '<', 
						                'type'    => 'DATETIME'
						            ),
						            array(
						                'key'   => '_redeemed', 
						                'value' => '',    
						                'compare' => '='    
						            ),
						        ));
							}
						}
					}
				}
			}
			return $query;
		}
		function views_yeepdf_vc_order($views){
			$unredeemed = "current";
			$redeemed = "";
			$expired = "";
			if(isset($_GET["type"])){
				$type = sanitize_text_field(wp_unslash($_GET["type"]));
				$unredeemed = "";
				if($type == "redeemed"){
					$redeemed ="current";
				}else{
					$expired = "current";
				}
			}
			$new_views = array();
			$class = "";
			$url = admin_url("edit.php?post_type=yeepdf_vc_order");
			$new_views["unredeemed"] = '<a href="'.$url.'" class="'.$unredeemed.'">'.esc_html__("Unredeemed Voucher Codes","pdf-product-vouchers-for-woocommerce")."</a>";
			$new_views["redeemed"] = '<a href="'.$url.'&type=redeemed" class="'.$redeemed.'">'.esc_html__("Redeemed Voucher Codes","pdf-product-vouchers-for-woocommerce").'</a>';
			$new_views["expired"] = '<a href="'.$url.'&type=expired" class="'.$expired.'">'.esc_html__("Expired Voucher Codes","pdf-product-vouchers-for-woocommerce").'</a>';
			if(isset($views["trash"])){
				$new_views["trash"] = $views["trash"];
			}
			return $new_views;
		}
		function set_custom_edit_book_columns($columns){
			unset( $columns['date'] );
			unset( $columns['title'] );
			$columns['title'] = __( 'Voucher Code', "pdf-product-vouchers-for-woocommerce" );
			$columns['product_if'] = __( 'Product Information', "pdf-product-vouchers-for-woocommerce" );
			$columns['buyer_if'] = __( "Buyer's Information", "pdf-product-vouchers-for-woocommerce" );
			$columns['order_if'] = __( 'Order Information', "pdf-product-vouchers-for-woocommerce" );
			$columns['actions'] = __( 'Status', "pdf-product-vouchers-for-woocommerce" );
			$columns['date'] = __( 'Date', "pdf-product-vouchers-for-woocommerce" );
			return $columns;
		}
		function product_custom_column_values( $column, $post_id ) {
			$product_id = get_post_meta($post_id,"_product_id",true);
			switch ($column) {
				case 'product_if':
					$price = get_post_meta($post_id,"_price",true);
					?>
					<strong><?php esc_html_e("Product","pdf-product-vouchers-for-woocommerce") ?> </strong> <a href="<?php echo esc_url(get_edit_post_link($product_id)) ?>">#<?php echo esc_attr($product_id) ?> - <?php echo esc_html(get_the_title( $product_id )) ?></a><br>
					<strong><?php esc_html_e("Price","pdf-product-vouchers-for-woocommerce") ?> </strong> <?php echo esc_attr(strip_tags(wc_price($price))) ?>
					<?php
					break;
				case 'buyer_if':
					$order_id = get_post_meta($post_id,"_order_id",true);
					$order = wc_get_order($order_id);
					?>
					<strong><?php esc_html_e("Name","pdf-product-vouchers-for-woocommerce") ?> </strong> <?php echo esc_html($order->get_formatted_billing_full_name()) ?><br>
					<strong><?php esc_html_e("Email","pdf-product-vouchers-for-woocommerce") ?> </strong> <?php echo esc_html( $order->get_billing_email() )?>
					<?php
					break;
				case 'order_if':
					$order_id = get_post_meta($post_id,"_order_id",true);
					$order = wc_get_order($order_id);
					?>
					<strong><?php esc_html_e("Order ID","pdf-product-vouchers-for-woocommerce") ?> </strong>  <a href="<?php echo esc_url($order->get_view_order_url()) ?>">#<?php echo esc_attr($order_id) ?></a><br>
					<strong><?php esc_html_e("Order Total","pdf-product-vouchers-for-woocommerce") ?> </strong> <?php echo esc_html(strip_tags(wc_price($order->get_total()) ))?>
					<?php
					break;
				case 'actions':
					$redeem = get_post_meta($post_id,"_redeemed",true);
					$redeem_date = get_post_meta($post_id,"_redeemed_date",true);
					$voucher_code = get_post_meta($post_id,"_code",true);
					$voucher_price = get_post_meta($post_id,"_price",true);
					$voucher_price_fm = wc_price($voucher_price);
					$partial_redemption = get_post_meta($post_id,"_partial_redemption",true);
					?>
					<strong>
					<?php
					 if($redeem == ""){
					 		?>
					 		<mark class="yeepdf-status-hold yeepdf-order-status"><span>
					 		<?php
					   		esc_html_e("Unredeemed","pdf-product-vouchers-for-woocommerce");
					   		?>
					   		</span>
					   		</mark>
					   		<br><a data-id="<?php echo esc_attr($post_id) ?>" data-product_id="<?php echo esc_attr($product_id) ?>" data-product_name="<?php echo esc_attr(get_the_title($product_id)) ?>" data-voucher="<?php echo esc_attr($voucher_code) ?>" data-price="<?php echo esc_attr($voucher_price) ?>" data-price_fm="<?php echo esc_attr($voucher_price_fm) ?>"  data-type="redeem" data-partial_redemption="<?php echo esc_attr($partial_redemption) ?>" href="#" class="button yeepdf-action-redeeem"><?php esc_html_e("Redeem","pdf-product-vouchers-for-woocommerce") ?></a>
					   		<?php
					   }else{
					   	?>
					   	<mark class="yeepdf-status-completed yeepdf-order-status">
					   		<span>
						   	<?php
						   	esc_html_e("Redeemed","pdf-product-vouchers-for-woocommerce");
						   	?>	
						   	</span>
					   	</mark>
					   		<br><a data-id="<?php echo esc_attr($post_id) ?>" data-type="unredeem" href="#" class="button yeepdf-action-redeeem-check"><?php esc_html_e("Unredeem","pdf-product-vouchers-for-woocommerce") ?></a>
					   		<?php
					   }
					   ?>
					</strong>
					   <?php
					break;
				case 'product_if':
					$price = get_post_meta($post_id,"_price",true);
					?>
					<strong><?php esc_html_e("Product","pdf-product-vouchers-for-woocommerce") ?> </strong> <a href="<?php echo esc_url(get_edit_post_link($product_id)) ?>">#<?php echo esc_attr($product_id) ?> - <?php echo esc_html(get_the_title( $product_id )) ?></a><br>
					<strong><?php esc_html_e("Price","pdf-product-vouchers-for-woocommerce") ?> </strong> <?php echo esc_attr(strip_tags(wc_price($price))) ?>
					<?php
					break;
			}
		}
	 function create_posttype() {
	    register_post_type( 'yeepdf_vc_order',
	        array(
	            'labels' => array(
	                'name' => esc_html__( 'Voucher Codes',"pdf-product-vouchers-for-woocommerce" ),
	                'singular_name' => esc_html__( 'yeepdf_vc_order',"pdf-product-vouchers-for-woocommerce" ),
	                'edit_item' => esc_html__( 'Edit voucher',"pdf-product-vouchers-for-woocommerce" ),
	                'view_item' => esc_html__( 'View voucher',"pdf-product-vouchers-for-woocommerce" ),
	                'search_items' => esc_html__( 'Seach vouchers',"pdf-product-vouchers-for-woocommerce" ),
	                'not_found' => esc_html__( 'No vouchers found',"pdf-product-vouchers-for-woocommerce" ),
	                'item_updated' => esc_html__( 'Vouchers updated.',"pdf-product-vouchers-for-woocommerce" ),
	            ),
	            'public' => true,
	            'has_archive' => true,
	            'supports'    => array( 'title' ),
	            'show_in_menu' => true,
	            'rewrite' => array('slug' => 'yeepdf'),
	            'show_in_rest' => true,
	            'menu_icon'           => 'dashicons-email',
	            'show_in_menu' => "woocommerce",
	            'exclude_from_search' => true,
	            'publicly_queryable' => false,
	            'query_var'=>false,
	        )
	    );
	}
	function add_meta_boxes( $post_type, $post ) {
			add_meta_box( 
	        'yeepdf_vc_order_voucher_redeem',
	        __( 'Redeemed Information',"pdf-product-vouchers-for-woocommerce" ),
	        array($this,"yeepdf_vc_order_voucher_redeem"),
	        'yeepdf_vc_order',
	        'normal',
	        'high'
	    );
	    add_meta_box( 
	        'yeepdf_vc_order_voucher_info',
	        __( 'Voucher Information',"pdf-product-vouchers-for-woocommerce" ),
	        array($this,"yeepdf_vc_order_voucher_info"),
	        'yeepdf_vc_order',
	        'normal',
	        'default'
	    );
	    add_meta_box( 
	        'yeepdf_vc_order_product_info',
	        __( 'Product Information',"pdf-product-vouchers-for-woocommerce" ),
	        array($this,"yeepdf_vc_order_product_info"),
	        'yeepdf_vc_order',
	        'normal',
	        'default'
	    );
	    add_meta_box( 
	        'yeepdf_vc_order_forms',
	        __( 'Recipients',"pdf-product-vouchers-for-woocommerce" ),
	        array($this,"yeepdf_vc_order_forms"),
	        'yeepdf_vc_order',
	        'normal',
	        'default'
	    );
	    add_meta_box( 
	        'yeepdf_vc_order_buyer_info',
	        esc_html__("Buyer's Information","pdf-product-vouchers-for-woocommerce"),
	        array($this,"yeepdf_vc_order_buyer_info"),
	        'yeepdf_vc_order',
	        'normal',
	        'default'
	    );
	    add_meta_box( 
	        'yeepdf_vc_order_order_info',
	        esc_html__("Order Information","pdf-product-vouchers-for-woocommerce"),
	        array($this,"yeepdf_vc_order_order_info"),
	        'yeepdf_vc_order',
	        'normal',
	        'default'
	    );
	    add_meta_box( 
	        'yeepdf_vc_order_voucher_note',
	        __( 'Voucher Note',"pdf-product-vouchers-for-woocommerce" ),
	        array($this,"yeepdf_vc_order_voucher_note"),
	        'yeepdf_vc_order',
	        'side',
	        'core'
	    );
	}
	function yeepdf_vc_order_voucher_redeem($post){
		$post_id= $post->ID;
		$redeemed = new  WP_Query( array("post_type"=>"yeepdf_redeem","post_per_page" => -1,"meta_key"=>"_voucher_id","meta_value"=>$post_id) );
		//$redeemed = new WP_Query( array("post_type"=>"yeepdf_redeem") );
		if ( $redeemed->have_posts() ) {
		?>
		<table class="widefat woo-vou-history-table">
	        <tr class="woo-vou-history-title-row"> 
	            <th><?php esc_html_e("Item Name","pdf-product-vouchers-for-woocommerce") ?></th>
	            <th><?php esc_html_e("Redeemed Amount","pdf-product-vouchers-for-woocommerce") ?></th>
	            <th><?php esc_html_e("Redeemed By","pdf-product-vouchers-for-woocommerce") ?></th>
	            <th><?php esc_html_e("Redeemed On","pdf-product-vouchers-for-woocommerce") ?></th>
	            <th><?php esc_html_e("Redeemed Date","pdf-product-vouchers-for-woocommerce") ?></th>
	        </tr>
	        <?php while ( $redeemed->have_posts() ) {
	        	$redeemed->the_post();
	        	$redeemed_id = get_the_ID();
	        	$redeemed_amount = get_post_meta($redeemed_id,"_redeemed_amount",true);
	        	$redeemed_user = get_the_author_meta('ID');
				if($redeemed_user){
					$user_info = get_userdata($redeemed_user);
					$author = $user_info->display_name;
				}else{
					$author = get_post_meta($redeemed_id,"_redeemed_author",true);
				}
	        	$redeemed_type = get_post_meta($redeemed_id,"_redeemed_type",true);
	        ?>
	         <tr class="woo-vou-history-title-row">                                                
         		<td class="woo-vou-history-td"><?php the_title() ?></td>
			    <td class="woo-vou-history-td"><?php echo wp_kses_post(wc_price($redeemed_amount)) ?></td>
		        <td class="woo-vou-history-td"><?php echo esc_html($author) ?></td>
		        <td class="woo-vou-history-td"><?php echo esc_html($redeemed_type) ?></td>
		        <td class="woo-vou-history-td"><?php the_time() ?> - <?php the_time(get_option('date_format'))?></td>
			</tr> 
			<?php } ?>
		</table>
		<?php
		}else{ ?>
		<p><?php esc_html_e( 'Sorry, no redeemed',"pdf-product-vouchers-for-woocommerce" ); ?></p>
		<?php }; 
		wp_reset_postdata();
	}
	function yeepdf_vc_order_voucher_info($post){
		$post_id= $post->ID;
		$code = get_post_meta($post_id,"_code",true);
		$template_id = get_post_meta($post_id,"_template_id",true);
		$coupon_code = get_post_meta($post_id,"_coupon_code",true);
		$redeem = get_post_meta($post_id,"_redeemed",true);
		$voucher_price = get_post_meta($post_id,"_price",true);
		$voucher_price_fm = wc_price($voucher_price);
		$partial_redemption = get_post_meta($post_id,"_partial_redemption",true);
		$product_id = get_post_meta($post_id,"_product_id",true);
		if($partial_redemption != "yes"){
			$partial_redemption ="No";
		}
		wp_nonce_field("yeepdf_order_voucher","yeepdf_order_voucher");
		?>
		<div class="yeepdf-voucher-container">
          <div class="yeepdf-voucher-tab-content">
              <div class="yeepdf-voucher-tab-main">
                  <table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Voucher ID","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td class="uppercase">                                
						   #<?php echo esc_attr($post_id) ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Voucher Code","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td>
						  <div class="yeepdf-hover-show-input">
								<div class="yeepdf-hover-show-input-show">                                 
								   <strong class="uppercase"><?php echo esc_html($code) ?></strong>
								</div>
								  <div class="yeepdf-hover-show-input-hide hidden">
									<input class="yeepdf-vourcher-date" type="text" name="_code" value="<?php echo esc_attr($code) ?>">	
								</div>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Partial Redemption","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td>
						  <div class="yeepdf-hover-show-input">
								<div class="">                                 
								   <strong class="uppercase"><?php echo esc_html($partial_redemption) ?></strong>
								</div>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Remaining amount","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td class="uppercase">                                
						   <?php 
						   $tota_price_used = 0;
							$redeems = get_posts(array("post_type"=>"yeepdf_redeem","meta_key"=>"_voucher_id","meta_value"=>$post_id,"numberposts"=>-1));
							foreach($redeems as $redeem_data){
								$tota_price_used += get_post_meta($redeem_data->ID,"_redeemed_amount",true);
							}
							$balance = $voucher_price - $tota_price_used;
							echo wp_kses_post(wc_price($balance));
						   ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Redeem Voucher Codes","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td>  
							<div class="yeepdf-hover-show-input-nonce">
								<strong>
								<?php
								 if($redeem == ""){
								 		?>
								 		<mark class="yeepdf-status-hold yeepdf-order-status"><span>
								 		<?php
								   		esc_html_e("Unredeemed","pdf-product-vouchers-for-woocommerce");
								   		?>
								   		</span>
								   		</mark>
								   		<a data-id="<?php echo esc_attr($post_id) ?>" data-product_id="<?php echo esc_attr($product_id) ?>" data-product_name="<?php echo esc_attr(get_the_title($product_id)) ?>" data-voucher="<?php echo esc_attr($code) ?>" data-price="<?php echo esc_attr($voucher_price) ?>" data-price_fm="<?php echo esc_attr($voucher_price_fm) ?>"  data-type="redeem" data-partial_redemption="<?php echo esc_attr($partial_redemption) ?>" href="#" class="button yeepdf-action-redeeem"><?php esc_html_e("Redeem","pdf-product-vouchers-for-woocommerce") ?></a>
								   		<?php
								   }else{
								   	?>
								   	<mark class="yeepdf-status-completed yeepdf-order-status">
								   		<span>
									   	<?php
									   	esc_html_e("Redeemed","pdf-product-vouchers-for-woocommerce");
									   	?>	
									   	</span>
								   	</mark>
								   		 <a data-id="<?php echo esc_attr($post_id) ?>" data-type="unredeem" href="#" class="button yeepdf-action-redeeem-check"><?php esc_html_e("Unredeem","pdf-product-vouchers-for-woocommerce") ?></a>
								   		<?php
								   }
								   ?>
								</strong>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Expires","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td>   
							<div class="yeepdf-hover-show-input">
								<div class="yeepdf-hover-show-input-show">                             
							   <?php 
							   	$expires = get_post_meta($post_id,"_expires",true);
							   	if($expires != ""){
							   		$timestamp = strtotime($expires);
                                    $formatted_date = wp_date(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
                                    $now = current_time('timestamp');
                                    if ( $timestamp > $now ) {
                                    	 $diff_seconds = $timestamp - $now;
    									 $days_left = floor( $diff_seconds / DAY_IN_SECONDS )." ";
                                    	esc_html_e("Expired at: ","pdf-product-vouchers-for-woocommerce");
                                    	echo esc_html($formatted_date);
                                    	echo esc_html(" - ").$days_left;
                                    	esc_html_e("Days left","pdf-product-vouchers-for-woocommerce");
                                    }else{
                                    	esc_html_e("Expires on: ","pdf-product-vouchers-for-woocommerce");
                                    	echo esc_html($formatted_date);
                                    }
							   		//
							   	}else{
							   		esc_html_e("Never Expire","pdf-product-vouchers-for-woocommerce");
							   	}
							    ?>
								</div>
								<div class="yeepdf-hover-show-input-hide hidden">
									<input class="yeepdf-vourcher-date" type="datetime-local" name="_expires" value="<?php echo esc_attr($expires) ?>">	
								</div>
						    <div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("PDF Template","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td>                                
						   <a target="_blank" href="<?php echo esc_url(esc_url(get_edit_post_link($template_id))) ?>"><?php echo esc_html(get_the_title($template_id)) ?></a>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Download PDF","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td>   
							<?php 
							$pdfs = get_post_meta($post_id,"_pdf",true);
							if(isset($pdfs["url"])) {
								?>                             
						   <a download href="<?php echo esc_url($pdfs["url"]) ?>"><?php esc_html_e("Download","pdf-product-vouchers-for-woocommerce") ?></a>
							<?php } ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e("Coupon Code","pdf-product-vouchers-for-woocommerce") ?> </th>
						<td>   
							<div class="yeepdf-hover-show-input">
								<div class="yeepdf-hover-show-input-show"> 
									<?php 
									$text_coupon_code = $coupon_code;
									if($coupon_code == ""){
										$text_coupon_code = esc_html_e("Add coupon","pdf-product-vouchers-for-woocommerce");
									} ?>
							   		<strong class="uppercase"><?php echo esc_html( $text_coupon_code )?></strong>
								</div>
								<div class="yeepdf-hover-show-input-hide hidden">
									<input class="yeepdf-vourcher-date" type="text" name="_coupon_code" value="<?php echo esc_attr($coupon_code) ?>">	
								</div>
						    <div>                             
						</td>
					</tr>
                </table>
              </div>
          </div>
      </div>
		<?php
	}
	function yeepdf_vc_order_product_info($post){
		$post_id= $post->ID;
		$price = get_post_meta($post_id,"_price",true);
		$product_id = get_post_meta($post_id,"_product_id",true);
		if (get_post_status($product_id)) {
		}
		?>
		<div class="yeepdf-voucher-container">
          <div class="yeepdf-voucher-tab-content">
              <div class="yeepdf-voucher-tab-main">
              	<table class="widefat woo-vou-history-table">
			        <tr class="woo-vou-history-title-row"> 
			            <th><?php esc_html_e("Product Name","pdf-product-vouchers-for-woocommerce") ?></th>
			            <th><?php esc_html_e("Price","pdf-product-vouchers-for-woocommerce") ?></th>
			            <th><?php esc_html_e("Redeemable Price","pdf-product-vouchers-for-woocommerce") ?></th>
			        </tr>
			         <tr class="woo-vou-history-title-row">                                                
		         		<td class="woo-vou-history-td"><a href="<?php echo esc_url(get_edit_post_link($product_id)) ?>">#<?php echo esc_attr($product_id) ?> - <?php echo esc_html(get_the_title( $product_id )) ?></a></td>
					    <td class="woo-vou-history-td"><?php echo esc_attr(strip_tags(wc_price($price))) ?></td>
				        <td class="woo-vou-history-td"><?php echo esc_attr(strip_tags(wc_price($price))) ?></td>
					</tr> 
				</table>
              </div>
          </div>
      </div>
		<?php
	}
	function yeepdf_vc_order_forms($post){
		$post_id= $post->ID;
		$product_id = get_post_meta($post_id,"_product_id",true);
		$product = wc_get_product( $product_id );
		?>
		<input type="hidden" name="_product_id" value="<?php echo esc_attr($product_id) ?>">
		<div class="yeepdf-voucher-container">
          <div class="yeepdf-voucher-tab-content">
              <div class="yeepdf-voucher-tab-main">
              		<?php
					$meta_value = get_post_meta($post_id,"_forms",true);
					if($meta_value != ""){
						$fields = json_decode($meta_value,true);
					}else{
						$fields = "";
					}
					if(is_array($fields)){
              		?>
              		<div class="yeepdf-hover-show-input">
						<div class="yeepdf-hover-show-input-show"> 
							<table class="form-table">
		                  	<?php 
		                  	foreach($fields as $field ){
						      ?>
							<tr valign="top">
								<th scope="row"><?php echo esc_html($field["label"]) ?> </th>
								<td>                                
								   <?php echo esc_html($field["value"]) ?>
								</td>
							</tr>
							<?php } ?>
		                </table>
						</div>
						<div class="yeepdf-hover-show-input-hide hidden1">
							<?php 
							if( $product ){
								?>
								<table class="form-table">
								<?php
								$show_fields = $product->get_meta( '_yeepdf_product_vouchers_forms', true );
								foreach($show_fields as $field){
									$label ="";
			                        $default ="";
			                        $name ="";
			                        if(isset($field["name"])){
			                            $name = $field["name"];
			                        }
			                        if(isset($field["label"])){
			                            $label = $field["label"];
			                        }
			                        if(isset($field["default"])){
			                            $default = $field["default"];
			                        }
			                        $required ="";
			                        $required_t ="";
			                        if(isset($field["required"]) && $field["required"] == "yes"){
			                            $required = "required";
			                            $required = "1";
			                            $required_t = " *";
			                        }
									
			                        if(array_key_exists($name,$fields)){;
			                        	$default = $fields[$name]["value"];
			                        }
								?>
								<tr valign="top">
									<th scope="row"><?php echo esc_html($field["label"]) ?> <?php echo esc_html($required_t) ?> </th>
									<td>                                
									   <input name="yeeaddons_product_vouchers_forms[<?php echo esc_attr( $name ) ?>]" type="text" value="<?php echo esc_attr($default) ?>">
									</td>
								</tr>
								<?php 
								}
								?>
								</table>
							<?php
						} ?>	
						</div>
				    </div>
				<?php }else{
					?>
					<p><?php esc_html_e("No recipient information available","pdf-product-vouchers-for-woocommerce") ?></p>
					<?php
				} ?>
              </div>
          </div>
      </div>
		<?php
	}
	function yeepdf_vc_order_buyer_info($post){
		$post_id= $post->ID;
		$order_id = get_post_meta($post_id,"_order_id",true);
		$order = wc_get_order($order_id);
		if ($order) {
		?>
		<div class="yeepdf-voucher-container">
          <div class="yeepdf-voucher-tab-content">
              <div class="yeepdf-voucher-tab-main">
              	<table class="widefat woo-vou-history-table">
			        <tr class="woo-vou-history-title-row"> 
			            <th><?php esc_html_e("Full Name","pdf-product-vouchers-for-woocommerce") ?></th>
			            <th><?php esc_html_e("Email","pdf-product-vouchers-for-woocommerce") ?></th>
			            <th><?php esc_html_e("Phone","pdf-product-vouchers-for-woocommerce") ?></th>
			            <th><?php esc_html_e("Addess","pdf-product-vouchers-for-woocommerce") ?></th>
			        </tr>
			         <tr class="woo-vou-history-title-row">                                                
		         		<td class="woo-vou-history-td"><?php echo esc_html($order->get_formatted_billing_full_name()) ?></td>
					    <td class="woo-vou-history-td"><?php echo esc_html( $order->get_billing_email() )?></td>
				        <td class="woo-vou-history-td"><?php echo esc_html( $order->get_billing_phone())?></td>
				        <td class="woo-vou-history-td"><?php 
						   $address    = $order->get_formatted_billing_address();
						   echo wp_kses_post( $address ? $address : esc_html__( 'N/A', "pdf-product-vouchers-for-woocommerce" ) ); 
						   ?></td>
					</tr> 
				</table>
              </div>
          </div>
      </div>
      <?php
  		}
	}
	function yeepdf_vc_order_order_info($post){
		$post_id= $post->ID;
		$order_id = get_post_meta($post_id,"_order_id",true);
		$order = wc_get_order($order_id);
		if ($order) {
		?>
		<div class="yeepdf-voucher-container">
          <div class="yeepdf-voucher-tab-content">
              <div class="yeepdf-voucher-tab-main">
              	<table class="widefat woo-vou-history-table">
			        <tr class="woo-vou-history-title-row"> 
			            <th><?php esc_html_e("Order ID","pdf-product-vouchers-for-woocommerce") ?></th>
			            <th><?php esc_html_e("Order Date","pdf-product-vouchers-for-woocommerce") ?></th>
			            <th><?php esc_html_e("Payment Method","pdf-product-vouchers-for-woocommerce") ?></th>
			            <th><?php esc_html_e("Order Total","pdf-product-vouchers-for-woocommerce") ?></th>
			        </tr>
			         <tr class="woo-vou-history-title-row">                                                
		         		<td class="woo-vou-history-td"><a href="<?php echo esc_url($order->get_view_order_url()) ?>">#<?php echo esc_attr($order_id) ?></a></td>
					    <td class="woo-vou-history-td"><?php echo esc_html(wc_format_datetime( $order->get_date_created() )); ?></td>
				        <td class="woo-vou-history-td"><?php echo esc_html( $order->get_payment_method_title() )?></td>
				        <td class="woo-vou-history-td"><?php echo esc_html(strip_tags(wc_price($order->get_total()) ))?></td>
					</tr> 
				</table>
              </div>
          </div>
      </div>
      <?php
  		}
	}
	function yeepdf_vc_order_voucher_note($post){
		$post_id= $post->ID;
		?>
		<div class="yeepdf-voucher-container">
          <div class="yeepdf-voucher-tab-content">
              <div class="yeepdf-voucher-tab-main">
              		<ul class="yee_order_notes ">
              		<?php
              		$notes = get_posts(array("numberposts"=>-1,"post_type"=>"yeepdf_vc_notes","meta_key"=>"_voucher_id","meta_value"=>$post_id,"post_status"=>"private"));
              		if(is_array($notes) && count($notes)>0){
              			?>
              			<?php
              			foreach($notes as $note){
              				setup_postdata( $note );
              				?>
              				<li class="note_content">
              					<p><?php the_content( $more_link_text = null, $strip_teaser = false ) ?></p>
              					<p class="meta">
								<abbr class="exact-date">
									<?php the_date() ?>
								</abbr>
								 <?php $user = get_userdata( $note->post_author ); ?>
								 <?php esc_html_e("by","pdf-product-vouchers-for-woocommerce") ?> <?php the_author() ?>				
								 <a data-id="<?php echo esc_attr($note->ID)  ?>" href="#" class="yeepdf_delete_note" role="button"><?php esc_html_e("Delete note","pdf-product-vouchers-for-woocommerce") ?></a>
							</p>
              				</li>
              				<?php
              			}
              			?>
              			<?php
              		}
              		?>
              		</ul>
              		<div class="yepdf-notes-container">
              			<hr>
              		<div>
						<textarea type="text"  id="yeepdf_add_order_note" class="input-text" rows="3"></textarea>
					</div>
              		<a href="#" class="yeepdf_add_note button"><?php esc_html_e("Add Note","pdf-product-vouchers-for-woocommerce") ?></a>
              		</div>
              </div>
          </div>
      </div>
		<?php
	}
	function save_metabox($post_id, $post) {
		if ( ! isset( $_POST['yeepdf_order_voucher'] ) || ! wp_verify_nonce( wp_unslash($_POST['yeepdf_order_voucher']), 'yeepdf_order_voucher' )  ) {
			return $post_id;
		}
		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		$code = sanitize_text_field(wp_unslash($_POST['_code']));
		update_post_meta($post_id, '_code', $code);
		$_redeemed = sanitize_text_field(wp_unslash($_POST['_redeemed']));
		if($_redeemed != ""){
			$redeem_old = get_post_meta($post_id,"_redeemed",true);
			if($redeem_old == ""){
				$current_datetime = current_time('mysql'); 
				update_post_meta($post_id, '_redeemed_date', $current_datetime); 
			}
		}
		update_post_meta($post_id, '_redeemed', $_redeemed);
		$_expires = sanitize_text_field(wp_unslash($_POST['_expires']));
		update_post_meta($post_id, '_expires', $_expires);
		$_coupon_code = sanitize_text_field(wp_unslash($_POST['_coupon_code']));
		update_post_meta($post_id, '_coupon_code', $_coupon_code);
		$inputs = map_deep( $_POST['yeeaddons_product_vouchers_forms'], 'sanitize_textarea_field' );
		$inputs = map_deep( $inputs, 'wp_unslash' );
		$product_id = sanitize_text_field(wp_unslash($_POST['_product_id']));
		$product = wc_get_product( $product_id );
		$forms = $product->get_meta( '_yeepdf_product_vouchers_forms', true, 'edit' );
		$data_save = array();
		foreach($forms as $field ){
            if(isset($inputs[$field["name"]]) && $field["name"] != ""){
                $data_save[ $field["name"] ] = array("label"=>$field["label"],"value"=>$inputs[$field["name"]],"type"=>$field["type"]);
            }
        }
		update_post_meta($post_id, '_forms', json_encode($data_save,JSON_UNESCAPED_UNICODE));
    }
}
new Yeeaddons_Woo_PDF_Voucher_Menu;