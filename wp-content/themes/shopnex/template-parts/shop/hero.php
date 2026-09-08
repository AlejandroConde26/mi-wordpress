<?php
/**
 * Shop Hero Section Template Part
 *
 * Displays the centered page header for product archives
 *
 * @package shopnex
 */

defined('ABSPATH') || exit;

// Get current term info
$term = get_queried_object();
$term_description = isset($term->description) ? $term->description : '';

// Determine title
if (is_product_category() || is_product_tag()) {
    $page_title = single_term_title('', false);
    $page_desc  = $term_description;
} elseif (is_shop()) {
    $page_title = get_the_title(get_option('woocommerce_shop_page_id'));
    if (empty($page_title)) {
        $page_title = __('Shop', 'shopnex');
    }
    $page_desc = '';
} else {
    $page_title = __('Products', 'shopnex');
    $page_desc  = '';
}

// Get product count
$total_products = wc_get_loop_prop('total', 0);
?>

<!-- Archive Page Header -->
<section class="archive-page-header">
    <div class="breadcrumb shop-breadcrumb-nav" aria-label="<?php esc_attr_e('Breadcrumb', 'shopnex'); ?>">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'shopnex'); ?></a>
                <span class="shop-breadcrumb-sep">/</span>
        <?php
        if (is_product_category()) {
            echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">' . esc_html__('Shop', 'shopnex') . '</a>';
            echo '        <span class="shop-breadcrumb-sep">/</span>';
            echo '<span class="shop-breadcrumb-current">' . esc_html(single_term_title('', false)) . '</span>';
        } elseif (is_shop()) {
            echo '<span class="shop-breadcrumb-current">' . esc_html__('Shop All', 'shopnex') . '</span>';
        } else {
            echo '<span class="shop-breadcrumb-current">' . esc_html($page_title) . '</span>';
        }
        ?>
    </div>
    <h1 class="archive-page-title"><?php echo esc_html($page_title); ?></h1>
    <?php if (!empty($page_desc)) : ?>
        <p class="archive-page-desc"><?php echo esc_html($page_desc); ?></p>
    <?php endif; ?>
    <p class="archive-page-result-count"><?php printf(esc_html__('Showing %d products', 'shopnex'), esc_html($total_products)); ?></p>
</section>
