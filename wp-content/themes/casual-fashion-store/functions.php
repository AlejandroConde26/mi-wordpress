<?php
/**
 * Casual Fashion Store - Child Theme Functions
 *
 * @package Casual_Fashion_Store
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register child theme pattern categories.
 */
function casual_fashion_store_register_pattern_categories() {
	register_block_pattern_category(
		'casual-fashion-store-pages',
		array(
			'label' => __( 'Casual Fashion Store Pages', 'casual-fashion-store' ),
		)
	);
}
add_action( 'init', 'casual_fashion_store_register_pattern_categories' );

/**
 * Enqueue parent and child theme stylesheets.
 */
function casual_fashion_store_enqueue_styles() {
	$parent_style = 'casual-fashion-store-parent-style';

	wp_enqueue_style(
		$parent_style,
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);

	wp_enqueue_style(
		'casual-fashion-store-style',
		get_stylesheet_uri(),
		array( $parent_style ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'casual_fashion_store_enqueue_styles' );

/**
 * Show the Fashion Nook admin notice for this child theme.
 */
add_filter( 'fashion_nook_show_admin_notice', '__return_true' );
