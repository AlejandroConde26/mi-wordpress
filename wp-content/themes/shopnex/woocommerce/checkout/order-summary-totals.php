<?php
/**
 * Checkout Order Summary — Totals
 *
 * Rendered inside the order summary sidebar and registered as a
 * `woocommerce_update_order_review_fragments` fragment (see inc/woocommerce.php),
 * so applied coupons, fees, shipping, taxes and the order total stay in sync
 * after WooCommerce AJAX checkout updates.
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="summary-totals" id="orderSummaryTotals">

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
	<div class="s-promo-applied show">
		<span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
		<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
	</div>
	<?php endforeach; ?>

	<div class="summary-divider"></div>

	<div class="summary-row">
		<span><?php esc_html_e( 'Subtotal', 'shopnex' ); ?></span>
		<span><?php wc_cart_totals_subtotal_html(); ?></span>
	</div>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
	<div class="summary-row">
		<span><?php echo esc_html( $fee->name ); ?></span>
		<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
	</div>
	<?php endforeach; ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
	<div class="summary-row">
		<span><?php esc_html_e( 'Shipping', 'shopnex' ); ?></span>
		<span class="free-shipping"><?php echo wp_kses_post( WC()->cart->get_cart_shipping_total() ); ?></span>
	</div>
	<?php elseif ( WC()->cart->needs_shipping() ) : ?>
	<div class="summary-row">
		<span><?php esc_html_e( 'Shipping', 'shopnex' ); ?></span>
		<span><?php esc_html_e( 'Calculated at next step', 'shopnex' ); ?></span>
	</div>
	<?php endif; ?>

	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
	<div class="summary-row">
		<span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
		<span><?php wc_cart_totals_taxes_total_html(); ?></span>
	</div>
	<?php endif; ?>

	<div class="summary-row total">
		<span><?php esc_html_e( 'Total', 'shopnex' ); ?></span>
		<span><?php wc_cart_totals_order_total_html(); ?></span>
	</div>

	<?php if ( wc_tax_enabled() && WC()->cart->get_taxes_total() ) : ?>
	<p class="s-tax-note"><?php printf( esc_html__( 'Including %s in taxes', 'shopnex' ), wp_kses_post( WC()->cart->get_taxes_total() ) ); ?></p>
	<?php endif; ?>

</div>
