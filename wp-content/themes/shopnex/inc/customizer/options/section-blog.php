<?php
/**
 * Theme Customizer Controls - Blog Settings
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'shopnex_customizer_blog_register' ) ) :
function shopnex_customizer_blog_register( $wp_customize ) {

	/**
	 * Blog Settings Section
	 */
	$wp_customize->add_section(
		'shopnex_blog_settings',
		array(
			'priority'   => 22,
			'capability' => 'edit_theme_options',
			'title'      => esc_html__( 'Blog Settings', 'shopnex' ),
		)
	);

	// Title label - Blog Page Header
	$wp_customize->add_setting(
		'shopnex_blog_header_label',
		array(
			'sanitize_callback' => 'shopnex_sanitize_title',
		)
	);

	$wp_customize->add_control(
		new Shopnex_Title_Info_Control( $wp_customize, 'shopnex_blog_header_label',
		array(
			'label'    => esc_html__( 'Blog Page Header', 'shopnex' ),
			'section'  => 'shopnex_blog_settings',
			'type'     => 'shopnex-title',
			'settings' => 'shopnex_blog_header_label',
		)
	));

	// Blog page heading text
	$wp_customize->add_setting(
		'shopnex_blog_heading',
		array(
			'default'           => __( 'Blog', 'shopnex' ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'shopnex_sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'shopnex_blog_heading',
		array(
			'settings'    => 'shopnex_blog_heading',
			'section'     => 'shopnex_blog_settings',
			'type'        => 'text',
			'label'       => esc_html__( 'Blog Page Heading', 'shopnex' ),
			'description' => esc_html__( 'Heading text displayed on the blog listing page.', 'shopnex' ),
		)
	);

	// Blog page subheading text
	$wp_customize->add_setting(
		'shopnex_blog_subheading',
		array(
			'default'           => esc_html__( 'Stories on design, craftsmanship, and the art of intentional living.', 'shopnex' ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'shopnex_sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'shopnex_blog_subheading',
		array(
			'settings'    => 'shopnex_blog_subheading',
			'section'     => 'shopnex_blog_settings',
			'type'        => 'textarea',
			'label'       => esc_html__( 'Blog Page Subheading', 'shopnex' ),
			'description' => esc_html__( 'Subheading text displayed below the heading on the blog listing page.', 'shopnex' ),
		)
	);
}
endif;

add_action( 'customize_register', 'shopnex_customizer_blog_register' );
