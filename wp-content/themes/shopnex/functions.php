<?php
/**
 * Shopnex functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package shopnex
 */

/**
 * Defining Constants
 */
define('SHOPNEX_REQUIRED_PHP_VERSION', '7.4');
define('SHOPNEX_DIR_PATH', get_template_directory());
define('SHOPNEX_DIR_URI', get_template_directory_uri());
define('SHOPNEX_THEME_AUTH','https://spiraclethemes.com/');
/**
 * Setup functions.
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Enqueue functions.
 */
require get_template_directory() . '/inc/scripts.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Pro section in Customizer.
 */
require get_template_directory() . '/shopnex-pro/class-shopnex-pro-upsell.php';

/**
 * Template functions.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * WooCommerce Functions.
 */
if (shopnex_is_active_woocommerce()) :
    require get_template_directory() . '/inc/shop-functions.php';
    require get_template_directory() . '/inc/woocommerce.php';
endif;

/**
 * Performance optimization functions.
 */
require get_template_directory() . '/inc/performance.php';

/**
 * Template hooks and shortcodes.
 */
require get_template_directory() . '/inc/template-hooks.php';

/**
 * Load Widgets.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Notices
 */
require get_parent_theme_file_path( '/inc/activation/class-welcome-notice.php' );
require get_parent_theme_file_path( '/inc/activation/class-rating-notice.php' );

/**
 * Info Page
 */
require get_parent_theme_file_path( '/inc/admin/class-shopnex-info-page.php' );