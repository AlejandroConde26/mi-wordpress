<?php
/**
 * WooCommerce Compatibility & Customizations
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;

/*--------------------------------------------------------------
 * CHECKOUT ORDER SUMMARY FRAGMENT
 *--------------------------------------------------------------*/

/**
 * Keep the checkout sidebar order summary in sync.
 *
 * Registers the sidebar totals block as a WooCommerce order-review fragment so
 * applied coupons, fees, shipping, taxes and the order total are re-rendered
 * on every AJAX checkout update instead of showing stale page-load values.
 *
 * @param array $fragments Fragments replaced after update_order_review.
 * @return array
 */
function shopnex_checkout_order_summary_fragment( $fragments ) {
	if ( ! function_exists( 'wc_get_template' ) || ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	wc_get_template( 'checkout/order-summary-totals.php' );
	$fragments['#orderSummaryTotals'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_update_order_review_fragments', 'shopnex_checkout_order_summary_fragment' );

/*--------------------------------------------------------------
 * THEME SUPPORT & SETUP
 *--------------------------------------------------------------*/

/**
 * Register WooCommerce theme support and force classic templates.
 */
function shopnex_woocommerce_setup() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'shopnex_woocommerce_setup' );

/**
 * Disable  WooCommerce-Blocks
 *
 * Cart and Checkout blocks are conditionally disabled based on the
 * Customizer toggles "Enable Block Cart" / "Enable Block Checkout".
 * When the toggle is enabled (true) the default WooCommerce Block
 * experience is restored for that page.
 */
function shopnex_disable_woocommerce_blocks() {
	$enable_block_cart     = (bool) get_theme_mod( 'shopnex_enable_block_cart', false );
	$enable_block_checkout = (bool) get_theme_mod( 'shopnex_enable_block_checkout', false );

	if ( ! $enable_block_cart ) {
		add_filter( 'woocommerce_cart_page_use_block_editor', '__return_false' );
	}
	if ( ! $enable_block_checkout ) {
		add_filter( 'woocommerce_checkout_page_use_block_editor', '__return_false' );
	}

	// Prevent block assets from loading on cart / checkout only when the
	// respective block experience is disabled.
	if ( ! $enable_block_cart || ! $enable_block_checkout ) {
		add_filter( 'woocommerce_blocks_load_cart_checkout_block_assets', '__return_false' );
	}
}
add_action( 'woocommerce_init', 'shopnex_disable_woocommerce_blocks', 1 );

/**
 * Replace cart/checkout block content with classic shortcodes.
 *
 * Only runs for the page whose block experience is disabled by the
 * Customizer toggle.
 */
function shopnex_replace_block_with_shortcode( $content ) {
	$enable_block_cart     = (bool) get_theme_mod( 'shopnex_enable_block_cart', false );
	$enable_block_checkout = (bool) get_theme_mod( 'shopnex_enable_block_checkout', false );

	if ( ! $enable_block_cart && is_cart() && has_block( 'woocommerce/cart', get_the_ID() ) ) {
		return '[woocommerce_cart]';
	}

	if ( ! $enable_block_checkout && is_checkout() && has_block( 'woocommerce/checkout', get_the_ID() ) ) {
		return '[woocommerce_checkout]';
	}

	return $content;
}
add_filter( 'the_content', 'shopnex_replace_block_with_shortcode', 1 );

/**
 * Prevent WooCommerce Blocks from supplying a block template for
 * cart, checkout or single-product pages.
 *
 * Cart and checkout are conditional on the Customizer toggles.
 * Single-product block templates are always disabled to keep using
 * the theme classic single-product.php override.
 */
function shopnex_disable_block_templates( $template ) {
	$enable_block_cart     = (bool) get_theme_mod( 'shopnex_enable_block_cart', false );
	$enable_block_checkout = (bool) get_theme_mod( 'shopnex_enable_block_checkout', false );

	if ( ( is_cart() && ! $enable_block_cart ) || ( is_checkout() && ! $enable_block_checkout ) || is_singular( 'product' ) ) {
		return false;
	}
	return $template;
}
add_filter( 'woocommerce_blocks_template_for_current_page', 'shopnex_disable_block_templates', 999 );

/**
 * Force classic single-product.php from the theme.
 */
function shopnex_force_classic_single_product_template( $template ) {
    if ( is_singular( 'product' ) ) {
        $theme_template = locate_template( 'woocommerce/single-product.php' );
        if ( $theme_template ) {
            return $theme_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'shopnex_force_classic_single_product_template', 20 );

/*--------------------------------------------------------------
 * REMOVE DEFAULT WOOCOMMERCE HOOKS
 *--------------------------------------------------------------*/

/**
 * Strip default loop hooks
 */
function shopnex_remove_default_woocommerce_hooks() {
    // Product link wrappers.
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

    // Add-to-cart button (replaced by custom quick-add).
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

    // Result count & ordering
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

    // Order review table on checkout (items shown in sidebar).
    remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );

    // Default payment section (theme uses custom review-order.php instead).
    remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

    // Remove default WooCommerce coupon form from checkout (theme uses sidebar promo code).
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

    // Remove default WooCommerce empty cart message (theme uses custom design).
    remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
}
add_action( 'woocommerce_init', 'shopnex_remove_default_woocommerce_hooks' );

/**
 * Replace WooCommerce's default empty cart.
 */
function shopnex_custom_empty_cart_message() {
    ?>
    <div class="woocommerce-cart cart-empty-wrapper">
        <div class="empty-cart show">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <h2><?php esc_html_e( 'Your Cart is Empty', 'shopnex' ); ?></h2>
            <p><?php esc_html_e( 'Looks like you haven\'t added anything to your cart yet. Explore our collections and find something you love.', 'shopnex' ); ?></p>
            <?php
            if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
                <a class="btn-shop" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                    <?php esc_html_e( 'Start Shopping', 'shopnex' ); ?>
                    <svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_cart_is_empty', 'shopnex_custom_empty_cart_message', 10 );

/*--------------------------------------------------------------
 * PRODUCT LOOP CUSTOMISATIONS
 *--------------------------------------------------------------*/

/**
 * Customize the product loop wrapper classes.
 */
function shopnex_product_loop_start_wrapper( $html ) {
    // Replace columns class to force 3-column layout
    $html = preg_replace( '/columns-\d+/', 'columns-3', $html );
    return str_replace( '<ul class="products', '<ul class="products shop-product-grid', $html );
}
add_filter( 'woocommerce_product_loop_start', 'shopnex_product_loop_start_wrapper' );

/**
 * Force WooCommerce to use 3 columns for product loops.
 */
function shopnex_loop_columns() {
    return 3;
}
add_filter( 'loop_shop_columns', 'shopnex_loop_columns' );

/**
 * Remove default rating and price from after shop loop item title
 */
function shopnex_remove_loop_defaults() {
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'shopnex_pro_addons_render_product_countdown_loop', 15 );
}
add_action( 'woocommerce_init', 'shopnex_remove_loop_defaults' );

/**
 * Strip Saved Sale from price HTML.
 */
function shopnex_strip_saved_sale_from_price( $price ) {
    if ( ! empty( $price ) && false !== strpos( $price, 'saved-sale' ) ) {
        $price = preg_replace( '/<p\s+class="saved-sale">.*?<\/p>/i', '', $price );
    }
    return $price;
}
add_filter( 'woocommerce_get_price_html', 'shopnex_strip_saved_sale_from_price', 999 );

/*--------------------------------------------------------------
 * ENQUEUE STYLES & SCRIPTS
 *--------------------------------------------------------------*/

/**
 * Enqueue WooCommerce-specific front-end assets.
 *
 * @since 1.0.0
 */
function shopnex_woocommerce_scripts() {
    // WooCommerce-specific assets are consolidated in inc/scripts.php
    // to avoid duplicate enqueuing. Add any WooCommerce-only assets here.
}
add_action( 'wp_enqueue_scripts', 'shopnex_woocommerce_scripts' );

/*--------------------------------------------------------------
 * BODY CLASSES
 *--------------------------------------------------------------*/

/**
 * Consolidated body-class callback for WooCommerce pages.
 */
function shopnex_woocommerce_body_classes( $classes ) {
    $classes[] = 'woocommerce-active';

    // Cart sidebar layout.
    $cart_layout = get_theme_mod( 'shopnex_cart_page_sidebar_layout', 'right' );
    $classes[]   = esc_attr( $cart_layout ) . '-sidebar-cart';

    // Checkout sidebar layout.
    $checkout_layout = get_theme_mod( 'shopnex_checkout_page_sidebar_layout', 'right' );
    $classes[]       = esc_attr( $checkout_layout ) . '-sidebar-checkout';

    return $classes;
}
add_filter( 'body_class', 'shopnex_woocommerce_body_classes' );

/*--------------------------------------------------------------
 * CART FRAGMENTS & LINKS
 *--------------------------------------------------------------*/

if ( ! function_exists( 'shopnex_woocommerce_cart_link_fragment' ) ) :
    /**
     * Cart fragment
     */
    function shopnex_woocommerce_cart_link_fragment( $fragments ) {
        ob_start();
        shopnex_woocommerce_cart_link();
        $fragments['a.cart-content'] = ob_get_clean();

        ob_start();
        shopnex_new_cart_link();
        $fragments['a.cart-action'] = ob_get_clean();

        // Header cart count fragment for the button-based header
        ob_start();
        shopnex_header_cart_count();
        $fragments['span.cart-count'] = ob_get_clean();

        // Cart drawer items fragment
        ob_start();
        shopnex_cart_drawer_items();
        $fragments['div.cart-drawer-items'] = ob_get_clean();

        // Cart drawer footer fragment
        ob_start();
        shopnex_cart_drawer_footer();
        $fragments['div.drawer-footer'] = ob_get_clean();

        // Cart drawer count label fragment
        ob_start();
        shopnex_cart_drawer_count_label();
        $fragments['span.cart-count-label'] = ob_get_clean();

        return $fragments;
    }
endif;
add_filter( 'woocommerce_add_to_cart_fragments', 'shopnex_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'shopnex_new_cart_link' ) ) :
    /**
     * Minimal cart icon used in the new header.
     */
    function shopnex_new_cart_link() {
        $cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
        ?>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nav-action cart-action" aria-label="<?php esc_attr_e( 'Cart', 'shopnex' ); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3 6h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
        </a>
        <?php
    }
endif;

if ( ! function_exists( 'shopnex_header_cart_count' ) ) :
    /**
     * Cart count span for the button-based header cart icon.
     */
    function shopnex_header_cart_count() {
        $cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
        ?>
        <span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
        <?php
    }
endif;


/**
 * Cart drawer header count label, e.g. "(3)".
 */
if ( ! function_exists( 'shopnex_cart_drawer_count_label' ) ) :
    function shopnex_cart_drawer_count_label() {
        $cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
        ?>
        <span class="cart-count-label">(<?php echo esc_html( $cart_count ); ?>)</span>
        <?php
    }
endif;

/**
 * Cart drawer items list – renders all cart products or an empty state.
 */
if ( ! function_exists( 'shopnex_cart_drawer_items' ) ) :
    function shopnex_cart_drawer_items() {
        ?>
        <div class="cart-drawer-items">
        <?php if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) : ?>
            <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                $product = $cart_item['data'];
                $product_name = $product->get_name();
                $product_price = WC()->cart->get_product_price( $product );
                $product_image = $product->get_image( 'woocommerce_thumbnail' );
                $product_permalink = $product->get_permalink();
            ?>
            <div class="cart-drawer-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                <div class="cart-item-image">
                    <?php echo wp_kses_post( $product_image ); ?>
                </div>
                <div class="cart-item-details">
                    <h4 class="cart-item-name"><?php echo esc_html( $product_name ); ?></h4>
                    <div class="cart-item-meta">
                        <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                    </div>
                    <div class="cart-item-actions">
                        <div class="cart-item-qty">
                            <button class="qty-btn qty-minus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">&#8722;</button>
                            <span class="qty-value"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
                            <button class="qty-btn qty-plus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">+</button>
                        </div>
                        <span class="cart-item-price"><?php echo wp_kses_post( $product_price ); ?></span>
                    </div>
                </div>
                <button class="cart-item-remove" data-key="<?php echo esc_attr( $cart_item_key ); ?>" aria-label="<?php esc_attr_e( 'Remove item', 'shopnex' ); ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="cart-empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke="#E5E7EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="3" y1="6" x2="21" y2="6" stroke="#E5E7EB" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M16 10a4 4 0 0 1-8 0" stroke="#E5E7EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p><?php esc_html_e( 'Your cart is empty', 'shopnex' ); ?></p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Start Shopping', 'shopnex' ); ?></a>
            </div>
        <?php endif; ?>
        </div>
        <?php
    }
endif;

/**
 * Cart drawer footer – subtotal, checkout button, and tax notice.
 * Hidden when cart is empty.
 */
if ( ! function_exists( 'shopnex_cart_drawer_footer' ) ) :
    function shopnex_cart_drawer_footer() {
        if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) :
        ?>
        <div class="drawer-footer">
            <div class="cart-subtotal">
                <span class="subtotal-label"><?php esc_html_e( 'Subtotal', 'shopnex' ); ?></span>
                <span class="subtotal-value"><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
            </div>
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn-primary btn-checkout">
                <?php esc_html_e( 'Checkout', 'shopnex' ); ?> &mdash; <?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
            </a>
            <p class="cart-tax-note"><?php esc_html_e( 'Taxes & shipping calculated at checkout', 'shopnex' ); ?></p>
        </div>
        <?php
        else :
        ?>
        <div class="drawer-footer" style="display:none;"></div>
        <?php
        endif;
    }
endif;

/**
 * Legacy header cart link with icon, count badge, and subtotal.
 */
if ( ! function_exists( 'shopnex_woocommerce_cart_link' ) ) :
    function shopnex_woocommerce_cart_link() {
        $title     = apply_filters( 'shopnex_cart_icon_title', esc_html__( 'View your shopping cart', 'shopnex' ) );
        $cart_text = shopnex_get_mod_or_default( 'shopnex_header_menucart_text', __( 'Your Cart', 'shopnex' ) );
        ?>
        <a class="cart-content" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo esc_attr( $title ); ?>">
            <i class="la la-shopping-bag"></i>
            <span class="count badge"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
            <span class="cart-details">
                <label class="your-cart"><?php echo esc_html( $cart_text ); ?></label>
                <label class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></label>
            </span>
        </a>
        <?php
    }
endif;

/**
 * Header wishlist icon – fires an action for the pro plugin to hook into.
 */
if ( ! function_exists( 'shopnex_header_wishlist' ) ) :
    function shopnex_header_wishlist() {
        do_action( 'shopnex_wishlist_icon' );
    }
endif;
add_action( 'shopnex_action_header_wishlist', 'shopnex_header_wishlist' );


if ( ! function_exists( 'shopnex_get_mod_or_default' ) ) :
    /**
     * Get a Customizer theme mod value with a fallback default.
     *
     * Returns the escaped theme mod if it is set and non-empty,
     * otherwise returns the escaped default value.
     *
     * @param string $mod_name Theme modification name.
     * @param string $default  Default value when theme mod is empty.
     * @return string Escaped value.
     */
    function shopnex_get_mod_or_default( $mod_name, $default ) {
        $value = get_theme_mod( $mod_name, '' );
        return ! empty( $value ) ? esc_html( $value ) : esc_html( $default );
    }
endif;

/*--------------------------------------------------------------
 * SEARCH FORMS
 *--------------------------------------------------------------*/

if ( ! function_exists( 'shopnex_product_search_form' ) ) :
    /**
     * Product search form with category dropdown.
     */
    function shopnex_product_search_form() {
        $search_ph = shopnex_get_mod_or_default( 'shopnex_header_product_search_placeholder', __( 'Search for products', 'shopnex' ) );
        $cat_ph    = shopnex_get_mod_or_default( 'shopnex_header_product_category_placeholder', __( 'All Categories', 'shopnex' ) );
        $btn_text  = shopnex_get_mod_or_default( 'shopnex_header_product_search_button_text', __( 'Search', 'shopnex' ) );

        $product_cats          = get_terms( array( 'taxonomy' => 'product_cat' ) );
        $selected_product_cat  = get_query_var( 'product_cat' );
        ?>
        <div class="search-form-wrapper">
            <form method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="form-group search">
                    <label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e( 'Search for:', 'shopnex' ); ?></label>
                    <div class="search-container">
                        <input type="search" id="woocommerce-product-search-field" class="search-field" placeholder="<?php echo esc_attr( $search_ph ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
                        <?php if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) : ?>
                            <select name="product_cat" class="category-dropdown">
                                <option value=""><?php echo esc_html( $cat_ph ); ?></option>
                                <?php foreach ( $product_cats as $cat ) : ?>
                                    <option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $cat->slug, $selected_product_cat ); ?>><?php echo esc_html( $cat->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                        <button type="submit"><i class="la la-search" aria-hidden="true"></i> <?php echo esc_html( $btn_text ); ?></button>
                    </div>
                    <input type="hidden" name="post_type" value="product" />
                </div>
            </form>
        </div>
        <?php
    }
endif;



/*--------------------------------------------------------------
 * PRICING HELPERS
 *--------------------------------------------------------------*/


if ( ! function_exists( 'shopnex_get_sale_percentage' ) ) :
    /**
     * Return a formatted sale-percentage string (e.g. "-25%") for any product type.
     */
    function shopnex_get_sale_percentage( $product ) {
        if ( ! $product->is_on_sale() ) {
            return '';
        }

        $regular = 0;
        $sale    = 0;

        if ( $product->is_type( 'variable' ) ) {
            $regular = (float) $product->get_variation_regular_price( 'min' );
            $sale    = (float) $product->get_variation_sale_price( 'min' );

            // Fallback: iterate variations.
            if ( $regular <= 0 || $sale <= 0 || $sale >= $regular ) {
                foreach ( $product->get_available_variations() as $variation ) {
                    $v = wc_get_product( $variation['variation_id'] );
                    if ( $v && $v->is_on_sale() ) {
                        $vr = (float) $v->get_regular_price();
                        $vs = (float) $v->get_sale_price();
                        if ( $vr > 0 && $vs > 0 && $vs < $vr && ( $sale <= 0 || $vs < $sale ) ) {
                            $regular = $vr;
                            $sale    = $vs;
                        }
                    }
                }
            }
        } else {
            $regular = (float) $product->get_regular_price();
            $sale    = (float) $product->get_sale_price();
            if ( $sale <= 0 ) {
                $sale = (float) $product->get_price();
            }
        }

        if ( $regular > 0 && $sale > 0 && $sale < $regular ) {
            $pct = max( 1, min( 100, round( 100 - ( $sale / $regular * 100 ) ) ) );
            return '-' . $pct . '%';
        }

        return esc_html__( 'SALE', 'shopnex' );
    }
endif;

/*--------------------------------------------------------------
  * SVG ICON HELPER
  *--------------------------------------------------------------*/

if ( ! function_exists( 'shopnex_svg_icon' ) ) :
    function shopnex_svg_icon( $icon, $size = 16 ) {
        static $icons = null;

        if ( null === $icons ) {
            $s = $size;
            $icons = array(
                'chevron-right' => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
                'star'          => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
                'star-filled'   => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
                'ruler'         => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2"/><path d="m11.5 9.5 2-2"/><path d="m8.5 6.5 2-2"/><path d="m17.5 15.5 2-2"/></svg>',
                'minus'         => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14"/></svg>',
                'plus'          => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>',
                'shopping-bag'  => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                'heart'         => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
                'truck'         => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>',
                'refresh-cw'    => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>',
                'shield-check'  => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>',
                'chevron-down'  => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>',
                'x'             => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>',
                'chevron-up'    => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>',
                'check'         => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
                'check-circle'  => '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>',
            );
        }

        return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
    }
endif;

/*--------------------------------------------------------------
  * RELATED PRODUCTS
  *--------------------------------------------------------------*/

/**
 * Adjust related-products count and columns from Customizer.
 */
function shopnex_related_products_args( $args ) {
    $count          = intval( get_theme_mod( 'shopnex_row_items', 3 ) );
    $args['posts_per_page'] = $count;
    $args['columns']        = $count;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'shopnex_related_products_args' );




/*--------------------------------------------------------------
 * AJAX HANDLERS
 *--------------------------------------------------------------*/

/* ---- Cart data (product IDs in cart) ---- */

function shopnex_get_cart_data_ajax() {
    check_ajax_referer( 'shopnex_cart_data_nonce', 'nonce' );

    if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
        wp_send_json_error( array( 'message' => 'WooCommerce cart not available' ) );
    }

    $ids = wp_list_pluck( WC()->cart->get_cart(), 'product_id' );

    wp_send_json_success( array(
        'cart_product_ids' => array_values( $ids ),
        'cart_count'       => WC()->cart->get_cart_contents_count(),
    ) );
}
add_action( 'wp_ajax_shopnex_get_cart', 'shopnex_get_cart_data_ajax' );
add_action( 'wp_ajax_nopriv_shopnex_get_cart', 'shopnex_get_cart_data_ajax' );

