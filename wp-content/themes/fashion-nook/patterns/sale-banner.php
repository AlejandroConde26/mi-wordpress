<?php
/**
 * Title: Sale Banner
 * Slug: fashion-nook/sale-banner
 * Categories: Text, WooCommerce, Featured
 */
?>
<!-- wp:group {"metadata":{"patternName":"solana/hero-headline","name":"Sale Banner","categories":["Featured","Text"]},"align":"full","className":"is-style-section-3","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-3" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","textAlign":"center"}},"fontSize":"custom-1"} -->
<p class="has-text-align-center has-custom-1-font-size" style="text-transform:uppercase"><?php esc_html_e('End of Season Sale', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"align":"wide","style":{"typography":{"textAlign":"center"}}} -->
<h2 class="wp-block-heading has-text-align-center alignwide"><?php /* Translators: 1. is a 'br' HTML element */ 
echo sprintf( esc_html__( 'Take a further 30%% off%1$sall reduced styles', 'fashion-nook' ), '<br>' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button"><?php esc_html_e('Shop dresses sale', 'fashion-nook');?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button"><?php esc_html_e('shop Accessories Sale', 'fashion-nook');?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button"><?php esc_html_e('Shop all sale', 'fashion-nook');?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->