<?php
/**
 * Shopnex Theme Customizer
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

if ( ! function_exists( 'shopnex_customize_register' ) ) :
function shopnex_customize_register( $wp_customize ) {

    // Add custom controls.
    require get_parent_theme_file_path( 'inc/customizer/custom-controls/info/class-info-control.php' );
    require get_parent_theme_file_path( 'inc/customizer/custom-controls/info/class-title-info-control.php' );
    require get_parent_theme_file_path( 'inc/customizer/custom-controls/toggle-button/class-login-designer-toggle-control.php' );
    require get_parent_theme_file_path( 'inc/customizer/custom-controls/radio-images/class-radio-image-control.php' );

    // Register the custom control type.
    $wp_customize->register_control_type( 'shopnex_Toggle_Control' );


    $wp_customize->get_setting( 'blogname' )->transport         = 'refresh';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'refresh';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'refresh';

    // Remove the core "Display Site Title and Tagline" checkbox to avoid
    // duplicating our custom toggle below.
    if ( $wp_customize->get_control( 'display_header_text' ) ) {
        $wp_customize->remove_control( 'display_header_text' );
    }

    if ( isset( $wp_customize->selective_refresh ) ) :
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'shopnex_site_title_callback',
            'fallback_refresh'    => false,
        ) );
        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'shopnex_site_description_callback',
            'fallback_refresh'    => false, 
        ) );
    endif;

    // Display Site Title and Tagline
    $wp_customize->add_setting( 
        'shopnex_display_site_title_tagline', 
        array(
            'default'           => true,
            'type'              => 'theme_mod',
            'sanitize_callback' => 'shopnex_sanitize_checkbox',
            'transport'         => 'refresh',
        ) 
    );

    $wp_customize->add_control( 
        new shopnex_Toggle_Control( $wp_customize, 'shopnex_display_site_title_tagline', 
        array(
            'label'       => esc_html__( 'Display Site Title and Tagline', 'shopnex' ),
            'section'     => 'title_tagline',
            'type'        => 'shopnex-toggle',
            'settings'    => 'shopnex_display_site_title_tagline',
        ) 
    ));   

    // Logo Width control.
    $wp_customize->add_setting(
        'shopnex_logo_width',
        array(
            'default'           => 180,
            'type'              => 'theme_mod',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'shopnex_logo_width',
        array(
            'label'       => esc_html__( 'Logo Width (px)', 'shopnex' ),
            'description' => esc_html__( 'Set the maximum width of the site logo. The logo keeps its aspect ratio.', 'shopnex' ),
            'section'     => 'title_tagline',
            'type'        => 'number',
            'priority'    => 8,
            'input_attrs' => array(
                'min'  => 60,
                'max'  => 500,
                'step' => 1,
            ),
        )
    );
}
endif;
add_action( 'customize_register', 'shopnex_customize_register' );

//page settings
get_template_part( 'inc/customizer/options/section-page' );

//blog settings
get_template_part( 'inc/customizer/options/section-blog' );

//footer settings
get_template_part( 'inc/customizer/options/section-footer' );

//woocommerce settings
get_template_part( 'inc/customizer/options/section-woocommerce' );

//data sanitization
get_template_part( 'inc/customizer/data-sanitization' );


/**
 * Enqueue the customizer stylesheet.
 */
if ( ! function_exists( 'shopnex_enqueue_customizer_stylesheets' ) ) :
function shopnex_enqueue_customizer_stylesheets() {
    wp_register_style( 'shopnex-customizer-css', get_template_directory_uri() . '/inc/customizer/assets/css/customizer.css', array(), wp_get_theme()->get( 'Version' ), 'all' );
    wp_enqueue_style( 'shopnex-customizer-css' );
}
endif;
add_action( 'customize_controls_print_styles', 'shopnex_enqueue_customizer_stylesheets' );

/**
 * Enqueue customizer preview JS.
 */
if ( ! function_exists( 'shopnex_customize_preview_js' ) ) :
function shopnex_customize_preview_js() {
    wp_enqueue_script( 'shopnex-customizer-preview-js', get_template_directory_uri() . '/inc/customizer/assets/js/customizer.js', array( 'customize-preview' ), false, true );
}
endif;
add_action( 'customize_preview_init', 'shopnex_customize_preview_js' );