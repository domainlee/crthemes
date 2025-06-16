<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Frontend_Cart{
    function __construct(){
        //add_action("woocommerce_cart_totals_before_order_total",array($this,"add_input_redeem"));
        //add_action("woocommerce_cart_calculate_fees",array($this,"woocommerce_cart_calculate_fees"));
        //add_action( 'wp_ajax_yeepdf_action_check_code', array($this,"check_code") );
        //add_action( 'wp_ajax_nopriv_yeepdf_action_check_code', array($this,"check_code") );
        //add_action( 'woocommerce_applied_coupon', array($this,"woocommerce_cart_coupon_applied") );
    }
    function woocommerce_cart_coupon_applied($coupon_code){
        ?>
        <div id="custom-coupon-amount-form" style="margin-top:20px;">
        <label>Nhập số tiền bạn muốn dùng từ mã <strong><?php echo esc_html($coupon_code); ?></strong>:</label>
        <input type="number" id="custom_coupon_amount" min="1" step="1">
        <button type="button" onclick="applyCustomCouponAmount('<?php echo esc_js($coupon_code); ?>')">Xác nhận</button>
    </div>
        <?php
    }
    function check_code(){
        check_ajax_referer( 'yeepdf_vc_cart', 'security' );
        if(isset($_POST["code"])){
            $code = sanitize_text_field(wp_unslash($_POST["code"]));
            $codes = get_posts(array("numberposts"=>1,"post_type"=>"yeepdf_vc_order","meta_key"=>"_code","meta_value"=>$code));
            if(is_array($codes) && count($codes)> 0){
               $code_data = $codes[0];
               $voucher_id = $code_data->ID;
               $balance = get_post_meta($voucher_id,"_price",true);
               $remaining_amount = $balance;
               $status = get_post_meta($voucher_id,"_redeemed",true);
               $partial_redemption = get_post_meta($voucher_id,"_partial_redemption",true);
               $expires = get_post_meta($voucher_id,"_expires",true);
               $check_expires =  true;
               //kiểm tra đã sử dụng chưa
               if($status != "redeemed"){
                    // voucher chưa sử dụng
                    $now = current_time('timestamp');
                    //kiểm tra voucher còn hạn không
                    if($expires != ""){
                        $timestamp = strtotime($expires);
                        $formatted_date = wp_date(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
                        $now = current_time('timestamp');
                        if ( $timestamp < $now ) {
                             $check_expires = false;
                        }
                    }
                    if( $check_expires ) {
                        $amount_used = 0;
                        $redeems = get_posts(array("post_type"=>"yeepdf_redeem","meta_key"=>"_voucher_id","meta_value"=>$post_id,"numberposts"=>-1));
                        foreach($redeems as $redeem_data){
                            $amount_used += get_post_meta($redeem_data->ID,"_redeemed_amount",true);
                        }
                        $remaining_amount = $balance-$amount_used;
                        if($remaining_amount <= 0){
                            //đã sử dụng hết tiền
                            $status = "error";
                            $message = esc_html__("The voucher has been used up","pdf-product-vouchers-for-woocommerce");
                        }else{
                            //Còn tiền và trả về
                            $status = "ok";
                            $message = array("balance"=>$remaining_amount,"balance_fm"=>wc_price($remaining_amount));
                        }
                    }else{
                        $status = "error";
                        $message = esc_html__("The voucher has expired","pdf-product-vouchers-for-woocommerce");
                    }
               }else{
                    //Voucher đã sử dụng
                    $message = esc_html__("The voucher used","pdf-product-vouchers-for-woocommerce");
                    $status = "error";
               }
            }
            wp_send_json(array("status"=>$status,",message"=>$message));
        }else{
            wp_send_json(array("status"=>"error",",message"=>esc_html__("Please enter voucher","pdf-product-vouchers-for-woocommerce")));
        }
        die();
    }
    function add_input_redeem(){
        $user_id = get_current_user_id();
        if(isset($user_id)){
            $balance = get_user_meta( $user_id, '_voucher_balance', true );
            $balance = $balance ? floatval($balance) : 0;
            wp_nonce_field("yeepdf_vc_cart","yeepdf_vc_cart");
            ?>
            <tr class="order-total">
                <th><?php esc_html_e( 'Voucher Code', 'woocommerce' ); ?></th>
                <td>
                    <input type="text" name="yeepdf_redeem_code" id="yeepdf_redeem_code"
                        value="<?php echo esc_attr( WC()->session->get('yeepdf_redeem_code', '') ); ?>"
                         />
                    <button type="submit" name="yeepdf_check_code" class="button yeepdf_check_code"><?php esc_html_e( 'Check code', 'your-textdomain' ); ?>
                    </button>
                </td>
            </tr>
            <tr class="order-total">
                <th><?php esc_html_e( 'Available Balance', 'woocommerce' ); ?></th>
                <td data-title="<?php esc_attr_e( '$100', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
            </tr>
            <tr class="order-total">
                <th><?php esc_html_e( 'Redeem Amount', 'woocommerce' ); ?></th>
                <td >
                    <input type="number" name="yeepdf_redeem_amount" id="yeepdf_redeem_amount"
                    value="<?php echo esc_attr( WC()->session->get('yeepdf_redeem_amount', '') ); ?>"
                    min="0" step="0.01" style="width: 100px;" />
                </td>
            </tr>
            <?php 
        }
    }
    function woocommerce_cart_calculate_fees($cart){
       if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
        $redeem_amount = 10;
        if ( $redeem_amount > 0 ) {
            $label = __( 'Voucher Redeemed', 'your-textdomain' ); 
            $cart->add_fee( $label, -$redeem_amount );
        }
    }
}
new Yeeaddons_Woo_PDF_Product_Frontend_Cart;