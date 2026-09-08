<?php
/**
 * Shopnex Pro Upsell - Customizer Integration
 *
 * Singleton class for handling the theme's customizer upsell section.
 * Displays an "Upgrade to Pro" banner at the top of the Customizer sidebar.
 *
 * @package shopnex
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Shopnex_Pro_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) :
			$instance = new self;
			$instance->setup_actions();
		endif;

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  WP_Customize_Manager $manager Theme Customizer object.
	 * @return void
	 */
	public function sections( $manager ) {

		// Load custom section.
		require_once trailingslashit( get_template_directory() ) . 'shopnex-pro/section-pro.php';

		// Register custom section types.
		$manager->register_section_type( 'Shopnex_Pro_Customize_Section_Pro' );

		// Register the upsell section at the very top.
		$manager->add_section(
			new Shopnex_Pro_Customize_Section_Pro(
				$manager,
				'shopnex_pro_upsell',
				array(
					'priority'  => -1,
					'title'     => esc_html__( 'ShopNex Pro', 'shopnex' ),
					'pro_text'  => esc_html__( 'Upgrade to Pro', 'shopnex' ),
					'pro_url'   => 'https://spiraclethemes.com/shopnex-pro-addons/',
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS and JS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script(
			'shopnex-pro-customize-controls',
			trailingslashit( get_template_directory_uri() ) . 'shopnex-pro/customize-controls.js',
			array( 'customize-controls' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_enqueue_style(
			'shopnex-pro-customize-controls',
			trailingslashit( get_template_directory_uri() ) . 'shopnex-pro/css/upsell.css',
			array(),
			wp_get_theme()->get( 'Version' ),
			'all'
		);
	}
}

// Initialize the customizer upsell.
Shopnex_Pro_Customize::get_instance();
