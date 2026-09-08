<?php
/**
 * Shopnex Theme Asset Enqueuing
 *
 * Handles enqueuing of all CSS and JavaScript assets.
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue Styles and Scripts
 *
 * Enqueues all front-end styles and scripts.
 *
 * @return void
 */
function shopnex_scripts() {
	// Google Fonts
	wp_enqueue_style( 'shopnex-google-fonts', get_template_directory_uri() . '/assets/fonts/google-fonts.css', array(), wp_get_theme()->get( 'Version' ) );
	
	// Style CSS (cache-bust via file modification time so edits always load)
	$shopnex_style_ver = filemtime( get_template_directory() . '/style.css' );
	wp_enqueue_style( 'shopnex-style', get_stylesheet_uri(), array(), $shopnex_style_ver );
	
	// Main theme CSS
	wp_enqueue_style( 'shopnex-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), wp_get_theme()->get('Version') );
	
	// Shop CSS
	if ( shopnex_is_active_woocommerce() ) {
		wp_enqueue_style( 'shopnex-shop-style', get_template_directory_uri() . '/assets/css/shop.css', array(), wp_get_theme()->get('Version') );
	}

	// Product Single CSS
	if ( shopnex_is_active_woocommerce()) {
		wp_enqueue_style( 'shopnex-product-single-style', get_template_directory_uri() . '/assets/css/product-single.css', array(), wp_get_theme()->get('Version') );
	}

	// Blog CSS
	if ( is_home() || is_archive() || is_category() || is_tag() || is_author() || is_date() || is_search() || is_singular( 'post' ) ) {
		wp_enqueue_style( 'shopnex-blog-style', get_template_directory_uri() . '/assets/css/blog.css', array(), wp_get_theme()->get('Version') );
	}

	// Responsive CSS
	wp_enqueue_style( 'shopnex-responsive-style', get_template_directory_uri() . '/assets/css/responsive.css', array(), wp_get_theme()->get('Version') );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) :
		wp_enqueue_script( 'comment-reply' );
	endif;

	// Main theme JavaScript
	$shopnex_script_ver = filemtime( get_template_directory() . '/assets/js/main.js' );
	wp_enqueue_script( 'shopnex-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), $shopnex_script_ver, true );

	// Shop JavaScript
	if ( shopnex_is_active_woocommerce() ) {
		wp_enqueue_script( 'shopnex-shop-script', get_template_directory_uri() . '/assets/js/shop.js', array('jquery'), wp_get_theme()->get('Version'), true );

		// Inline script: shop base URL for archive product pages.
		if ( function_exists( 'wc_get_page_permalink' ) ) {
			$shop_base_url = esc_js( wp_parse_url( wc_get_page_permalink( 'shop' ), PHP_URL_PATH ) );
			wp_add_inline_script( 'shopnex-shop-script', "var shopnex_shop_base_url = '{$shop_base_url}';", 'before' );
		}

		// Product Single JavaScript
		if ( is_product() ) {
			wp_enqueue_script( 'shopnex-product-single-script', get_template_directory_uri() . '/assets/js/product-single.js', array('jquery'), wp_get_theme()->get('Version'), true );
			
			// Localize product single script with checkout URL and currency format data
			wp_localize_script( 'shopnex-product-single-script', 'shopnex_product_single', array(
				'checkout_url' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '',
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'currency_symbol' => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$',
				'currency_pos' => get_option( 'woocommerce_currency_pos', 'left' ),
				'decimal_sep' => function_exists( 'wc_get_price_decimal_separator' ) ? wc_get_price_decimal_separator() : '.',
				'thousand_sep' => function_exists( 'wc_get_price_thousand_separator' ) ? wc_get_price_thousand_separator() : ',',
				'decimals' => function_exists( 'wc_get_price_decimals' ) ? wc_get_price_decimals() : 2,
				'texts' => array(
					'review_submitted' => esc_html__( 'Thank you for your review!', 'shopnex' ),
					'review_pending' => esc_html__( 'Your review is pending approval.', 'shopnex' ),
					'select_options' => esc_html__( 'Please select all options before adding to cart.', 'shopnex' ),
				),
			) );
		}
		
		// Ensure WooCommerce variation scripts are loaded on product pages
		if ( is_product() ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}
		
		// Build WooCommerce AJAX parameters
		$wc_ajax_params = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'wc_ajax_url' => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : '/?wc-ajax=%%endpoint%%',
			'i18n_view_cart' => esc_attr__( 'View cart', 'shopnex' ),
			'cart_url' => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
			'is_cart' => function_exists( 'is_cart' ) ? is_cart() : false,
			'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ? 1 : 0,
			'cart_data_nonce' => wp_create_nonce( 'shopnex_cart_data_nonce' ),
		);
		
		// Localize script with WooCommerce AJAX parameters (available to both scripts)
		wp_localize_script( 'shopnex-script', 'shopnex_wc_params', $wc_ajax_params );
		wp_localize_script( 'shopnex-shop-script', 'shopnex_wc_params', $wc_ajax_params );
		
		// Localize shop script for AJAX
		wp_localize_script( 'shopnex-shop-script', 'shopnex_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'shopnex_ajax_nonce' ),
		));
	}

	// Blog JavaScript
	if ( is_home() || is_archive() || is_category() || is_tag() || is_author() || is_date() || is_search() || is_singular( 'post' ) ) {
		wp_enqueue_script( 'shopnex-blog-script', get_template_directory_uri() . '/assets/js/blog.js', array('jquery'), wp_get_theme()->get('Version'), true );

		// Localize blog script for AJAX
		wp_localize_script( 'shopnex-blog-script', 'shopnex_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'shopnex_ajax_nonce' ),
		));
	}

	// Cart Page Assets
	if ( shopnex_is_active_woocommerce() && is_cart() ) {
		wp_enqueue_style( 'shopnex-cart-style', get_template_directory_uri() . '/assets/css/cart.css', array(), wp_get_theme()->get('Version') );
		wp_enqueue_script( 'shopnex-cart-script', get_template_directory_uri() . '/assets/js/cart.js', array('jquery'), wp_get_theme()->get('Version'), true );
		wp_localize_script( 'shopnex-cart-script', 'shopnex_cart_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'shopnex_cart_nonce' ),
		) );
		wp_localize_script( 'shopnex-cart-script', 'shopnex_cart_texts', array(
			'remove_confirm' => esc_html__( 'Remove %s from cart?', 'shopnex' ),
		) );

		// Cart Page AJAX Quantity Update — updates pricing without page reload.
		wp_enqueue_script(
			'shopnex-cart-qty-ajax-js',
			get_template_directory_uri() . '/assets/js/cart-qty-ajax.js',
			array( 'jquery', 'shopnex-cart-script' ),
			wp_get_theme()->get( 'Version' ),
			true
		);
		wp_localize_script( 'shopnex-cart-qty-ajax-js', 'shopnexCartQty', array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'shopnex_cart_qty_nonce' ),
			'i18nItem'  => __( '%s Item', 'shopnex' ),
			'i18nItems' => __( '%s Items', 'shopnex' ),
		) );
	}

	// Checkout Page Assets
	if ( shopnex_is_active_woocommerce() && is_checkout() ) {
		wp_enqueue_style( 'shopnex-checkout-style', get_template_directory_uri() . '/assets/css/checkout.css', array(), wp_get_theme()->get('Version') );
		wp_enqueue_script( 'shopnex-checkout-script', get_template_directory_uri() . '/assets/js/checkout.js', array('jquery'), wp_get_theme()->get('Version'), true );
	}

	// My Account Page Assets
	if ( shopnex_is_active_woocommerce() && is_account_page() ) {
		wp_enqueue_style( 'shopnex-account-style', get_template_directory_uri() . '/assets/css/account.css', array(), wp_get_theme()->get('Version') );
	}
}
add_action( 'wp_enqueue_scripts', 'shopnex_scripts' );

