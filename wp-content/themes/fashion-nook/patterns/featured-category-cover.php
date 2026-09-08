<?php
/**
 * Title: Featured Category Cover
 * Slug: fashion-nook/featured-category-cover
 * Categories: Featured
 */
?>
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/woman-dress-model.jpg","dimRatio":20,"overlayColor":"theme-5","isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.3},"minHeight":35,"minHeightUnit":"rem","contentPosition":"bottom left","sizeSlug":"large","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-bottom-left" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);min-height:35rem"><img class="wp-block-cover__image-background size-large" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/woman-dress-model.jpg" style="object-position:50% 30%" data-object-fit="cover" data-object-position="50% 30%"/><span aria-hidden="true" class="wp-block-cover__background has-theme-5-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e('Boho Chic Collection', 'fashion-nook');?></h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button"><?php esc_html_e('View Lookbook', 'fashion-nook');?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->