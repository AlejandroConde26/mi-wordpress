<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section
 * 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package shopnex
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class();?>>
	<?php 
      wp_body_open();
    ?>

<!-- Skip to main content link for accessibility -->
<a class="skip-link" href="#main-content">
    <?php esc_html_e( 'Skip to content', 'shopnex' ); ?>
</a>

<!-- Search Overlay -->
<div id="searchOverlay" class="search-overlay">
    <div class="search-overlay-backdrop"></div>
    <div class="search-overlay-inner">
        <div class="search-overlay-header">
            <div class="search-overlay-heading">
                <span class="search-overlay-label"><?php esc_html_e( 'Search', 'shopnex' ); ?></span>
            </div>
            <button id="search-close" class="search-close-btn" aria-label="<?php esc_attr_e( 'Close search', 'shopnex' ); ?>">
                <span class="search-close-text"><?php esc_html_e( 'Close', 'shopnex' ); ?></span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <div class="search-input-wrapper">
            <?php get_search_form(); ?>
        </div>
        <div class="search-trending">
            <p class="search-trending-label"><?php esc_html_e( 'Trending', 'shopnex' ); ?></p>
            <div class="search-tags">
                <?php
                $trending_searches = array();
                if ( function_exists( 'shopnex_get_trending_searches' ) ) {
                    $trending_searches = apply_filters( 'shopnex_trending_searches', shopnex_get_trending_searches( 6 ) );
                }
                if ( ! empty( $trending_searches ) && is_array( $trending_searches ) ) :
                    foreach ( $trending_searches as $search_term ) :
                ?>
                <a href="<?php echo esc_url( home_url( '/?s=' . urlencode( $search_term ) . ( shopnex_is_active_woocommerce() ? '&post_type=product' : '' ) ) ); ?>" class="search-tag"><?php echo esc_html( $search_term ); ?></a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobileMenuOverlay" class="mobile-menu-overlay"></div>
<!-- Mobile Menu -->
<div id="mobileMenuDrawer" class="mobile-menu-fullscreen" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobile Menu', 'shopnex' ); ?>">
    <button id="mobileMenuClose" class="mobile-menu-close-btn" aria-label="<?php esc_attr_e( 'Close menu', 'shopnex' ); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>
    <nav class="mobile-menu-nav">
        <?php 
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'mobile-menu-links',
            'fallback_cb' => 'shopnex_default_menu_fallback',
            'depth' => 3,
        ));
        ?>
    </nav>
    <div class="mobile-menu-footer">
        <?php if ( shopnex_is_active_woocommerce() ) : 
            $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
            if ($myaccount_page_id) :
        ?>
        <a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>" class="mobile-menu-footer-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <?php esc_html_e( 'Account', 'shopnex' ); ?>
        </a>
        <?php 
            endif;
        endif; 
        ?>
        <?php do_action( 'shopnex_mobile_menu_footer' ); ?>
    </div>
</div>

<!-- ANNOUNCEMENT BAR -->
<?php if ( is_active_sidebar( 'topbar' ) ) : ?>
<div class="announcement-bar">
    <?php dynamic_sidebar( 'topbar' ); ?>
</div>
<?php endif; ?>

<!-- HEADER -->
<header id="mainHeader" class="site-header">
    <div class="header-inner">
        <!-- Logo -->
        <div class="site-branding">
            <?php
            $shopnex_show_brand_text = get_theme_mod( 'shopnex_display_site_title_tagline', true );
            $shopnex_site_desc       = get_bloginfo( 'description', 'display' );

            if ( has_custom_logo() ) :
                if ( $shopnex_show_brand_text ) :
                    $shopnex_custom_logo_id = get_theme_mod( 'custom_logo' );
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-link">
                        <img src="<?php echo esc_url( wp_get_attachment_image_url( $shopnex_custom_logo_id, 'full' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="custom-logo">
                        <span class="site-title-text">
                            <span class="site-title-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
                            <?php if ( $shopnex_site_desc ) : ?>
                                <span class="site-description"><?php echo esc_html( $shopnex_site_desc ); ?></span>
                            <?php endif; ?>
                        </span>
                    </a>
                    <?php
                else :
                    shopnex_custom_logo();
                endif;
            elseif ( $shopnex_show_brand_text ) :
                ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-text">
                    <span class="site-title-text">
                        <span class="site-title-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?><span class="period">.</span></span>
                        <?php if ( $shopnex_site_desc ) : ?>
                            <span class="site-description"><?php echo esc_html( $shopnex_site_desc ); ?></span>
                        <?php endif; ?>
                    </span>
                </a>
                <?php
            endif;
            ?>
        </div>

        <!-- Desktop Navigation -->
        <nav class="main-navigation">
            <ul class="nav-links">
                <?php 
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'items_wrap' => '%3$s',
                    'fallback_cb' => 'shopnex_default_menu_fallback',
                    'depth' => 3,
                ));
                ?>
            </ul>
        </nav>

        <!-- Header Actions -->
        <div class="nav-actions">
            <!-- Search -->
            <button class="nav-action-btn search-btn" id="search-toggle" aria-label="<?php esc_attr_e( 'Search', 'shopnex' ); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </button>

            <?php do_action('shopnex_nav_after_search'); ?>

            <!-- Account -->
            <?php 
            if (shopnex_is_active_woocommerce()) :
                $myaccount_page_id = get_option('woocommerce_myaccount_page_id');
                if ($myaccount_page_id) :
            ?>
            <a href="<?php echo esc_url(get_permalink($myaccount_page_id)); ?>" class="nav-action-btn nav-action-desktop" aria-label="<?php esc_attr_e( 'Account', 'shopnex' ); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </a>
            <?php 
                endif;
            endif; 
            ?>

            <!-- Wishlist -->
            <?php do_action( 'shopnex_action_header_wishlist' ); ?>

            <!-- Cart -->
            <?php 
            if (shopnex_is_active_woocommerce()) :
                if (function_exists('wc_get_cart_url')) :
            ?>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nav-action-btn" id="cart-toggle" aria-label="<?php esc_attr_e( 'Cart', 'shopnex' ); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <span class="cart-count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : '0' ); ?></span>
            </a>
            <?php
                endif;
            endif; 
            ?>

            <!-- Hamburger Menu -->
            <button class="hamburger" id="hamburger" aria-label="<?php esc_attr_e( 'Menu', 'shopnex' ); ?>">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>
