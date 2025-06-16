<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! empty( $attributes['yeepdf_title'] )  ) {
    $display_text = $attributes['yeepdf_title'];
} else {
    $display_text = "Generate PDF";
}
if ( ! empty( $attributes['yeepdf_template_id'] )  ) {
    $url = add_query_arg(array("pdf_preview"=>"preview","preview"=>1,"id"=>$attributes['yeepdf_template_id']),get_home_url());
    ?>
    <div class="yeepdf-button-download"><a class="wc-block-components-button wp-element-button contained" href="<?php echo esc_url(wp_nonce_url($url,"yeepdf")) ?>" download class="checkout-button button alt wc-forward wp-element-button"><?php echo esc_html($display_text) ?></a></div>
    <?php
} else {
    esc_html_e( "Please choose a Template","pdf-customizer-for-woocommerce" );
}
