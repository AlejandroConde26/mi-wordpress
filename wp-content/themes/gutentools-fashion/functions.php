<?php


/**
 * Gutentools Fashion functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @subpackage Gutentools Fashion
 * @since Gutentools Fashion 1.0
 */

 function gutentools_fashion_block_assets(){
    // Enqueue theme stylesheet for the front-end.
    wp_enqueue_style( 'gutentools-fashion-style', get_stylesheet_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );   
}

add_action('enqueue_block_assets', 'gutentools_fashion_block_assets');

// register own theme pattern

function gutentools_fashion_register_pattern_category() {

	$patterns = array();

	$block_pattern_categories = array(
		'gutentools-fashion' => array( 'label' => __( 'Gutentools Fashion', 'gutentools-fashion' ) )
	);

	$block_pattern_categories = apply_filters( 'gutentools_fashion_block_pattern_categories', $block_pattern_categories );

	foreach ( $block_pattern_categories as $name => $properties ) {
		if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
			register_block_pattern_category( $name, $properties );
		}
	}
}

add_action( 'init', 'gutentools_fashion_register_pattern_category');