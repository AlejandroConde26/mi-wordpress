<?php
/**
 * Shopnex Theme Customizer Helper Functions
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


/**
* Render callback for site title
* 
* @return void
*/
function shopnex_site_title_callback() {
    echo esc_html( get_bloginfo( 'name' ) );
}

/**
* Render callback for site description
*
* @return void
*/
function shopnex_site_description_callback() {
    echo esc_html( get_bloginfo( 'description' ) );
}


/**
 * Check if the top bar is enabled or not
 */
function shopnex_header_topbar_enable( $control ) {
	if ( $control->manager->get_setting( 'shopnex_enable_header_topbar' )->value() === true ) :
		return true;
	else :
		return false;
	endif;
}


/**
 * Check if the header product category menu is enabled or not
 */
function shopnex_header_product_category_menu_enable( $control ) {
	if ( $control->manager->get_setting( 'shopnex_enable_header_category_menu' )->value() === true ) :
		return true;
	else :
		return false;
	endif;
}


/**
 * Check if the header product custom menu is enabled or not
 */
function shopnex_header_product_custom_menu_enable( $control ) {
	if ( $control->manager->get_setting( 'shopnex_enable_header_category_menu' )->value() === true ) :
		return true;
	else :
		return false;
	endif;
}

/**
 * Check if the header menu cart is enabled or not
 */
function shopnex_header_menucart_enable( $control ) {
	if ( $control->manager->get_setting( 'shopnex_enable_header_menucart' )->value() === true ) :
		return true;
	else :
		return false;
	endif;
}

/**
 * Check if the page sidebar enable or not
 */
function shopnex_page_sidebar_enable( $control ) {
	if ( $control->manager->get_setting( 'shopnex_enable_page_sidebar' )->value() === true ) :
		return true;
	else :
		return false;
	endif;
}

/**
 * Check if the page title enable or not
 */
function shopnex_page_title_enable( $control ) {
	if ( $control->manager->get_setting( 'shopnex_enable_page_title' )->value() === true ) :
		return true;
	else :
		return false;
	endif;
}


/**
 * Check if the header product search enable or not
 */
function shopnex_header_product_search_enable( $control ) {
	if ( $control->manager->get_setting( 'shopnex_enable_header_product_search' )->value() === true ) :
		return true;
	else :
		return false;
	endif;
}


/**
 * Check if the header login links enable or not
 */
function shopnex_header_login_links_enable( $control ) {
	if ( $control->manager->get_setting( 'shopnex_enable_header_login_register_links' )->value() === true ) :
		return true;
	else :
		return false;
	endif;
}
