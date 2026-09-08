<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Shopnex_Rating_Notice {
	private $past_date;

	public function __construct() {
		$this->past_date = false == get_option('shopnex_maybe_later_time') ? strtotime( '-5 days' ) : strtotime('-15 days');

		if ( current_user_can('administrator') ) {
			if ( empty(get_option('shopnex_rating_dismiss_notice')) && empty(get_option('shopnex_rating_already_rated')) ) {
				add_action( 'admin_init', [$this, 'shopnex_check_theme_install_time'] );
			}
		}

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', [$this, 'shopnex_enqueue_scripts'] );
		}

		add_action( 'wp_ajax_shopnex_rating_dismiss_notice', [$this, 'shopnex_rating_dismiss_notice'] );
		add_action( 'wp_ajax_shopnex_rating_already_rated', [$this, 'shopnex_rating_already_rated'] );
		add_action( 'wp_ajax_shopnex_rating_maybe_later', [$this, 'shopnex_rating_maybe_later'] );
	}

	public function shopnex_check_theme_install_time() {   
		$install_date = get_option('shopnex_activation_time');

		if ( false !== $install_date && $this->past_date >= $install_date ) {
			add_action( 'admin_notices', [$this, 'shopnex_render_rating_notice' ]);
		}
	}

	public function shopnex_rating_maybe_later() {
		check_ajax_referer( 'shopnex_rating_nonce', 'nonce' );
		update_option('shopnex_maybe_later_time', true);
		update_option('shopnex_activation_time', strtotime('now'));
	}
	
	public function shopnex_rating_dismiss_notice() {
		check_ajax_referer( 'shopnex_rating_nonce', 'nonce' );
		update_option( 'shopnex_rating_dismiss_notice', true );
	}

	public function shopnex_rating_already_rated() {
		check_ajax_referer( 'shopnex_rating_nonce', 'nonce' );
		update_option( 'shopnex_rating_already_rated' , true );
	}

	public function shopnex_render_rating_notice() {
		if ( is_admin() ) {

			echo '<div class="notice shopnex-rating-notice is-dismissible" style="border-left-color: #0073aa!important; display: flex; align-items: center;">
						<div class="shopnex-rating-notice-logo">
						<img class="shopnex-logo" src="' . esc_url( get_theme_file_uri() . '/inc/activation/img/logo-spiracle.png' ) . '" alt="' . esc_attr__( 'Spiracle Themes', 'shopnex' ) . '">
						</div>
						<div>
							<h3>' . esc_html__( 'Thank you for using Shopnex WordPress Theme to build this website!', 'shopnex' ) . '</h3>
							<p>' . esc_html__( 'Could you please do us a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.', 'shopnex' ) . '</p>
							<p>
								<a href="https://wordpress.org/support/theme/shopnex/reviews/?filter=5" target="_blank" class="shopnex-you-deserve-it button button-primary">' . esc_html__( 'OK, you deserve it!', 'shopnex' ) . '</a>
								<a class="shopnex-maybe-later"><span class="dashicons dashicons-clock"></span> ' . esc_html__( 'Maybe Later', 'shopnex' ) . '</a>
								<a class="shopnex-already-rated"><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'I Already did', 'shopnex' ) . '</a>
							</p>
						</div>
				</div>';
		}
	}

	public function shopnex_enqueue_scripts( $hook ) {
		// Only load on admin pages where the notice might appear.
		wp_enqueue_style(
			'shopnex-rating-notice',
			get_template_directory_uri() . '/inc/activation/css/rating-notice.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);

		wp_enqueue_script(
			'shopnex-rating-notice',
			get_template_directory_uri() . '/inc/activation/js/rating-notice.js',
			array( 'jquery' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_localize_script( 'shopnex-rating-notice', 'shopnex_rating', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'shopnex_rating_nonce' ),
		) );
	}
}

new Shopnex_Rating_Notice();
