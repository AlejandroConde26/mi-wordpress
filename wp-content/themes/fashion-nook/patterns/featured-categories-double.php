<?php
/**
 * Title: Featured Categories Double
 * Slug: fashion-nook/featured-categories-double
 * Categories: Columns, Featured
 */
?>
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:columns {"align":"full","style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
<div class="wp-block-columns alignfull"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/woman-laughing.jpg","dimRatio":20,"customOverlayColor":"#979494","isUserOverlayColor":false,"contentPosition":"bottom left","isDark":false,"sizeSlug":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|theme-1"}}},"dimensions":{"aspectRatio":"1"}},"textColor":"theme-1","layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light has-custom-content-position is-position-bottom-left has-theme-1-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><img class="wp-block-cover__image-background size-full" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/woman-laughing.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-20 has-background-dim" style="background-color:#979494"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"placeholder":"Write title…","fontSize":"large"} -->
<p class="has-large-font-size"><?php esc_html_e('Clothing', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sShop now%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/accessories-shoes.jpg","dimRatio":20,"customOverlayColor":"#726a5c","isUserOverlayColor":false,"contentPosition":"bottom left","sizeSlug":"large","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|theme-1"}}},"dimensions":{"aspectRatio":"1"}},"textColor":"theme-1","layout":{"type":"constrained"}} -->
<div class="wp-block-cover has-custom-content-position is-position-bottom-left has-theme-1-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><img class="wp-block-cover__image-background size-large" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/accessories-shoes.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-20 has-background-dim" style="background-color:#726a5c"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"placeholder":"Write title…","fontSize":"large"} -->
<p class="has-large-font-size"><?php esc_html_e('Shoes &amp; Accessories', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sShop now%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->