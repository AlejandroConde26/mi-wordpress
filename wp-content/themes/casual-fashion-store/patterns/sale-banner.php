<?php
/**
 * Title: Sale Banner
 * Slug: casual-fashion-store/sale-banner
 * Categories: text, woo-commerce, featured
 */
?>
<!-- wp:group {"metadata":{"patternName":"casual-fashion-store/sale-banner","name":"Sale Banner","categories":["Text","WooCommerce","Featured"]},"align":"full","className":"is-style-default","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}},"elements":{"link":{"color":{"text":"#3a3530"}}},"color":{"text":"#3a3530","gradient":"var:preset|gradient|casual-sale"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-default has-text-color has-background has-link-color" style="color:#3a3530;background:var(--wp--preset--gradient--casual-sale);padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"style":{"typography":{"textAlign":"center","textTransform":"uppercase"}}} -->
<p class="has-text-align-center" style="text-transform:uppercase"><?php esc_html_e( "Clearance sale", "casual-fashion-store" ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"textAlign":"center","fontStyle":"normal","fontWeight":"600"}}} -->
<h3 class="wp-block-heading has-text-align-center" style="font-style:normal;font-weight:600"><?php esc_html_e( "60% off all tees, tops, and shirts", "casual-fashion-store" ); ?></h3>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( "Shop now", "casual-fashion-store" ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
