<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Frontend_Coupon{
    function __construct(){
        //add coupon
        add_action( 'woocommerce_order_status_completed', array($this,"generate_couponcode"),10 );
        add_action( 'woocommerce_order_status_completed', array($this,"check_coupon_and_reddem_online"),11 );
        add_action( 'woocommerce_order_status_on-hold', array($this,"generate_couponcode") );
        add_filter( 'woocommerce_email_attachments', array($this,'add_pdf'), 10, 4 );
    }
    function add_pdf($attachments, $email_id, $order, $email ){
        if(!$order){
            return $attachments;
        }
        $order_status = $order->get_status();
        if($order_status == "completed" && $email_id == "customer_completed_order"){
            foreach ($order->get_items() as $item_id => $item) {
                $_product = $item->get_product();
                $options = $_product->get_meta( '_yeepdf_product_vouchers', true );
                if(isset($options["delivery"]) && $options["delivery"] == "email"){
                    $voucher_id= wc_get_order_item_meta( $item_id, "_yeepdf_voucher_id", true ); 
                    $pdfs = get_post_meta($voucher_id,"_pdf",true);
                    if(isset($pdfs["path"])){
                        $attachments[] = $pdfs["path"];
                    }
                }
            }
        }
        return $attachments;
    }
    function generate_code(){
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ023456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $code = '' ; 
        while ($i <= 7) { 
            $num = rand() % 33; 
            $tmp = substr($chars, $num, 1); 
            $code = $code . $tmp; 
            $i++; 
        } 
        return $code; 
    }
    function check_coupon_and_reddem_online($order_id){
        $order = wc_get_order($order_id); // Get order details
        //sử dụng copoun từ phần
        if ($order->get_coupon_codes()) {
            $user_id = $order->get_user_id();
            foreach ($order->get_coupon_codes() as $coupon_code) {
                $coupon = new WC_Coupon($coupon_code);
                $discount_amount = $order->get_discount_total();
                $vouchers = get_posts(array("post_type"=>"yeepdf_vc_order","meta_key"=>"_code","meta_value"=>$coupon_code,"numberposts"=>1));
                if(count($vouchers) > 0){
                    $voucher = $vouchers[0];
                    $voucher_id = $voucher->ID;
                    $status_reddem = get_post_meta( $voucher_id, "_redeemed", true);
                    $partial_redemption = get_post_meta($voucher_id,"_partial_redemption",true);
                    $user_identifier = "";
                    if($partial_redemption != "yes" && $status_reddem != "redeemed"){
                        //redeem 1 lần
                        $balance = 0;
                        $redeemed_method = "Full";
                        update_post_meta( $voucher_id, "_redeemed", "redeemed");
                        //add reddem
                        $my_post = array(
                            'post_title'    => "Redeemed Product ID #".$product_id,
                            'post_content'  => "",
                            'post_status'   => 'publish',
                            'post_author'   => $user_id,
                            'post_type' =>  "yeepdf_redeem"
                            );
                        $redeemed_id = wp_insert_post($my_post);
                        if(isset($redeemed_id) && $redeemed_id > 0){
                            $redeemed_type = "Copoun";
                            update_post_meta( $redeemed_id, '_redeemed_amount', $discount_amount );
                            update_post_meta( $redeemed_id, '_redeemed_type', $redeemed_type );
                            update_post_meta( $redeemed_id, '_voucher_id', $voucher_id );
                            if ( ! $user_id ) {
                                $user_identifier = $order->get_billing_email();
                                update_post_meta( $redeemed_id, '_redeemed_author', $user_identifier ); 
                            }
                        }
                    }else{
                        // reddem từng phần
                        if($status_reddem != "redeemed"){
                            $tota_price_used = 0;
                            $redeemed_method = "Partial";
                            $redeems = get_posts(array("post_type"=>"yeepdf_redeem","meta_key"=>"_voucher_id","meta_value"=>$voucher_id,"numberposts"=>-1));
                            foreach($redeems as $redeem_data){
                                $tota_price_used += get_post_meta($redeem_data->ID,"_redeemed_amount",true);
                            }
                            $my_post = array(
                                'post_title'    => "Redeemed Product ID #".$product_id,
                                'post_content'  => "",
                                'post_status'   => 'publish',
                                'post_author'   => $user_id,
                                'post_type' =>  "yeepdf_redeem"
                                );
                            $redeemed_id = wp_insert_post($my_post);
                            $tota_price_used_full = $tota_price_used + $discount_amount;
                            $voucher_price = get_post_meta($voucher_id,"_price",true);
                            $balance = $voucher_price - $tota_price_used_full;
                            if(isset($redeemed_id) && $redeemed_id > 0){
                                $redeemed_type = "Copoun";
                                update_post_meta( $redeemed_id, '_redeemed_amount', $discount_amount );
                                update_post_meta( $redeemed_id, '_redeemed_type', $redeemed_type );
                                update_post_meta( $redeemed_id, '_voucher_id', $voucher_id );
                                if ( ! $user_id ) {
                                    $user_identifier = $order->get_billing_email();
                                    update_post_meta( $redeemed_id, '_redeemed_author', $user_identifier ); 
                                }
                                if($discount_amount >= $voucher_price){
                                    update_post_meta( $voucher_id, "_redeemed", "redeemed");
                                }
                            }
                        }
                    }
                    if(!$user_id){
                        $user_id = $user_identifier;
                    }
                    $datas_redeem = array(
                        "voucher_id"=>$voucher_id,
                        "redeemed_amount"=>wc_price($discount_amount),
                        "redeemed_id"=>$redeemed_id,
                        "redeemed_type"=>$redeemed_type,
                        "redeemed_balance"=>wc_price($balance),
                        "redeemed_method"=>$redeemed_method,
                        "user_redeem"=>$user_id,
                        "date"=>current_time( get_option('date_format') . ' ' . get_option('time_format') ),
                    );
                    do_action( 'yeepdf_redeem_email_notification', $datas_redeem);
                }
            }
        }
    }
    function generate_couponcode($order_id){
        $order = wc_get_order($order_id); // Get order details
        $user_id = $order->get_user_id();
        $first_name = $order->get_billing_first_name();
        $last_name = $order->get_billing_last_name();
        $email = $order->get_billing_email();
        //check created ?
        // get active coupons
        $meta_key_to_vl = array(
            'discount_type'              => 'fixed_cart',
            "coupon_amount_type"         =>"price",
            'coupon_amount'              => 0,
            'minimum_amount'             => '',
            'maximum_amount'             => '',
            'expiry_date'                => '',
            'product_ids'                => '',
            'exclude_product_ids'        => '',
            'date_expires'               => '',
            'free_shipping'              => '',
            'product_categories'         => '',
            'exclude_product_categories' => '',
            'usage_limit' => 1,
        );
        foreach ($order->get_items() as $item_id => $item) {
            $_product = $item->get_product();
            $product_id = $_product->get_id();
            $price = $_product->get_price();
            $options = $_product->get_meta( '_yeepdf_product_vouchers', true );
            $enable = $_product->get_meta( '_yee_voucher', true );
            $voucher_id_old = wc_get_order_item_meta( $item_id, "_yeepdf_voucher_id", true ); 
            $voucher_id_old = "";
            $code_coupon = "";
            if( $enable == "yes" && $voucher_id_old == "" ){
                $code_used = $this->get_code_used($product_id,$_product);
                if(!is_array($code_used)){
                    $code_used = array();
                }
                if( isset($options["auto_create"]) && $options["auto_create"] == "yes" ){
                    //Nếu tạo coupon
                    $coupon_options = $_product->get_meta( '_yeepdf_product_vouchers_coupon', true );
                    if($coupon_options["discount_type"] != "percent"){
                        if( $coupon_options["coupon_amount_type"] == "price" ){
                            $coupon_amount = $item->get_total();
                        }else{
                            $coupon_amount = $coupon_options["coupon_amount"];
                        }
                    }else{
                        $coupon_amount = $coupon_options["coupon_amount"];
                    }
                    $coupon_options["coupon_amount"] = $coupon_amount;
                    $post_author = get_post_field('post_author', $product_id);
                    if( isset($options["codes"]) && $options["codes"] != ""){
                        $array_code = implode(",",$options["codes"]);
                        //if use 1 time
                        //code used
                        $list_codes = array_diff($array_code, $code_used);
                        if(count($list_codes) > 0 ){
                            $codei = array_rand($list_codes);
                            $code = trim($list_codes[$codei]);
                        }else{
                            $code = $this->generate_code();
                        }
                    }else{
                        $code = $this->generate_code();
                    }
                    $code_coupon = $code;
                    $coupon = array(
                        'post_title' => $code_coupon,
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_author' => $post_author,
                        'post_type' => 'shop_coupon'
                    );
                    // Generate coupon
                    $coupon_id = wp_insert_post(
                        apply_filters(
                            'woocommerce_new_coupon_data',
                            array(
                                'post_type'     => 'shop_coupon',
                                'post_status'   => 'publish',
                                'post_author'   => $post_author,
                                'post_title'    => $code,
                                'post_content'  => "Create by order #".$order_id,
                                'post_excerpt'  => "Create by order #".$order_id,
                            )
                        ),
                        true
                    );
                    if ( $coupon_id ) {
                        $datas = shortcode_atts( $meta_key_to_vl,$coupon_options );
                        foreach($datas as $key=>$vl){
                            if($vl != ""){
                                update_post_meta($coupon_id, $key, $vl);
                            }
                        }
                        do_action( 'woocommerce_new_coupon', $coupon_id, $coupon );
                    }
                }else{
                    ///Nếu không tạo coupon
                    if(isset($options["auto_create_cp"]) && $options["auto_create_cp"] == "yes" ){
                        //Nếu tự động tạo coupon
                        $code = $this->generate_code();
                    }else{
                        if( isset($options["codes"]) && $options["codes"] != ""){
                            $array_code = explode(",",$options["codes"]);
                            //if use 1 time
                            if( isset($options["usage"]) && $options["usage"] == 1){
                                $list_codes = array_diff($array_code, $code_used);
                                if(count($list_codes) > 0 ){
                                    $codei = array_rand($list_codes);
                                    $code = $list_codes[$codei];
                                }else{
                                    $code = "Empty code";
                                }
                            }else{
                                $codei = array_rand($array_code);
                                $code = $list_codes[$codei];
                            }
                            
                        }else{
                            $code = $this->generate_code();
                        }
                    }
                }
                $expires = $this->get_expires($product_id,$_product);
                $forms = wc_get_order_item_meta( $item_id, "_yeepdf_product_vouchers_forms", true ); 
                $template_id = wc_get_order_item_meta( $item_id, "_yeepdf_product_vouchers_template_id", true ); 
                if($template_id == "") {
                    $templates =  $_product->get_meta( '_yeepdf_product_vouchers_templates', true );
                    foreach($templates as $template){
                        $template_id = $template["template"];
                        break;
                    }
                }
                $partial_redemption = "";
                if(isset($options["partial_redemption"])){
                    $partial_redemption = $options["partial_redemption"];
                }
                $start_date ="";
                if(isset($options["voucher_start_date"])){
                    $start_date = $options["voucher_start_date"];
                }
                $add_voucher = array(
                    '_order_id' => $order_id,
                    '_product_id' => $product_id,
                    '_price' => $price,
                    '_code' => $code,
                    '_coupon_code' => $code_coupon,
                    '_template_id' => $template_id,
                    '_forms' => $forms,
                    '_expires' => $expires,
                    '_start_date' => $start_date,
                    '_partial_redemption' => $partial_redemption,
                    '_user_id' => $user_id,
                );
                $voucher_id = $this->add_voucher($add_voucher);
                $current_user_id = get_current_user_id();
                wc_update_order_item_meta($item_id,"_yeepdf_voucher_id",$voucher_id);
                $this->set_code_used($code,$_product,$product_id);
                //create pdf
                $name ="voucher-".$order_id;
                $password ="";
                $data_send_settings = array(
                    "id_template"=> $template_id,
                    "type"=> "html",
                    "name"=> $name,
                    "datas" =>array(),
                    "woo_order_id" =>$order_id,
                    "voucher_id" => $voucher_id,
                    "return_html" =>true,
                );
                $message =Yeepdf_Create_PDF::pdf_creator_preview($data_send_settings);
                if (preg_match('/\[yeepdf_images(?:\s+width="(\d+)")?(?:\s+height="(\d+)")?\](.*?)\[\/yeepdf_images\]/', $message, $matches)) {
                    $width = !empty($matches[1]) ? $matches[1] : "auto"; 
                    $height = !empty($matches[2]) ? $matches[2] : "auto";
                    $imageUrls = explode(",", $matches[3]);
                    if(is_numeric($height) ){
                        $height .= "px";
                    }
                    if(is_numeric($width) ){
                        $width .= "px";
                    }
                    $imagesHtml = "";
                    foreach ($imageUrls as $url) {
                        $imagesHtml .= "<img src='$url' width='$width' height='$height' > ";
                    }
                    $message = str_replace($matches[0], $imagesHtml, $message);
                }
                $data_send_settings_download = array(
                    "id_template"=> $template_id,
                    "type"=> "upload",
                    "name"=> $name,
                    "datas" =>array(),
                    "woo_order_id" =>$order_id,
                    "woo_shortcode" =>false,
                    "password" =>$password,
                    "html" =>$message,
                    "voucher_id" => $voucher_id,
                    "save_dropbox" =>false,
                );
                $data_send_settings_download = apply_filters("pdf_before_render_datas",$data_send_settings_download);
                $folder_uploads =Yeepdf_Create_PDF::pdf_creator_preview($data_send_settings_download);
                update_post_meta($voucher_id,"_pdf",array("path"=>$folder_uploads["path"],"url"=>$folder_uploads["url"]));
                if($forms !="" ){
                    $forms_datas = json_decode($forms,true);
                    $datas_voucher = array(
                        "order_id"=>$order_id,
                        "pdf_path"=>$folder_uploads["path"],
                        "pdf_url"=>$folder_uploads["url"],
                        "voucher_id"=>$voucher_id,
                        "first_name"=>$first_name,
                        "last_name"=>$last_name,
                        "email"=>$email,
                        "voucher_code"=>$code,
                        "price"=>$price,
                        "forms"=>$forms_datas,
                        "date"=>current_time( get_option('date_format') . ' ' . get_option('time_format') ),
                    );
                    do_action( 'yeepdf_gift_email_notification', $datas_voucher);
                }
            }
        }
    }
    function get_code_used($product_id,$_product = null){
        $code_used = $_product->get_meta( '_yeepdf_product_code_used', true );
        return $code_used;
    }
    function set_code_used($code,$_product = null,$product_id=null){
        $code_used = $_product->get_meta( '_yeepdf_product_code_used', true );
        if(!is_array($code_used)){
            $code_used = array($code);
        }else{
            $code_used[] = $code;
        }
        update_post_meta($product_id,"_yeepdf_product_code_used",$code_used);
    }
    function get_expires($product_id,$_product){
        $options = $_product->get_meta( '_yeepdf_product_vouchers', true );
        if($options["expiration_type"] == "specific_time"){
            $expires = $options["voucher_expiration_date"];
        }else{
            $expires = date('Y-m-d H:i:s', strtotime('+'.$options["expiration_days"].' days', current_time('timestamp')));
        }
        return $expires;
    }
    function add_voucher($atts){
        global $wpdb;
        $voucher= shortcode_atts( array(
            '_order_id' => "",
            '_product_id' => "",
            '_price' => "",
            '_code' => "",
            '_coupon_code' => "",
            '_template_id' => "",
            '_forms' => "",
            '_start_date' => "",
            '_expires' => "",
            '_partial_redemption' => "",
            '_user_id' => "",
        ), $atts );
        $my_post = array(
            'post_title'    => $voucher["_code"],
            'post_content'  => '',
            'post_type'  => 'yeepdf_vc_order',
            'post_status'   => 'publish',
            'post_author'   => $voucher["_user_id"],
        );
        $voucher_id = wp_insert_post($my_post);
        update_post_meta($voucher_id,"_order_id",$voucher["_order_id"]);
        update_post_meta($voucher_id,"_product_id",$voucher["_product_id"]);
        update_post_meta($voucher_id,"_price",$voucher["_price"]);
        update_post_meta($voucher_id,"_code",$voucher["_code"]);
        update_post_meta($voucher_id,"_coupon_code",$voucher["_coupon_code"]);
        update_post_meta($voucher_id,"_template_id",$voucher["_template_id"]);
        update_post_meta($voucher_id,"_forms",$voucher["_forms"]);
        update_post_meta($voucher_id,"_expires",$voucher["_expires"]);
        update_post_meta($voucher_id,"_partial_redemption",$voucher["_partial_redemption"]);
        update_post_meta($voucher_id,"_start_date",$voucher["_start_date"]);
        return $voucher_id;
    }
}
new Yeeaddons_Woo_PDF_Product_Frontend_Coupon;