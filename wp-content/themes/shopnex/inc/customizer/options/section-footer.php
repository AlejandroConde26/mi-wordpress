<?php
/**
 * Theme Customizer Controls
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'shopnex_customizer_footer_register' ) ) :
function shopnex_customizer_footer_register( $wp_customize ) {
 	
 	/**
     * Footer Settings Section
     */
    $wp_customize->add_section(
        'shopnex_footer_settings',
        array(
            'priority'   => 25,
            'capability' => 'edit_theme_options',
            'title'      => esc_html__( 'Footer Settings', 'shopnex' ),
        )
    );

    // Title label - Copyright
    $wp_customize->add_setting(
        'shopnex_footer_copyright_label',
        array(
            'sanitize_callback' => 'shopnex_sanitize_title',
        )
    );

    $wp_customize->add_control(
        new Shopnex_Title_Info_Control( $wp_customize, 'shopnex_footer_copyright_label',
        array(
            'label'    => esc_html__( 'Copyright Settings', 'shopnex' ),
            'section'  => 'shopnex_footer_settings',
            'type'     => 'shopnex-title',
            'settings' => 'shopnex_footer_copyright_label',
        )
    ));

    // Copyright text
    $wp_customize->add_setting(
        'shopnex_footer_copyright_text',
        array(
            'type'              => 'theme_mod',
            'sanitize_callback' => 'shopnex_sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'shopnex_footer_copyright_text',
        array(
            'settings'    => 'shopnex_footer_copyright_text',
            'section'     => 'shopnex_footer_settings',
            'type'        => 'textarea',
            'label'       => esc_html__( 'Footer Copyright Text', 'shopnex' ),
            'description' => esc_html__( 'Copyright text to be displayed in the footer. No HTML allowed.', 'shopnex' ),
        )
    );

    // Title label - Footer Bottom Links
    $wp_customize->add_setting(
        'shopnex_footer_bottom_links_label',
        array(
            'sanitize_callback' => 'shopnex_sanitize_title',
        )
    );

    $wp_customize->add_control(
        new Shopnex_Title_Info_Control( $wp_customize, 'shopnex_footer_bottom_links_label',
        array(
            'label'    => esc_html__( 'Footer Bottom Links', 'shopnex' ),
            'section'  => 'shopnex_footer_settings',
            'type'     => 'shopnex-title',
            'settings' => 'shopnex_footer_bottom_links_label',
        )
    ));

    // Privacy Policy URL
    $wp_customize->add_setting(
        'shopnex_footer_privacy_policy_url',
        array(
            'type'              => 'theme_mod',
            'sanitize_callback' => 'shopnex_sanitize_url',
        )
    );

    $wp_customize->add_control(
        'shopnex_footer_privacy_policy_url',
        array(
            'settings'    => 'shopnex_footer_privacy_policy_url',
            'section'     => 'shopnex_footer_settings',
            'type'        => 'url',
            'label'       => esc_html__( 'Privacy Policy URL', 'shopnex' ),
            'description' => esc_html__( 'Enter the URL for the Privacy Policy page.', 'shopnex' ),
        )
    );

    // Cookie Policy URL
    $wp_customize->add_setting(
        'shopnex_footer_cookie_policy_url',
        array(
            'type'              => 'theme_mod',
            'sanitize_callback' => 'shopnex_sanitize_url',
        )
    );

    $wp_customize->add_control(
        'shopnex_footer_cookie_policy_url',
        array(
            'settings'    => 'shopnex_footer_cookie_policy_url',
            'section'     => 'shopnex_footer_settings',
            'type'        => 'url',
            'label'       => esc_html__( 'Cookie Policy URL', 'shopnex' ),
            'description' => esc_html__( 'Enter the URL for the Cookie Policy page.', 'shopnex' ),
        )
    );

    // Terms & Conditions URL
    $wp_customize->add_setting(
        'shopnex_footer_terms_url',
        array(
            'type'              => 'theme_mod',
            'sanitize_callback' => 'shopnex_sanitize_url',
        )
    );

    $wp_customize->add_control(
        'shopnex_footer_terms_url',
        array(
            'settings'    => 'shopnex_footer_terms_url',
            'section'     => 'shopnex_footer_settings',
            'type'        => 'url',
            'label'       => esc_html__( 'Terms & Conditions URL', 'shopnex' ),
            'description' => esc_html__( 'Enter the URL for the Terms & Conditions page.', 'shopnex' ),
        )
    );
}
endif;

add_action( 'customize_register', 'shopnex_customizer_footer_register' );