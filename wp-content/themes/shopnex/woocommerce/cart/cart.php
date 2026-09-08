<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs, the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<?php if ( WC()->cart->is_empty() ) : ?>

	<div class="woocommerce-cart cart-empty-wrapper">
		<div class="empty-cart show">
			<div class="empty-icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
			</div>
			<h2><?php esc_html_e( 'Your Cart is Empty', 'shopnex' ); ?></h2>
			<p><?php esc_html_e( 'Looks like you haven\'t added anything to your cart yet. Explore our collections and find something you love.', 'shopnex' ); ?></p>
			<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
				<a class="btn-shop" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
					<?php esc_html_e( 'Start Shopping', 'shopnex' ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
				</a>
			<?php endif; ?>
		</div>
	</div>

<?php else : ?>

	<h1 class="cart-page-title"><?php esc_html_e( 'Your Cart', 'shopnex' ); ?></h1>

	<div class="cart-layout">
		<form class="woocommerce-cart-form cart-items" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				/**
				 * Filter the product name.
				 *
				 * @since 2.1.0
				 * @param string $product_name Name of the product in the cart.
				 * @param array  $cart_item    The product in the cart.
				 * @param string $cart_item_key Key for the product in the cart.
				 */
				$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<div class="cart-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">

						<div class="item-image">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo wp_kses_post( $thumbnail );
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
							}
							?>
						</div>

						<div class="item-details">
							<div class="item-name">
								<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( $product_name . '&nbsp;' );
								} else {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
								}
								?>
							</div>

							<?php do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key ); ?>

							<?php
							$item_data = wc_get_formatted_cart_item_data( $cart_item );
							if ( $item_data ) :
							?>
							<div class="item-variant"><?php echo wp_kses_post( $item_data ); ?></div>
							<?php endif; ?>

							<?php
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'shopnex' ) . '</p>', $product_id ) );
							}
							?>

							<div class="item-price">
								<?php echo wp_kses_post( WC()->cart->get_product_price( $_product ) ); ?>
							</div>
						</div>

						<div class="item-actions">
							<div class="quantity-control">
								<?php
								if ( $_product->is_sold_individually() ) {
									$min_quantity = 1;
									$max_quantity = 1;
								} else {
									$min_quantity = 0;
									$max_quantity = $_product->get_max_purchase_quantity();
								}

								$product_quantity = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $max_quantity,
										'min_value'    => $min_quantity,
										'product_name' => $product_name,
									),
									$_product,
									false
								);

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								?>
							</div>
							<?php
							echo wp_kses_post( apply_filters(
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a role="button" href="%s" class="remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">%s</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_attr( sprintf( __( 'Remove %s from cart', 'shopnex' ), wp_strip_all_tags( $product_name ) ) ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() ),
									esc_attr( $cart_item_key ),
									esc_html__( 'Remove', 'shopnex' )
								),
								$cart_item_key
							) );
							?>
						</div>

						<input type="hidden" name="cart_item_key" value="<?php echo esc_attr( $cart_item_key ); ?>">
					</div>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<button type="submit" class="button update-cart-btn" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'shopnex' ); ?>" style="display:none;"><?php esc_html_e( 'Update cart', 'shopnex' ); ?></button>

			<?php do_action( 'woocommerce_cart_actions' ); ?>
			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</form>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>

		<aside class="cart-summary">
			<h2 class="summary-title"><?php esc_html_e( 'Order Summary', 'shopnex' ); ?></h2>

			<?php do_action( 'woocommerce_before_cart_totals' ); ?>

			<div class="summary-products">
				<?php
				foreach ( WC()->cart->get_cart() as $item_key => $item ) {
					$item_product    = apply_filters( 'woocommerce_cart_item_product', $item['data'], $item, $item_key );
					$item_product_id = apply_filters( 'woocommerce_cart_item_product_id', $item['product_id'], $item, $item_key );

					if ( $item_product && $item_product->exists() && $item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $item, $item_key ) ) {
						$item_permalink = apply_filters( 'woocommerce_cart_item_permalink', $item_product->is_visible() ? $item_product->get_permalink( $item ) : '', $item, $item_key );
						$item_thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $item_product->get_image( 'woocommerce_thumbnail' ), $item, $item_key );
						$item_name      = apply_filters( 'woocommerce_cart_item_name', $item_product->get_name(), $item, $item_key );
						?>
						<div class="summary-product-item" data-cart-item-key="<?php echo esc_attr( $item_key ); ?>">
							<div class="summary-product-thumb">
								<?php
								if ( ! $item_permalink ) {
									echo wp_kses_post( $item_thumbnail );
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $item_permalink ), wp_kses_post( $item_thumbnail ) );
								}
								?>
							</div>
							<div class="summary-product-info">
								<span class="summary-product-name">
									<?php
									if ( ! $item_permalink ) {
										echo wp_kses_post( $item_name );
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $item_permalink ), wp_kses_post( $item_name ) );
									}
									?>
								</span>
								<span class="summary-product-qty"><?php echo esc_html( $item['quantity'] ); ?> &times; <?php echo wp_kses_post( WC()->cart->get_product_price( $item_product ) ); ?></span>
							</div>
							<div class="summary-product-total">
								<?php
								echo wp_kses_post( apply_filters(
									'woocommerce_cart_item_subtotal',
									WC()->cart->get_product_subtotal( $item_product, $item['quantity'] ),
									$item,
									$item_key
								) );
								?>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>

			<div class="summary-row summary-subtotal">
				<span><?php esc_html_e( 'Subtotal', 'shopnex' ); ?></span>
				<span class="summary-value"><?php wc_cart_totals_subtotal_html(); ?></span>
			</div>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="summary-row discount-line">
				<span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
				<span class="summary-value discount"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
			</div>
			<?php endforeach; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="summary-row">
				<span><?php echo esc_html( $fee->name ); ?></span>
				<span class="summary-value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</div>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<div class="summary-row">
				<span><?php esc_html_e( 'Shipping', 'shopnex' ); ?></span>
				<span class="summary-value" id="shippingVal"><?php echo WC()->cart->get_cart_shipping_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>
			<?php elseif ( WC()->cart->needs_shipping() ) : ?>
			<div class="summary-row">
				<span><?php esc_html_e( 'Shipping', 'shopnex' ); ?></span>
				<span class="summary-value"><?php esc_html_e( 'Calculated at checkout', 'shopnex' ); ?></span>
			</div>
			<?php endif; ?>

			<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<div class="summary-row">
				<span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
				<span class="summary-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
			</div>
			<?php endif; ?>

			<?php if ( wc_coupons_enabled() ) : ?>
			<div class="promo-input-group">
				<form class="promo-box" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
					<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'shopnex' ); ?></label>
					<input type="text" name="coupon_code" class="promo-input" id="coupon_code" placeholder="<?php esc_attr_e( 'Promo code', 'shopnex' ); ?>">
					<button type="submit" class="promo-btn" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'shopnex' ); ?>"><?php esc_html_e( 'Apply', 'shopnex' ); ?></button>
				</form>
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
			</div>
			<?php endif; ?>

			<div class="summary-row total">
				<span><?php esc_html_e( 'Total', 'shopnex' ); ?></span>
				<span class="summary-value"><?php wc_cart_totals_order_total_html(); ?></span>
			</div>

			<?php if ( wc_tax_enabled() ) : ?>
			<p class="summary-tax"><?php echo wp_kses_post( WC()->cart->get_taxes_total() ); ?></p>
			<?php endif; ?>

			<div class="wc-proceed-to-checkout">
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn-checkout">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
					<?php esc_html_e( 'Proceed to Checkout', 'shopnex' ); ?>
				</a>
			</div>

			<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn-continue-shopping">
				<?php esc_html_e( 'Continue Shopping', 'shopnex' ); ?>
			</a>

			<?php do_action( 'woocommerce_after_cart_totals' ); ?>
		</aside>
	</div>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

	<div class="cart-collaterals">
		<?php do_action( 'woocommerce_cart_collaterals' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart' ); ?>

<?php endif; ?>
