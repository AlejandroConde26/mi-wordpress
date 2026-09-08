<?php
/**
 * Checkout Order Summary Sidebar
 *
 * Loaded via form-checkout.php. The totals section is a separate template
 * (order-summary-totals.php) registered as an order-review fragment so it is
 * re-rendered by WooCommerce AJAX updates.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;
?>

<aside class="order-summary" id="orderSummary">
	<h2 class="summary-title"><?php esc_html_e( 'Order Summary', 'shopnex' ); ?></h2>

	<div class="summary-items">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<div class="summary-item">
					<div class="summary-item-img">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						if ( ! $product_permalink ) {
							echo wp_kses_post( $thumbnail );
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
						}
						?>
					</div>
					<div class="summary-item-details">
						<div class="summary-item-name">
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
							} else {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
							}
							?>
						</div>
						<?php
						$item_data = wc_get_formatted_cart_item_data( $cart_item );
						if ( $item_data ) :
						?>
							<div class="summary-item-variant"><?php echo wp_kses_post( $item_data ); ?></div>
						<?php endif; ?>
						<div class="summary-item-qty"><?php printf( esc_html__( 'Qty: %d', 'shopnex' ), $cart_item['quantity'] ); ?></div>
					</div>
					<div class="summary-item-price">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) ); ?>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>

	<?php if ( wc_coupons_enabled() ) : ?>
	<div class="s-promo" id="sPromoBox">
		<div class="s-promo-form">
			<input type="text" name="coupon_code" class="s-promo-input" placeholder="<?php esc_attr_e( 'Discount code', 'shopnex' ); ?>" id="promo_code_input">
			<button type="button" class="s-promo-btn" id="apply_promo_btn"><?php esc_html_e( 'Apply', 'shopnex' ); ?></button>
		</div>
		<div class="s-promo-message" id="promoMessage"></div>
	</div>
	<?php endif; ?>

	<?php wc_get_template( 'checkout/order-summary-totals.php' ); ?>

</aside>
