<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p><?php echo wp_kses_post($content);?></p>
<?php 
do_action( 'woocommerce_email_footer', $email ); ?>