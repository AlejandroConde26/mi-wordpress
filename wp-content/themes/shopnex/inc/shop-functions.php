<?php
/**
 * Shop Helper Functions
 *
 * Contains helper functions for WooCommerce shop templates
 *
 * @package shopnex
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get sale products count with caching
 *
 * @return int Number of products on sale
 */
if (!function_exists('shopnex_get_sale_products_count')) {
    function shopnex_get_sale_products_count() {
        $count = get_transient('shopnex_sale_count');
        
        if ($count === false) {
            // Optimized query - only get IDs and count
            $query = new WP_Query(array(
                'post_type'      => 'product',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'meta_query'     => array(
                    'relation' => 'OR',
                    array(
                        'key'     => '_sale_price',
                        'value'   => 0,
                        'compare' => '>',
                        'type'    => 'NUMERIC'
                    ),
                    array(
                        'key'     => '_min_variation_sale_price',
                        'value'   => 0,
                        'compare' => '>',
                        'type'    => 'NUMERIC'
                    )
                )
            ));
            
            $count = $query->found_posts;
            
            // Cache for 1 hour
            set_transient('shopnex_sale_count', $count, HOUR_IN_SECONDS);
        }
        
        return (int) $count;
    }
}

/**
 * Get new products count (last 30 days) with caching
 *
 * @return int Number of new products
 */
if (!function_exists('shopnex_get_new_products_count')) {
    function shopnex_get_new_products_count() {
        $count = get_transient('shopnex_new_count');
        
        if ($count === false) {
            // Optimized query - only get IDs and count
            $query = new WP_Query(array(
                'post_type'      => 'product',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'date_query'     => array(
                    array(
                        'after' => '30 days ago'
                    )
                )
            ));
            
            $count = $query->found_posts;
            
            // Cache for 30 minutes
            set_transient('shopnex_new_count', $count, 30 * MINUTE_IN_SECONDS);
        }
        
        return (int) $count;
    }
}

/**
 * Get product brand or category
 *
 * @param int $product_id Product ID
 * @return string Brand or category name
 */
if (!function_exists('shopnex_get_product_brand')) {
    function shopnex_get_product_brand($product_id) {
        // Try to get brand first
        $brands = wp_get_post_terms($product_id, 'product_brand');
        
        if (!empty($brands) && !is_wp_error($brands)) {
            return $brands[0]->name;
        }
        
        // Fallback to first product category
        $categories = get_the_terms($product_id, 'product_cat');
        
        if (!empty($categories) && !is_wp_error($categories)) {
            return $categories[0]->name;
        }
        
        return '';
    }
}

/**
 * Get product categories for tabs
 *
 * @param int $limit Number of categories to return
 * @return array Array of category objects
 */
if (!function_exists('shopnex_get_shop_categories')) {
    function shopnex_get_shop_categories($limit = 5) {
        $categories = get_transient('shopnex_shop_categories');
        
        if ($categories === false) {
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'number'     => $limit,
                'orderby'    => 'count',
                'order'      => 'DESC'
            ));
            
            // Cache for 1 hour
            set_transient('shopnex_shop_categories', $categories, HOUR_IN_SECONDS);
        }
        
        return $categories;
    }
}

/**
 * Get total product categories count
 *
 * @return int Number of product categories
 */
if (!function_exists('shopnex_get_categories_count')) {
    function shopnex_get_categories_count() {
        $count = get_transient('shopnex_categories_count');
        
        if ($count === false) {
            $count = wp_count_terms('product_cat', array('hide_empty' => true));
            
            // Cache for 1 hour
            set_transient('shopnex_categories_count', $count, HOUR_IN_SECONDS);
        }
        
        return (int) $count;
    }
}

/**
 * Get term image URL
 *
 * @param int $term_id Term ID
 * @return string Image URL
 */
if (!function_exists('shopnex_get_term_image')) {
    function shopnex_get_term_image($term_id) {
        if (!function_exists('get_term_meta')) {
            return '';
        }
        
        $thumbnail_id = get_term_meta($term_id, 'thumbnail_id', true);
        
        if ($thumbnail_id) {
            $image_url = wp_get_attachment_url($thumbnail_id);
            if ($image_url) {
                return $image_url;
            }
        }
        
        return '';
    }
}

