<?php
/**
 * Search form template
 *
 * @package shopnex
 */

$shopnex_current_post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : '';

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="search-field-wrapper">
		<svg class="search-field-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.5" fill="none"/>
			<path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
		</svg>
		<label for="s" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'shopnex' ); ?></label>
		<input type="search" id="s" class="search-field" placeholder="<?php esc_attr_e( 'What are you looking for?', 'shopnex' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" autocomplete="off" />
		<?php if ( shopnex_is_active_woocommerce() ) : ?>
			<input type="hidden" name="post_type" value="product" />
		<?php elseif ( $shopnex_current_post_type ) : ?>
			<input type="hidden" name="post_type" value="<?php echo esc_attr( $shopnex_current_post_type ); ?>" />
		<?php endif; ?>
	</div>
	<button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Search', 'shopnex' ); ?>">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</button>
</form>