/**
 * Inject cart AJAX update CSS.
 * Adds a subtle loading indicator when cart quantities are being updated via AJAX.
 */
function shopnex_cart_ajax_inline_css() {
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'is_cart' ) || ! is_cart() ) {
		return;
	}

	$css = '
		.shopnex-cart-updating .ci-total {
			opacity: 0.4;
			transition: opacity 0.2s ease;
		}
		.shopnex-cart-updating .ci-qty {
			opacity: 0.6;
			pointer-events: none;
		}
	';

	wp_register_style( 'shopnex-cart-ajax', false );
	wp_enqueue_style( 'shopnex-cart-ajax' );
	wp_add_inline_style( 'shopnex-cart-ajax', $css );
}
add_action( 'wp_enqueue_scripts', 'shopnex_cart_ajax_inline_css', 20 );

/**
 * Inject inline CSS for the custom logo width.
 *
 * @return void
 */
function shopnex_logo_width_inline_css() {
	$logo_width = absint( get_theme_mod( 'shopnex_logo_width', 180 ) );

	if ( $logo_width > 0 ) {
		$css = '.site-branding .custom-logo{max-width:' . $logo_width . 'px !important;}';
		wp_add_inline_style( 'shopnex-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'shopnex_logo_width_inline_css', 20 );
