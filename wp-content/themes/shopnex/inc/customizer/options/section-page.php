<?php
/**
 * Theme Customizer Controls
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'shopnex_customizer_page_settings_register' ) ) :
function shopnex_customizer_page_settings_register( $wp_customize ) {

 	/**
     * Page Settings Section
     */
    $wp_customize->add_section(
        'shopnex_page_settings',
        array(
            'priority'   => 20,
            'capability' => 'edit_theme_options',
            'title'      => esc_html__( 'Page Settings', 'shopnex' ),
        )
    );

    // Title label - Page Title
    $wp_customize->add_setting(
        'shopnex_page_title_label',
        array(
            'sanitize_callback' => 'shopnex_sanitize_title',
        )
    );

    $wp_customize->add_control(
        new Shopnex_Title_Info_Control( $wp_customize, 'shopnex_page_title_label',
        array(
            'label'    => esc_html__( 'Page Title Settings', 'shopnex' ),
            'section'  => 'shopnex_page_settings',
            'type'     => 'shopnex-title',
            'settings' => 'shopnex_page_title_label',
        )
    ));

    // Enable/Disable Page Title
    $wp_customize->add_setting(
        'shopnex_enable_page_title',
        array(
            'default'           => true,
            'type'              => 'theme_mod',
            'sanitize_callback' => 'shopnex_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new Shopnex_Toggle_Control( $wp_customize, 'shopnex_enable_page_title',
        array(
            'label'       => esc_html__( 'Enable Page Title', 'shopnex' ),
            'description' => esc_html__( 'Enable or disable the page title for inner pages', 'shopnex' ),
            'section'     => 'shopnex_page_settings',
            'type'        => 'shopnex-toggle',
            'settings'    => 'shopnex_enable_page_title',
        )
    ));	
}
endif;

add_action( 'customize_register', 'shopnex_customizer_page_settings_register' );