<?php
/**
 * Title: Featured Categories Triple
 * Slug: fashion-nook/featured-categories-triple
 * Categories: Columns, Featured
 */
?>
<!-- wp:group {"metadata":{"name":"Featured Category Triple","categories":["featured-selling"],"patternName":"woocommerce-blocks/featured-category-triple"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:columns {"metadata":{"categories":["woo-commerce"],"patternName":"woocommerce-blocks/featured-category-triple","name":"Featured Category Triple"},"align":"wide","style":{"spacing":{"blockGap":{"left":"1.25em"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/woman-laughing.jpg","dimRatio":30,"customOverlayColor":"#979494","isUserOverlayColor":false,"contentPosition":"bottom left","isDark":false,"sizeSlug":"full","className":"is-light has-white-color","style":{"dimensions":{"aspectRatio":"2/3"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"0"}}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-left has-white-color" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><img class="wp-block-cover__image-background size-full" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/woman-laughing.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim" style="background-color:#979494"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"><?php esc_html_e('Bestellers', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sShop now%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/accessories-shoes.jpg","dimRatio":30,"customOverlayColor":"#726a5c","isUserOverlayColor":false,"contentPosition":"bottom left","sizeSlug":"large","className":"is-light has-white-color","style":{"dimensions":{"aspectRatio":"2/3"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"0"}}} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-left is-light has-white-color" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><img class="wp-block-cover__image-background size-large" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/accessories-shoes.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim" style="background-color:#726a5c"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><?php esc_html_e('Accessories', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sShop now%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/girl-jeans.jpg","dimRatio":30,"customOverlayColor":"#726a5c","isUserOverlayColor":true,"contentPosition":"bottom left","isDark":false,"sizeSlug":"large","className":"is-light has-white-color","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"0"},"dimensions":{"aspectRatio":"2/3"}}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-left has-white-color" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><img class="wp-block-cover__image-background size-large" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/girl-jeans.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim" style="background-color:#726a5c"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><?php esc_html_e('Jeans', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sShop now%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->