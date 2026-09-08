<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$product = wc_get_product( get_the_ID() );

if ( ! $product ) {
	return;
}

$product_id         = $product->get_id();
$product_title      = $product->get_title();
$product_short_desc = $product->get_short_description();
$sku                = $product->get_sku();

$rating_count = $product->get_rating_count();
$average      = $product->get_average_rating();

$categories    = get_the_terms( $product_id, 'product_cat' );
$main_category = ! empty( $categories ) && ! is_wp_error( $categories ) ? $categories[0] : null;

$attachment_ids  = $product->get_gallery_image_ids();
$main_image_id   = $product->get_image_id();
$all_images      = array();

if ( $main_image_id ) {
	$all_images[] = $main_image_id;
}
if ( ! empty( $attachment_ids ) ) {
	$all_images = array_merge( $all_images, $attachment_ids );
}

$attributes     = $product->get_attributes();
$size_attribute = null;

foreach ( $attributes as $attribute ) {
	$attr_name = $attribute->get_name();
	if ( false !== strpos( $attr_name, 'size' ) || false !== strpos( $attr_name, 'pa_size' ) ) {
		$size_attribute = $attribute;
	}
}

$related_products = wc_get_related_products( $product_id, 3 );

$comments = get_comments( array(
	'post_id' => $product_id,
	'status'  => 'approve',
	'type'    => 'review',
) );

$rating_distribution = array( 5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0 );
foreach ( $comments as $comment ) {
	$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
	if ( $rating >= 1 && $rating <= 5 ) {
		$rating_distribution[ $rating ]++;
	}
}

$price_html     = $product->get_price_html();
$current_price  = '';
$original_price = '';

if ( $product->is_type( 'variable' ) ) {
	$current_price = $price_html;
} elseif ( $product->is_on_sale() ) {
	if ( preg_match( '/<ins[^>]*>(.*?)<\/ins>/s', $price_html, $matches ) ) {
		$current_price = $matches[1];
	}
	if ( preg_match( '/<del[^>]*>(.*?)<\/del>/s', $price_html, $matches ) ) {
		$original_price = $matches[1];
	}
} else {
	$current_price = $price_html;
}

$badge_text = '';
$tag_text   = '';

if ( $product->is_on_sale() ) {
	$sale_pct   = ! $product->is_type( 'variable' ) ? shopnex_get_sale_percentage( $product ) : '';
	$badge_text = $sale_pct ? $sale_pct : esc_html__( 'Sale', 'shopnex' );
} elseif ( function_exists( 'shopnex_is_product_new' ) && shopnex_is_product_new( $product_id ) ) {
	$badge_text = esc_html__( 'New', 'shopnex' );
	$tag_text   = esc_html__( 'New Arrival', 'shopnex' );
} elseif ( $product->is_featured() ) {
	$tag_text = esc_html__( 'Bestseller', 'shopnex' );
}

$breadcrumb_items   = array();
$breadcrumb_items[] = array( 'label' => __( 'Home', 'shopnex' ), 'url' => home_url( '/' ) );

if ( $main_category ) {
	$breadcrumb_items[] = array( 'label' => $main_category->name, 'url' => get_term_link( $main_category ) );
}

$breadcrumb_items[] = array( 'label' => $product_title, 'url' => '' );
?>

