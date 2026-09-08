<?php
/**
 * Template Hooks
 *
 * @package shopnex
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Archive swatches hook.
 * Renders color swatch dots on product archive cards.
 */
if ( ! function_exists( 'shopnex_archive_swatches' ) ) :
function shopnex_archive_swatches() {
	do_action( 'shopnex_archive_swatches' );
}
endif;

/**
 * Single product swatches hook.
 * Renders color swatch circles on the single product page.
 */
if ( ! function_exists( 'shopnex_single_swatches' ) ) :
function shopnex_single_swatches() {
	do_action( 'shopnex_single_swatches' );
}
endif;

/**
 * Trust signals hook.
 * Renders trust signal icons and text on the single product page.
 */
if ( ! function_exists( 'shopnex_trust_signals' ) ) :
function shopnex_trust_signals() {
	do_action( 'shopnex_trust_signals' );
}
endif;

/**
 * Size chart hook.
 * Renders the size chart button or modal on the single product page.
 *
 * @param string $type Either 'button' or 'modal'.
 */
if ( ! function_exists( 'shopnex_size_chart' ) ) :
function shopnex_size_chart( $type = 'button' ) {
	do_action( 'shopnex_size_chart', $type );
}
endif;

/**
 * Product tabs hook.
 * Renders the dynamic product tabs section on single product pages.
 */
if ( ! function_exists( 'shopnex_product_tabs' ) ) :
function shopnex_product_tabs() {
	do_action( 'shopnex_product_tabs' );
}
endif;

/**
 * Countdown loop hook.
 * Renders countdown timer on shop/archive product cards.
 */
if ( ! function_exists( 'shopnex_countdown_loop' ) ) :
function shopnex_countdown_loop() {
	do_action( 'shopnex_countdown_loop' );
}
endif;

/**
 * Wishlist button hook.
 * Renders the wishlist button on the single product page.
 */
if ( ! function_exists( 'shopnex_wishlist_button' ) ) :
function shopnex_wishlist_button() {
	do_action( 'shopnex_wishlist_button' );
}
endif;

/**
 * Countdown single product hook.
 * Renders countdown timer on the single product page, before the category/title.
 */
if ( ! function_exists( 'shopnex_countdown_single' ) ) :
function shopnex_countdown_single() {
	do_action( 'shopnex_countdown_single' );
}
endif;

/**
 * Before Title Wrapper
 */
if( !function_exists( 'shopnex_before_title' ) ):
    function shopnex_before_title() {
        do_action( 'shopnex_before_title' );
    }
endif;

/**
 * After Title Wrapper
 */
if( !function_exists( 'shopnex_after_title' ) ):
    function shopnex_after_title() {
        do_action( 'shopnex_after_title' );
    }
endif;

/**
 * Before Title Content
 */
if( !function_exists( 'shopnex_before_title_content' ) ):
    function shopnex_before_title_content() {
        do_action( 'shopnex_before_title_content' );
    }
endif;

/**
 * After Title Content
 */
if( !function_exists( 'shopnex_after_title_content' ) ):
    function shopnex_after_title_content() {
        do_action( 'shopnex_after_title_content' );
    }
endif;
