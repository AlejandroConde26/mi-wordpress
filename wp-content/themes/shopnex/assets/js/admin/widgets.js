/**
 * Widget admin scripts for Shopnex theme.
 *
 * @package shopnex
 */

jQuery(document).ready(function($) {

	function shopnexReindexAnnouncements(container) {
		container.find('.shopnex-announcement-item-field').each(function(idx) {
			$(this).find('input').each(function() {
				var name = $(this).attr('name');
				if (name) {
					name = name.replace(/\[announcements\]\[\d+\]/, '[announcements][' + idx + ']');
					$(this).attr('name', name);
				}
			});
		});
	}

	// Add new announcement.
	$(document).on('click', '.shopnex-announcement-add-btn', function(e) {
		e.preventDefault();
		var widgetForm = $(this).closest('.shopnex-announcement-widget-form');
		var itemsContainer = widgetForm.find('.shopnex-announcement-items');
		var fieldIndex = itemsContainer.find('.shopnex-announcement-item-field').length;

		var namePrefix = widgetForm.find('.shopnex-announcement-text-input').first().attr('name');
		if (namePrefix) {
			var baseNameMatch = namePrefix.match(/^(.*\[announcements\])\[\d+\]/);
			if (baseNameMatch) {
				var baseName = baseNameMatch[1];
			}
		}

		if (typeof baseName === 'undefined') {
			return;
		}

		var html = '<div class="shopnex-announcement-item-field">' +
			'<p><label>Text:</label>' +
			'<input type="text" name="' + baseName + '[' + fieldIndex + '][text]" class="widefat shopnex-announcement-text-input" placeholder="e.g. Free shipping on orders over $200"></p>' +
			'<button type="button" class="button shopnex-announcement-remove-btn" style="margin-bottom:10px;color:#d63639;">✕ Remove</button>' +
			'<hr style="margin:10px 0;border-top:1px solid #eee">' +
			'</div>';

		itemsContainer.append(html);
	});

	// Remove announcement.
	$(document).on('click', '.shopnex-announcement-remove-btn', function(e) {
		e.preventDefault();
		var widgetForm = $(this).closest('.shopnex-announcement-widget-form');
		$(this).closest('.shopnex-announcement-item-field').fadeOut(200, function() {
			$(this).remove();
			shopnexReindexAnnouncements(widgetForm);
		});
	});

	// ── Footer About Widget ──

	// Logo upload.
	$(document).on('click', '.shopnex-logo-upload', function(e) {
		e.preventDefault();
		var button = $(this);
		var frame = wp.media({
			title: 'Select Footer Logo',
			button: { text: 'Use as Logo' },
			multiple: false
		});
		frame.on('select', function() {
			var attachment = frame.state().get('selection').first().toJSON();
			button.siblings('.shopnex-logo-id').val(attachment.id);
			button.siblings('.shopnex-logo-preview').html('<img src="' + attachment.url + '" style="max-width:100%;height:auto;border:1px solid #ddd;border-radius:4px;padding:4px;">').show();
			button.siblings('.shopnex-logo-remove').show();
		});
		frame.open();
	});

	// Logo remove.
	$(document).on('click', '.shopnex-logo-remove', function(e) {
		e.preventDefault();
		var button = $(this);
		button.siblings('.shopnex-logo-id').val('');
		button.siblings('.shopnex-logo-preview').hide();
		button.hide();
	});
});
