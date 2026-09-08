<?php
/**
 * Title: Featured Banner
 * Slug: gutentools-fashion/banner
 * Categories: gutentools-fashion
 * Keywords: banner
 * Block Types: core/post-content
 * Post Types: page, wp_template
 */
?>
<!-- wp:group {"metadata":{"name":"Banner"},"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"right":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:0;padding-left:0"><!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"center","width":"45%","style":{"spacing":{"padding":{"right":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-column is-vertically-aligned-center" style="padding-right:0;padding-left:0;flex-basis:45%"><!-- wp:group {"className":"gf-banner-left-content","style":{"spacing":{"padding":{"left":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group gf-banner-left-content" style="padding-left:0"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}}} -->
<p style="text-transform:uppercase"><?php echo esc_html__( 'Modern Elegance', 'gutentools-fashion' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<h1 class="wp-block-heading" style="margin-top:0;margin-bottom:0"><?php echo esc_html__( 'Elevate Every Outfit Effortlessly', 'gutentools-fashion' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Explore curated collections of modern apparel, accessories, and timeless essentials designed to elevate your everyday style.', 'gutentools-fashion' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"color":{"background":"#000000"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-background wp-element-button" style="background-color:#000000" href="#"><?php echo esc_html__( 'Explore Now', 'gutentools-fashion' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"gf-banner-right"} -->
<div class="wp-block-column gf-banner-right"><!-- wp:cover {"url":"<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/banner.jpg","id":1824,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.95,"y":0.47},"minHeight":700,"isDark":false,"sizeSlug":"full","className":"gf-responsive-img","layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light gf-responsive-img" style="min-height:700px"><img class="wp-block-cover__image-background wp-image-1824 size-full" alt="" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/banner.jpg" style="object-position:95% 47%" data-object-fit="cover" data-object-position="95% 47%"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"placeholder":"Write title…","style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->