<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
add_filter( 'woocommerce_email_classes', 'yeepdf_wc_add_custom_notification_email_class' );
function yeepdf_wc_add_custom_notification_email_class($emails){
    require_once YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH . 'backend/emails/gift.php';
    require_once YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH . 'backend/emails/redeem.php';
    $emails['Yeeaddons_Woo_PDF_Product_Gift_Notification'] = new Yeeaddons_Woo_PDF_Product_Gift_Notification();
    $emails['Yeeaddons_Woo_PDF_Product_Redeem_Notification'] = new Yeeaddons_Woo_PDF_Product_Redeem_Notification();
    return $emails;
}
 add_filter('woocommerce_email_actions', 'yeepdf_wc_add_actions_custom_notification_email_class');
 function yeepdf_wc_add_actions_custom_notification_email_class($email_actions){
    $email_actions[] = "yeepdf_gift_email_notification";
    $email_actions[] = "yeepdf_redeem_email_notification";
    return $email_actions;
 }