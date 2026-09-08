<?php
/**
 * shopnex Theme Customizer Data Sanitization
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
if ( ! function_exists( 'shopnex_sanitize_checkbox' ) ) :
function shopnex_sanitize_checkbox( $checked ) {
    // Boolean check.
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}
endif;


/**
 * URL sanitization.
 *
 * @see esc_url_raw() https://developer.wordpress.org/reference/functions/esc_url_raw/
 *
 * @param string $url URL to sanitize.
 * @return string Sanitized URL.
 */
if ( ! function_exists( 'shopnex_sanitize_url' ) ) :
function shopnex_sanitize_url( $url ) {
    return esc_url_raw( $url );
}
endif;


/**
 * String sanitization.
 *
 * @see sanitize_text_field() https://developer.wordpress.org/reference/functions/sanitize_text_field/
 *
 * @param string $str to sanitize.
 * @return string Sanitized string.
 */
if ( ! function_exists( 'shopnex_sanitize_text_field' ) ) :
function shopnex_sanitize_text_field( $str ) {
    return sanitize_text_field( $str );
}
endif;


/**
 * Multiline String sanitization.
 *
 * @see sanitize_textarea_field() https://developer.wordpress.org/reference/functions/sanitize_textarea_field/
 *
 * @param string $str to sanitize.
 * @return string Sanitized string.
 */
if ( ! function_exists( 'shopnex_sanitize_textarea_field' ) ) :
function shopnex_sanitize_textarea_field( $str ) {
    return sanitize_textarea_field( $str );
}
endif;


/**
 * Select sanitization.
 */
if ( ! function_exists( 'shopnex_sanitize_select' ) ) :
function shopnex_sanitize_select( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
endif;

/**
 * Title sanitization.
 */
if ( ! function_exists( 'shopnex_sanitize_title' ) ) :
function shopnex_sanitize_title( $str ) {
	return sanitize_title( $str );	
}
endif;

/**
 * Sanitize Content layout options
 *
 * @param string $input
 * @return string
 */
if ( ! function_exists( 'shopnex_layout_content_width_options_selection' ) ) :
function shopnex_layout_content_width_options_selection( $input ){
    $valid = array(
        'os-container' => esc_html__('1350px', 'shopnex'),
        'container' => esc_html__('1170px', 'shopnex'),
     );

     if ( array_key_exists( $input, $valid ) ) :
        return $input;
     else :
        return '';
     endif;
}
endif;