<?php
/**
 * Payment Method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs, the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$gateway_id    = $args['gateway']->id;
$gateway_title = $args['gateway']->get_title();
$is_selected   = $args['gateway']->chosen;
$description   = $args['gateway']->get_description() ? wp_strip_all_tags( $args['gateway']->get_description() ) : '';

$payment_icons = array(
	'bacs'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 4l9 6.5"/><path d="M5 10.5V20h14V10.5"/><path d="M9 20v-6h6v6"/></svg>',
	'cheque' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h6M7 13h4"/><circle cx="17.5" cy="12.5" r="2.5"/></svg>',
	'cod'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 13h13V7H3v6z"/><path d="M16 10h3l2 3v2h-5z"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="17.5" cy="17.5" r="1.5"/></svg>',
);

$default_icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h6M7 13h4"/><circle cx="17.5" cy="12.5" r="2.5"/></svg>';
$icon         = isset( $payment_icons[ $gateway_id ] ) ? $payment_icons[ $gateway_id ] : $default_icon;
$gateway_icon = $args['gateway']->get_icon();

$allowed_svg = array(
	'svg'    => array( 'viewbox' => true, 'xmlns' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'class' => true, 'width' => true, 'height' => true ),
	'path'   => array( 'd' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true ),
	'rect'   => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'fill' => true, 'stroke' => true ),
	'circle' => array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true ),
	'img'    => array( 'src' => true, 'alt' => true, 'class' => true, 'width' => true, 'height' => true ),
);

$gateway = $args['gateway'];

// Customizer toggle: render WooCommerce's default payment method
if ( (bool) get_theme_mod( 'shopnex_default_checkout_payment', false ) ) :
	?>
	<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway_id ); ?>">
		<input
			id="payment_method_<?php echo esc_attr( $gateway_id ); ?>"
			type="radio"
			class="input-radio"
			name="payment_method"
			value="<?php echo esc_attr( $gateway_id ); ?>"
			<?php checked( $is_selected, true ); ?>
			data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"
		/>
		<label for="payment_method_<?php echo esc_attr( $gateway_id ); ?>">
			<?php echo esc_html( $gateway_title ); ?>
			<?php echo $gateway_icon ? wp_kses( $gateway_icon, $allowed_svg ) : ''; ?>
		</label>
		<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
			<div class="payment_box payment_method_<?php echo esc_attr( $gateway_id ); ?>"<?php echo ! $is_selected ? ' style="display:none;"' : ''; ?>>
				<?php $gateway->payment_fields(); ?>
			</div>
		<?php endif; ?>
	</li>
	<?php
	return;
endif;
?>

<div class="payment-option">
	<input
		type="radio"
		name="payment_method"
		id="payment_method_<?php echo esc_attr( $gateway_id ); ?>"
		value="<?php echo esc_attr( $gateway_id ); ?>"
		<?php checked( $is_selected, true ); ?>
		class="payment-radio-hidden"
		data-order_button_text="<?php echo esc_attr( $args['gateway']->order_button_text ); ?>"
		data-desc="<?php echo esc_attr( $description ); ?>"
		autocomplete="off"
	/>
	<label class="payment-card" for="payment_method_<?php echo esc_attr( $gateway_id ); ?>">
		<span class="icon-circle" aria-hidden="true">
			<?php echo $gateway_icon ? wp_kses( $gateway_icon, $allowed_svg ) : wp_kses( $icon, $allowed_svg ); ?>
		</span>
		<span class="payment-label-text"><?php echo esc_html( $gateway_title ); ?></span>
	</label>
</div>
