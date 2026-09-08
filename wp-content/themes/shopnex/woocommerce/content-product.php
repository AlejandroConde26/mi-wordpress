<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs, the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}

$post        = get_post();
$product_id  = $product->get_id();

$average_rating = $product->get_average_rating();
$review_count   = $product->get_review_count();
$is_on_sale     = $product->is_on_sale();
$is_in_stock    = $product->is_in_stock();

$badge_text  = '';
$badge_class = '';

if ( ! $is_in_stock ) {
	$badge_text  = __( 'Sold Out', 'shopnex' );
	$badge_class = 'sold-out';
} elseif ( $is_on_sale ) {
	$badge_text  = shopnex_get_product_sale_percentage( $product );
	$badge_class = 'sale-badge';
} elseif ( function_exists( 'shopnex_is_product_new' ) && shopnex_is_product_new( $product_id ) ) {
	$badge_text  = __( 'New', 'shopnex' );
	$badge_class = 'new';
}

$brand_name         = shopnex_get_product_brand( $product_id );
$product_categories = wc_get_product_category_list( $product_id, ', ' );
?>

<div <?php wc_product_class( 'product-card', $product ); ?> data-product-id="<?php echo esc_attr( $product_id ); ?>">

	<div class="product-card-img">
		<a href="<?php the_permalink(); ?>" class="product-card-img-link">
			<?php
			if ( function_exists( 'shopnex_template_loop_product_thumbnail' ) ) {
				shopnex_template_loop_product_thumbnail();
			} else {
				woocommerce_template_loop_product_thumbnail();
			}
			?>
			<?php do_action( 'shopnex_product_second_image', $product_id ); ?>
		</a>

		<?php if ( ! empty( $badge_text ) ) : ?>
			<span class="product-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $badge_text ); ?></span>
		<?php endif; ?>

		<div class="product-card-actions">
			<?php if ( $is_in_stock ) :
				$is_in_cart = false;
				if ( function_exists( 'WC' ) && WC()->cart ) {
					foreach ( WC()->cart->get_cart() as $cart_item ) {
						if ( $cart_item['product_id'] === $product_id ) {
							$is_in_cart = true;
							break;
						}
					}
				}

				if ( $is_in_cart ) : ?>
					<button class="product-card-wishlist quick-view-added" disabled aria-label="<?php esc_attr_e( 'Already in cart', 'shopnex' ); ?>" title="<?php esc_attr_e( 'Added to cart', 'shopnex' ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
					</button>
				<?php else : ?>
					<button data-quick-add-to-cart="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Add to cart', 'shopnex' ); ?>" title="<?php esc_attr_e( 'Add to cart', 'shopnex' ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
					</button>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action( 'shopnex_before_compare_button', $product_id ); ?>
			<?php do_action( 'shopnex_after_wishlist_button', $product_id ); ?>
			<?php do_action( 'shopnex_after_compare_button', $product_id ); ?>
		</div>
	</div>

	<div class="product-card-info">
		<h3 class="product-card-name">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( ! empty( $product_categories ) ) : ?>
			<div class="product-card-category"><?php echo wp_kses_post( $product_categories ); ?></div>
		<?php elseif ( ! empty( $brand_name ) ) : ?>
			<div class="product-card-category"><?php echo esc_html( $brand_name ); ?></div>
		<?php endif; ?>

		<?php if ( $review_count > 0 ) : ?>
			<div class="product-card-rating">
				<span class="rating-stars">
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
						<span class="<?php echo $i <= round( $average_rating ) ? 'star-filled' : 'star-empty'; ?>"><?php echo shopnex_svg_icon( 'star', 12 ); ?></span>
					<?php endfor; ?>
				</span>
				<span class="rating-count">(<?php echo esc_html( $review_count ); ?>)</span>
			</div>
		<?php endif; ?>

		<div class="product-card-price">
			<?php woocommerce_template_loop_price(); ?>
		</div>

		<?php
		do_action( 'woocommerce_after_shop_loop_item_title' );

		shopnex_archive_swatches();
		?>

		<?php shopnex_countdown_loop(); ?>
	</div>

</div>