/**
 * Check if a product is new (published within last 30 days)
 *
 * @param int $product_id Product ID
 * @return bool Whether product is new
 */
if (!function_exists('shopnex_is_product_new')) {
    function shopnex_is_product_new($product_id) {
        $product = wc_get_product($product_id);
        if (!$product) {
            return false;
        }

        $post_date = get_the_date('U', $product_id);
        $thirty_days_ago = strtotime('-30 days');

        return $post_date >= $thirty_days_ago;
    }
}

/**
 * Clear shop transients
 */
if (!function_exists('shopnex_clear_shop_transients')) {
    function shopnex_clear_shop_transients() {
        delete_transient('shopnex_sale_count');
        delete_transient('shopnex_new_count');
        delete_transient('shopnex_shop_categories');
        delete_transient('shopnex_categories_count');
        delete_transient('shopnex_trending_searches');
    }
}
add_action('woocommerce_product_set_stock', 'shopnex_clear_shop_transients');
add_action('woocommerce_product_set_stock_status', 'shopnex_clear_shop_transients');
add_action('woocommerce_new_product', 'shopnex_clear_shop_transients');
add_action('woocommerce_update_product', 'shopnex_clear_shop_transients');
add_action('woocommerce_delete_product', 'shopnex_clear_shop_transients');

/**
 * Get sale percentage for product
 */
if (!function_exists('shopnex_get_product_sale_percentage')) {
    function shopnex_get_product_sale_percentage($product) {
        if (function_exists('shopnex_get_sale_percentage')) {
            return shopnex_get_sale_percentage($product);
        }
        return '';
    }
}

/**
 * Check if sidebar should be displayed
 *
 * @return bool Whether sidebar is active
 */
if (!function_exists('shopnex_has_shop_sidebar')) {
    function shopnex_has_shop_sidebar() {
        $sidebar_layout = get_theme_mod('shopnex_shop_page_sidebar_layout', 'right');
        return is_active_sidebar('woosidebar') && $sidebar_layout !== 'none';
    }
}

/**
 * Get sidebar layout class
 *
 * @return string CSS class for sidebar layout
 */
if (!function_exists('shopnex_get_sidebar_layout_class')) {
    function shopnex_get_sidebar_layout_class() {
        $sidebar_layout = get_theme_mod('shopnex_shop_page_sidebar_layout', 'right');
        
        switch ($sidebar_layout) {
            case 'left':
                return 'sidebar-left';
            case 'right':
                return 'sidebar-right';
            case 'none':
            default:
                return 'no-sidebar';
        }
    }
}

/**
 * Ensure WooCommerce uses correct image size for product thumbnails
 *
 * @param string $size Image size
 * @return string Modified image size
 */
if (!function_exists('shopnex_woocommerce_loop_product_thumbnail_size')) {
    function shopnex_woocommerce_loop_product_thumbnail_size($size) {
        return 'woocommerce_thumbnail';
    }
}
add_filter('woocommerce_loop_product_thumbnail_size', 'shopnex_woocommerce_loop_product_thumbnail_size');

/**
 * Override WooCommerce product thumbnail template to use woocommerce_thumbnail size
 *
 * @return void
 */
if (!function_exists('shopnex_override_product_thumbnail')) {
    function shopnex_override_product_thumbnail() {
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
        add_action('woocommerce_before_shop_loop_item_title', 'shopnex_template_loop_product_thumbnail', 10);
    }
}
add_action('woocommerce_init', 'shopnex_override_product_thumbnail');

/**
 * Custom product thumbnail template using woocommerce_thumbnail size
 *
 * @return void
 */
if (!function_exists('shopnex_template_loop_product_thumbnail')) {
    function shopnex_template_loop_product_thumbnail() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        $image_size = apply_filters('single_product_archive_thumbnail_size', 'woocommerce_thumbnail');
        
        echo '<div class="product-image-wrapper">';
        
        if (has_post_thumbnail()) {
            $props = wc_get_product_attachment_props(get_post_thumbnail_id(), $product);
            
            echo get_the_post_thumbnail(
                $product->get_id(),
                $image_size,
                array(
                    'title'                   => $props['title'],
                    'alt'                     => $props['alt'],
                    'class'                   => 'product-img',
                    'data-caption'             => $props['caption'],
                    'data-src'                => $props['src'],
                    'data-large_image'         => $props['full_src'],
                    'data-large_image_width'   => $props['full_src_w'],
                    'data-large_image_height'  => $props['full_src_h'],
                )
            );
        } else {
            $placeholder_url = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src('woocommerce_thumbnail') : '';
            if ( $placeholder_url ) {
                printf(
                    '<img src="%s" alt="%s" class="product-img" />',
                    esc_url($placeholder_url),
                    esc_attr__('No Image', 'shopnex')
                );
            }
        }
        
        echo '</div>';
    }
}

