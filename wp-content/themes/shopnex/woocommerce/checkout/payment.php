<?php
/**
 * Checkout Payment
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it
 * does happen. When this occurs, the version of the template file will be
 * bumped and the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $available_gateways ) ) {
	if ( WC()->cart && WC()->cart->needs_payment() ) {
		$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
	} else {
		$available_gateways = array();
	}
}

if ( ! isset( $order_button_text ) ) {
	$order_button_text = apply_filters( 'woocommerce_order_button_text', esc_html__( 'Place order', 'shopnex' ) );
}

// Customizer toggle: use WooCommerce's default payment section instead of the theme's card-style selector.
$shopnex_default_payment = (bool) get_theme_mod( 'shopnex_default_checkout_payment', false );

$default_description = '';
if ( ! $shopnex_default_payment && ! empty( $available_gateways ) ) {
	foreach ( $available_gateways as $gw ) {
		if ( $gw->chosen && $gw->get_description() ) {
			$default_description = wp_strip_all_tags( $gw->get_description() );
			break;
		}
	}
}
?>

<div id="payment" class="woocommerce-checkout-payment<?php echo $shopnex_default_payment ? ' shopnex-default-payment' : ''; ?>">

	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>

		<?php if ( $shopnex_default_payment ) : ?>

			<h2 class="section-label" id="payment-method-label"><?php esc_html_e( 'Payment method', 'shopnex' ); ?></h2>

			<ul class="wc_payment_methods payment_methods methods" aria-label="<?php esc_attr_e( 'Payment methods', 'shopnex' ); ?>">
				<?php if ( ! empty( $available_gateways ) ) : ?>
					<?php
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
					?>
				<?php else : ?>
					<li>
						<?php
						$no_methods_message = ( WC()->customer && WC()->customer->get_billing_country() )
							? esc_html__( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'shopnex' )
							: esc_html__( 'Please fill in your details above to see available payment methods.', 'shopnex' );
						wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', $no_methods_message ), 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
						?>
					</li>
				<?php endif; ?>
			</ul>

		<?php else : ?>

			<h2 class="section-label" id="payment-method-label"><?php esc_html_e( 'Payment method', 'shopnex' ); ?></h2>

			<div class="payment-grid" role="radiogroup" aria-label="<?php esc_attr_e( 'Payment method', 'shopnex' ); ?>">
				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					$message = WC()->cart->needs_payment()
						? esc_html__( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'shopnex' )
						: esc_html__( 'Please log in to your account to view available payment methods.', 'shopnex' );
					echo '<div class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . wp_kses_post( apply_filters( 'woocommerce_no_available_payment_methods_message', $message ) ) . '</div>';
				}
				?>
			</div>

			<?php if ( ! empty( $available_gateways ) ) : ?>
			<div class="description-box" id="payment-desc" aria-live="polite"<?php echo empty( $default_description ) ? ' style="display:none;"' : ''; ?>>
				<?php echo esc_html( $default_description ); ?>
			</div>
			<?php endif; ?>

			<ul class="wc_payment_methods payment_methods methods" style="display:none !important;">
				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						?>
						<li class="wc_payment_method" id="wc-payment-method-details-<?php echo esc_attr( $gateway->id ); ?>" data-gateway="<?php echo esc_attr( $gateway->id ); ?>">
							<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
								<div class="payment-method-details">
									<?php $gateway->payment_fields(); ?>
								</div>
							<?php endif; ?>
						</li>
						<?php
					}
				}
				?>
			</ul>

		<?php endif; ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

	<?php
	// Terms, conditions and privacy policy
	wc_get_template( 'checkout/terms.php' );
	?>

	<?php
	$no_payment_methods = empty( $available_gateways ) && WC()->cart->needs_payment();
	$button_disabled    = $no_payment_methods ? ' disabled' : '';
	$button_class       = $no_payment_methods ? 'button alt place-order-btn disabled' : 'button alt place-order-btn';
	?>
	<div class="place-order-btn-wrap">
		<?php
		echo wp_kses_post(
			apply_filters(
				'woocommerce_order_button_html',
				'<button type="submit" class="' . esc_attr( $button_class ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '"' . $button_disabled . '>' . esc_html( $order_button_text ) . '</button>'
			)
		);
		?>
	</div>

	<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

	<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
</div>
