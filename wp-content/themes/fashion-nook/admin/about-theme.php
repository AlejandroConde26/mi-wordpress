<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'fashion_nook_theme_admin_page' ) ) :

class fashion_nook_theme_admin_page {

    private $cfg;

    public function __construct( array $cfg ) {
        $this->cfg = $cfg;
        add_action( 'admin_menu',            array( $this, 'add_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_notices',         array( $this, 'show_notice' ) );
        add_action( 'wp_ajax_' . $this->cfg['prefix'] . '_dismiss_notice',
                                             array( $this, 'dismiss_notice' ) );
        add_action( 'after_switch_theme',    array( $this, 'reset_notice' ) );
    }

    public function add_page() {
        add_theme_page(
            sprintf( __( 'About %s', 'fashion-nook' ), $this->cfg['name'] ),
            $this->cfg['name'],
            $this->cfg['cap'],
            $this->cfg['slug'] . '-about',
            array( $this, 'render_page' )
        );
    }

    public function enqueue_assets( $hook ) {
        $page_hook = 'appearance_page_' . $this->cfg['slug'] . '-about';

        if ( $hook === $page_hook || $hook === 'index.php' ) {
            wp_enqueue_style(
                $this->cfg['prefix'] . '-admin-css',
                $this->cfg['admin_css'],
                array(),
                wp_get_theme()->get( 'Version' )
            );
        }

        wp_enqueue_script(
            $this->cfg['prefix'] . '-admin-js',
            $this->cfg['admin_js'],
            array( 'jquery' ),
            wp_get_theme()->get( 'Version' ),
            true
        );

        wp_localize_script(
            $this->cfg['prefix'] . '-admin-js',
            'themeAdminNotice',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( $this->cfg['prefix'] . '_notice_nonce' ),
                'action'   => $this->cfg['prefix'] . '_dismiss_notice',
            )
        );
    }

    private function should_show_notice() {
        if ( ! is_child_theme() ) {
            return true;
        }

        /**
         * Filters whether the Fashion Nook admin notice should show for a child theme.
         *
         * Child themes can opt in with:
         * add_filter( 'fashion_nook_show_admin_notice', '__return_true' );
         *
         * @param bool     $show_notice Whether to show the admin notice.
         * @param string   $stylesheet  Active child theme stylesheet directory name.
         * @param WP_Theme $theme       Active child theme object.
         */
        return (bool) apply_filters(
            'fashion_nook_show_admin_notice',
            false,
            wp_get_theme()->get_stylesheet(),
            wp_get_theme()
        );
    }

