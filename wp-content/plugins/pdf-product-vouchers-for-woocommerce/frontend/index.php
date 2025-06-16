<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Frontend{
    function __construct(){
        add_action( 'woocommerce_single_product_summary', array($this,"expiry_product") );
        add_action('woocommerce_before_add_to_cart_button', array($this,"show_fields"));
        add_action( "wp_enqueue_scripts",array($this,"add_libs"));
        //validate forms
        add_action( "woocommerce_add_to_cart_validation",array($this,"validate_add_cart_item"),10,3);
        add_filter( 'woocommerce_add_cart_item_data', array($this,'add_cart_item_data'), 10, 3 );
        add_filter( 'woocommerce_get_cart_item_from_session', array($this,"get_cart_items_from_session"), 1, 3 );
       // add_action( 'woocommerce_add_order_item_meta',array($this,'add_values_to_order_item_meta'),1,2);
        add_action( 'woocommerce_new_order_item',array($this,'add_values_to_order_item_meta_new'),11,2);
        add_filter( 'woocommerce_get_item_data',array($this,'filter_woocommerce_get_item_data'),10,2);
        add_filter( 'woocommerce_order_item_display_meta_key',array($this,'woocommerce_order_item_display_meta_key'),10);
        add_filter( 'woocommerce_order_item_display_meta_value',array($this,'woocommerce_order_item_display_meta_value'),10,2);
        //add_filter('woocommerce_get_item_downloads', array($this,"get_item_pdf_downloads"), 10, 3);
        //show code when price = 0
        add_action( "woocommerce_before_add_to_cart_form", array($this,"woocommerce_before_add_to_cart_form"),9);
    }
    function woocommerce_before_add_to_cart_form(){
        global $product;
        if ($product->get_price() == 0) {
            $settings = get_option( 'yeepdf_vouchers', array("show_code"=>"yes") );
            if(isset($settings["show_code"]) && $settings["show_code"] == "yes") {
                $options = $product->get_meta( '_yeepdf_product_vouchers', true );
                $codes = array();
                if(isset($options["codes"])){
                    $codes = $options["codes"];
                    $codes = explode(",",$codes);
                }
                ?>
                <div class="single-free-code">
                    <?php
                    $i=1; 
                    foreach($codes as $code){
                        $code = trim($code);
                        ?>
                    <span><?php echo esc_attr( $code ) ?><a class="copy-code" data-id="#code-<?php echo esc_attr( $i ) ?>"> <i class="yeepdf-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M208 0L332.1 0c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9L448 336c0 26.5-21.5 48-48 48l-192 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48zM48 128l80 0 0 64-64 0 0 256 192 0 0-32 64 0 0 48c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 176c0-26.5 21.5-48 48-48z"/></svg></i></a>
                        <input id="code-<?php echo esc_attr( $i ) ?>" type="text" value="<?php echo esc_attr( $code ) ?>">
                    </span>
                    <?php 
                    $i++;
                    } ?>
                </div>
            <?php
            }
        }else{
            if($product->get_sale_price()){
                ?>
                <div class="single-price-save">
                    <div>
                        <?php
                        $sale_price = $product->get_regular_price() - $product->get_sale_price();
                        esc_attr_e( "You save : ", "pdf-product-vouchers-for-woocommerce" );
                        echo esc_attr(strip_tags(wc_price($sale_price)));
                        ?>
                    </div>
                </div>
                <?php
            }
        }
    }
    function get_item_pdf_downloads($files, $item, $abs_order) {
        $product = $item->get_product();
        if (!( $product && $abs_order && $product->is_downloadable() && $abs_order->is_download_permitted() ))
            return $files;
        $item_id = $item->get_id();
        $extra_downloads = wc_get_order_item_meta($item->get_id(), '_yeepdf_voucher_pdf', true);
        if(isset($extra_downloads["path"])){
            $product_id = !empty($item['variation_id']) ? $item['variation_id'] : $item['product_id'];
            $downloadable_files['yeepdf_vou_pdf_1'] = array(
                        'name' => "Voucher Download",
                        'file' => $extra_downloads["path"]
                    );
            $pdf_files["yeepdf_vou_pdf_1"]['download_url'] = $extra_downloads["url"];
            $pdf_files["yeepdf_vou_pdf_1"]['id'] = "yeepdf_vou_pdf_1";
            $pdf_files["yeepdf_vou_pdf_1"]['access_expires'] = '';
            $pdf_files["yeepdf_vou_pdf_1"]['downloads_remaining'] = '';
            $pdf_files["yeepdf_vou_pdf_1"]['name'] = 'Download Voucher';
            $pdf_files["yeepdf_vou_pdf_1"]['file'] = $extra_downloads["path"];
            $files = array_merge($files, $pdf_files);
        }
       return $files;
    }
    function woocommerce_order_item_display_meta_key($meta_key ) {
        if($meta_key == "_yeepdf_product_vouchers_forms"){
            $meta_key = "Recipient Fields";
        }
        if($meta_key == "_yeepdf_voucher_id"){
            $meta_key = "Voucher";
        }
        if($meta_key == "_yeepdf_product_vouchers_template_id"){
            $meta_key = "Template";
        }
        return $meta_key;
    }
    function woocommerce_order_item_display_meta_value($meta_value, $meta ) {
        $meta_data = $meta->get_data();
        if($meta_data["key"] == "_yeepdf_product_vouchers_forms"){
            $html =array();
            if($meta_value !=""){
                $fields = json_decode($meta_value,true);
                foreach($fields as $field ){
                    $html[] = $field["label"].": ".$field["value"];
                }
            }
            $meta_value = implode("<br>",$html);
        }
        if($meta_data["key"] == "_yeepdf_product_vouchers_template_id"){
            $template_name = get_the_title($meta_value);
            $meta_value = $template_name;
        }
        if($meta_data["key"] == "_yeepdf_voucher_id"){
            $template_name = get_the_title($meta_value);
            $pdfs = get_post_meta($meta_value,"_pdf",true);
            if(isset($pdfs["url"])){
                $meta_value = '<a href="'.get_edit_post_link($meta_value).'" target="_bank">'.$template_name.'</a> - Download PDF: <a href="'.esc_url( $pdfs["url"] ).'" download>Download PDF</a>';
            }else{
                $meta_value = '<a href="'.get_edit_post_link($meta_value).'" target="_bank">';
            }
        }
        return $meta_value;
    }
    //show meta in cart and checkout
    function filter_woocommerce_get_item_data($item_data, $cart_item ){
        if(isset($cart_item["_yeepdf_product_vouchers_forms"]) && $cart_item["_yeepdf_product_vouchers_forms"] != ""){
            $item_vouchers = json_decode($cart_item["_yeepdf_product_vouchers_forms"],true);
            foreach ($item_vouchers as $name => $voucher) {
                $item_data[] = array("key"=>$voucher["label"],"value"=>($voucher["value"]));
            }
        }
        if(isset($cart_item["_yeepdf_product_vouchers_template_id"]) && $cart_item["_yeepdf_product_vouchers_template_id"] != ""){
            $item_data[] = array("key"=>"Template","value"=>get_the_title($cart_item["_yeepdf_product_vouchers_template_id"]));
        }
        return $item_data;
    }
    function add_values_to_order_item_meta($item_id, $values) {
        global $woocommerce,$wpdb;
        if(isset($values["_yeepdf_product_vouchers_forms"])){
            wc_add_order_item_meta($item_id,'_yeepdf_product_vouchers_forms',$values['_yeepdf_product_vouchers_forms']);
        }
        if(isset($values["_yeepdf_product_vouchers_template_id"])){
            wc_add_order_item_meta($item_id,'_yeepdf_product_vouchers_template_id',$values['_yeepdf_product_vouchers_template_id']);
        }
    }
    function add_values_to_order_item_meta_new($item_id, $item) {
        global $woocommerce,$wpdb;
        if ( ! is_a( $item, 'WC_Order_Item_Product' ) ) {
            return; 
        }
        if ( isset( $item->legacy_values["_yeepdf_product_vouchers_forms"] ) ) {
            wc_add_order_item_meta( $item_id, '_yeepdf_product_vouchers_forms', $item->legacy_values['_yeepdf_product_vouchers_forms'] );
        }
        if ( isset( $item->legacy_values["_yeepdf_product_vouchers_template_id"] ) ) {
            wc_add_order_item_meta( $item_id, '_yeepdf_product_vouchers_template_id', $item->legacy_values['_yeepdf_product_vouchers_template_id'] );
        }
    }
    function get_cart_items_from_session($item,$values,$key) {
        if (array_key_exists( '_yeepdf_product_vouchers_forms', $values ) ) {
            $item['_yeepdf_product_vouchers_forms'] = $values['_yeepdf_product_vouchers_forms'];
        }
        if (array_key_exists( '_yeepdf_product_vouchers_template_id', $values ) ) {
            $item['_yeepdf_product_vouchers_template_id'] = $values['_yeepdf_product_vouchers_template_id'];
        }
        return $item;
    }
    function add_cart_item_data($cart_item_data, $product_id, $variation_id ){
        if ( isset( $_POST['_yeeaddons_product_vouchers_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yeeaddons_product_vouchers_nonce'] ) ) , '_yeeaddons_product_vouchers_nonce' ) ) {
            if ( ! is_object( $product_id ) || ! is_a( $product_id, 'WC_Product' ) ) {
                $product = wc_get_product( $product_id );
                if(isset($_POST['yeeaddons_product_vouchers_forms'])) {
                    $forms = $product->get_meta( '_yeepdf_product_vouchers_forms', true, 'edit' );
                    if(is_array($forms) && count($forms) > 0 ){
                        $inputs =  map_deep( $_POST['yeeaddons_product_vouchers_forms'], 'sanitize_textarea_field' );
                        $inputs =  map_deep( $inputs, 'wp_unslash' );
                        $data_save = array();
                        foreach($forms as $field ){
                            if(isset($inputs[$field["name"]]) && $field["name"] != ""){
                                if($inputs[$field["name"]] != ""){
                                    if(is_array($inputs[$field["name"]])){
                                        $value = implode(", ",$inputs[$field["name"]]);
                                    }else{
                                        $value = $inputs[$field["name"]];
                                    }
                                    $data_save[ $field["name"] ] = array("label"=>$field["label"],"value"=>$value,"type"=>$field["type"]);
                                }
                            }
                        }
                        $cart_item_data['_yeepdf_product_vouchers_forms'] = json_encode($data_save,JSON_UNESCAPED_UNICODE);
                    }
                }
                if(isset($_POST['yeeaddons_product_vouchers_template'])) {
                    //update cart
                    $template_id = sanitize_text_field(wp_unslash($_POST['yeeaddons_product_vouchers_template']));
                    $cart_item_data['_yeepdf_product_vouchers_template_id'] = $template_id;
                }
            }
        }
        return $cart_item_data;
    }
    function validate_add_cart_item($passed, $product_id, $quantity){
        if ( wc()->is_rest_api_request() ) {
            return $passed;
        }
        $required_datas = array();
        if ( ! is_object( $product_id ) || ! is_a( $product_id, 'WC_Product' ) ) {
            $product = wc_get_product( $product_id );
            $forms = $product->get_meta( '_yeepdf_product_vouchers_forms', true, 'edit' );
            if(is_array($forms) && count($forms) > 0 ){
                foreach($forms as $field ){
                    if(isset($field["required"]) && $field["required"] == "yes" ){
                        $required_datas[$field["name"]] = $field["label"];
                    }
                }
            }
            if( count($required_datas) > 0 ){
                if ( isset( $_POST['_yeeaddons_product_vouchers_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['_yeeaddons_product_vouchers_nonce'] ) ) , '_yeeaddons_product_vouchers_nonce' ) ) {
                    if(isset($_POST["yeeaddons_product_vouchers_forms"])) {
                        $inputs =  map_deep( $_POST['yeeaddons_product_vouchers_forms'], 'sanitize_text_field' );
                        $inputs =  map_deep( $inputs, 'wp_unslash' );
                        if(!is_array($inputs)){
                            $inputs = array();
                        }
                        foreach($required_datas as $data => $label){
                            if( !isset($inputs[$data]) || trim($inputs[$data]) == ""){
                                $passed = false;
                                $text_valid = "<strong>".$label."</strong>". __( ' is required.', "pdf-product-vouchers-for-woocommerce" );
                                wc_add_notice( $text_valid, 'error' );
                            }
                        }
                    }
                }else{
                    $passed = false;
                     $text_valid = __( 'Validate Nonce.', "pdf-product-vouchers-for-woocommerce" );
                    wc_add_notice( $text_valid, 'error' );
                }
            }
        }
        return $passed;
    }
    function add_libs(){
        wp_enqueue_style( 'yeeaddons_product_vouchers', YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL."frontend/css/pdf-product-vouchers.css",array(),time() );
        wp_enqueue_style( 'magnific-popup', YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL."frontend/libs/magnific-popup/magnific-popup.css" );
        wp_enqueue_script( 'magnific-popup', YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL."frontend/libs/magnific-popup/jquery.magnific-popup.min.js",array("jquery"));
        wp_enqueue_script( 'yeeaddons_product_vouchers', YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL."frontend/js/pdf-product-vouchers.js",array("jquery"),time() );
        wp_localize_script('yeeaddons_product_vouchers','yeeaddons_product_vouchers',array('nonce' => wp_create_nonce('checkout_file_upload'),"url_plugin"=>YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_URL,'ajax_url' => admin_url( 'admin-ajax.php' ),"text_maximum"=>__("You can upload maximum:","pdf-product-vouchers-for-woocommerce")));
    }
    function has_pdf_product($product,$settings = "enable",$value = "yes"){
        $check = $product->get_meta( '_yee_voucher', true );
        if($check == "yes"){
            return true;
        }else{
            return false;
        }
    }
    function show_fields(){
        global $product;
        if( $this->has_pdf_product($product) ){
            wp_nonce_field( '_yeeaddons_product_vouchers_nonce', '_yeeaddons_product_vouchers_nonce' ); 
            $options = $product->get_meta( '_yeepdf_product_vouchers', true ); 
            $show_fields = $product->get_meta( '_yeepdf_product_vouchers_forms', true );
            $show_templates = $product->get_meta( '_yeepdf_product_vouchers_templates', true );
            if(is_array($show_fields) && count($show_fields) > 0){
            ?>
                <div class="yeeaddons_product_vouchers_fields">
                    <?php
                    $i=0;
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
                        if(isset($field["type"])) {
                            switch( $field["type"] ) {
                                case "text":
                                case "number":
                                case "email":
                                case "date":
                            ?>
                            <div class="yeeaddons_product_vouchers_input">
                                 <label class="yeeaddons_product_vouchers_input_label"><?php echo esc_html( $label.$required_t ) ?></label>
                                <input type="<?php echo esc_attr($field["type"]) ?>" class="yeeaddons_product_vouchers_input_field" name="yeeaddons_product_vouchers_forms[<?php echo esc_attr( $name ) ?>]" <?php echo esc_html($required) ?> value="<?php echo esc_attr($default) ?>"/>
                            </div>
                            <?php
                            break;
                            case "textarea":
                            ?>
                            <div class="yeeaddons_product_vouchers_input">
                                 <label class="yeeaddons_product_vouchers_input_label"><?php echo esc_html( $label.$required_t ) ?></label>
                                 <textarea class="yeeaddons_product_vouchers_textarea_field" name="yeeaddons_product_vouchers_forms[<?php echo esc_attr( $name ) ?>]"><?php echo esc_attr($default) ?></textarea>
                            </div>
                            <?php
                            break;
                            case "select":
                                 ?>
                            <div class="yeeaddons_product_vouchers_input yeeaddons_product_vouchers_select">
                                 <label class="yeeaddons_product_vouchers_input_label"><?php echo esc_html( $label.$required_t ) ?></label>
                                <select name="yeeaddons_product_vouchers_forms[<?php echo esc_attr( $name ) ?>]" <?php echo esc_html($required) ?> class="yeeaddons_product_vouchers_select_field">
                                <?php
                                    $options = explode(PHP_EOL, $default);
                                    if(count($options)> 0) {
                                        foreach($options as $option){
                                            ?>
                                            <option value="<?php echo esc_attr($option) ?>"><?php echo esc_html($option) ?></option>
                                            <?php
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                            <?php
                            break;
                            case "radio":
                            case "checkbox":
                                if($field["type"] == "radio"){
                                    $name_type = "yeeaddons_product_vouchers_forms[".esc_attr( $name)."]";
                                }else{
                                    $name_type = "yeeaddons_product_vouchers_forms[".esc_attr( $name)."][]";
                                }
                              ?>
                            <div class="yeeaddons_product_vouchers_input yeeaddons_product_vouchers_radio">
                                <label class="yeeaddons_product_vouchers_input_label"><?php echo esc_html( $label.$required_t ) ?></label>
                                <div class="yeeaddons_product_vouchers_radio_field">
                                <?php
                                    $options = explode(PHP_EOL, $default);
                                    if(count($options)> 0) {
                                        $j=0;
                                        foreach($options as $option){
                                            ?>
                                            <div class="yeepdf-inner-input">
                                            <input id="<?php echo esc_attr($name."-".$j) ?>" type="<?php echo esc_attr($field["type"]) ?>" name="<?php echo esc_attr( $name_type ) ?>"  value="<?php echo esc_attr($option) ?>"> <label for="<?php echo esc_attr($name."-".$j) ?>"><?php echo esc_html($option) ?></label>
                                            </div>
                                            <?php
                                            $j++;
                                        }
                                    }
                                ?>
                                </div>
                            </div>
                            <?php
                            break;
                        }
                    }
                    $i++;
                    } ?>
                </div>
            <?php
            }
            if( isset($options["preview"]) && $options["preview"] == "yes" && is_array($show_templates) && count($show_templates) > 0){
                ?>
                <div class="yeeaddons_product_vouchers_templates">
                        <?php
                        $template = "";
                        $i = 0;
                        foreach($show_templates as $datas){
                            $class ="";
                            if($i == 0){
                                $template = $datas["template"];
                                $class = "active";
                            }
                            if(filter_var($datas["image"], FILTER_VALIDATE_URL) != FALSE) {
                                $src = $datas["image"];
                            }else{
                                $src = wc_placeholder_img_src();
                            }
                            ?>
                            <a href="<?php echo esc_url($src) ?>" class="<?php echo esc_attr($class) ?>" data-id="<?php echo esc_attr($datas["template"]) ?>"><img src="<?php echo esc_url($src) ?>" alt="<?php echo esc_attr($datas["label"]) ?>" /></a>
                            <?php
                            $i++;
                        }
                        ?>
                        <input type="hidden" id="yeeaddons_product_vouchers_template" name="yeeaddons_product_vouchers_template" value="<?php echo esc_attr($template) ?>">
                </div>
                <?php
            }
        }
    }
    function expiry_product(){
        global $product;
        $expired = $this->check_product_date($product);
        if ( $expired == 'upcoming' ) {
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
            ?>
            <div class="yeeaddons_product_vouchers_status yeeaddons_product_vouchers_status_upcoming">
                <?php echo esc_html__( 'Upcoming product', "pdf-product-vouchers-for-woocommerce" ); ?>
            </div>
            <?php
        }elseif ($expired == 'expired' ) {
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
            ?>
            <div class="yeeaddons_product_vouchers_status yeeaddons_product_vouchers_status_upcoming">
                <?php echo esc_html__( 'Expired product', "pdf-product-vouchers-for-woocommerce" ); ?>
            </div>
            <?php
        }
        else{
        }
    }
    function check_product_date($product){
        $expired = "";
        $options = $product->get_meta( '_yeepdf_product_vouchers', true ); 
        $curent_date = date('Y-m-d\TH:i', current_time('timestamp')); 
        if(isset($options["start_date"]) && $options["start_date"] != ""){
            if(!$this->compare_wp_dates($curent_date,$options["start_date"])){
                return "upcoming";
            }
        }
        if(isset($options["end_date"]) && $options["end_date"] != ""){
            if($this->compare_wp_dates($curent_date,$options["end_date"])){
                 return "expired";
            }
        }
        return $expired;
    }
    function compare_wp_dates($date1,$date2){
        $d1 = new DateTime($date1, wp_timezone());
        $d2 = new DateTime($date2, wp_timezone());
        if ($d1 > $d2) {
            return true;
        } else{
            return false;
        }
    }
}
new Yeeaddons_Woo_PDF_Product_Frontend;