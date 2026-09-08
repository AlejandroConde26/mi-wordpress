/**
 * Rating Notice JavaScript
 *
 * @package shopnex
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		$(document).on('click', '.shopnex-rating-notice .notice-dismiss', function(e) {
			e.preventDefault();
			$(document).find('.shopnex-rating-notice').slideUp();
			$.post({
				url: shopnex_rating.ajax_url,
				data: {
					action: 'shopnex_rating_dismiss_notice',
					nonce: shopnex_rating.nonce,
				}
			});
		});

		$(document).on('click', '.shopnex-maybe-later', function() {
			$(document).find('.shopnex-rating-notice').slideUp();
			$.post({
				url: shopnex_rating.ajax_url,
				data: {
					action: 'shopnex_rating_maybe_later',
					nonce: shopnex_rating.nonce,
				}
			});
		});

		$(document).on('click', '.shopnex-already-rated', function() {
			$(document).find('.shopnex-rating-notice').slideUp();
			$.post({
				url: shopnex_rating.ajax_url,
				data: {
					action: 'shopnex_rating_already_rated',
					nonce: shopnex_rating.nonce,
				}
			});
		});

	});

})(jQuery);
