<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Frontend_My_Account{
    function __construct(){
        add_filter( 'woocommerce_account_menu_items', array($this,"add_menu") );
        add_action( 'init', array($this,"my_account_endpoint") );
        add_action( 'woocommerce_account_my-voucher_endpoint', array($this,"forms") );
        add_action( 'woocommerce_order_details_after_order_table_items', array($this,"add_detail") );
    }
    function add_detail($order){
        $status = $order->get_status();
        if($status == "completed"){
            foreach ($order->get_items() as $item_id => $item) {
                $voucher_id = wc_get_order_item_meta( $item_id, "_yeepdf_voucher_id", true );
                $_product = $item->get_product();
                $options = $_product->get_meta( '_yeepdf_product_vouchers', true );
                if(isset($options["delivery"]) && $options["delivery"] == "email"){
                    $code = get_post_meta($voucher_id,"_code",true); 
                    ?>
                    <tr>
                        <th scope="row"><?php esc_html_e("Voucher Code","pdf-product-vouchers-for-woocommerce") ?>:</th>
                        <td><?php echo esc_html($code) ?></td>
                    </tr>
                    <?php
                }
            }
        }
    }
    function add_menu($items){
        $new_item = ['my-voucher' => __('Vouchers', 'pdf-product-vouchers-for-woocommerce')];
        $position = array_search('downloads', array_keys($items));
        if ($position !== false) {
            $position += 1; 
        } else {
            $position = 2; 
        }
        $items = array_slice($items, 0, $position, true) + $new_item + array_slice($items, $position, null, true);
        return $items;
    }
    function my_account_endpoint(){
        $options = get_option( 'yeepdf_vouchers', array() );
        if(isset($options["menu_voucher"]) && $options["menu_voucher"] == "yes"){
            add_rewrite_endpoint( 'my-voucher', EP_PAGES );
        }
    }
    function forms(){
        $current_user_id = get_current_user_id();
        $columns = apply_filters(
        'woocommerce_account_downloads_voucher_columns',
            array(
                'download-product'   => __( 'Voucher Code', 'pdf-product-vouchers-for-woocommerce' ),
                'download-expires'   => __( 'Expires', 'pdf-product-vouchers-for-woocommerce' ),
                'download-remaining'      => __( 'Remaining', 'pdf-product-vouchers-for-woocommerce' ),
                //'download-coupon'      => __( 'Coupon', 'pdf-product-vouchers-for-woocommerce' ),
                'download-actions'   => __( 'Download', 'pdf-product-vouchers-for-woocommerce' ),
            )
        );
        $downloads = get_posts(array("post_type"=>"yeepdf_vc_order","author"=>$current_user_id,"numberposts"=>-1));
        ?>
        <section class="woocommerce-order-downloads">
                <h2 class="woocommerce-order-downloads__title"><?php esc_html_e( 'Vouchers', 'pdf-product-vouchers-for-woocommerce' ); ?></h2>
            <?php if ( count($downloads) > 0 ) {
             ?>
            <table class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive order_details">
                <thead>
                    <tr>
                        <?php foreach ( $columns as $column_id => $column_name ) : ?>
                        <th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <?php 
                foreach ( $downloads as $post_data ) {
                    $post_id = $post_data->ID;
                    $product_id = get_post_meta($post_id,"_product_id",true);
                    $product = wc_get_product( $product_id );
                    if($product){
                        $options = $product->get_meta( '_yeepdf_product_vouchers', true );
                    }
                    if(isset($options["delivery"]) && $options["delivery"] == "email"){
                    ?>
                    <tr>
                        <?php foreach ( $columns as $column_id => $column_name ) { ?>
                            <td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
                                <?php
                                if ( has_action( 'woocommerce_account_downloads_voucher_column_' . $column_id ) ) {
                                    do_action( 'woocommerce_account_downloads_voucher_column_' . $column_id, $download );
                                } else {
                                    switch ( $column_id ) {
                                        case 'download-product':
                                            $code = get_post_meta($post_id,"_code",true);
                                            echo esc_html($code);
                                            break;
                                        case 'download-actions':
                                            $pdfs = get_post_meta($post_id,"_pdf",true);
                                            if(isset($pdfs["url"])){
                                                ?>
                                                <a download href="<?php echo esc_url($pdfs["url"]) ?>"><?php esc_html_e("Download","pdf-product-vouchers-for-woocommerce") ?></a>
                                                <?php
                                            }
                                            break;
                                        case 'download-remaining':
                                            $used = 1;
                                            $product_id = get_post_meta($post_id,"_product_id",true);
                                            $_product = wc_get_product($product_id);
                                            if($_product){
                                                $options = $_product->get_meta( '_yeepdf_product_vouchers', true );
                                                if(isset($options["usage_limit"]) && $options["usage_limit"] != 1){
                                                    $used = "unlimited";
                                                }
                                            }
                                            $price = get_post_meta($post_id,"_price",true);
                                            if($used == "unlimited"){
                                                 $after = __("unlimited","pdf-product-vouchers-for-woocommerce");
                                            }else{
                                                $redeemed_amount = 0;
                                                $redeemed = new WP_Query( array("post_type"=>"yeepdf_redeem","post_per_page" => -1,"meta_key"=>"_voucher_id","meta_value"=>$post_id) );
                                               if ( $redeemed->have_posts() ) {
                                                    while ( $redeemed->have_posts() ) {
                                                        $redeemed->the_post();
                                                        $redeemed_id = get_the_ID();
                                                        $redeemed_amount += get_post_meta($redeemed_id,"_redeemed_amount",true);
                                                    }
                                               }
                                               wp_reset_postdata();
                                               $after = $price - $redeemed_amount;
                                               if($after < 0) {
                                                    $after = 0;
                                               }
                                               $after = wc_price($after);
                                            }
                                            echo wp_kses_post($after);
                                            break;
                                        case 'download-coupon':
                                            $coupon = get_post_meta($post_id,"_coupon_code",true);
                                            echo esc_html($coupon);
                                            break;
                                        case 'download-expires':
                                            $expires = get_post_meta($post_id,"_expires",true);
                                            if($expires != ""){
                                                $timestamp = strtotime($expires);
                                                $formatted_date = wp_date(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
                                                echo esc_html($formatted_date);
                                                //
                                            }else{
                                                esc_html_e("Never Expire","pdf-product-vouchers-for-woocommerce");
                                            }
                                            break;
                                    }
                                }
                                ?>
                            </td>
                        <?php }; ?>
                    </tr>
                <?php }}; ?>
            </table>
        <?php }else{
            ?>
            <h3><?php esc_html_e("No Voucher","pdf-product-vouchers-for-woocommerce") ?></h3>
            <?php
        }
         ?>
        </section>
        <?php
    }
}
new Yeeaddons_Woo_PDF_Product_Frontend_My_Account;