<div class="shopnex-breadcrumb">
	<div class="shopnex-container">
		<nav class="shopnex-breadcrumb-nav">
			<?php foreach ( $breadcrumb_items as $i => $item ) : ?>
				<?php if ( ! empty( $item['url'] ) ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>" class="shopnex-breadcrumb-link"><?php echo esc_html( $item['label'] ); ?></a>
					<span class="shopnex-breadcrumb-sep">/</span>
				<?php else : ?>
					<span class="shopnex-breadcrumb-current"><?php echo esc_html( $item['label'] ); ?></span>
				<?php endif; ?>
			<?php endforeach; ?>
		</nav>
	</div>
</div>

<main class="shopnex-pdp-main">
	<div class="shopnex-container shopnex-pdp-container">
		<div class="shopnex-pdp-grid">

			<div class="shopnex-gallery">
				<div class="shopnex-gallery-main">
					<img id="mainImg" src="<?php echo ! empty( $all_images ) ? esc_url( wp_get_attachment_image_url( $all_images[0], 'large' ) ) : wc_placeholder_img_src(); ?>" alt="<?php echo esc_attr( $product_title ); ?>">
					<?php if ( $badge_text ) : ?>
						<div class="shopnex-pdp-badge <?php echo $product->is_on_sale() ? 'shopnex-badge-sale' : ( $product->is_featured() ? 'shopnex-badge-bestseller' : 'shopnex-badge-new' ); ?>"><?php echo esc_html( $badge_text ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( count( $all_images ) > 1 ) : ?>
				<div class="shopnex-gallery-thumbs">
					<?php foreach ( $all_images as $index => $image_id ) : ?>
						<button data-change-image="<?php echo intval( $index ); ?>" class="shopnex-thumb-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo intval( $index ); ?>" data-full="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'large' ) ); ?>">
							<img src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) ); ?>" alt="<?php echo esc_attr( $product_title ) . ' ' . ( $index + 1 ); ?>">
						</button>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>

			<div class="shopnex-pdp-info">
				<?php shopnex_countdown_single(); ?>

				<?php if ( ! empty( $tag_text ) ) : ?>
					<span class="shopnex-pdp-tag"><?php echo esc_html( $tag_text ); ?></span>
				<?php endif; ?>

				<?php
				$pdp_brand = shopnex_get_product_brand( $product_id );
				if ( ! empty( $pdp_brand ) ) : ?>
					<p class="shopnex-pdp-brand"><?php echo esc_html( $pdp_brand ); ?></p>
				<?php endif; ?>

				<h1 class="shopnex-pdp-title"><?php echo esc_html( $product_title ); ?></h1>

				<?php if ( $rating_count > 0 ) : ?>
				<div class="shopnex-pdp-rating">
					<div class="shopnex-rating-stars">
						<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
							<span class="shopnex-star-icon <?php echo $i <= round( $average ) ? 'shopnex-star-filled' : 'shopnex-star-empty'; ?>"><?php echo shopnex_svg_icon( 'star', 14 ); ?></span>
						<?php endfor; ?>
					</div>
					<a href="#reviews" class="shopnex-rating-link"><?php echo esc_html( number_format( $average, 1 ) ); ?> (<?php echo esc_html( $rating_count ); ?> <?php echo esc_html( _n( 'review', 'reviews', $rating_count, 'shopnex' ) ); ?>)</a>
				</div>
				<?php endif; ?>

				<div class="shopnex-pdp-price-block" id="productPriceBlock">
					<?php if ( ! empty( $current_price ) ) : ?>
						<span class="shopnex-price-current"><?php echo wp_kses_post( $current_price ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $original_price ) ) : ?>
						<span class="shopnex-price-original"><?php echo wp_kses_post( $original_price ); ?></span>
					<?php endif; ?>
				</div>

				<?php if ( $product_short_desc ) : ?>
					<p class="shopnex-pdp-desc"><?php echo wp_kses_post( $product_short_desc ); ?></p>
				<?php endif; ?>

				<?php if ( $product->is_type( 'variable' ) ) : ?>
					<div class="variations_form" data-product_id="<?php echo esc_attr( $product_id ); ?>">
						<script type="application/json" id="shopnex-variations-data"><?php echo wp_json_encode( $product->get_available_variations() ); ?></script>
						<?php
						$variations         = $product->get_available_variations();
						$var_attributes     = $product->get_variation_attributes();
						$default_attributes = $product->get_default_attributes();

						shopnex_single_swatches();

						foreach ( $var_attributes as $attribute_name => $options ) :
							$attribute_label = wc_attribute_label( $attribute_name );
							$selected_value  = isset( $default_attributes[ $attribute_name ] ) ? $default_attributes[ $attribute_name ] : '';
							?>
							<div class="shopnex-option-group" data-attribute-name="<?php echo esc_attr( $attribute_name ); ?>">
								<div class="shopnex-option-label"><?php echo esc_html( $attribute_label ); ?></div>
								<div class="shopnex-size-options">
									<?php
									$size_map = array(
										'small'              => 'S',
										'medium'             => 'M',
										'large'              => 'L',
										'extra small'        => 'XS',
										'extra large'        => 'XL',
										'double extra large' => 'XXL',
										'triple extra large' => 'XXXL',
									);

									foreach ( $options as $option ) :
										$option_slug = $option;
										if ( taxonomy_exists( $attribute_name ) ) {
											$term       = get_term_by( 'name', $option, $attribute_name );
											$option_slug = $term ? $term->slug : sanitize_title( $option );
										}

										$is_selected = ( $selected_value === $option || $selected_value === $option_slug );
										$is_oos      = false;

										foreach ( $variations as $variation ) {
											$attr_key = 'attribute_' . sanitize_title( $attribute_name );
											if ( isset( $variation['attributes'][ $attr_key ] ) && ( $variation['attributes'][ $attr_key ] === $option_slug || $variation['attributes'][ $attr_key ] === $option ) ) {
												$is_oos = ! $variation['is_in_stock'];
												break;
											}
										}

										$option_lower  = strtolower( $option );
										$size_abbrev   = isset( $size_map[ $option_lower ] ) ? $size_map[ $option_lower ] : $option;
										?>
										<button class="shopnex-size-btn <?php echo $is_selected ? 'active' : ''; ?> <?php echo $is_oos ? 'oos' : ''; ?>"
											data-attribute="<?php echo esc_attr( 'attribute_' . sanitize_title( $attribute_name ) ); ?>"
											data-value="<?php echo esc_attr( $option_slug ); ?>"
											data-display-name="<?php echo esc_attr( $option ); ?>"
											data-tooltip="<?php echo esc_attr( $option ); ?>"
											aria-label="<?php echo esc_attr( $option ); ?>"
											title="<?php echo esc_attr( $option ); ?>">
											<?php echo esc_html( $size_abbrev ); ?>
										</button>
									<?php endforeach; ?>
								</div>
								<?php shopnex_size_chart( 'button' ); ?>
							</div>
						<?php endforeach; ?>

						<div class="shopnex-variation-price" id="variationPriceDisplay" style="display:none;">
							<span class="shopnex-variation-price-current" id="variationPriceCurrent"></span>
							<span class="shopnex-variation-price-original" id="variationPriceOriginal"></span>
						</div>

						<form class="cart" method="post" enctype="multipart/form-data">
							<input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
							<input type="hidden" name="variation_id" value="" id="variation_id">
							<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
							<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" class="single_add_to_cart_button alt" style="display:none!important"></button>
							<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
						</form>
					</div>
				<?php else : ?>
					<?php if ( $size_attribute && $size_attribute->get_visible() ) : ?>
					<div class="shopnex-option-group">
						<div class="shopnex-option-label"><?php esc_html_e( 'Size', 'shopnex' ); ?></div>
						<div class="shopnex-size-options">
							<?php
							$terms = wc_get_product_terms( $product_id, $size_attribute->get_name(), array( 'fields' => 'all' ) );
							foreach ( $terms as $term ) :
								?>
								<button class="shopnex-size-btn"
									data-size-name="<?php echo esc_attr( $term->name ); ?>"
									data-tooltip="<?php echo esc_attr( $term->name ); ?>"
									aria-label="<?php echo esc_attr( $term->name ); ?>"
									title="<?php echo esc_attr( $term->name ); ?>">
									<?php echo esc_html( $term->name ); ?>
								</button>
							<?php endforeach; ?>
						</div>
						<?php shopnex_size_chart( 'button' ); ?>
					</div>
					<?php endif; ?>

					<form class="cart" method="post" enctype="multipart/form-data" style="display:none">
						<input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
						<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" class="single_add_to_cart_button alt"></button>
					</form>
				<?php endif; ?>

				<div class="shopnex-pdp-purchase">
					<div class="shopnex-qty-selector">
						<button class="shopnex-qty-btn" data-qty-change="-1">
							<?php echo shopnex_svg_icon( 'minus', 16 ); ?>
						</button>
						<input type="number" id="qtyValue" class="shopnex-qty-input" value="1" min="1">
						<button class="shopnex-qty-btn" data-qty-change="1">
							<?php echo shopnex_svg_icon( 'plus', 16 ); ?>
						</button>
					</div>
					<button class="shopnex-add-to-cart">
						<?php echo shopnex_svg_icon( 'plus', 18 ); ?>
						<span id="cartBtnText"><?php esc_html_e( 'Add to Cart', 'shopnex' ); ?></span>
					</button>
					<?php shopnex_wishlist_button(); ?>
				</div>

			<div class="shopnex-product-meta">
				<?php shopnex_trust_signals(); ?>
				<?php if ( $sku ) : ?>
					<span><?php esc_html_e( 'SKU:', 'shopnex' ); ?> <?php echo esc_html( $sku ); ?></span>
				<?php endif; ?>
			</div>

			<?php shopnex_product_tabs(); ?>
		</div>
		</div>
	</div>
