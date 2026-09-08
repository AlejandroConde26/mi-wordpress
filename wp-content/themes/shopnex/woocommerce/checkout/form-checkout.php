<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs, the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

$checkout = WC()->checkout();
?>

<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

<?php if ( WC()->cart->is_empty() ) : ?>
	<div class="woocommerce-checkout cart-empty-wrapper">
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

<?php if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) : ?>
	<?php esc_html_e( 'You must be logged in to checkout.', 'shopnex' ); ?>
	<?php return; ?>
<?php endif; ?>

<h1 class="page-title checkout-page-title"><?php esc_html_e( 'Checkout', 'shopnex' ); ?></h1>

<div class="steps-indicator">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="step-dot completed">
		<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
	</a>
	<span class="step-line completed"></span>
	<span class="step-dot active-step">2</span>
	<span class="step-line"></span>
	<span class="step-dot">3</span>
</div>
<div class="step-label-group">
	<span class="step-label" style="color:var(--text-primary);"><?php esc_html_e( 'Cart', 'shopnex' ); ?></span>
	<span class="step-label active-label"><?php esc_html_e( 'Checkout', 'shopnex' ); ?></span>
	<span class="step-label"><?php esc_html_e( 'Confirmation', 'shopnex' ); ?></span>
</div>

<div class="checkout-layout">

	<div class="checkout-main">

		<?php if ( $checkout->get_checkout_fields() ) : ?>

		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="checkout-section" id="section-shipping">
				<div class="section-title-row">
					<span class="section-number">1</span>
					<h2 class="section-heading"><?php esc_html_e( 'Shipping Information', 'shopnex' ); ?></h2>
				</div>

				<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
				<div class="login-prompt">
					<span><?php esc_html_e( 'Already have an account?', 'shopnex' ); ?></span>
					<a href="<?php echo esc_url( wp_login_url( wc_get_checkout_url() ) ); ?>">
						<?php esc_html_e( 'Log in', 'shopnex' ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
					</a>
				</div>
				<?php endif; ?>

				<?php do_action( 'woocommerce_checkout_billing' ); ?>

				<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
				<div class="check-row" style="margin-top:16px;">
					<input type="checkbox" class="f-check" id="createaccount" name="createaccount" value="1" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?>>
					<label class="check-label" for="createaccount"><?php esc_html_e( 'Create an account for faster checkout', 'shopnex' ); ?></label>
				</div>
				<?php endif; ?>

				<div class="check-row ship-to-different" style="margin-top:12px;">
					<input type="checkbox" class="f-check" id="ship-to-different-address-checkbox" name="ship_to_different_address" value="1">
					<label class="check-label" for="ship-to-different-address-checkbox"><?php esc_html_e( 'Ship to a different address?', 'shopnex' ); ?></label>
				</div>

				<div class="shipping-address-fields" id="shipping-address-fields">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			</div>

			<div class="checkout-section" id="section-payment">
				<div class="section-title-row">
					<span class="section-number">2</span>
					<h2 class="section-heading"><?php esc_html_e( 'Payment Details', 'shopnex' ); ?></h2>
				</div>

				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php
					wc_get_template( 'checkout/review-order.php' );

					$available_gateways = array();
					if ( WC()->cart && WC()->cart->needs_payment() ) {
						$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
						WC()->payment_gateways()->set_current_gateway( $available_gateways );
					}

					wc_get_template(
						'checkout/payment.php',
						array(
							'checkout'           => WC()->checkout(),
							'available_gateways' => $available_gateways,
							'order_button_text'  => apply_filters( 'woocommerce_order_button_text', esc_html__( 'Place order', 'shopnex' ) ),
						)
					);
					?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>

	</form>

	<?php endif; ?>

</div>

<?php wc_get_template( 'checkout/order-summary.php' ); ?>

</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php endif; ?>
