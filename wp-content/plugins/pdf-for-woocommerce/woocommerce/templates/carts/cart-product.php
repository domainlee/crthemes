<?php
defined( 'ABSPATH' ) || exit;
//do_action( 'woocommerce_before_cart' ); ?>
<?php //do_action( 'woocommerce_before_cart_table' ); 
?>
<table class="yeepdf-table yeepdf-woocommerce-table" cellspacing="0">
	<thead>
		<tr>
			<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<?php if(isset($data_atts["cart_hide_price"]) && $data_atts["cart_hide_price"] !="yes"){ ?>
			<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
			<?php }
			if(isset($data_atts["cart_hide_qty"]) && $data_atts["cart_hide_qty"] !="yes"){ 
			?>
			<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
			<?php }
			if(isset($data_atts["cart_hide_total"]) && $data_atts["cart_hide_total"] !="yes"){ 
			?>
			<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php //do_action( 'woocommerce_before_cart_contents' ); ?>
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			/**
			 * Filter the product name.
			 *
			 * @since 2.1.0
			 * @param string $product_name Name of the product in the cart.
			 * @param array $cart_item The product in the cart.
			 * @param string $cart_item_key Key for the product in the cart.
			 */
			$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
					<?php
					echo wp_kses_post( $product_name . '&nbsp;' );
					do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
					// Meta data.
					echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
					// Backorder notification.
					if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
						echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
					}
					?>
					</td>
					<?php if(isset($data_atts["cart_hide_price"]) && $data_atts["cart_hide_price"] !="yes"){ ?>
					<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
						?>
					</td>
					<?php }
					if(isset($data_atts["cart_hide_qty"]) && $data_atts["cart_hide_qty"] !="yes"){ 
					?>
					<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
					<?php
					$product_quantity = $cart_item['quantity'];
					echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
					?>
					</td>
					<?php }
					if(isset($data_atts["cart_hide_total"]) && $data_atts["cart_hide_total"] !="yes"){ 
					?>
					<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
						?>
					</td>
					<?php } ?>
				</tr>
				<?php
			}
		}
		?>
		<?php //do_action( 'woocommerce_cart_contents' ); ?>
		<?php //do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>
<?php //do_action( 'woocommerce_after_cart' ); ?>