/* ---- Update cart item quantity ---- */

function shopnex_update_cart_item_quantity() {
    check_ajax_referer( 'shopnex_cart_nonce', 'security' );

    $key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
    $qty = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

    if ( empty( $key ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid cart item.', 'shopnex' ) ) );
    }

    WC()->cart->set_quantity( $key, $qty );
    WC()->cart->calculate_totals();

    $item = WC()->cart->get_cart_item( $key );
    if ( ! $item ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Cart item not found.', 'shopnex' ) ) );
    }

    wp_send_json_success( array(
        'item_total'  => wp_kses_post( wc_price( $item['line_total'] ) ),
        'quantity'    => $qty,
        'cart_count'  => WC()->cart->get_cart_contents_count(),
        'totals'      => shopnex_get_cart_totals_array(),
    ) );
}
add_action( 'wp_ajax_shopnex_cart_item_update', 'shopnex_update_cart_item_quantity' );
add_action( 'wp_ajax_nopriv_shopnex_cart_item_update', 'shopnex_update_cart_item_quantity' );

/* ---- Remove cart item ---- */

function shopnex_remove_cart_item() {
    check_ajax_referer( 'shopnex_cart_nonce', 'security' );

    $key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';

    if ( empty( $key ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid cart item.', 'shopnex' ) ) );
    }

    WC()->cart->remove_cart_item( $key );
    WC()->cart->calculate_totals();

    wp_send_json_success( array(
        'count'  => WC()->cart->get_cart_contents_count(),
        'totals' => shopnex_get_cart_totals_array(),
    ) );
}
add_action( 'wp_ajax_shopnex_cart_item_remove', 'shopnex_remove_cart_item' );
add_action( 'wp_ajax_nopriv_shopnex_cart_item_remove', 'shopnex_remove_cart_item' );

/**
 * Build a reusable cart-totals array for AJAX responses.
 */
function shopnex_get_cart_totals_array() {
    $cart = WC()->cart;
    return array(
        'subtotal'   => wp_kses_post( $cart->get_cart_subtotal() ),
        'tax'        => wp_kses_post( $cart->get_total_tax() ),
        'tax_text'   => sprintf(
            /* translators: %s: formatted tax amount */
            esc_html__( 'Including %s in taxes', 'shopnex' ),
            wp_kses_post( wc_price( $cart->get_total_tax() ) )
        ),
        'total'      => wp_kses_post( $cart->get_total() ),
        'cart_count' => $cart->get_cart_contents_count(),
    );
}

/* ---- Submit product review ---- */

function shopnex_submit_product_review() {
    if ( ! isset( $_POST['shopnex_review_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['shopnex_review_nonce'] ) ), 'shopnex_submit_review' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'shopnex' ) ) );
    }

    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $rating     = isset( $_POST['rating'] ) ? absint( $_POST['rating'] ) : 0;
    $author     = isset( $_POST['reviewer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['reviewer_name'] ) ) : '';
    $email      = isset( $_POST['reviewer_email'] ) ? sanitize_email( wp_unslash( $_POST['reviewer_email'] ) ) : '';
    $title      = isset( $_POST['review_title'] ) ? sanitize_text_field( wp_unslash( $_POST['review_title'] ) ) : '';
    $content    = isset( $_POST['review_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['review_content'] ) ) : '';

    if ( ! $product_id || ! $rating || empty( $author ) || empty( $email ) || empty( $content ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Please fill in all required fields.', 'shopnex' ) ) );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address.', 'shopnex' ) ) );
    }

    if ( $rating < 1 || $rating > 5 ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Please select a valid rating.', 'shopnex' ) ) );
    }

    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid product.', 'shopnex' ) ) );
    }

    if ( ! $product->get_reviews_allowed() ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Reviews are not enabled for this product.', 'shopnex' ) ) );
    }

    // Build the comment content.
    $comment_content = $content;
    if ( ! empty( $title ) ) {
        $comment_content = '<strong>' . esc_html( $title ) . '</strong>' . "\n\n" . $content;
    }

    $commentdata = array(
        'comment_post_ID'      => $product_id,
        'comment_author'       => $author,
        'comment_author_email' => $email,
        'comment_content'      => $comment_content,
        'comment_type'         => 'review',
        'user_id'              => get_current_user_id(),
    );

    // Temporarily set the rating and post ID so WooCommerce hooks can access them.
    add_filter( 'preprocess_comment', function( $commentdata ) use ( $rating ) {
        $_POST['rating']          = $rating;
        $_POST['comment_post_ID'] = $commentdata['comment_post_ID'];
        return $commentdata;
    } );

    // Ensure WooCommerce review approval setting is respected.
    $commentdata['comment_approved'] = get_option( 'comment_moderation' ) ? 0 : 1;

    $comment_id = wp_new_comment( $commentdata );

    if ( ! $comment_id || is_wp_error( $comment_id ) ) {
        $error_msg = is_wp_error( $comment_id ) ? $comment_id->get_error_message() : esc_html__( 'Failed to submit review.', 'shopnex' );
        wp_send_json_error( array( 'message' => $error_msg ) );
    }

    // Determine the approval status message.
    $comment = get_comment( $comment_id );
    $is_approved = $comment && $comment->comment_approved === '1';

    wp_send_json_success( array(
        'message'    => $is_approved
            ? esc_html__( 'Thank you for your review!', 'shopnex' )
            : esc_html__( 'Thank you for your review! It will be published after moderation.', 'shopnex' ),
        'comment_id' => $comment_id,
    ) );
}
add_action( 'wp_ajax_shopnex_submit_review', 'shopnex_submit_product_review' );
add_action( 'wp_ajax_nopriv_shopnex_submit_review', 'shopnex_submit_product_review' );


/*--------------------------------------------------------------
 * CART PAGE — AJAX QUANTITY UPDATE
 *--------------------------------------------------------------*/

/**
 * Ensure WooCommerce session is initialised early during AJAX requests.
 */
if ( ! function_exists( 'shopnex_ensure_wc_session_ajax' ) ) :
    function shopnex_ensure_wc_session_ajax() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        if ( WC()->cart ) {
            WC()->cart->get_cart();
        }
    }
    add_action( 'wp_ajax_shopnex_update_cart_qty',        'shopnex_ensure_wc_session_ajax', 1 );
    add_action( 'wp_ajax_nopriv_shopnex_update_cart_qty', 'shopnex_ensure_wc_session_ajax', 1 );
endif;

/**
 * AJAX handler — update cart item quantity and return refreshed pricing.
 *
 */
if ( ! function_exists( 'shopnex_update_cart_qty_ajax' ) ) :
    function shopnex_update_cart_qty_ajax() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( array( 'message' => __( 'WooCommerce not active.', 'shopnex' ) ) );
        }

        check_ajax_referer( 'shopnex_cart_qty_nonce', 'nonce' );

        $cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
        $quantity      = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 0;

        if ( empty( $cart_item_key ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid cart item.', 'shopnex' ) ) );
        }

        // Ensure cart session is initialized.
        WC()->cart->get_cart();

        // If quantity is 0, remove the item.
        if ( 0 === $quantity ) {
            WC()->cart->remove_cart_item( $cart_item_key );
        } else {
            $updated = WC()->cart->set_quantity( $cart_item_key, $quantity, true );
            if ( ! $updated ) {
                wp_send_json_error( array( 'message' => __( 'Could not update quantity.', 'shopnex' ) ) );
            }
        }

        // Recalculate totals.
        WC()->cart->calculate_totals();

        // Check if cart is now empty.
        if ( WC()->cart->is_empty() ) {
            wp_send_json_success( array(
                'cart_is_empty'       => true,
                'cart_contents_count' => 0,
            ) );
        }

        // Build the line item unit price HTML.
        $line_total_html = '';
        $cart_items = WC()->cart->get_cart();
        if ( isset( $cart_items[ $cart_item_key ] ) ) {
            $cart_item = $cart_items[ $cart_item_key ];
            $_product  = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() ) {
                $line_total_html = wp_kses_post( WC()->cart->get_product_price( $_product ) );
            }
        }

        // Build subtotal HTML.
        ob_start();
        wc_cart_totals_subtotal_html();
        $subtotal_html = ob_get_clean();

        // Build coupon lines HTML.
        $coupon_html = '';
        $coupons = WC()->cart->get_coupons();
        if ( ! empty( $coupons ) ) {
            ob_start();
            foreach ( $coupons as $code => $coupon ) {
                ?>
                <div class="summary-row discount-line">
                    <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                    <span class="summary-value discount"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                </div>
                <?php
            }
            $coupon_html = ob_get_clean();
        }

        // Build shipping HTML.
        $shipping_html = '';
        if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {
            $shipping_html = WC()->cart->get_cart_shipping_total();
        }

        // Build total HTML.
        ob_start();
        wc_cart_totals_order_total_html();
        $total_html = ob_get_clean();

        // Build tax HTML.
        $tax_html = '';
        if ( wc_tax_enabled() ) {
            $tax_html = WC()->cart->get_taxes_total();
        }

        // Build products list HTML for order summary.
        ob_start();
        foreach ( WC()->cart->get_cart() as $sp_key => $sp_item ) {
            $sp_product = apply_filters( 'woocommerce_cart_item_product', $sp_item['data'], $sp_item, $sp_key );
            if ( $sp_product && $sp_product->exists() && $sp_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $sp_item, $sp_key ) ) {
                $sp_permalink  = apply_filters( 'woocommerce_cart_item_permalink', $sp_product->is_visible() ? $sp_product->get_permalink( $sp_item ) : '', $sp_item, $sp_key );
                $sp_thumbnail  = apply_filters( 'woocommerce_cart_item_thumbnail', $sp_product->get_image( 'woocommerce_thumbnail' ), $sp_item, $sp_key );
                $sp_name       = apply_filters( 'woocommerce_cart_item_name', $sp_product->get_name(), $sp_item, $sp_key );
                ?>
                <div class="summary-product-item" data-cart-item-key="<?php echo esc_attr( $sp_key ); ?>">
                    <div class="summary-product-thumb">
                        <?php
                        if ( ! $sp_permalink ) {
                            echo wp_kses_post( $sp_thumbnail );
                        } else {
                            printf( '<a href="%s">%s</a>', esc_url( $sp_permalink ), wp_kses_post( $sp_thumbnail ) );
                        }
                        ?>
                    </div>
                    <div class="summary-product-info">
                        <span class="summary-product-name">
                            <?php
                            if ( ! $sp_permalink ) {
                                echo wp_kses_post( $sp_name );
                            } else {
                                printf( '<a href="%s">%s</a>', esc_url( $sp_permalink ), wp_kses_post( $sp_name ) );
                            }
                            ?>
                        </span>
                        <span class="summary-product-qty"><?php echo esc_html( $sp_item['quantity'] ); ?> &times; <?php echo wp_kses_post( WC()->cart->get_product_price( $sp_product ) ); ?></span>
                    </div>
                    <div class="summary-product-total">
                        <?php
                        echo wp_kses_post(
                            apply_filters(
                                'woocommerce_cart_item_subtotal',
                                WC()->cart->get_product_subtotal( $sp_product, $sp_item['quantity'] ),
                                $sp_item,
                                $sp_key
                            )
                        );
                        ?>
                    </div>
                </div>
                <?php
            }
        }
        $products_html = ob_get_clean();

        wp_send_json_success( array(
            'item_key'            => $cart_item_key,
            'line_total_html'     => $line_total_html,
            'subtotal_html'       => $subtotal_html,
            'coupon_html'         => $coupon_html,
            'shipping_html'       => $shipping_html,
            'total_html'          => $total_html,
            'tax_html'            => $tax_html,
            'products_html'       => $products_html,
            'cart_contents_count' => WC()->cart->get_cart_contents_count(),
            'cart_is_empty'       => false,
        ) );
    }
    add_action( 'wp_ajax_shopnex_update_cart_qty', 'shopnex_update_cart_qty_ajax' );
    add_action( 'wp_ajax_nopriv_shopnex_update_cart_qty', 'shopnex_update_cart_qty_ajax' );
endif;

/*--------------------------------------------------------------
 * PAGINATION
 *--------------------------------------------------------------*/

/**
 * Replace WooCommerce default pagination
 */
function shopnex_custom_woocommerce_pagination() {
    if ( is_product() ) {
        return;
    }

    $total_pages = wc_get_loop_prop( 'total_pages' );

    if ( $total_pages <= 1 ) {
        return;
    }

    $current_page = max( 1, get_query_var( 'paged' ) );
    ?>
    <div class="pagination shop-pagination">
        <?php if ( $current_page > 1 ) : ?>
        <a href="<?php echo esc_url( get_pagenum_link( $current_page - 1 ) ); ?>" class="pg-btn" aria-label="<?php esc_attr_e( 'Previous page', 'shopnex' ); ?>">
            <svg viewBox="0 0 24 24"><path d="m15 18l-6-6l6-6"/></svg>
        </a>
        <?php else : ?>
        <span class="pg-btn disabled" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="m15 18l-6-6l6-6"/></svg>
        </span>
        <?php endif; ?>

        <?php
        $show_pages = 5;
        $start_page = max( 1, $current_page - floor( $show_pages / 2 ) );
        $end_page   = min( $total_pages, $start_page + $show_pages - 1 );

        if ( $start_page > 1 ) {
            echo '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '" class="pg-btn">1</a>';
            if ( $start_page > 2 ) {
                echo '<span class="pg-dots">&hellip;</span>';
            }
        }

        for ( $i = $start_page; $i <= $end_page; $i++ ) {
            if ( $i == $current_page ) {
                echo '<span class="pg-btn active" aria-current="page">' . esc_html( $i ) . '</span>';
            } else {
                echo '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="pg-btn">' . esc_html( $i ) . '</a>';
            }
        }

        if ( $end_page < $total_pages ) {
            if ( $end_page < $total_pages - 1 ) {
                echo '<span class="pg-dots">&hellip;</span>';
            }
            echo '<a href="' . esc_url( get_pagenum_link( $total_pages ) ) . '" class="pg-btn">' . esc_html( $total_pages ) . '</a>';
        }
        ?>

        <?php if ( $current_page < $total_pages ) : ?>
        <a href="<?php echo esc_url( get_pagenum_link( $current_page + 1 ) ); ?>" class="pg-btn" aria-label="<?php esc_attr_e( 'Next page', 'shopnex' ); ?>">
            <svg viewBox="0 0 24 24"><path d="m9 18l6-6l-6-6"/></svg>
        </a>
        <?php else : ?>
        <span class="pg-btn disabled" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="m9 18l6-6l-6-6"/></svg>
        </span>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Remove WooCommerce's default pagination and replace with custom one.
 */
function shopnex_replace_woocommerce_pagination() {
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
    add_action( 'woocommerce_after_shop_loop', 'shopnex_custom_woocommerce_pagination', 10 );
}
add_action( 'woocommerce_init', 'shopnex_replace_woocommerce_pagination' );