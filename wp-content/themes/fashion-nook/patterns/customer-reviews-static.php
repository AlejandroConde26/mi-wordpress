<?php
/**
 * Title: Customer Reviews (static)
 * Slug: fashion-nook/customer-reviews-static
 * Categories: Featured, Reviews
 */
?>
<!-- wp:group {"metadata":{"name":"Reviews"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide"><!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<h3 class="wp-block-heading has-text-align-center" style="margin-bottom:1rem"><?php esc_html_e('Customer reviews', 'fashion-nook');?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sRead all reviews%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/fashion-clothing-3.jpg" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"6px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
<p style="font-style:normal;font-weight:500"><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sBeige Melange Cowl-Neck Knit Sweater%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e('⭑⭑⭑⭑⭑', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"custom-1"} -->
<p class="has-custom-1-font-size"><?php esc_html_e('What a great top, versatile, goes with anything, can be dressed up or down. Sizing is spot on too!', 'fashion-nook');?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/fashion-clothing-5.jpg" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"6px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
<p style="font-style:normal;font-weight:500"><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sBeige Melange Cowl-Neck Knit Sweater%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e('⭑⭑⭑⭑⭑', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"custom-1"} -->
<p class="has-custom-1-font-size"><?php esc_html_e('What a great top, versatile, goes with anything, can be dressed up or down. Sizing is spot on too!', 'fashion-nook');?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/fashion-clothing-2.jpg" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"6px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
<p style="font-style:normal;font-weight:500"><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sBeige Melange Cowl-Neck Knit Sweater%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e('⭑⭑⭑⭑⭑', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"custom-1"} -->
<p class="has-custom-1-font-size"><?php esc_html_e('What a great top, versatile, goes with anything, → be dressed up or down. Sizing is spot on too!', 'fashion-nook');?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->