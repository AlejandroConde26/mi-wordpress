<?php
/**
 * Title: Order Confirmation
 * Slug: fashion-nook/hidden-order-confirmation
 * Inserter: no
 */
declare( strict_types = 1 );
?>

<!-- wp:template-part {"slug":"header","theme":"fashion-nook"} /-->

<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|50"}}},"layout":{"inherit":true,"type":"constrained"}} -->
<main class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:woocommerce/order-confirmation-status /-->

	<!-- wp:woocommerce/order-confirmation-summary /-->

	<!-- wp:woocommerce/order-confirmation-totals-wrapper {"align":"wide"} -->
		<!-- wp:heading {"fontSize":"medium"} -->
		<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e('Order details', 'fashion-nook');?></h2>
		<!-- /wp:heading -->

		<!-- wp:woocommerce/order-confirmation-totals {"lock":{"remove":true},"fontSize":"small"} /-->
	<!-- /wp:woocommerce/order-confirmation-totals-wrapper -->

	<!-- wp:woocommerce/order-confirmation-downloads-wrapper {"align":"wide"} -->
		<!-- wp:heading {"fontSize":"medium"} -->
		<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e('Downloads', 'fashion-nook');?></h2>
		<!-- /wp:heading -->

		<!-- wp:woocommerce/order-confirmation-downloads {"lock":{"remove":true},"fontSize":"small"} /-->
	<!-- /wp:woocommerce/order-confirmation-downloads-wrapper -->

	<!-- wp:columns {"align":"wide","className":"wc-block-order-confirmation-address-wrapper"} -->
	<div class="wp-block-columns alignwide wc-block-order-confirmation-address-wrapper">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:woocommerce/order-confirmation-shipping-wrapper {"align":"wide"} -->
				<!-- wp:heading {"fontSize":"medium"} -->
				<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e('Shipping address', 'fashion-nook');?></h2>
				<!-- /wp:heading -->

				<!-- wp:woocommerce/order-confirmation-shipping-address {"lock":{"remove":true},"fontSize":"small"} /-->
			<!-- /wp:woocommerce/order-confirmation-shipping-wrapper -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:woocommerce/order-confirmation-billing-wrapper {"align":"wide"} -->
				<!-- wp:heading {"fontSize":"medium"} -->
				<h2 class="wp-block-heading has-medium-font-size"><?php esc_html_e('Billing address', 'fashion-nook');?></h2>
				<!-- /wp:heading -->

				<!-- wp:woocommerce/order-confirmation-billing-address {"lock":{"remove":true},"fontSize":"small"} /-->
			<!-- /wp:woocommerce/order-confirmation-billing-wrapper -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:woocommerce/order-confirmation-additional-fields-wrapper {"align":"wide"} -->
		<!-- wp:heading {"fontSize":"medium"} -->
		<h2 class="wp-block-heading"><?php esc_html_e('Additional information', 'fashion-nook');?></h2>
		<!-- /wp:heading -->

		<!-- wp:woocommerce/order-confirmation-additional-fields /-->
	<!-- /wp:woocommerce/order-confirmation-additional-fields-wrapper -->

	<!-- wp:woocommerce/order-confirmation-additional-information /-->
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","theme":"fashion-nook"} /-->
