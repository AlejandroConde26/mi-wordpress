<?php
/**
 * Fashion Nook functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Fashion Nook
 * @since Fashion Nook 1.0
 */

declare( strict_types = 1 );

if ( ! function_exists( 'fashion_nook_unregister_patterns' ) ) :
	/**
	 * Unregister Jetpack patterns and core patterns bundled in WordPress.
	 */
	function fashion_nook_unregister_patterns() {
		$pattern_names = array(
			// Jetpack form patterns.
			'contact-form',
			'newsletter-form',
			'rsvp-form',
			'registration-form',
			'appointment-form',
			'feedback-form',
			// Patterns bundled in WordPress core.
			// These would be removed by remove_theme_support( 'core-block-patterns' )
			// if it's called on the init action with priority 9 from a plugin, not from a theme.
			'core/query-standard-posts',
			'core/query-medium-posts',
			'core/query-small-posts',
			'core/query-grid-posts',
			'core/query-large-title-posts',
			'core/query-offset-posts',
			'core/social-links-shared-background-color',
		);
		foreach ( $pattern_names as $pattern_name ) {
			$pattern = \WP_Block_Patterns_Registry::get_instance()->get_registered( $pattern_name );
			if ( $pattern ) {
				unregister_block_pattern( $pattern_name );
			}
		}
	}

endif;

if ( ! function_exists( 'fashion_nook_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Fashion Nook 1.0
	 *
	 * @return void
	 */
	function fashion_nook_setup() {
		/**
		 * About Theme Function
		 */
		require get_theme_file_path( '/admin/about-theme.php' );
		add_editor_style( array( 'admin/style.css' ) );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );
		// Unregister Jetpack form patterns and core patterns bundled in WordPress.
		// Simple sites
		fashion_nook_unregister_patterns();
		add_filter( 'wp_loaded', function () {
			// Atomic sites
			fashion_nook_unregister_patterns();
		} );
		// Remove theme support for the core and featured patterns coming from the Dotorg pattern directory.
		remove_theme_support( 'core-block-patterns' );
	}

endif;

add_action( 'after_setup_theme', 'fashion_nook_setup' );

if ( ! function_exists( 'fashion_nook_styles' ) ) :
	/**
	 * Enqueue styles.
	 *
	 * @since Fashion Nook 1.0
	 *
	 * @return void
	 */
	function fashion_nook_styles() {

		// Register theme stylesheet.
		wp_register_style(
			'fashion-nook-style',
			get_stylesheet_directory_uri() . '/style.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'fashion-nook-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'fashion_nook_styles' );

if ( ! function_exists( 'fashion_nook_remove_upsells' ) ) :
	/**
	 * Remove upsells from product description.
	 *
	 * @since Fashion Nook 1.0
	 *
	 * @return void
	 */
	function fashion_nook_remove_upsells() {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	}

endif;

add_action( 'init', 'fashion_nook_remove_upsells' );
