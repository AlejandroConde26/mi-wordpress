/**
 * ShopNex Info Page 
 *
 * @package shopnex
 */

(function ($) {
	'use strict';

	$(document).ready(function () {

		/**
		 * Handle plugin action button clicks.
		 */
		$(document).on('click', '.shopnex-plugin-action-btn', function (e) {
			e.preventDefault();

			var $btn = $(this);
			var action = $btn.data('action');
			var slug = $btn.data('slug');
			var plugin = $btn.data('plugin');
			var $card = $btn.closest('.shopnex-info-plugin-card');
			var originalText = $btn.text();

			// Disable button and show loading state.
			$btn.prop('disabled', true);

			if (action === 'install') {
				$btn.text(shopnexInfo.strings.installing);
			} else if (action === 'activate') {
				$btn.text(shopnexInfo.strings.activating);
			} else if (action === 'deactivate') {
				$btn.text(shopnexInfo.strings.deactivating);
			}

			$.ajax({
				url: shopnexInfo.ajaxUrl,
				type: 'POST',
				data: {
					action: 'shopnex_plugin_action',
					nonce: shopnexInfo.nonce,
					plugin_action: action,
					slug: slug,
					plugin: plugin,
				},
				success: function (response) {
					if (response.success) {
						updatePluginCard($card, response.data.status, slug);
					} else {
						// Show error and restore button.
						alert(response.data.message || 'An error occurred.');
						$btn.prop('disabled', false).text(originalText);
					}
				},
				error: function () {
					alert('Connection error. Please try again.');
					$btn.prop('disabled', false).text(originalText);
				},
			});
		});

		/**
		 * Update the plugin card UI after a successful action.
		 *
		 * @param {jQuery} $card  The plugin card element.
		 * @param {string} status The new status (active, inactive, installed).
		 * @param {string} slug   The plugin slug.
		 */
		function updatePluginCard($card, status, slug) {
			var $actions = $card.find('.shopnex-info-plugin-actions');

			if (status === 'active') {
				$actions.html(
					'<span class="shopnex-info-badge shopnex-info-badge-active">' +
					'<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' +
					shopnexInfo.strings.active + '</span>' +
					'<button class="shopnex-info-btn shopnex-info-btn-outline shopnex-plugin-action-btn" data-action="deactivate" data-slug="' + slug + '" data-plugin="' + $card.data('plugin') + '">' +
					shopnexInfo.strings.deactivating.replace('...', '') + '</button>'
				);
			} else if (status === 'inactive') {
				$actions.html(
					'<span class="shopnex-info-badge shopnex-info-badge-inactive">' +
					shopnexInfo.strings.inactive + '</span>' +
					'<button class="shopnex-info-btn shopnex-info-btn-primary shopnex-plugin-action-btn" data-action="activate" data-slug="' + slug + '" data-plugin="' + $card.data('plugin') + '">' +
					shopnexInfo.strings.activating.replace('...', '') + '</button>'
				);
			}
		}
	});

})(jQuery);
