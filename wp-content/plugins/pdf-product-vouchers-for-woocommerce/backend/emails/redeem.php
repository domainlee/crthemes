<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Redeem_Notification extends WC_Email {
	public $model;
	/**
	 * Constructor
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 2.3.4
	 */
	public function __construct() {
		$this->id = 'yeepdf_redeem_notification';
		$this->title = esc_html__('Voucher Redeem Notification ok', 'pdf-product-vouchers-for-woocommerce');
		$this->description = esc_html__('Voucher Redeem Notification Email are sent to chosen recipient(s) when a voucher code is redeemed.', 'pdf-product-vouchers-for-woocommerce');
		$this->heading = esc_html__('Voucher Code Redeemed', 'pdf-product-vouchers-for-woocommerce');
		$this->subject = esc_html__('Voucher code has been redeemed!', 'pdf-product-vouchers-for-woocommerce') . ' [yeepdf_woo_billing_fullname]';
		$this->template_html = 'emails/voucher/redeem_template_html.php';
		$this->template_plain = 'emails/voucher/redeem_template_html.php';
		$this->template_base  = YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH . 'woocommerce/templates/';
		$this->email_type = $this->get_option( 'email_type' );
		add_action('yeepdf_redeem_email_notification', array($this, 'trigger'), 20, 1);
		parent::__construct();
	}
	/**
	 * Gift Notification
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 2.3.4
	 */
	public function trigger($datas = array()) {
        if ( $this->get_option('enabled') != "yes" ) {
            return;
        }
        $voucher_id = $datas["voucher_id"];
        $order_id = get_post_meta( $voucher_id, "_order_id", true );
        $order = wc_get_order( $order_id );
        if (!$order ) {
		    return;
		}
		$voucher_code = get_post_meta($voucher_id,"_code",true);
		$voucher_copoun = get_post_meta($voucher_id,"_coupon_code",true);
		$datas["voucher_code"] = $voucher_code;
		$datas["voucher_copoun"] = $voucher_code;
		$expires = get_post_meta($voucher_id,"_expires",true);
		$datas["voucher_expires"] = $expires;
        $billing_email = $order->get_billing_email();
        if($billing_email != ''){
        	$this->recipient = $billing_email;
			$this->datas = $datas;
			$datas_replace = array();
			foreach ($datas as $key => $value) {
			    $datas_replace["[".$key."]"] = $value;
			}
			$content_ok = str_replace(array_keys($datas_replace), array_values($datas_replace),$this->get_option('custom_content'));
			$this->custom_content = $content_ok;
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        }
	}
	public function get_content_html() {
	   return wc_get_template_html( $this->template_html, array(
                'order'         => $this->object,
                'content'         => $this->custom_content,
                'datas'         => $this->datas,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text'    => false,
                'email'         => $this,
            ),'', $this->template_base );
	}
	public function get_content_plain() {
	    return $this->get_option('custom_content');
	}
	/**
	 * Initialize Settings Form Fields
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 2.3.4
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title' => esc_html__('Enable/Disable', 'pdf-product-vouchers-for-woocommerce'),
				'type' => 'checkbox',
				'label' => esc_html__('Enable this email notification', 'pdf-product-vouchers-for-woocommerce'),
				'default' => 'no',
			),
			'subject' => array(
				'title' => esc_html__('Subject', 'woovoucher'),
				'type' => 'text',
				'default' => 'Voucher code has been redeemed!',
			),
			'heading' => array(
				'title' => __('Email Heading', 'woovoucher'),
				'type' => 'text',
				'default' => esc_html__('Voucher code has been redeemed!', 'pdf-product-vouchers-for-woocommerce'),
			),
			'custom_content' => array(
				'title' => esc_html__('Content', 'pdf-product-vouchers-for-woocommerce'),
				'type' => 'textarea',
				'description' => esc_html__('Choose which format of email to send.', 'pdf-product-vouchers-for-woocommerce'),
				'default' => 'aaa',
			),
			'email_type'         => array(
				'title'       => __( 'Email type', 'woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'     => 'html',
				'class'       => 'email_type wc-enhanced-select',
				'options'     => $this->get_email_type_options(),
				'desc_tip'    => true,
			),
		);
	}
}