<?php
/**
 * ShopNex Info Page 
 *
 *
 * @package shopnex
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Shopnex_Info_Page
 *
 */
class Shopnex_Info_Page {

	/**
	 * Theme object.
	 *
	 * @var WP_Theme
	 */
	private $theme;

	/**
	 * Slug for the admin page.
	 *
	 * @var string
	 */
	private $page_slug = 'shopnex-info';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->theme = wp_get_theme();

		add_action( 'admin_menu', array( $this, 'register_submenu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_shopnex_plugin_action', array( $this, 'handle_plugin_action' ) );
		add_action( 'admin_head', array( $this, 'add_menu_notification_badge' ) );
	}

	/**
	 * Register the submenu page under Appearance.
	 *
	 * @return void
	 */
	public function register_submenu_page() {
		add_theme_page(
			esc_html__( 'ShopNex Info', 'shopnex' ),
			esc_html__( 'ShopNex Info', 'shopnex' ),
			'manage_options',
			$this->page_slug,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue admin styles and scripts.
	 *
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		if ( 'appearance_page_' . $this->page_slug !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'shopnex-info-admin',
			get_template_directory_uri() . '/inc/admin/css/shopnex-info.css',
			array(),
			$this->theme->get( 'Version' )
		);

		wp_enqueue_script(
			'shopnex-info-admin',
			get_template_directory_uri() . '/inc/admin/js/shopnex-info.js',
			array( 'jquery' ),
			$this->theme->get( 'Version' ),
			true
		);

		wp_localize_script( 'shopnex-info-admin', 'shopnexInfo', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'shopnex_plugin_action_nonce' ),
			'strings' => array(
				'installing'  => esc_html__( 'Installing...', 'shopnex' ),
				'activating'  => esc_html__( 'Activating...', 'shopnex' ),
				'deactivating' => esc_html__( 'Deactivating...', 'shopnex' ),
				'installed'   => esc_html__( 'Installed', 'shopnex' ),
				'active'      => esc_html__( 'Active', 'shopnex' ),
				'inactive'    => esc_html__( 'Inactive', 'shopnex' ),
			),
		) );
	}

	/**
	 * Handle plugin install/activate/deactivate via AJAX.
	 *
	 * @return void
	 */
	public function handle_plugin_action() {
		check_ajax_referer( 'shopnex_plugin_action_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Unauthorized.', 'shopnex' ) ) );
		}

		$action   = isset( $_POST['plugin_action'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_action'] ) ) : '';
		$plugin   = isset( $_POST['plugin'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) : '';
		$slug     = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		if ( empty( $action ) || empty( $slug ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Missing parameters.', 'shopnex' ) ) );
		}

		// Include required files for plugin installation.
		if ( ! class_exists( 'Plugin_Upgrader' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}
		if ( ! function_exists( 'plugins_api' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		}
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		switch ( $action ) {
			case 'install':
				$api = plugins_api( 'plugin_information', array(
					'slug'   => $slug,
					'fields' => array( 'sections' => false ),
				) );

				if ( is_wp_error( $api ) ) {
					wp_send_json_error( array( 'message' => $api->get_error_message() ) );
				}

				$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
				$result   = $upgrader->install( $api->download_link );

				if ( is_wp_error( $result ) ) {
					wp_send_json_error( array( 'message' => $result->get_error_message() ) );
				}

				// Try to activate after install.
				$plugin_file = $upgrader->plugin_info();
				if ( $plugin_file ) {
					$activate_result = activate_plugin( $plugin_file );
					if ( is_wp_error( $activate_result ) ) {
						wp_send_json_success( array(
							'status'  => 'installed',
							'message' => esc_html__( 'Plugin installed. Please activate it manually.', 'shopnex' ),
						) );
					}
				}

				wp_send_json_success( array(
					'status'  => 'active',
					'message' => esc_html__( 'Plugin installed and activated successfully.', 'shopnex' ),
				) );
				break;

			case 'activate':
				if ( empty( $plugin ) ) {
					wp_send_json_error( array( 'message' => esc_html__( 'Plugin file not specified.', 'shopnex' ) ) );
				}
				$result = activate_plugin( $plugin );
				if ( is_wp_error( $result ) ) {
					wp_send_json_error( array( 'message' => $result->get_error_message() ) );
				}
				wp_send_json_success( array(
					'status'  => 'active',
					'message' => esc_html__( 'Plugin activated successfully.', 'shopnex' ),
				) );
				break;

			case 'deactivate':
				if ( empty( $plugin ) ) {
					wp_send_json_error( array( 'message' => esc_html__( 'Plugin file not specified.', 'shopnex' ) ) );
				}
				deactivate_plugins( $plugin );
				wp_send_json_success( array(
					'status'  => 'inactive',
					'message' => esc_html__( 'Plugin deactivated successfully.', 'shopnex' ),
				) );
				break;

			default:
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid action.', 'shopnex' ) ) );
		}
	}

	/**
	 * Get recommended plugins data.
	 *
	 * @return array
	 */
	private function get_recommended_plugins() {
		$plugins = array(
			array(
				'slug'        => 'woocommerce',
				'name'        => 'WooCommerce',
				'description' => esc_html__( 'The most popular eCommerce plugin for WordPress. Transform your website into a powerful online store.', 'shopnex' ),
				'icon'        => 'cart',
				'category'    => esc_html__( 'E-Commerce', 'shopnex' ),
				'required'    => true,
			),
			array(
				'slug'        => 'elementor',
				'name'        => 'Elementor',
				'description' => esc_html__( 'The best drag & drop page builder plugin. Create beautiful pages with a visual editor.', 'shopnex' ),
				'icon'        => 'builder',
				'category'    => esc_html__( 'Page Builder', 'shopnex' ),
				'required'    => false,
			),
			array(
				'slug'        => 'contact-form-7',
				'name'        => 'Contact Form 7',
				'description' => esc_html__( 'A simple and flexible contact form plugin. Manage multiple forms with ease.', 'shopnex' ),
				'icon'        => 'email',
				'category'    => esc_html__( 'Forms', 'shopnex' ),
				'required'    => false,
			),
			array(
				'slug'        => 'spiraclethemes-site-library',
				'name'        => 'Spiraclethemes Site Library',
				'description' => esc_html__( 'Import ready-made demo sites with one click. Quickly set up your site using pre-designed templates.', 'shopnex' ),
				'icon'        => 'library',
				'category'    => esc_html__( 'Starter Templates', 'shopnex' ),
				'required'    => false,
			),

		);

		// Check installed plugins and their status.
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$installed_plugins = get_plugins();

		foreach ( $plugins as $key => $plugin ) {
			$plugin_file = $this->find_plugin_file( $plugin['slug'], $installed_plugins );

			if ( $plugin_file ) {
				$plugins[ $key ]['installed'] = true;
				$plugins[ $key ]['plugin']     = $plugin_file;
				$plugins[ $key ]['active']     = is_plugin_active( $plugin_file );
			} else {
				$plugins[ $key ]['installed'] = false;
				$plugins[ $key ]['plugin']     = '';
				$plugins[ $key ]['active']     = false;
			}
		}

		return $plugins;
	}

	/**
	 * Find the main plugin file for a given slug.
	 *
	 * @param string $slug             Plugin slug.
	 * @param array  $installed_plugins Array of installed plugins.
	 * @return string|false
	 */
	private function find_plugin_file( $slug, $installed_plugins ) {
		foreach ( $installed_plugins as $file => $data ) {
			if ( strpos( $file, $slug . '/' ) === 0 || $file === $slug . '.php' ) {
				return $file;
			}
		}
		return false;
	}

	/**
	 * Get count of recommended plugins that are not installed or not active.
	 *
	 * @return int
	 */
	private function get_incomplete_plugins_count() {
		$plugins = $this->get_recommended_plugins();
		$count   = 0;

		foreach ( $plugins as $plugin ) {
			if ( ! $plugin['active'] ) {
				$count++;
			}
		}

		return $count;
	}

	/**
	 * Add notification badge to the ShopNex Info submenu item.
	 *
	 * @return void
	 */
	public function add_menu_notification_badge() {
		$incomplete = $this->get_incomplete_plugins_count();

		if ( $incomplete <= 0 ) {
			return;
		}
		?>
		<style>
			.shopnex-menu-notification-badge {
				display: inline-block;
				background: var(--sn-color-red, #E53E3E);
				background: #E53E3E;
				color: #fff;
				font-size: 11px;
				font-weight: 600;
				min-width: 18px;
				height: 18px;
				line-height: 18px;
				text-align: center;
				border-radius: 9px;
				margin-left: 6px;
				padding: 0 5px;
				vertical-align: middle;
			}
		</style>
		<script>
			jQuery( document ).ready( function( $ ) {
				var menuItem = $( 'a[href="themes.php?page=shopnex-info"]' );
				if ( menuItem.length ) {
					menuItem.append( '<span class="shopnex-menu-notification-badge"><?php echo esc_js( $incomplete ); ?></span>' );
				}
			} );
		</script>
		<?php
	}

	/**
	 * Get free vs pro features comparison data.
	 *
	 * @return array
	 */
	private function get_free_vs_pro_features() {
		return array(
			array(
				'feature' => esc_html__( 'Responsive Design', 'shopnex' ),
				'free'    => true,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'WooCommerce Compatibility', 'shopnex' ),
				'free'    => true,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Custom Logo Support', 'shopnex' ),
				'free'    => true,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Blog Layouts', 'shopnex' ),
				'free'    => true,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Footer Widgets', 'shopnex' ),
				'free'    => true,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Customizer Options', 'shopnex' ),
				'free'    => true,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Translation Ready', 'shopnex' ),
				'free'    => true,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Page Title Settings', 'shopnex' ),
				'free'    => true,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Sticky Header Options', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Advanced Typography Options', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Product Quick View', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Wishlist Functionality', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Product Comparison', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Product Countdown', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Color Swatches', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Advanced Product Filters', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Product Badge Customization', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Product Second Image', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Footer Layout Options', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Cookie Banner', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Newsletter Popup', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Mailchimp Integration', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Maintenance Mode', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Pro Elementor Widgets', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Header Slider', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Product Slider', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Performance Optimization', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
			array(
				'feature' => esc_html__( 'Priority Support', 'shopnex' ),
				'free'    => false,
				'pro'     => true,
			),
		);
	}

	/**
	 * Render the admin page.
	 *
	 * @return void
	 */
	public function render_page() {
		$theme            = $this->theme;
		$plugins          = $this->get_recommended_plugins();
		$free_vs_pro      = $this->get_free_vs_pro_features();
		$pro_active       = class_exists( 'Shopnex_Pro' );
		$active_tab       = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'theme-info';
		$incomplete_count = 0;

		foreach ( $plugins as $plugin ) {
			if ( ! $plugin['active'] ) {
				$incomplete_count++;
			}
		}
		?>
		<div class="shopnex-info-wrap">
			<!-- Header -->
			<div class="shopnex-info-header">
				<div class="shopnex-info-header-inner">
					<div class="shopnex-info-brand">
						<div class="shopnex-info-logo">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
						</div>
						<div class="shopnex-info-title-group">
							<h1 class="shopnex-info-title"><?php echo esc_html( $theme->get( 'Name' ) ); ?></h1>
							<span class="shopnex-info-version"><?php echo esc_html( sprintf( __( 'Version %s', 'shopnex' ), $theme->get( 'Version' ) ) ); ?></span>
						</div>
					</div>
					<div class="shopnex-info-header-actions">
						<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="shopnex-info-btn shopnex-info-btn-primary">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
							<?php esc_html_e( 'Customize', 'shopnex' ); ?>
						</a>
						<?php if ( ! $pro_active ) : ?>
						<a href="<?php echo esc_url( SHOPNEX_THEME_AUTH . 'shopnex-pro-addons/' ); ?>" class="shopnex-info-btn shopnex-info-btn-upgrade" target="_blank" rel="noopener noreferrer">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
							<?php esc_html_e( 'Upgrade to Pro', 'shopnex' ); ?>
						</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- Tabs -->
			<div class="shopnex-info-tabs">
				<div class="shopnex-info-tabs-inner">
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=' . $this->page_slug . '&tab=theme-info' ) ); ?>" class="shopnex-info-tab <?php echo 'theme-info' === $active_tab ? 'active' : ''; ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
						<?php esc_html_e( 'Theme Info', 'shopnex' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=' . $this->page_slug . '&tab=plugins' ) ); ?>" class="shopnex-info-tab <?php echo 'plugins' === $active_tab ? 'active' : ''; ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
						<?php esc_html_e( 'Recommended Plugins', 'shopnex' ); ?>
						<?php if ( $incomplete_count > 0 ) : ?>
							<span class="shopnex-info-tab-badge"><?php echo esc_html( $incomplete_count ); ?></span>
						<?php endif; ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=' . $this->page_slug . '&tab=free-vs-pro' ) ); ?>" class="shopnex-info-tab <?php echo 'free-vs-pro' === $active_tab ? 'active' : ''; ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
						<?php esc_html_e( 'Free vs Pro', 'shopnex' ); ?>
					</a>
				</div>
			</div>

			<!-- Content -->
			<div class="shopnex-info-content">
				<?php
				switch ( $active_tab ) {
					case 'plugins':
						$this->render_plugins_tab( $plugins );
						break;
					case 'free-vs-pro':
						$this->render_free_vs_pro_tab( $free_vs_pro, $pro_active );
						break;
					default:
						$this->render_theme_info_tab( $theme );
				}
				?>
			</div>

			<!-- Footer -->
			<div class="shopnex-info-footer">
				<div class="shopnex-info-footer-inner">
					<p>
						<?php
						printf(
							/* translators: %1$s: Theme name, %2$s: Author name */
							__( '%1$s is proudly designed by %2$s', 'shopnex' ),
							'<strong>' . esc_html( $theme->get( 'Name' ) ) . '</strong>',
							'<a href="' . esc_url( SHOPNEX_THEME_AUTH ) . '" target="_blank" rel="noopener noreferrer">Spiracle Themes</a>'
						);
						?>
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the Theme Info tab.
	 *
	 * @param WP_Theme $theme Theme object.
	 * @return void
	 */
	private function render_theme_info_tab( $theme ) {
		?>
		<div class="shopnex-info-grid">
			<!-- Theme Details Card -->
			<div class="shopnex-info-card shopnex-info-card-main">
				<div class="shopnex-info-card-header">
					<h2 class="shopnex-info-card-title"><?php esc_html_e( 'Theme Details', 'shopnex' ); ?></h2>
				</div>
				<div class="shopnex-info-card-body">
					<div class="shopnex-info-details-list">
						<div class="shopnex-info-detail-item">
							<span class="shopnex-info-detail-label"><?php esc_html_e( 'Theme Name', 'shopnex' ); ?></span>
							<span class="shopnex-info-detail-value"><?php echo esc_html( $theme->get( 'Name' ) ); ?></span>
						</div>
						<div class="shopnex-info-detail-item">
							<span class="shopnex-info-detail-label"><?php esc_html_e( 'Version', 'shopnex' ); ?></span>
							<span class="shopnex-info-detail-value"><?php echo esc_html( $theme->get( 'Version' ) ); ?></span>
						</div>
						<div class="shopnex-info-detail-item">
							<span class="shopnex-info-detail-label"><?php esc_html_e( 'Author', 'shopnex' ); ?></span>
							<span class="shopnex-info-detail-value">
								<a href="<?php echo esc_url( SHOPNEX_THEME_AUTH ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $theme->get( 'Author' ) ); ?></a>
							</span>
						</div>
						<div class="shopnex-info-detail-item">
							<span class="shopnex-info-detail-label"><?php esc_html_e( 'Requires PHP', 'shopnex' ); ?></span>
							<span class="shopnex-info-detail-value"><?php echo esc_html( $theme->get( 'RequiresPHP' ) ); ?></span>
						</div>
						<div class="shopnex-info-detail-item">
							<span class="shopnex-info-detail-label"><?php esc_html_e( 'WordPress Version', 'shopnex' ); ?></span>
							<span class="shopnex-info-detail-value"><?php echo esc_html( $theme->get( 'RequiresWP' ) ); ?>+</span>
						</div>
						<div class="shopnex-info-detail-item">
							<span class="shopnex-info-detail-label"><?php esc_html_e( 'License', 'shopnex' ); ?></span>
							<span class="shopnex-info-detail-value"><?php esc_html_e( 'GPL v2 or later', 'shopnex' ); ?></span>
						</div>
						<div class="shopnex-info-detail-item">
							<span class="shopnex-info-detail-label"><?php esc_html_e( 'Text Domain', 'shopnex' ); ?></span>
							<span class="shopnex-info-detail-value"><?php echo esc_html( $theme->get( 'TextDomain' ) ); ?></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Sidebar -->
			<div class="shopnex-info-card shopnex-info-card-sidebar">
				<!-- Description -->
				<div class="shopnex-info-card">
					<div class="shopnex-info-card-header">
						<h2 class="shopnex-info-card-title"><?php esc_html_e( 'About', 'shopnex' ); ?></h2>
					</div>
					<div class="shopnex-info-card-body">
						<p class="shopnex-info-description"><?php echo esc_html( $theme->get( 'Description' ) ); ?></p>
					</div>
				</div>

				<!-- Useful Links -->
				<div class="shopnex-info-card card-two">
					<div class="shopnex-info-card-header">
						<h2 class="shopnex-info-card-title"><?php esc_html_e( 'Useful Links', 'shopnex' ); ?></h2>
					</div>
					<div class="shopnex-info-card-body">
						<ul class="shopnex-info-links">
							<li>
								<a href="<?php echo esc_url( $theme->get( 'ThemeURI' ) ); ?>" target="_blank" rel="noopener noreferrer">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
									<?php esc_html_e( 'Theme Homepage', 'shopnex' ); ?>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( 'https://wordpress.org/support/theme/shopnex/' ); ?>" target="_blank" rel="noopener noreferrer">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
									<?php esc_html_e( 'Support Forum', 'shopnex' ); ?>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( 'https://wordpress.org/support/theme/shopnex/reviews/?filter=5' ); ?>" target="_blank" rel="noopener noreferrer">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
									<?php esc_html_e( 'Rate This Theme', 'shopnex' ); ?>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( SHOPNEX_THEME_AUTH . 'shopnex-pro-addons/' ); ?>" target="_blank" rel="noopener noreferrer">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
									<?php esc_html_e( 'Upgrade to Pro', 'shopnex' ); ?>
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
									<?php esc_html_e( 'Theme Customizer', 'shopnex' ); ?>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- Theme Features -->
		<div class="shopnex-info-card shopnex-info-card-full">
			<div class="shopnex-info-card-header">
				<h2 class="shopnex-info-card-title"><?php esc_html_e( 'Theme Features', 'shopnex' ); ?></h2>
			</div>
			<div class="shopnex-info-card-body">
				<div class="shopnex-info-features-grid">
					<div class="shopnex-info-feature">
						<div class="shopnex-info-feature-icon shopnex-info-feature-icon-blue">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
						</div>
						<h3 class="shopnex-info-feature-title"><?php esc_html_e( 'Responsive Design', 'shopnex' ); ?></h3>
						<p class="shopnex-info-feature-desc"><?php esc_html_e( 'Looks great on all devices — desktop, tablet, and mobile.', 'shopnex' ); ?></p>
					</div>
					<div class="shopnex-info-feature">
						<div class="shopnex-info-feature-icon shopnex-info-feature-icon-purple">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
						</div>
						<h3 class="shopnex-info-feature-title"><?php esc_html_e( 'WooCommerce Ready', 'shopnex' ); ?></h3>
						<p class="shopnex-info-feature-desc"><?php esc_html_e( 'Full WooCommerce support with custom shop and product pages.', 'shopnex' ); ?></p>
					</div>
					<div class="shopnex-info-feature">
						<div class="shopnex-info-feature-icon shopnex-info-feature-icon-green">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
						</div>
						<h3 class="shopnex-info-feature-title"><?php esc_html_e( 'Customizer Options', 'shopnex' ); ?></h3>
						<p class="shopnex-info-feature-desc"><?php esc_html_e( 'Live theme customizer with real-time preview for easy setup.', 'shopnex' ); ?></p>
					</div>
					<div class="shopnex-info-feature">
						<div class="shopnex-info-feature-icon shopnex-info-feature-icon-orange">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
						</div>
						<h3 class="shopnex-info-feature-title"><?php esc_html_e( 'Performance Optimized', 'shopnex' ); ?></h3>
						<p class="shopnex-info-feature-desc"><?php esc_html_e( 'Clean, lightweight code for fast loading and better performance.', 'shopnex' ); ?></p>
					</div>
					<div class="shopnex-info-feature">
						<div class="shopnex-info-feature-icon shopnex-info-feature-icon-red">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
						</div>
						<h3 class="shopnex-info-feature-title"><?php esc_html_e( 'SEO Friendly', 'shopnex' ); ?></h3>
						<p class="shopnex-info-feature-desc"><?php esc_html_e( 'Built with best SEO practices for better search rankings.', 'shopnex' ); ?></p>
					</div>
					<div class="shopnex-info-feature">
						<div class="shopnex-info-feature-icon shopnex-info-feature-icon-teal">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
						</div>
						<h3 class="shopnex-info-feature-title"><?php esc_html_e( 'Translation Ready', 'shopnex' ); ?></h3>
						<p class="shopnex-info-feature-desc"><?php esc_html_e( 'Fully translation-ready for global audiences.', 'shopnex' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the Recommended Plugins tab.
	 *
	 * @param array $plugins Array of plugin data.
	 * @return void
	 */
	private function render_plugins_tab( $plugins ) {
		?>
		<div class="shopnex-info-card shopnex-info-card-full">
			<div class="shopnex-info-card-header">
				<h2 class="shopnex-info-card-title"><?php esc_html_e( 'Recommended Plugins', 'shopnex' ); ?></h2>
				<p class="shopnex-info-card-subtitle"><?php esc_html_e( 'These plugins work beautifully with ShopNex and are recommended for the best experience.', 'shopnex' ); ?></p>
			</div>
			<div class="shopnex-info-card-body">
				<div class="shopnex-info-plugins-grid">
					<?php foreach ( $plugins as $plugin ) : ?>
					<div class="shopnex-info-plugin-card" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" data-plugin="<?php echo esc_attr( $plugin['plugin'] ); ?>">
						<div class="shopnex-info-plugin-icon shopnex-info-plugin-icon-<?php echo esc_attr( $plugin['icon'] ); ?>">
							<?php echo $this->get_plugin_icon_svg( $plugin['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<div class="shopnex-info-plugin-info">
							<div class="shopnex-info-plugin-top">
								<h3 class="shopnex-info-plugin-name"><?php echo esc_html( $plugin['name'] ); ?></h3>
								<span class="shopnex-info-plugin-category"><?php echo esc_html( $plugin['category'] ); ?></span>
							</div>
							<p class="shopnex-info-plugin-desc"><?php echo esc_html( $plugin['description'] ); ?></p>
							<div class="shopnex-info-plugin-actions">
								<?php if ( $plugin['active'] ) : ?>
									<span class="shopnex-info-badge shopnex-info-badge-active">
										<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
										<?php esc_html_e( 'Active', 'shopnex' ); ?>
									</span>
									<button class="shopnex-info-btn shopnex-info-btn-outline shopnex-plugin-action-btn" data-action="deactivate" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" data-plugin="<?php echo esc_attr( $plugin['plugin'] ); ?>">
										<?php esc_html_e( 'Deactivate', 'shopnex' ); ?>
									</button>
								<?php elseif ( $plugin['installed'] ) : ?>
									<span class="shopnex-info-badge shopnex-info-badge-inactive">
										<?php esc_html_e( 'Installed', 'shopnex' ); ?>
									</span>
									<button class="shopnex-info-btn shopnex-info-btn-primary shopnex-plugin-action-btn" data-action="activate" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" data-plugin="<?php echo esc_attr( $plugin['plugin'] ); ?>">
										<?php esc_html_e( 'Activate', 'shopnex' ); ?>
									</button>
								<?php else : ?>
									<button class="shopnex-info-btn shopnex-info-btn-primary shopnex-plugin-action-btn" data-action="install" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" data-plugin="">
										<?php esc_html_e( 'Install & Activate', 'shopnex' ); ?>
									</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the Free vs Pro tab.
	 *
	 * @param array $features   Feature comparison data.
	 * @param bool  $pro_active Whether the pro plugin is active.
	 * @return void
	 */
	private function render_free_vs_pro_tab( $features, $pro_active ) {
		?>
		<div class="shopnex-info-card shopnex-info-card-full">
			<div class="shopnex-info-card-header">
				<h2 class="shopnex-info-card-title"><?php esc_html_e( 'Free vs Pro Comparison', 'shopnex' ); ?></h2>
				<p class="shopnex-info-card-subtitle"><?php esc_html_e( 'See what you get with ShopNex free and what additional features the Pro version unlocks.', 'shopnex' ); ?></p>
			</div>
			<div class="shopnex-info-card-body">
				<?php if ( $pro_active ) : ?>
					<div class="shopnex-info-pro-notice shopnex-info-pro-notice-active">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
						<span><?php esc_html_e( 'ShopNex Pro is active! You have access to all features.', 'shopnex' ); ?></span>
					</div>
				<?php else : ?>
					<div class="shopnex-info-pro-notice shopnex-info-pro-notice-upsell">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
						<span><?php esc_html_e( 'Unlock all features with ShopNex Pro!', 'shopnex' ); ?></span>
						<a href="<?php echo esc_url( SHOPNEX_THEME_AUTH . 'shopnex-pro-addons/' ); ?>" class="shopnex-info-btn shopnex-info-btn-upgrade" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get Pro', 'shopnex' ); ?></a>
					</div>
				<?php endif; ?>

				<div class="shopnex-info-comparison-table-wrap">
					<table class="shopnex-info-comparison-table">
						<thead>
							<tr>
								<th class="shopnex-info-comparison-feature"><?php esc_html_e( 'Feature', 'shopnex' ); ?></th>
								<th class="shopnex-info-comparison-free">
									<span class="shopnex-info-comparison-plan"><?php esc_html_e( 'Free', 'shopnex' ); ?></span>
									<span class="shopnex-info-comparison-price"><?php esc_html_e( '$0', 'shopnex' ); ?></span>
								</th>
								<th class="shopnex-info-comparison-pro">
									<span class="shopnex-info-comparison-plan"><?php esc_html_e( 'Pro', 'shopnex' ); ?></span>
									<span class="shopnex-info-comparison-price"><?php esc_html_e( 'Premium', 'shopnex' ); ?></span>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $features as $index => $feature ) : ?>
							<tr class="<?php echo ( 0 === $index % 2 ) ? 'shopnex-info-row-even' : 'shopnex-info-row-odd'; ?>">
								<td class="shopnex-info-comparison-feature"><?php echo esc_html( $feature['feature'] ); ?></td>
								<td class="shopnex-info-comparison-free">
									<?php if ( $feature['free'] ) : ?>
										<span class="shopnex-info-check shopnex-info-check-yes">
											<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
										</span>
									<?php else : ?>
										<span class="shopnex-info-check shopnex-info-check-no">
											<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
										</span>
									<?php endif; ?>
								</td>
								<td class="shopnex-info-comparison-pro">
									<?php if ( $feature['pro'] ) : ?>
										<span class="shopnex-info-check shopnex-info-check-yes">
											<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
										</span>
									<?php else : ?>
										<span class="shopnex-info-check shopnex-info-check-no">
											<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
										</span>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<?php if ( ! $pro_active ) : ?>
				<div class="shopnex-info-cta">
					<div class="shopnex-info-cta-inner">
						<h3 class="shopnex-info-cta-title"><?php esc_html_e( 'Ready to unlock the full power of ShopNex?', 'shopnex' ); ?></h3>
						<p class="shopnex-info-cta-desc"><?php esc_html_e( 'Get access to all premium features, priority support, and regular updates with ShopNex Pro.', 'shopnex' ); ?></p>
						<div class="shopnex-info-cta-actions">
							<a href="<?php echo esc_url( SHOPNEX_THEME_AUTH . 'shopnex-pro-addons/' ); ?>" class="shopnex-info-btn shopnex-info-btn-upgrade shopnex-info-btn-lg" target="_blank" rel="noopener noreferrer">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
								<?php esc_html_e( 'Upgrade to Pro Now', 'shopnex' ); ?>
							</a>
							<a href="<?php echo esc_url( SHOPNEX_THEME_AUTH . 'shopnex-pro-addons/' ); ?>" class="shopnex-info-btn shopnex-info-btn-outline" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Learn More', 'shopnex' ); ?>
							</a>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get SVG icon for plugin category.
	 *
	 * @param string $icon Icon name.
	 * @return string SVG markup.
	 */
	private function get_plugin_icon_svg( $icon ) {
		$svgs = array(
			'cart' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
			'builder' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>',
			'email' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
			'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
			'forms' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
			'performance' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
			'library' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
		);

		return isset( $svgs[ $icon ] ) ? $svgs[ $icon ] : $svgs['cart'];
	}
}

// Initialize the admin page.
new Shopnex_Info_Page();