</main>

<?php
$product_long_desc = $product->get_description();
$product_short_d  = $product->get_short_description();
$product_desc     = ! empty( $product_long_desc ) ? $product_long_desc : $product_short_d;

$has_additional_info = $product->has_attributes() || $product->has_weight() || $product->has_dimensions();
?>

<section class="shopnex-product-tabs-section">
	<div class="shopnex-container">
		<div class="shopnex-product-tabs<?php echo ! $has_additional_info ? ' shopnex-product-tabs--single' : ''; ?>">
			<div class="shopnex-tab-headers">
				<span class="shopnex-tab-header active-tab" data-tab="description"><?php esc_html_e( 'Description', 'shopnex' ); ?></span>
				<?php if ( $has_additional_info ) : ?>
					<span class="shopnex-tab-header" data-tab="additional"><?php esc_html_e( 'Additional information', 'shopnex' ); ?></span>
				<?php endif; ?>
			</div>
			<div class="shopnex-tab-panel active-panel" id="panel-description">
				<?php
				if ( $product_desc ) {
					echo wp_kses_post( wpautop( $product_desc ) );
				} else {
					echo '<p>' . esc_html__( 'A thoughtfully designed piece, crafted with quality materials and built to last.', 'shopnex' ) . '</p>';
				}
				?>
			</div>
			<?php if ( $has_additional_info ) : ?>
				<div class="shopnex-tab-panel" id="panel-additional">
					<?php wc_display_product_attributes( $product ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section id="reviews" class="shopnex-reviews-section">
	<div class="shopnex-container">
		<div class="shopnex-reviews-grid">
			<div class="shopnex-reviews-summary">
				<h2 class="shopnex-reviews-title"><?php esc_html_e( 'Customer Reviews', 'shopnex' ); ?></h2>
				<div class="shopnex-reviews-score">
					<span class="shopnex-reviews-big-number"><?php echo esc_html( number_format( $average, 1 ) ); ?></span>
					<span class="shopnex-reviews-out-of"><?php esc_html_e( 'out of 5', 'shopnex' ); ?></span>
				</div>
				<div class="shopnex-reviews-stars">
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
						<span class="shopnex-star-icon <?php echo $i <= round( $average ) ? 'shopnex-star-filled' : 'shopnex-star-empty'; ?>"><?php echo shopnex_svg_icon( 'star', 16 ); ?></span>
					<?php endfor; ?>
				</div>
				<p class="shopnex-reviews-based-on"><?php printf( esc_html__( 'Based on %d reviews', 'shopnex' ), $rating_count ); ?></p>

				<div class="shopnex-rating-bars">
					<?php for ( $i = 5; $i >= 1; $i-- ) :
						$percentage = $rating_count > 0 ? round( ( $rating_distribution[ $i ] / $rating_count ) * 100 ) : 0;
					?>
						<div class="shopnex-bar-row">
							<span class="shopnex-bar-label"><?php echo esc_html( $i ); ?></span>
							<div class="shopnex-bar-track">
								<div class="shopnex-bar-fill" style="width:<?php echo esc_attr( $percentage ); ?>%"></div>
							</div>
							<span class="shopnex-bar-count"><?php echo esc_html( $percentage ); ?>%</span>
						</div>
					<?php endfor; ?>
				</div>

				<button class="shopnex-write-review-btn" id="writeReviewBtn"><?php esc_html_e( 'Write a Review', 'shopnex' ); ?></button>
			</div>

			<div class="shopnex-reviews-list">
				<?php if ( ! empty( $comments ) ) : ?>
					<?php
					$review_index = 0;
					foreach ( $comments as $comment ) :
						$comment_rating  = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
						$comment_author  = $comment->comment_author;
						$comment_content = $comment->comment_content;

						$review_title = '';
						$review_text  = $comment_content;
						if ( preg_match( '/<strong>(.*?)<\/strong>\s*\n\s*\n(.*)/s', $comment_content, $matches ) ) {
							$review_title = $matches[1];
							$review_text  = $matches[2];
						}

						$comment_time = human_time_diff( get_comment_time( 'U' ), time() );
						$is_hidden    = $review_index >= 3;
					?>
						<div class="shopnex-review-card<?php echo $is_hidden ? ' shopnex-review-hidden' : ''; ?>"<?php echo $is_hidden ? ' style="display:none"' : ''; ?>>
							<div class="shopnex-review-top">
								<div>
									<div class="shopnex-review-stars">
										<?php for ( $s = 1; $s <= 5; $s++ ) : ?>
											<span class="shopnex-star-icon <?php echo $s <= $comment_rating ? 'shopnex-star-filled' : 'shopnex-star-empty'; ?>"><?php echo shopnex_svg_icon( 'star', 14 ); ?></span>
										<?php endfor; ?>
									</div>
									<?php if ( ! empty( $review_title ) ) : ?>
										<p class="shopnex-review-title"><?php echo esc_html( $review_title ); ?></p>
									<?php endif; ?>
								</div>
								<span class="shopnex-review-verified"><?php esc_html_e( 'Verified', 'shopnex' ); ?></span>
							</div>
							<p class="shopnex-review-text"><?php echo wp_kses_post( $review_text ); ?></p>
							<div class="shopnex-review-author">
								<div class="shopnex-author-avatar">
									<?php echo wp_kses_post( get_avatar( $comment->comment_author_email, 32, '', $comment_author, array( 'class' => 'shopnex-avatar-img' ) ) ); ?>
								</div>
								<div>
									<p class="shopnex-author-name"><?php echo esc_html( $comment_author ); ?></p>
									<p class="shopnex-author-meta"><?php printf( esc_html__( '%s ago', 'shopnex' ), esc_html( $comment_time ) ); ?></p>
								</div>
							</div>
						</div>
					<?php
						$review_index++;
					endforeach;
					?>
				<?php else : ?>
					<p class="shopnex-no-reviews"><?php esc_html_e( 'No reviews yet. Be the first to review this product!', 'shopnex' ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( count( $comments ) > 3 ) : ?>
			<button class="shopnex-load-more-reviews" id="loadMoreReviews" data-per-page="3"><?php esc_html_e( 'Load More Reviews', 'shopnex' ); ?></button>
		<?php endif; ?>
	</div>
</section>

<div class="shopnex-review-modal" id="reviewModal">
	<div class="shopnex-review-modal-overlay" id="reviewModalOverlay"></div>
	<div class="shopnex-review-modal-content">
		<button class="shopnex-review-modal-close" id="reviewModalClose">
			<?php echo shopnex_svg_icon( 'x', 20 ); ?>
		</button>
		<div class="shopnex-review-modal-header">
			<h3><?php esc_html_e( 'Write a Review', 'shopnex' ); ?></h3>
			<p><?php esc_html_e( 'Share your experience with this product', 'shopnex' ); ?></p>
		</div>
		<form class="shopnex-review-form" id="reviewForm" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="shopnex_submit_review">
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
			<?php wp_nonce_field( 'shopnex_submit_review', 'shopnex_review_nonce' ); ?>

			<div class="shopnex-form-group">
				<label><?php esc_html_e( 'Your Rating', 'shopnex' ); ?></label>
				<div class="shopnex-star-rating-input" id="starRatingInput">
					<span class="star" data-rating="5"><?php echo shopnex_svg_icon( 'star', 24 ); ?></span>
					<span class="star" data-rating="4"><?php echo shopnex_svg_icon( 'star', 24 ); ?></span>
					<span class="star" data-rating="3"><?php echo shopnex_svg_icon( 'star', 24 ); ?></span>
					<span class="star" data-rating="2"><?php echo shopnex_svg_icon( 'star', 24 ); ?></span>
					<span class="star" data-rating="1"><?php echo shopnex_svg_icon( 'star', 24 ); ?></span>
					<input type="hidden" name="rating" id="ratingInput" value="">
				</div>
			</div>

			<div class="shopnex-form-group">
				<label for="reviewTitle"><?php esc_html_e( 'Review Title', 'shopnex' ); ?></label>
				<input type="text" id="reviewTitle" name="review_title" placeholder="<?php esc_attr_e( 'Give your review a title', 'shopnex' ); ?>" required>
			</div>

			<div class="shopnex-form-group">
				<label for="reviewContent"><?php esc_html_e( 'Your Review', 'shopnex' ); ?></label>
				<textarea id="reviewContent" name="review_content" rows="5" placeholder="<?php esc_attr_e( 'What did you like or dislike?', 'shopnex' ); ?>" required></textarea>
			</div>

			<div class="shopnex-form-row">
				<div class="shopnex-form-group">
					<label for="reviewerName"><?php esc_html_e( 'Your Name', 'shopnex' ); ?></label>
					<input type="text" id="reviewerName" name="reviewer_name" placeholder="<?php esc_attr_e( 'Enter your name', 'shopnex' ); ?>" required>
				</div>
				<div class="shopnex-form-group">
					<label for="reviewerEmail"><?php esc_html_e( 'Email', 'shopnex' ); ?></label>
					<input type="email" id="reviewerEmail" name="reviewer_email" placeholder="<?php esc_attr_e( 'Enter your email', 'shopnex' ); ?>" required>
				</div>
			</div>

			<button type="submit" class="shopnex-submit-review"><?php esc_html_e( 'Submit Review', 'shopnex' ); ?></button>
		</form>
	</div>
</div>

<section class="shopnex-related-section">
	<div class="shopnex-container">
		<h2 class="shopnex-related-title"><?php esc_html_e( 'You may also like', 'shopnex' ); ?></h2>
		<div class="shopnex-related-grid">
			<?php foreach ( $related_products as $related_product ) :
				$related_obj = wc_get_product( $related_product );
				if ( ! $related_obj ) {
					continue;
				}
				$related_id = $related_obj->get_id();

				$rel_badge = '';
				if ( $related_obj->is_on_sale() ) {
					$rel_pct   = shopnex_get_sale_percentage( $related_obj );
					$rel_badge = $rel_pct ? $rel_pct : __( 'Sale', 'shopnex' );
				} elseif ( function_exists( 'shopnex_is_product_new' ) && shopnex_is_product_new( $related_id ) ) {
					$rel_badge = __( 'New', 'shopnex' );
				}
				?>
				<a href="<?php echo get_permalink( $related_id ); ?>" class="shopnex-related-card">
					<?php if ( $rel_badge ) : ?>
						<div class="shopnex-related-badge <?php echo $related_obj->is_on_sale() ? 'shopnex-badge-sale' : 'shopnex-badge-new'; ?>"><?php echo esc_html( $rel_badge ); ?></div>
					<?php endif; ?>
					<div class="shopnex-related-img">
						<?php echo wp_kses_post( $related_obj->get_image( 'medium' ) ); ?>
					</div>
					<div class="shopnex-related-info">
						<h3 class="shopnex-related-name"><?php echo esc_html( $related_obj->get_title() ); ?></h3>
						<span class="shopnex-related-price"><?php echo wp_kses_post( $related_obj->get_price_html() ); ?></span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php shopnex_size_chart( 'modal' ); ?>

<div id="lightbox" class="shopnex-lightbox">
	<button class="shopnex-lightbox-close"><?php echo shopnex_svg_icon( 'x', 24 ); ?></button>
	<img id="lightboxImg" src="" alt="<?php esc_attr_e( 'Zoom', 'shopnex' ); ?>">
</div>

<button id="scrollTop" class="shopnex-scroll-top">
	<?php echo shopnex_svg_icon( 'chevron-up', 20 ); ?>
</button>

<?php get_footer(); ?>
