/**
 * Shopnex Pro Upsell - Customizer Controls JS
 *
 * Registers the custom section type so it renders properly
 * in the WordPress Customizer sidebar.
 *
 * @package shopnex
 */

( function( api ) {

	'use strict';

	// Extend our custom "shopnex-pro" section type.
	api.sectionConstructor['shopnex-pro'] = api.Section.extend( {

		// No events for this type of section (non-expandable).
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
