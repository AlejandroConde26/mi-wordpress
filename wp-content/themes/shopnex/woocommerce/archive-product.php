<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs, the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

get_header( 'shop' );
?>

<div class="shop-archive-wrapper">
	<div class="container">

		<?php get_template_part( 'template-parts/shop/hero' ); ?>

		<div class="shop-filter-bar" id="shopFilterBar">
			<div class="shop-filter-group" id="categoryFilters">
				<?php
				$categories  = get_terms( array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
					'number'     => 6,
					'orderby'    => 'count',
					'order'      => 'DESC',
				) );

				$current_cat    = is_product_category() ? get_queried_object()->slug : '';
				$is_all_active  = empty( $current_cat ) && ! isset( $_GET['product_cat'] );
				$shop_url       = get_permalink( wc_get_page_id( 'shop' ) );
				?>
				<a href="<?php echo esc_url( $shop_url ); ?>" class="filter-pill<?php echo $is_all_active ? ' active-filter' : ''; ?>"><?php esc_html_e( 'All', 'shopnex' ); ?></a>

				<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
					<?php foreach ( $categories as $category ) :
						$is_active = ( $current_cat === $category->slug );
						$cat_link  = get_term_link( $category );
						$cat_url   = ! is_wp_error( $cat_link ) ? $cat_link : add_query_arg( 'product_cat', $category->slug, $shop_url );
						?>
						<a href="<?php echo esc_url( $cat_url ); ?>" class="filter-pill<?php echo $is_active ? ' active-filter' : ''; ?>"><?php echo esc_html( $category->name ); ?></a>
					<?php endforeach; ?>
				<?php endif; ?>

				<span class="filter-pill filter-divider">|</span>

				<a href="<?php echo esc_url( add_query_arg( 'filter_new', '1', $shop_url ) ); ?>" class="filter-pill"><?php esc_html_e( 'New', 'shopnex' ); ?></a>
				<a href="<?php echo esc_url( add_query_arg( 'on_sale', '1', $shop_url ) ); ?>" class="filter-pill"><?php esc_html_e( 'Sale', 'shopnex' ); ?></a>
			</div>

			<div class="shop-sort-wrapper">
				<span class="sort-label"><?php esc_html_e( 'Sort by', 'shopnex' ); ?></span>
				<?php
				if ( function_exists( 'woocommerce_catalog_ordering' ) ) {
					woocommerce_catalog_ordering();
				}
				?>
			</div>
		</div>

		<main class="shop-main">
			<?php if ( woocommerce_product_loop() ) : ?>

				<?php do_action( 'woocommerce_before_shop_loop' ); ?>

				<?php woocommerce_product_loop_start(); ?>

				<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php do_action( 'woocommerce_shop_loop' ); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php do_action( 'woocommerce_after_shop_loop' ); ?>

			<?php else : ?>

				<?php do_action( 'woocommerce_no_products_found' ); ?>

			<?php endif; ?>
		</main>
	</div>
</div>

<?php
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
