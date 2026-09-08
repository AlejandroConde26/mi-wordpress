<?php
/**
 * WooCommerce Template Wrapper
 *
 * This file serves as the universal wrapper for WooCommerce pages.
 * For shop archive pages, it delegates to woocommerce/archive-product.php
 * which has its own header/footer and full layout (hero, category tabs, etc.).
 * For other WooCommerce pages (cart, checkout, account), it provides
 * a clean content wrapper.
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;

// For shop archive pages
if ( is_shop() || is_product_category() || is_product_tag() || is_post_type_archive( 'product' ) ) {
	include get_template_directory() . '/woocommerce/archive-product.php';
	return;
}

// For single product pages
if ( is_product() ) {
	include get_template_directory() . '/woocommerce/single-product.php';
	return;
}

// For all other WooCommerce pages
get_header();

// Build breadcrumb items for WooCommerce pages
$breadcrumb_items = array();
$breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );

if ( is_cart() ) {
	$breadcrumb_items[] = array( 'label' => __( 'Cart', 'shopnex' ), 'url' => '' );
} elseif ( is_checkout() ) {
	$breadcrumb_items[] = array( 'label' => __( 'Cart', 'shopnex' ), 'url' => wc_get_cart_url() );
	$breadcrumb_items[] = array( 'label' => __( 'Checkout', 'shopnex' ), 'url' => '' );
} elseif ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
	$breadcrumb_items[] = array( 'label' => __( 'Cart', 'shopnex' ), 'url' => wc_get_cart_url() );
	$breadcrumb_items[] = array( 'label' => __( 'Checkout', 'shopnex' ), 'url' => wc_get_checkout_url() );
	$breadcrumb_items[] = array( 'label' => __( 'Order Received', 'shopnex' ), 'url' => '' );
} elseif ( is_account_page() ) {
	$breadcrumb_items[] = array( 'label' => __( 'My Account', 'shopnex' ), 'url' => '' );
} else {
	// Fallback for any other WooCommerce page
	$breadcrumb_items[] = array( 'label' => get_the_title(), 'url' => '' );
}
?>

<!-- Breadcrumbs -->
<div class="shop-breadcrumbs">
	<div class="container">
		<nav class="shop-breadcrumb-nav" aria-label="<?php esc_attr_e( 'Breadcrumb', 'shopnex' ); ?>">
			<?php foreach ( $breadcrumb_items as $i => $item ) : ?>
				<?php if ( ! empty( $item['url'] ) ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
					<span class="shop-breadcrumb-sep">/</span>
				<?php else : ?>
					<span class="shop-breadcrumb-current"><?php echo esc_html( $item['label'] ); ?></span>
				<?php endif; ?>
			<?php endforeach; ?>
		</nav>
	</div>
</div>

<div class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="content-inner">
				<?php woocommerce_content(); ?>
			</div>
		</main>
	</div>
</div>

<?php
get_footer();
