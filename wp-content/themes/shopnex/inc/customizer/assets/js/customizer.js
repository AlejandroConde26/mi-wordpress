
  (function ($) {

    // Site title.
    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.site-logo').text(to);
        });
    });

    // Site tagline.
    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.site-description').text(to);
        });
    });

    // Display Site Title and Tagline toggle.
    wp.customize('shopnex_display_site_title_tagline', function (value) {
        value.bind(function (to) {
            if (to) {
                $('.site-description').show();
            } else {
                $('.site-description').hide();
            }
        });
    });

  })(this.jQuery);
