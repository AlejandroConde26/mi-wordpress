<?php
/**
 * Welcome Notice class.
 *
 * @package shopnex
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Shopnex_Welcome_Notice
 */
class Shopnex_Welcome_Notice {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Render Notice.
		add_action( 'admin_notices', array( $this, 'shopnex_render_notice' ) );

		// Dismiss handler.
		add_action( 'wp_ajax_shopnex_dismissed_handler', array( $this, 'shopnex_dismissed_handler' ) );

		// Plugin install handler.
		add_action( 'wp_ajax_shopnex_install_site_library', array( $this, 'shopnex_install_site_library' ) );

		// Enqueue dismiss script.
		add_action( 'admin_enqueue_scripts', array( $this, 'shopnex_notice_enqueue_scripts' ) );

		// Reset on theme switch.
		add_action( 'switch_theme', array( $this, 'shopnex_reset_notices' ) );
		add_action( 'after_switch_theme', array( $this, 'shopnex_reset_notices' ) );
	}

	/**
	 * Render Notice.
	 */
	public function shopnex_render_notice() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		$transient_name = sprintf( '%s_activation_notice', get_template() );

		if ( get_transient( $transient_name ) ) {
			return;
		}

		$theme_name = wp_get_theme()->get( 'Name' );

		// Check if spiraclethemes-site-library plugin is active.
		$site_library_active = class_exists( 'Spiraclethemes_Site_Library' ) || ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'spiraclethemes-site-library/spiraclethemes-site-library.php' ) );

		// Determine redirect URL based on plugin status.
		if ( $site_library_active ) {
			$get_started_url = admin_url( 'themes.php?page=one-click-demo-import' );
		} else {
			$get_started_url = '#';
		}

		$theme_info_url = admin_url( 'themes.php?page=shopnex-info' );
		?>
		<div class="notice notice-info is-dismissible shopnex-welcome-notice" data-notice="<?php echo esc_attr( $transient_name ); ?>">
			<div class="shopnex-welcome-notice-content">
				<h2 class="shopnex-welcome-notice-title">
					<?php
					echo esc_html( sprintf(
						/* translators: %s: Theme name */
						__( 'Welcome to %s', 'shopnex' ),
						$theme_name
					) );
					?>
				</h2>
				<p class="shopnex-welcome-notice-description">
					<?php
					echo esc_html( sprintf(
						/* translators: %s: Theme name */
						__( 'Easily customize every aspect of your site with %s and the Elementor Page Builder. Import a ready-made demo and launch in minutes.', 'shopnex' ),
						$theme_name
					) );
					?>
				</p>
				<div class="shopnex-welcome-notice-actions">
					<a href="<?php echo esc_url( $get_started_url ); ?>" class="button button-primary button-hero shopnex-get-started-btn" <?php echo ! $site_library_active ? 'data-plugin-action="install"' : ''; ?>>
						<span class="shopnex-btn-text">
							<?php
							echo esc_html( sprintf(
								/* translators: %s: Theme name */
								__( 'Get Started with %s', 'shopnex' ),
								$theme_name
							) );
							?>
						</span>
						<span class="shopnex-btn-spinner spinner" style="display:none;"></span>
					</a>
					<a href="<?php echo esc_url( $theme_info_url ); ?>" class="button button-secondary button-hero shopnex-theme-info-btn">
						<?php esc_html_e( 'Theme Info', 'shopnex' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Reset Notice.
	 */
	public function shopnex_reset_notices() {
		delete_transient( sprintf( '%s_activation_notice', get_template() ) );
	}

	/**
	 * Dismissed handler.
	 */
	public function shopnex_dismissed_handler() {
		check_ajax_referer( 'shopnex_welcome_nonce', 'nonce' );

		if ( isset( $_POST['notice'] ) ) {
			set_transient( sanitize_text_field( wp_unslash( $_POST['notice'] ) ), true, 0 );
		}
	}

	/**
	 * Install and activate spiraclethemes-site-library plugin.
	 */
	public function shopnex_install_site_library() {
		check_ajax_referer( 'shopnex_welcome_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Unauthorized.', 'shopnex' ) ) );
		}

		$slug = 'spiraclethemes-site-library';

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

		// Check if plugin is already installed.
		$installed_plugins = get_plugins();
		$plugin_file       = false;

		foreach ( $installed_plugins as $file => $data ) {
			if ( strpos( $file, $slug . '/' ) === 0 || $file === $slug . '.php' ) {
				$plugin_file = $file;
				break;
			}
		}

		// If already active, just return success with redirect.
		if ( $plugin_file && is_plugin_active( $plugin_file ) ) {
			wp_send_json_success( array(
				'redirect' => admin_url( 'themes.php?page=one-click-demo-import' ),
				'message'  => esc_html__( 'Plugin is already active.', 'shopnex' ),
			) );
		}

		// If installed but not active, activate it.
		if ( $plugin_file ) {
			$result = activate_plugin( $plugin_file );
			if ( is_wp_error( $result ) ) {
				wp_send_json_error( array( 'message' => $result->get_error_message() ) );
			}
			wp_send_json_success( array(
				'redirect' => admin_url( 'themes.php?page=one-click-demo-import' ),
				'message'  => esc_html__( 'Plugin activated successfully.', 'shopnex' ),
			) );
		}

		// Install the plugin.
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

		// Activate after install.
		$plugin_file = $upgrader->plugin_info();
		if ( $plugin_file ) {
			$activate_result = activate_plugin( $plugin_file );
			if ( is_wp_error( $activate_result ) ) {
				wp_send_json_success( array(
					'redirect' => admin_url( 'plugins.php' ),
					'message'  => esc_html__( 'Plugin installed. Please activate it manually.', 'shopnex' ),
				) );
			}
		}

		wp_send_json_success( array(
			'redirect' => admin_url( 'themes.php?page=one-click-demo-import' ),
			'message'  => esc_html__( 'Plugin installed and activated successfully.', 'shopnex' ),
		) );
	}

	/**
	 * Enqueue dismiss script.
	 *
	 * @param string $page The current admin page.
	 */
	public function shopnex_notice_enqueue_scripts( $page ) {
		wp_enqueue_script( 'jquery' );

		$welcome_nonce = wp_create_nonce( 'shopnex_welcome_nonce' );

		ob_start();
		?>
		jQuery(function($) {
			// Dismiss notice.
			$( document ).on( 'click', '.shopnex-welcome-notice .notice-dismiss', function () {
				jQuery.post( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
					action: 'shopnex_dismissed_handler',
					notice: $( this ).closest( '.shopnex-welcome-notice' ).data( 'notice' ),
					nonce: '<?php echo esc_js( $welcome_nonce ); ?>',
				});
			} );

			// Get Started button - install plugin and redirect.
			$( document ).on( 'click', '.shopnex-get-started-btn[data-plugin-action="install"]', function(e) {
				e.preventDefault();

				var $btn = $( this );
				var $text = $btn.find( '.shopnex-btn-text' );
				var $spinner = $btn.find( '.shopnex-btn-spinner' );

				if ( $btn.hasClass( 'shopnex-installing' ) ) {
					return;
				}

				$btn.addClass( 'shopnex-installing disabled' );
				$spinner.addClass( 'is-active' ).show();
				$text.text( '<?php echo esc_js( __( 'Installing Plugin...', 'shopnex' ) ); ?>' );

				$.post( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
					action: 'shopnex_install_site_library',
					nonce: '<?php echo esc_js( $welcome_nonce ); ?>',
				}, function( response ) {
					if ( response.success && response.data.redirect ) {
						$text.text( '<?php echo esc_js( __( 'Redirecting...', 'shopnex' ) ); ?>' );
						window.location.href = response.data.redirect;
					} else {
						$btn.removeClass( 'shopnex-installing disabled' );
						$spinner.removeClass( 'is-active' ).hide();
						$text.text( '<?php echo esc_js( sprintf( __( 'Get Started with %s', 'shopnex' ), wp_get_theme()->get( 'Name' ) ) ); ?>' );
						if ( response.data && response.data.message ) {
							alert( response.data.message );
						}
					}
				}).fail(function() {
					$btn.removeClass( 'shopnex-installing disabled' );
					$spinner.removeClass( 'is-active' ).hide();
					$text.text( '<?php echo esc_js( sprintf( __( 'Get Started with %s', 'shopnex' ), wp_get_theme()->get( 'Name' ) ) ); ?>' );
					alert( '<?php echo esc_js( __( 'An error occurred. Please try again.', 'shopnex' ) ); ?>' );
				});
			});
		});
		<?php
		$script = ob_get_clean();

		wp_add_inline_script( 'jquery', $script );

		// Add inline styles for the welcome notice.
		$css = '
			.shopnex-welcome-notice {
				border-left-color: #2271b1 !important;
				padding: 20px 25px;
				background: #fff;
			}
			.shopnex-welcome-notice-content {
				display: flex;
				flex-direction: column;
				gap: 8px;
			}
			.shopnex-welcome-notice-title {
				margin: 0 0 4px 0 !important;
				font-size: 20px;
				font-weight: 600;
				color: #1d2327;
			}
			.shopnex-welcome-notice-description {
				margin: 0 0 12px 0;
				color: #50575e;
				font-size: 14px;
				line-height: 1.5;
			}
			.shopnex-welcome-notice-actions {
				display: flex;
				gap: 12px;
				align-items: center;
				flex-wrap: wrap;
			}
			.shopnex-welcome-notice-actions .button-hero {
				padding: 4px 20px !important;
				font-size: 14px !important;
				line-height: 2.2 !important;
				height: auto !important;
				display: inline-flex;
				align-items: center;
				gap: 6px;
			}
			.shopnex-welcome-notice-actions .button-primary {
				background: #2271b1 !important;
				border-color: #2271b1 !important;
			}
			.shopnex-welcome-notice-actions .button-primary:hover {
				background: #135e96 !important;
				border-color: #135e96 !important;
			}
			.shopnex-welcome-notice-actions .shopnex-get-started-btn.disabled {
				opacity: 0.7;
				pointer-events: none;
			}
			.shopnex-welcome-notice-actions .shopnex-btn-spinner {
				margin: 0;
			}
		';

		wp_add_inline_style( 'common', $css );
	}
}

new Shopnex_Welcome_Notice();
