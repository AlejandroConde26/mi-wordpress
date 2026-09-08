<?php
/**
 * Shop Category Tabs Template Part
 *
 * Displays category tabs with product counts
 *
 * @package shopnex
 */

defined('ABSPATH') || exit;

// Get current term info
$term = get_queried_object();
$term_id = isset($term->term_id) ? $term->term_id : 0;

// Get product count
$product_count = isset($term->count) ? $term->count : wc_get_loop_prop('total', 0);

// Get categories
$categories = shopnex_get_shop_categories(5);

// Get sale count
$sale_count = shopnex_get_sale_products_count();
?>

<!-- Category Tabs -->
<div class="category-tabs-wrapper">
    <div class="container">
        <div class="category-tabs" id="catTabs">
            <button class="cat-tab active" data-set-tab>
                <?php esc_html_e('All', 'shopnex'); ?> 
                <span class="tab-count"><?php echo esc_html($product_count); ?></span>
            </button>
            
            <?php foreach ($categories as $category) : ?>
                <button class="cat-tab" data-set-tab>
                    <?php echo esc_html($category->name); ?> 
                    <span class="tab-count"><?php echo esc_html($category->count); ?></span>
                </button>
            <?php endforeach; ?>
            
            <button class="cat-tab" data-set-tab>
                <?php esc_html_e('Sale', 'shopnex'); ?> 
                <span class="tab-count"><?php echo esc_html($sale_count); ?></span>
            </button>
        </div>
    </div>
</div>
