<?php
/**
 * shopnex Performance Optimization
 *
 * @package shopnex
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fragment Caching for Product Cards
 *
 * Caches product card HTML to improve performance on large shops.
 *
 * @param int $product_id Product ID
 * @return string Cached HTML
 */
if (!function_exists('shopnex_get_cached_product_card')) {
    function shopnex_get_cached_product_card($product_id) {
        $cache_key = 'shopnex_product_card_' . $product_id;
        $html = get_transient($cache_key);

        if ($html === false) {
            ob_start();
            wc_get_template_part('content', 'product');
            $html = ob_get_clean();

            // Cache for 6 hours
            set_transient($cache_key, $html, 6 * HOUR_IN_SECONDS);
        }

        return $html;
    }
}

/**
 * Clear product card cache when product is updated
 *
 * @param int $product_id Product ID
 */
if (!function_exists('shopnex_clear_product_card_cache')) {
    function shopnex_clear_product_card_cache($product_id) {
        $cache_key = 'shopnex_product_card_' . $product_id;
        delete_transient($cache_key);
    }
}
if (class_exists('WooCommerce')) {
    add_action('woocommerce_update_product', 'shopnex_clear_product_card_cache');
    add_action('woocommerce_delete_product', 'shopnex_clear_product_card_cache');
}

/**
 * Defer non-critical JavaScript
 *
 * Defers loading of non-critical JavaScript for better page load performance.
 *
 * @param string $tag Script tag
 * @param string $handle Script handle
 * @return string Modified script tag
 */
if (!function_exists('shopnex_defer_scripts')) {
    function shopnex_defer_scripts($tag, $handle) {
        $defer_scripts = array(
            'shopnex-script',
            'shopnex-woocommerce-single-product',
        );

        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }
}
add_filter('script_loader_tag', 'shopnex_defer_scripts', 10, 2);
