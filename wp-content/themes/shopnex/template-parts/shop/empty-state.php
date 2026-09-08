<?php
/**
 * Shop Empty State Template Part
 *
 * Displays empty state when no products are found.
 *
 * @package shopnex
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="shop-empty-state">
    <div class="empty-state-content">
        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="empty-state-icon">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>

        <h2 class="empty-state-title">
            <?php esc_html_e('No products found', 'shopnex'); ?>
        </h2>

        <p class="empty-state-message">
            <?php esc_html_e('We couldn\'t find any products that match your criteria. Please try adjusting your filters or search terms.', 'shopnex'); ?>
        </p>

        <div class="empty-state-actions">
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
                <?php esc_html_e('Return to Shop', 'shopnex'); ?>
            </a>

            <button class="btn btn-secondary reset-filters">
                <?php esc_html_e('Reset Filters', 'shopnex'); ?>
            </button>
        </div>
    </div>
</div>
