<?php
/**
 * Title: Home Hero Cover
 * Slug: casual-fashion-store/home-hero-cover
 * Categories: featured, woo-commerce
 */
?>
<!-- wp:group {"metadata":{"name":"Featured Category Cover Image","categories":["woo-commerce"],"patternName":"woocommerce-blocks/featured-category-cover-image"},"align":"full","layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull"><!-- wp:cover {"url":"<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/hero-5.jpg","dimRatio":20,"overlayColor":"theme-4","isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.2},"minHeight":750,"contentPosition":"bottom center","sizeSlug":"full","align":"full","style":{"spacing":{"padding":{"top":"3%","bottom":"10%","left":"5%","right":"5%"},"blockGap":"1%","margin":{"top":"0","bottom":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|theme-1"}}}},"textColor":"theme-1","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center has-theme-1-color has-text-color has-link-color" style="margin-top:0;margin-bottom:0;padding-top:3%;padding-right:5%;padding-bottom:10%;padding-left:5%;min-height:750px"><img class="wp-block-cover__image-background size-full" alt="" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/hero-5.jpg" style="object-position:50% 20%" data-object-fit="cover" data-object-position="50% 20%"/><span aria-hidden="true" class="wp-block-cover__background has-theme-4-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"fontSize":"xxx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-xxx-large-font-size"><?php esc_html_e( "Denim for days", "casual-fashion-store" ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"has-text-align-center","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center"><?php esc_html_e( "Shop denim essentials.", "casual-fashion-store" ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( "Shop now", "casual-fashion-store" ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->