    public function show_notice() {
        if ( ! $this->should_show_notice() ) return;
        if ( ! current_user_can( $this->cfg['cap'] ) ) return;

        $screen = get_current_screen();
        if ( $screen && $screen->id === 'appearance_page_' . $this->cfg['slug'] . '-about' ) return;

        $dismissed = get_option( $this->cfg['prefix'] . '_dismissed_users', array() );
        if ( in_array( get_current_user_id(), $dismissed ) ) return;

        $about_url = admin_url( 'themes.php?page=' . $this->cfg['slug'] . '-about' );
        ?>
        <div id="<?php echo esc_attr( $this->cfg['prefix'] ); ?>-notice"
            class="notice theme-about-notice"
            data-prefix="<?php echo esc_attr( $this->cfg['prefix'] ); ?>"
            style="display:flex; align-items:center; justify-content:space-between; padding: 24px 28px; border-left: none; border-radius: 4px; gap: 40px; position: relative;">

            <div style="flex: 1;">
                <p style="margin: 0 0 6px; font-size: 13px; color: #666;">
                    <?php printf( esc_html__( 'Hello, %s 👋', 'fashion-nook' ), esc_html( wp_get_current_user()->display_name ) ); ?>
                </p>
                <h3 style="margin: 0 0 12px; font-size: 22px; font-weight: 700; color: #1d2327;">
                    <?php printf( esc_html__( '%s is ready ✨', 'fashion-nook' ), esc_html( $this->cfg['name'] ) ); ?>
                </h3>
                <p style="margin: 0 0 20px; color: #444; font-size: 14px; line-height: 1.6;">
                    <b><?php esc_html_e( 'Next step: make your photos do the selling.', 'fashion-nook' ); ?></b>
                    <br />
                    <?php esc_html_e( 'Turn product photos into shoppable moments with our new plugin — or apply a fully styled look in one click.', 'fashion-nook' ); ?>
                </p>
                <p style="margin: 0; display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url( $about_url ); ?>" class="button button-primary" style="font-size: 13px; height: 36px; line-height: 36px; padding: 0 18px;">
                        <?php esc_html_e( 'Open Theme Dashboard →', 'fashion-nook' ); ?>
                    </a>
                </p>
            </div>

            <?php if ( ! empty( $this->cfg['notice_image'] ) ) : ?>
            <div style="flex-shrink: 0;">
                <img src="<?php echo esc_url( $this->cfg['notice_image'] ); ?>"
                    alt="<?php echo esc_attr( $this->cfg['name'] ); ?>"
                    style="max-width: 420px; height: auto; display: block; border-radius: 4px;">
            </div>
            <?php endif; ?>

            <button type="button" class="notice-dismiss" style="position: absolute; top: 12px; right: 12px;">
                <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'fashion-nook' ); ?></span>
            </button>
        </div>
        <?php
    }

    public function dismiss_notice() {
        check_ajax_referer( $this->cfg['prefix'] . '_notice_nonce', 'nonce' );

        if ( ! current_user_can( $this->cfg['cap'] ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ) );
        }

        $user_id   = get_current_user_id();
        $dismissed = get_option( $this->cfg['prefix'] . '_dismissed_users', array() );

        if ( ! in_array( $user_id, $dismissed ) ) {
            $dismissed[] = $user_id;
            update_option( $this->cfg['prefix'] . '_dismissed_users', $dismissed );
        }

        wp_send_json_success();
    }

    public function reset_notice() {
        delete_option( $this->cfg['prefix'] . '_dismissed_users' );
    }

    public function render_page() {
        $cfg = $this->cfg;

        $check_icon = '<svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true" style="flex-shrink:0;margin-top:1px"><circle cx="8" cy="8" r="7.5" stroke="#1e8a4a" stroke-width="1"/><path d="M4.5 8l2.5 2.5 4.5-5" stroke="#1e8a4a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        ?>
        <div class="ttn-about-wrap">

        <!-- Slim branded top bar -->
        <div class="ttn-top-bar">
            <div class="ttn-top-bar__logo">
                <img src="<?php echo esc_url( $cfg['image_dir'] . '/ttn_logo.png' ); ?>"
                    alt="<?php echo esc_attr( $cfg['name'] ); ?>"
                    class="ttn-top-bar__logo-img">
                <span class="ttn-top-bar__name"><?php esc_html_e( 'The Theme Nook', 'fashion-nook' ); ?></span>
            </div>
            <a href="<?php echo esc_url( $cfg['site_url'] ); ?>" target="_blank" rel="noopener" class="ttn-top-bar__link">
                <?php esc_html_e( 'thethemenook.com ↗', 'fashion-nook' ); ?>
            </a>
        </div>

        <!-- Shoppable Blocks promo -->
        <div class="ttn-shoppable-promo">
            <div class="ttn-shoppable-promo__content">
                <div class="ttn-shoppable-promo__eyebrow">
                    <span aria-hidden="true">🔥</span>
                    <span><?php esc_html_e( 'Founding member pricing', 'fashion-nook' ); ?></span>
                </div>
                <h1>
                    <?php esc_html_e( 'Turn your product photos into shoppable experience.', 'fashion-nook' ); ?>
                </h1>
                <p><?php esc_html_e( 'Shoppable Blocks adds three Gutenberg blocks that turn any product photo into something visitors can click and buy from directly.', 'fashion-nook' ); ?></p>
                <a href="<?php echo esc_url( $cfg['shoppable_blocks_url'] ); ?>" target="_blank" rel="noopener" class="button button-primary ttn-shoppable-promo__button">
                    <?php esc_html_e( 'Get Shoppable Blocks for JUST $29', 'fashion-nook' ); ?>
                </a>
                <p class="ttn-shoppable-promo__meta">
                    <?php esc_html_e( 'One-time payment · Lifetime updates · 1 site', 'fashion-nook' ); ?>
                    <br><?php esc_html_e( 'Price rises to $49 once the first 50 founding spots are gone.', 'fashion-nook' ); ?>
                </p>
            </div>

            <div class="ttn-shoppable-promo__visual">
                <img src="<?php echo esc_url( $cfg['image_dir'] . '/fnsb_admin_promo.webp' ); ?>"
                    alt="<?php esc_attr_e( 'Shoppable Blocks product hotspot preview', 'fashion-nook' ); ?>">
            </div>

            <div class="ttn-shoppable-promo__features">
                <h2 class="ttn-shoppable-promo__features-title"><?php esc_html_e( 'Three blocks. One plugin.', 'fashion-nook' ); ?></h2>
                <div class="ttn-shoppable-promo__feature">
                    <h2><?php esc_html_e( 'Shop the Look', 'fashion-nook' ); ?></h2>
                    <p><?php esc_html_e( 'Shoppable hotspots on any photo.', 'fashion-nook' ); ?></p>
                </div>
                <div class="ttn-shoppable-promo__feature">
                    <h2><?php esc_html_e( 'Shop the Story', 'fashion-nook' ); ?></h2>
                    <p><?php esc_html_e( 'Photo + hand-picked products, side by side.', 'fashion-nook' ); ?></p>
                </div>
                <div class="ttn-shoppable-promo__feature">
                    <h2><?php esc_html_e( 'Collection Switcher', 'fashion-nook' ); ?></h2>
                    <p><?php esc_html_e( 'Tabbed product carousels, no extra clicks.', 'fashion-nook' ); ?></p>
                </div>
            </div>
        </div>

        <!-- Hero card -->
        <div class="ttn-hero">
            <div>
                <h1><?php esc_html_e( 'Want the finished look too?', 'fashion-nook' ); ?></h1>
                <p><?php esc_html_e( 'Skip the styling decisions — apply a fully designed look in one click, then drop Shoppable Blocks in wherever you want a photo to sell.', 'fashion-nook' ); ?></p>
            </div>
        </div>

        <div class="ttn-outer-grid">

            <!-- LEFT COLUMN: cards -->
            <div class="ttn-left-col">

                <!-- Cards -->
                <div class="ttn-packs-grid">

                    <!-- Silque -->
                    <div class="ttn-pack-card">
                        <a class="ttn-pack-thumb" href="<?php echo esc_url( $cfg['pro_url_3'] ); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo esc_url( $cfg['image_dir'] . '/silque.jpg' ); ?>"
                                alt="<?php esc_attr_e( 'Silque Design Pack preview', 'fashion-nook' ); ?>">
                        </a>
                        <div class="ttn-pack-body">
                            <div class="ttn-pack-title-row">
                                <p class="ttn-pack-name"><?php esc_html_e( 'Silque', 'fashion-nook' ); ?></p>
                                <span class="ttn-pack-badge"><?php esc_html_e( 'New', 'fashion-nook' ); ?></span>
                            </div>
                            <p class="ttn-pack-desc"><?php esc_html_e( 'For stores that want to compete with high-end fashion brands — without the agency price tag.', 'fashion-nook' ); ?></p>
                            <div class="ttn-pack-actions">
                                <a href="<?php echo esc_url( $cfg['demo_url_3'] ); ?>" class="button button-secondary" target="_blank" rel="noopener">
                                    <?php esc_html_e( 'View demo →', 'fashion-nook' ); ?>
                                </a>
                                <a href="<?php echo esc_url( $cfg['pro_url_3'] ); ?>" class="button button-primary" target="_blank" rel="noopener">
                                    <?php esc_html_e( 'Get this look', 'fashion-nook' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Solana -->
                    <div class="ttn-pack-card">
                        <a class="ttn-pack-thumb" href="<?php echo esc_url( $cfg['pro_url_1'] ); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo esc_url( $cfg['image_dir'] . '/solana.jpg' ); ?>"
                                alt="<?php esc_attr_e( 'Solana Design Pack preview', 'fashion-nook' ); ?>">
                        </a>
                        <div class="ttn-pack-body">
                            <div class="ttn-pack-title-row">
                                <p class="ttn-pack-name"><?php esc_html_e( 'Solana', 'fashion-nook' ); ?></p>
                            </div>
                            <p class="ttn-pack-desc"><?php esc_html_e( 'Relaxed, earthy, and editorial. Made for creative labels with a bohemian soul.', 'fashion-nook' ); ?></p>
                            <div class="ttn-pack-actions">
                                <a href="<?php echo esc_url( $cfg['demo_url_1'] ); ?>" class="button button-secondary" target="_blank" rel="noopener">
                                    <?php esc_html_e( 'View demo →', 'fashion-nook' ); ?>
                                </a>
                                <a href="<?php echo esc_url( $cfg['pro_url_1'] ); ?>" class="button button-primary" target="_blank" rel="noopener">
                                    <?php esc_html_e( 'Get this look', 'fashion-nook' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Solmare -->
                    <div class="ttn-pack-card">
                        <a class="ttn-pack-thumb" href="<?php echo esc_url( $cfg['pro_url_2'] ); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo esc_url( $cfg['image_dir'] . '/solmare.jpg' ); ?>"
                                alt="<?php esc_attr_e( 'Solmare Design Pack preview', 'fashion-nook' ); ?>">
                        </a>
                        <div class="ttn-pack-body">
                            <div class="ttn-pack-title-row">
                                <p class="ttn-pack-name"><?php esc_html_e( 'Solmare', 'fashion-nook' ); ?></p>
                            </div>
                            <p class="ttn-pack-desc"><?php esc_html_e( 'Refined and coastal. For jewellery and lifestyle brands that want to look polished from day one.', 'fashion-nook' ); ?></p>
                            <div class="ttn-pack-actions">
                                <a href="<?php echo esc_url( $cfg['demo_url_2'] ); ?>" class="button button-secondary" target="_blank" rel="noopener">
                                    <?php esc_html_e( 'View demo →', 'fashion-nook' ); ?>
                                </a>
                                <a href="<?php echo esc_url( $cfg['pro_url_2'] ); ?>" class="button button-primary" target="_blank" rel="noopener">
                                    <?php esc_html_e( 'Get this look', 'fashion-nook' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                </div><!-- .ttn-packs-grid -->

            </div><!-- .ttn-left-col -->

            <!-- RIGHT COLUMN: sidebar panels -->
            <div class="ttn-sidebar">
                <div class="ttn-included">
                    <h2><?php esc_html_e( 'What\'s included', 'fashion-nook' ); ?></h2>
                    <div class="ttn-included-list">
                        <div class="ttn-check-item"><?php echo $check_icon; ?><span><?php esc_html_e( 'One-time payment — no subscriptions', 'fashion-nook' ); ?></span></div>
                        <div class="ttn-check-item"><?php echo $check_icon; ?><span><?php esc_html_e( 'Fully styled child theme ready to install', 'fashion-nook' ); ?></span></div>
                        <div class="ttn-check-item"><?php echo $check_icon; ?><span><?php esc_html_e( 'Demo content included for a fast start', 'fashion-nook' ); ?></span></div>
                        <div class="ttn-check-item"><?php echo $check_icon; ?><span><?php esc_html_e( 'Curated colour palette and font pairing', 'fashion-nook' ); ?></span></div>
                        <div class="ttn-check-item"><?php echo $check_icon; ?><span><?php esc_html_e( 'Direct support to match the demo look', 'fashion-nook' ); ?></span></div>
                        <div class="ttn-check-item"><?php echo $check_icon; ?><span><?php esc_html_e( 'Free updates for compatibility and improvements', 'fashion-nook' ); ?></span></div>
                    </div>
                </div>


                <div class="ttn-sidebar-panel">
                    <div class="ttn-review-stars">★★★★★</div>
                    <h3><?php esc_html_e( 'Enjoying Fashion Nook?', 'fashion-nook' ); ?></h3>
                    <p><?php esc_html_e( 'A quick review on WordPress.org helps more people find the theme — and it means a lot.', 'fashion-nook' ); ?></p>
                    <a href="<?php echo esc_url( $cfg['review_url'] ); ?>" target="_blank" rel="noopener">
                        <?php esc_html_e( '→ Leave a review', 'fashion-nook' ); ?>
                    </a>
                </div>

                <div class="ttn-sidebar-panel">
                    <h3><?php esc_html_e( 'Need help?', 'fashion-nook' ); ?></h3>
                    <p><?php esc_html_e( 'Questions about a Design Pack or getting set up? Get in touch.', 'fashion-nook' ); ?></p>
                    <a href="<?php echo esc_url( $cfg['support_url'] ); ?>" target="_blank" rel="noopener">
                        <?php esc_html_e( '→ Contact support', 'fashion-nook' ); ?>
                    </a>
                </div>

            </div><!-- .ttn-sidebar -->

        </div><!-- .ttn-outer-grid -->
        </div><!-- .ttn-about-wrap -->
        <?php
    }
}

endif;

$fashion_nook_campaign = sanitize_title( wp_get_theme()->get_stylesheet() );

new fashion_nook_theme_admin_page( array(
    'prefix'       => 'fashion_nook',
    'slug'         => 'fashion-nook',
    'name'         => is_child_theme() ? wp_get_theme()->get( 'Name' ) : 'Fashion Nook',
    'cap'          => 'edit_theme_options',
    'review_url'   => 'https://wordpress.org/support/theme/fashion-nook/reviews/#new-post',
    'support_url'  => 'https://thethemenook.com/contact/',
    'site_url'     => add_query_arg(
        array(
            'utm_source'   => 'wpadmin',
            'utm_medium'   => 'topbar',
            'utm_campaign' => $fashion_nook_campaign,
        ),
        'https://thethemenook.com/'
    ),
    'pro_url_1'    => add_query_arg(
        array(
            'utm_source'   => 'wpadmin',
            'utm_medium'   => 'upgrade',
            'utm_campaign' => $fashion_nook_campaign,
        ),
        'https://thethemenook.com/products/solana/'
    ),
    'pro_url_2'    => add_query_arg(
        array(
            'utm_source'   => 'wpadmin',
            'utm_medium'   => 'upgrade',
            'utm_campaign' => $fashion_nook_campaign,
        ),
        'https://thethemenook.com/products/solmare/'
    ),
    'pro_url_3'    => add_query_arg(
        array(
            'utm_source'   => 'wpadmin',
            'utm_medium'   => 'upgrade',
            'utm_campaign' => $fashion_nook_campaign,
        ),
        'https://thethemenook.com/products/silque/'
    ),
    'shoppable_blocks_url' => add_query_arg(
        array(
            'utm_source'   => 'wpadmin',
            'utm_medium'   => 'promo',
            'utm_campaign' => $fashion_nook_campaign,
        ),
        'https://thethemenook.com/products/fn-shoppable-blocks/'
    ),
    'demo_url_1'   => 'https://thethemenook.com/solana/',
    'demo_url_2'   => 'https://thethemenook.com/solmare/',
    'demo_url_3'   => 'https://thethemenook.com/silque/',
    'image_dir'    => get_template_directory_uri() . '/admin/images',
    'admin_css'    => get_template_directory_uri() . '/admin/about-theme.css',
    'admin_js'     => get_template_directory_uri() . '/admin/admin-notice.js',
) );
