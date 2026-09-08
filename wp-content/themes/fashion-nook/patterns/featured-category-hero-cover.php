<?php
/**
 * Title: Featured Category Hero Cover
 * Slug: fashion-nook/featured-category-hero-cover
 * Categories: Banners, Featured
 */
?>
<!-- wp:group {"metadata":{"name":"Featured Category Cover Image","categories":["woo-commerce"],"patternName":"woocommerce-blocks/featured-category-cover-image"},"align":"full","layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull"><!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/woman-portrait.jpg","dimRatio":40,"overlayColor":"theme-4","isUserOverlayColor":true,"minHeight":750,"contentPosition":"bottom center","sizeSlug":"full","align":"full","style":{"spacing":{"padding":{"top":"3%","right":"0%","bottom":"10%","left":"5%"},"blockGap":"1%","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center" style="margin-top:0;margin-bottom:0;padding-top:3%;padding-right:0%;padding-bottom:10%;padding-left:5%;min-height:750px"><img class="wp-block-cover__image-background size-full" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/woman-portrait.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-theme-4-background-color has-background-dim-40 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e('Cozy, classic, cute', 'fashion-nook');?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center"><?php esc_html_e('Shop the autumn new arrivals.', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e('SHOP AUTUMN COLLECTION', 'fashion-nook');?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->