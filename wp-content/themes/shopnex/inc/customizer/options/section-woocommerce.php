<?php
/**
 * Theme Customizer Controls - WooCommerce
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'shopnex_customizer_woocommerce_register' ) ) :
function shopnex_customizer_woocommerce_register( $wp_customize ) {

	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	/**
	 * Checkout Settings Section
	 *
	 */
	$wp_customize->add_section(
		'shopnex_woocommerce_checkout',
		array(
			'title'    => esc_html__( 'Checkout', 'shopnex' ),
			'panel'    => 'woocommerce',
			'priority' => 30,
		)
	);

	// Title label - Block Checkout.
	$wp_customize->add_setting(
		'shopnex_woocommerce_checkout_label',
		array(
			'sanitize_callback' => 'shopnex_sanitize_title',
		)
	);

	$wp_customize->add_control(
		new Shopnex_Title_Info_Control( $wp_customize, 'shopnex_woocommerce_checkout_label',
		array(
			'label'    => esc_html__( 'Block Checkout', 'shopnex' ),
			'section'  => 'shopnex_woocommerce_checkout',
			'type'     => 'shopnex-title',
			'settings' => 'shopnex_woocommerce_checkout_label',
		)
	));

	// Enable Block Checkout.
	$wp_customize->add_setting(
		'shopnex_enable_block_checkout',
		array(
			'default'           => false,
			'type'              => 'theme_mod',
			'sanitize_callback' => 'shopnex_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new Shopnex_Toggle_Control( $wp_customize, 'shopnex_enable_block_checkout',
		array(
			'label'       => esc_html__( 'Enable Block Checkout', 'shopnex' ),
			'description' => esc_html__( 'Use the default WooCommerce Block checkout. When enabled, the theme classic checkout template overrides are disabled.', 'shopnex' ),
			'section'     => 'shopnex_woocommerce_checkout',
			'type'        => 'shopnex-toggle',
			'settings'    => 'shopnex_enable_block_checkout',
		)
	));

	// Title label - Default Payment Section.
	$wp_customize->add_setting(
		'shopnex_woocommerce_checkout_payment_label',
		array(
			'sanitize_callback' => 'shopnex_sanitize_title',
		)
	);

	$wp_customize->add_control(
		new Shopnex_Title_Info_Control( $wp_customize, 'shopnex_woocommerce_checkout_payment_label',
		array(
			'label'    => esc_html__( 'Payment Section', 'shopnex' ),
			'section'  => 'shopnex_woocommerce_checkout',
			'type'     => 'shopnex-title',
			'settings' => 'shopnex_woocommerce_checkout_payment_label',
		)
	));

	// Default Payment Section.
	$wp_customize->add_setting(
		'shopnex_default_checkout_payment',
		array(
			'default'           => false,
			'type'              => 'theme_mod',
			'sanitize_callback' => 'shopnex_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new Shopnex_Toggle_Control( $wp_customize, 'shopnex_default_checkout_payment',
		array(
			'label'       => esc_html__( 'Default Payment Section', 'shopnex' ),
			'description' => esc_html__( 'Use WooCommerce default payment section with full gateway fields (e.g. credit card inputs). When disabled, the theme card-style payment selector is shown.', 'shopnex' ),
			'section'     => 'shopnex_woocommerce_checkout',
			'type'        => 'shopnex-toggle',
			'settings'    => 'shopnex_default_checkout_payment',
		)
	));

	/**
	 * Cart Settings Section
	 *
	 * Attached to the native `woocommerce` panel that the WooCommerce
	 * plugin registers automatically.
	 */
	$wp_customize->add_section(
		'shopnex_woocommerce_cart',
		array(
			'title'    => esc_html__( 'Cart', 'shopnex' ),
			'panel'    => 'woocommerce',
			'priority' => 40,
		)
	);

	// Title label - Block Cart.
	$wp_customize->add_setting(
		'shopnex_woocommerce_cart_label',
		array(
			'sanitize_callback' => 'shopnex_sanitize_title',
		)
	);

	$wp_customize->add_control(
		new Shopnex_Title_Info_Control( $wp_customize, 'shopnex_woocommerce_cart_label',
		array(
			'label'    => esc_html__( 'Block Cart', 'shopnex' ),
			'section'  => 'shopnex_woocommerce_cart',
			'type'     => 'shopnex-title',
			'settings' => 'shopnex_woocommerce_cart_label',
		)
	));

	// Enable Block Cart.
	$wp_customize->add_setting(
		'shopnex_enable_block_cart',
		array(
			'default'           => false,
			'type'              => 'theme_mod',
			'sanitize_callback' => 'shopnex_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new Shopnex_Toggle_Control( $wp_customize, 'shopnex_enable_block_cart',
		array(
			'label'       => esc_html__( 'Enable Block Cart', 'shopnex' ),
			'description' => esc_html__( 'Use the default WooCommerce Block cart. When enabled, the theme classic cart template overrides are disabled.', 'shopnex' ),
			'section'     => 'shopnex_woocommerce_cart',
			'type'        => 'shopnex-toggle',
			'settings'    => 'shopnex_enable_block_cart',
		)
	));
}
endif;

add_action( 'customize_register', 'shopnex_customizer_woocommerce_register' );
