<?php
/**
 * Title: Hero Headline
 * Slug: fashion-nook/hero-headline
 * Categories: Featured, Text
 */
?>
<!-- wp:group {"align":"full","className":"is-style-default","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-default" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","textAlign":"center"}},"fontSize":"custom-1"} -->
<p class="has-text-align-center has-custom-1-font-size" style="text-transform:uppercase"><?php esc_html_e('Designed for Real Life', 'fashion-nook');?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","align":"wide"} -->
<h2 class="wp-block-heading alignwide has-text-align-center"><?php esc_html_e('Our approach to fashion is effortless and expressive. It’s not about fitting in — it’s about feeling like yourself.', 'fashion-nook');?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center"><?php /* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */ 
echo sprintf( esc_html__( '%1$sAbout us%2$s', 'fashion-nook' ), '<a href="' . esc_url( '#' ) . '">', '</a>' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->