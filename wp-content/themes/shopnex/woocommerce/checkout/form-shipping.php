<?php
/**
 * Checkout Shipping Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs, the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

$checkout = WC()->checkout();
$fields   = $checkout->get_checkout_fields( 'shipping' );
?>

<div class="woocommerce-shipping-fields">
	<?php if ( $fields ) : ?>

	<div class="shipping-fields-grid">
		<?php
		$field_order = array(
			'shipping_first_name',
			'shipping_last_name',
			'shipping_address_1',
			'shipping_address_2',
			'shipping_city',
			'shipping_state',
			'shipping_postcode',
			'shipping_country',
			'shipping_phone',
		);

		$width_classes = array(
			'shipping_first_name' => 'half-width',
			'shipping_last_name'  => 'half-width',
			'shipping_address_1'  => 'full-width',
			'shipping_address_2'  => 'full-width',
			'shipping_city'       => 'third-width',
			'shipping_state'      => 'third-width',
			'shipping_postcode'   => 'third-width',
			'shipping_country'    => 'half-width',
			'shipping_phone'      => 'half-width',
		);

		$triple_fields      = array( 'shipping_city', 'shipping_state', 'shipping_postcode' );
		$in_triple_wrapper  = false;

		foreach ( $field_order as $key ) :
			if ( ! isset( $fields[ $key ] ) ) {
				continue;
			}

			$field = $fields[ $key ];

			if ( ! isset( $field['class'] ) ) {
				$field['class'] = array();
			}
			if ( ! isset( $field['input_class'] ) ) {
				$field['input_class'] = array();
			}

			$field['class'][]        = 'form-field-custom';
			$field['input_class'][]  = 'f-input';

			if ( isset( $width_classes[ $key ] ) ) {
				$field['class'][] = $width_classes[ $key ];
			}

			if ( in_array( $key, $triple_fields, true ) && ! $in_triple_wrapper ) {
				echo '<div class="triple-wrapper">';
				$in_triple_wrapper = true;
			}

			if ( $in_triple_wrapper && ! in_array( $key, $triple_fields, true ) ) {
				echo '</div>';
				$in_triple_wrapper = false;
			}

			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		endforeach;

		if ( $in_triple_wrapper ) {
			echo '</div>';
		}
		?>
	</div>

	<?php endif; ?>
</div>