/**
 * Handle clear cart action
 *
 *
 * @return void
 */
if ( ! function_exists( 'shopnex_handle_clear_cart' ) ) {
    function shopnex_handle_clear_cart() {
        if ( ! isset( $_GET['clear-cart'] ) || 'yes' !== $_GET['clear-cart'] ) {
            return;
        }

        if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
            return;
        }

        // Verify we're on the cart page
        if ( ! is_cart() ) {
            return;
        }

        // Verify nonce for security
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'shopnex_clear_cart' ) ) {
            wc_add_notice( __( 'Security check failed. Cart was not cleared.', 'shopnex' ), 'error' );
            return;
        }

        // Empty the cart
        WC()->cart->empty_cart();

        // Add success notice
        wc_add_notice( __( 'Cart has been cleared.', 'shopnex' ), 'success' );

        // Redirect to cart page without query params to avoid re-clearing on refresh
        wp_safe_redirect( wc_get_cart_url() );
        exit;
    }
}
add_action( 'template_redirect', 'shopnex_handle_clear_cart', 5 );

/**
 * Get trending search terms based on actual product data
 *
 * Fetches popular product categories, tags, and product names
 * to generate real-time trending search suggestions.
 *
 * @param int $limit Number of trending terms to return
 * @return array Array of trending search terms
 */
if (!function_exists('shopnex_get_trending_searches')) {
    function shopnex_get_trending_searches($limit = 6) {
        $trending_searches = get_transient('shopnex_trending_searches');
        
        if ($trending_searches === false) {
            $trending_searches = array();
            
            // Get popular product categories (by product count)
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'number' => ceil($limit / 2),
                'orderby' => 'count',
                'order' => 'DESC'
            ));
            
            if (!empty($categories) && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $trending_searches[] = $category->name;
                }
            }
            
            // Get popular product tags (by product count)
            if (count($trending_searches) < $limit) {
                $tags = get_terms(array(
                    'taxonomy' => 'product_tag',
                    'hide_empty' => true,
                    'number' => $limit - count($trending_searches),
                    'orderby' => 'count',
                    'order' => 'DESC'
                ));
                
                if (!empty($tags) && !is_wp_error($tags)) {
                    foreach ($tags as $tag) {
                        $trending_searches[] = $tag->name;
                    }
                }
            }
            
            // If still need more terms, get recent popular products
            if (count($trending_searches) < $limit && function_exists('wc_get_products')) {
                $products = wc_get_products(array(
                    'limit' => $limit - count($trending_searches),
                    'orderby' => 'popularity',
                    'order' => 'DESC',
                    'status' => 'publish'
                ));
                
                foreach ($products as $product) {
                    $trending_searches[] = $product->get_name();
                }
            }
            
            // Shuffle the results for variety
            shuffle($trending_searches);
            
            // Limit to requested number
            $trending_searches = array_slice($trending_searches, 0, $limit);
            
            // Cache for 1 hour
            set_transient('shopnex_trending_searches', $trending_searches, HOUR_IN_SECONDS);
        }
        
        return $trending_searches;
    }
}

/**
 * Clear trending searches transient
 * Call this when products, categories, or tags are updated
 */
if (!function_exists('shopnex_clear_trending_searches_transient')) {
    function shopnex_clear_trending_searches_transient() {
        delete_transient('shopnex_trending_searches');
    }
}
add_action('woocommerce_new_product', 'shopnex_clear_trending_searches_transient');
add_action('woocommerce_update_product', 'shopnex_clear_trending_searches_transient');
add_action('woocommerce_delete_product', 'shopnex_clear_trending_searches_transient');
add_action('created_term', 'shopnex_clear_trending_searches_transient');
add_action('edited_term', 'shopnex_clear_trending_searches_transient');
add_action('delete_term', 'shopnex_clear_trending_searches_transient');
