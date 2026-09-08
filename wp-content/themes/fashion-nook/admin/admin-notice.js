	jQuery(document).ready(function($) {
	    $(document).on('click', '.theme-about-notice .notice-dismiss', function() {
	        var $notice = $(this).closest('.theme-about-notice');
	        $notice.slideUp(150, function() {
	            $notice.remove();
	        });

	        $.post(themeAdminNotice.ajax_url, {
	            action: themeAdminNotice.action,
	            nonce:  themeAdminNotice.nonce
	        });
	    });
	});
