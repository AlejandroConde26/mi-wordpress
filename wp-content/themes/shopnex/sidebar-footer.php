<?php
/**
 * Footer widget area.
 *
 * @package shopnex
 */

// Return if no footer widgets are active.
if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) ) {
	return;
}

if ( is_active_sidebar( 'footer-1' ) ) :
	?>
	<div class="widget-column footer-col">
		<?php dynamic_sidebar( 'footer-1' ); ?>
	</div>
	<?php
endif;

if ( is_active_sidebar( 'footer-2' ) ) :
	?>
	<div class="widget-column footer-col">
		<?php dynamic_sidebar( 'footer-2' ); ?>
	</div>
	<?php
endif;

if ( is_active_sidebar( 'footer-3' ) ) :
	?>
	<div class="widget-column footer-col">
		<?php dynamic_sidebar( 'footer-3' ); ?>
	</div>
	<?php
endif;

if ( is_active_sidebar( 'footer-4' ) ) :
	?>
	<div class="widget-column footer-col">
		<?php dynamic_sidebar( 'footer-4' ); ?>
	</div>
	<?php
endif;
