<?php
/**
 * Shopnex Theme Setup
 *
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'shopnex_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @return void
 */
function shopnex_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in /languages/ directory.
	 * If you're building a theme based on shopnex, use a find and replace
	 * to change 'shopnex' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'shopnex', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'shopnex' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Support for align full and align wide option.
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add editor styles for block editor.
	add_editor_style( 'editor-style.css' );

	// Remove theme support for new widgets block editor
	if(true===get_theme_mod( 'shopnex_disable_widgets_block_editor',true)) :
		remove_theme_support( 'widgets-block-editor' );
	endif;

	/**
	 * shopnex custom posts image size
	 */
	add_image_size( 'shopnex-posts', 765, 500, true );

	/**
	 * shopnex custom posts thumbs size
	 */
	add_image_size( 'shopnex-posts-thumb', 150, 100, true );

}
endif;
add_action( 'after_setup_theme', 'shopnex_setup' );

/**
 * Custom Logo Setup
 *
 * @return void
 */
function shopnex_logo_setup() {
	add_theme_support( 'custom-logo', array(
		'height'      => 65,
		'width'       => 350,
		'flex-height' => true,
		'flex-width'  => true,
	) );
}
add_action( 'after_setup_theme', 'shopnex_logo_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 * @return void
 */
function shopnex_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'shopnex_content_width', 1380 );
}
add_action( 'after_setup_theme', 'shopnex_content_width', 0 );

/**
 * Check if WooCommerce is active
 *
 * @return bool Whether WooCommerce is active
 */
if ( ! function_exists( 'shopnex_is_active_woocommerce' ) ) {
	function shopnex_is_active_woocommerce() {
		return class_exists( 'WooCommerce' );
	}
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function shopnex_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'shopnex' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'shopnex' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Top Bar', 'shopnex' ),
		'id'            => 'topbar',
		'description'   => esc_html__( 'Add widgets here.', 'shopnex' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	//Footer widget columns
    $widget_num = absint(get_theme_mod( 'shopnex_footer_widgets', '4' ));
    for ( $i=1; $i <= $widget_num; $i++ ) :
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Column', 'shopnex' ) . $i,
            'id'            => 'footer-' . $i,
            'description'   => '',
            'before_widget' => '<aside id="%1$s" class="section %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );
    endfor;

}
add_action( 'widgets_init', 'shopnex_widgets_init' );