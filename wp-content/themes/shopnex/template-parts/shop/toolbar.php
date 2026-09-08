<?php
/**
 * Shop Toolbar Template Part
 *
 * Displays the toolbar with filters, sorting, and view options
 *
 * @package shopnex
 */

defined('ABSPATH') || exit;

$has_sidebar = shopnex_has_shop_sidebar();
?>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="filter-bar-left">
        <button class="fb-btn" data-open-drawer>
            <svg viewBox="0 0 24 24"><path d="M4 21V14"/><path d="M4 10V3"/><path d="M12 21V12"/><path d="M12 8V3"/><path d="M20 21V16"/><path d="M20 12V3"/><circle cx="4" cy="12" r="2"/><circle cx="12" cy="10" r="2"/><circle cx="20" cy="14" r="2"/></svg>
            <?php esc_html_e('All Filters', 'shopnex'); ?>
        </button>

        <div class="fb-divider"></div>

        <button class="fb-btn active" data-toggle-active><?php esc_html_e('Sneakers', 'shopnex'); ?></button>
        <button class="fb-btn" data-toggle-active><?php esc_html_e('Under $150', 'shopnex'); ?></button>
        <button class="fb-btn" data-toggle-active><?php esc_html_e('In Stock', 'shopnex'); ?></button>
        <button class="fb-btn" data-toggle-active>
            <svg viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
            <?php esc_html_e('Sustainable', 'shopnex'); ?>
        </button>
    </div>

    <div class="filter-bar-right">
        <span class="result-text">
            <?php
            $total = wc_get_loop_prop('total', 0);
            printf(esc_html__('Showing %d products', 'shopnex'), $total);
            ?>
        </span>

        <?php
        if (function_exists('woocommerce_catalog_ordering')) {
            woocommerce_catalog_ordering();
        }
        ?>

        <div class="view-toggles">
            <button class="view-btn active" data-set-view="4" title="4 columns">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="4" height="4" rx="1"/><rect x="10" y="3" width="4" height="4" rx="1"/><rect x="17" y="3" width="4" height="4" rx="1"/><rect x="3" y="10" width="4" height="4" rx="1"/><rect x="10" y="10" width="4" height="4" rx="1"/><rect x="17" y="10" width="4" height="4" rx="1"/></svg>
            </button>
            <button class="view-btn" data-set-view="3" title="3 columns">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="5" height="8" rx="1"/><rect x="10" y="3" width="5" height="8" rx="1"/><rect x="17" y="3" width="5" height="8" rx="1"/><rect x="3" y="13" width="5" height="8" rx="1"/><rect x="10" y="13" width="5" height="8" rx="1"/><rect x="17" y="13" width="5" height="8" rx="1"/></svg>
            </button>
        </div>
    </div>
</div>
