<?php
defined( 'ABSPATH' ) || exit;
?>
<table class="yeepdf-table yeepdf-woocommerce-table" cellspacing="0">
    <tr class="cart-subtotal">
        <th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
        <td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">$200.00</td>
    </tr>
    <tr class="cart-discount">
        <th>Coupon</th>
        <td >-$50.00</td>
    </tr>
    <tr class="shipping">
        <th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
        <td>$50.00</td>
    </tr>
    <tr class="fee">
        <th>Fee</th>
        <td>$50.00</td>
    </tr>
    <tr class="tax-total">
        <th>VAT</th>
        <td>$50.00</td>
    </tr>
    <tr class="order-total">
        <th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
        <td>$300.00</td>
    </tr>
</table>    