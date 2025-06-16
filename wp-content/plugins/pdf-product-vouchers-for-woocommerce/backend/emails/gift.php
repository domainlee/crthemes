<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Yeeaddons_Woo_PDF_Product_Gift_Notification extends WC_Email {
	public $model;
	/**
	 * Constructor
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 2.3.4
	 */
	public function __construct() {
		$this->id = 'yeepdf_gift_notification';
		$this->title = esc_html__('Gift Notification ok', 'pdf-product-vouchers-for-woocommerce');
		$this->description = esc_html__('Gift Notification email will be sent to customer choosen recipient(s) when their order gets access to downloads.', 'pdf-product-vouchers-for-woocommerce');
		$this->heading = esc_html__('Gift Notification', 'pdf-product-vouchers-for-woocommerce');
		$this->subject = esc_html__('You have received a voucher from', 'pdf-product-vouchers-for-woocommerce') . ' [yeepdf_woo_billing_fullname]';
		$this->template_html = 'emails/voucher/gift_template_html.php';
		$this->template_plain = 'emails/voucher/gift_template_text.php';
		$this->template_base  = YEEADDONS_WOO_PDF_PRODUCT_PLUGIN_PATH . 'woocommerce/templates/';
		$this->email_type = $this->get_option( 'email_type' );
		add_action('yeepdf_gift_email_notification', array($this, 'trigger'), 20, 3);
		parent::__construct();
	}
	/**
	 * Gift Notification
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 2.3.4
	 */
	public function trigger($datas_voucher) {
		if ( ! isset($datas_voucher["order_id"]) ) {
            return;
        }
        $this->object = wc_get_order( $datas_voucher["order_id"] );
        if ( $this->get_option('enabled') != "yes" ) {
            return;
        }
        if(is_array($datas_voucher["forms"]) && count($datas_voucher["forms"]) > 0){
        	$datas_replace_forms = array();
        	$datas_replace_forms_html = "";
        	$datas = $datas_voucher;
        	foreach ($datas_voucher["forms"] as $name => $value) {
        		$datas["recipient name='".$name."'"] = $value["value"];
        	}
        	foreach ($datas_voucher["forms"] as $name => $value) {
        		if(isset($value["type"]) && $value["type"] == "email"){
	    			$this->recipient = $value['value'];
	    			$this->forms = $datas_voucher["forms"];
	    			$datas_replace = array();
					foreach ($datas as $key => $value) {
					    $datas_replace["[".$key."]"] = $value;
					}
					$content_ok = str_replace(array_keys($datas_replace), array_values($datas_replace),$this->get_option('custom_content'));
					$this->custom_content = $content_ok;
        			 if($this->get_option('enabled_attachment') !="yes"){
        			 	$pdf = array();
        			 }else{
        			 	$pdf = $datas_voucher["pdf_path"];
        			 }
        			 $get_subject =$this->get_subject();
        			 $get_subject_ok = str_replace(array_keys($datas_replace), array_values($datas_replace),$this->get_subject());
        			$this->send( $this->get_recipient(), $get_subject_ok, $this->get_content(), $this->get_headers(), $pdf );
        		}
        	}
        }
	}
	public function get_content_html() {
	    return wc_get_template_html( $this->template_html, array(
                'order'         => $this->object,
                'content'         => $this->custom_content,
                'forms'         => $this->forms,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text'    => false,
                'email'         => $this,
            ),'', $this->template_base  );
	}
	public function get_content_plain() {
	    return wc_get_template_html( $this->template_html, array(
                'order'         => $this->object,
                'content'         => $this->custom_content,
                'forms'         => $this->forms,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text'    => false,
                'email'         => $this,
            ),'', $this->template_base  );
	}
	/**
	 * Initialize Settings Form Fields
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 2.3.4
	 */
	public function init_form_fields() {
		$des ="Available template tags for subject fields are:</br>
			<code>[pdf_url]</code> Url Download PDF</br>
			<code>[first_name]</code> displays the first name of custome</br>
			<code>[last_name]</code> Url displays the last name of custome</br>
			<code>[voucher_code]</code> Voucher Code</br>
			<code>[recipient name='change_name']</code> Recipient Fields</br>
			<code>[price]</code> Recipient Fields</br>
		";
		$this->form_fields = array(
			'enabled' => array(
				'title' => esc_html__('Enable/Disable', 'pdf-product-vouchers-for-woocommerce'),
				'type' => 'checkbox',
				'label' => esc_html__('Enable this email notification', 'pdf-product-vouchers-for-woocommerce'),
				'default' => 'yes',
			),
			'enabled_attachment' => array(
				'title' => esc_html__('Attachment PDF voucher', 'pdf-product-vouchers-for-woocommerce'),
				'type' => 'checkbox',
				'label' => esc_html__('Add pdf voucher to email attachment', 'pdf-product-vouchers-for-woocommerce'),
				'default' => 'yes',
			),
			'subject' => array(
				'title' => esc_html__('Subject', 'woovoucher'),
				'type' => 'text',
				'description' => '<p class="description">' .
				esc_html__('This is the subject line for the gift notification email.', 'pdf-product-vouchers-for-woocommerce').'</p>',
				'default' => esc_html__('You have received a voucher from', 'pdf-product-vouchers-for-woocommerce') . ' [last_name] [first_name]',
			),
			'heading' => array(
				'title' => __('Email Heading', 'woovoucher'),
				'type' => 'text',
				'description' => esc_html__('This controls the main heading contained within the email notification. Leave blank to use the default heading', 'pdf-product-vouchers-for-woocommerce'),
				'default' => 'Gift Notification',
			),
			'custom_content' => array(
				'title' => esc_html__('Content', 'pdf-product-vouchers-for-woocommerce'),
				'type' => 'textarea',
				'description' => $des,
				'default' => '<p>Hello,</p>
<p>You have been sent a voucher!<</p>
<p>Voucher code: [voucher_code]</p>
<p>Date & Time: [date]</p>
<p>Thank you so much</p>',
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