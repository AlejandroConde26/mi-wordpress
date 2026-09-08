<?php
/**
 * Title: Flash Sale
 * Slug: gutentools-fashion/flash-sale
 * Categories: gutentools-fashion
 * Keywords: flash-sale
 * Block Types: core/post-content
 * Post Types: page, wp_template
 */
?>
<!-- wp:group {"metadata":{"name":"Flash Sale"},"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--20)"><!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--40)"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","textAlign":"center"}}} -->
<p class="has-text-align-center" style="text-transform:uppercase"><?php echo esc_html__( 'Flash Sale', 'gutentools-fashion' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-top:0;margin-bottom:0"><?php echo esc_html__( 'Exclusive Fashion Deals', 'gutentools-fashion' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":0,"query":{"perPage":4,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":false,"parents":[],"format":[],"taxQuery":{"include":{"product_cat":[61]}}}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"grid","columnCount":null,"minimumColumnWidth":"18rem"}} -->
<!-- wp:post-featured-image {"isLink":true,"height":"350px","style":{"border":{"radius":"0px"}}} /-->

<!-- wp:post-title {"isLink":true,"style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"5px"}}},"fontSize":"mdm-large"} /-->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"fontSize":"sml-medium","style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"lineHeight":"1.5"}}} /-->

<!-- wp:woocommerce/product-rating {"isDescendentOfQueryLoop":true,"fontSize":"small","style":{"elements":{"link":{"color":{"text":"#ff8622"}}},"color":{"text":"#ff8622"}}} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results.","style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center" style="margin-top:0;margin-bottom:0"><?php echo esc_html__( 'No Products Found', 'gutentools-fashion' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->