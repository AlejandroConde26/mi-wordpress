<?php
/**
 * Title: Home Product Collection
 * Slug: casual-fashion-store/home-product-collection
 * Categories: woo-commerce, featured-selling
 */
?>
<!-- wp:group {"metadata":{"name":"Product Collection 5 Columns","categories":["woo-commerce","featured-selling"],"patternName":"woocommerce-blocks/product-collection-5-columns"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20","right":"var:preset|spacing|20"},"blockGap":"0"}},"layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:woocommerce/product-collection {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"date","search":"","exclude":[],"inherit":false,"taxQuery":[],"isProductCollectionBlock":true,"woocommerceOnSale":false,"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[],"filterable":true},"tagName":"div","displayLayout":{"type":"carousel","columns":5},"dimensions":{"widthType":"fill","fixedWidth":""},"queryContextIncludes":["collection"],"__privatePreviewState":{"isPreview":false,"previewMessage":"Actual products will vary depending on the page being viewed."},"align":"wide"} -->
<div class="wp-block-woocommerce-product-collection alignwide"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"align":"wide"} -->
<h3 class="wp-block-heading alignwide"><?php esc_html_e( "Trending", "casual-fashion-store" ); ?></h3>
<!-- /wp:heading -->

<!-- wp:woocommerce/product-gallery-large-image-next-previous {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-woocommerce-product-gallery-large-image-next-previous"></div>
<!-- /wp:woocommerce/product-gallery-large-image-next-previous --></div>
<!-- /wp:group -->

<!-- wp:woocommerce/product-template {"layout":{"type":"flex","justifyContent":"left","verticalAlignment":"top","flexWrap":"nowrap","orientation":"horizontal"}} -->
<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/product-image {"showSaleBadge":false,"isDescendentOfQueryLoop":true,"aspectRatio":"3/5"} -->
<!-- wp:woocommerce/product-sale-badge {"isDescendentOfQueryLoop":true,"align":"right"} /-->
<!-- /wp:woocommerce/product-image -->

<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"lineHeight":"1.4","textAlign":"left"}},"fontSize":"medium","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->

<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"left","fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:woocommerce/product-button {"width":100,"isDescendentOfQueryLoop":true,"className":"is-style-outline"} /-->
<!-- /wp:woocommerce/product-template --></div>
<!-- /wp:woocommerce/product-collection --></div>
<!-- /wp:group -->
