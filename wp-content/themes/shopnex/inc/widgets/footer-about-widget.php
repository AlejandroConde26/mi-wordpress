<?php

/**
 * Footer About widget.
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;


if( ! class_exists('Shopnex_Footer_About_Widget')) :

class Shopnex_Footer_About_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'shopnex_footer_about_widget', // Base ID
			esc_html__( 'Shopnex: Footer About Widget', 'shopnex' ), // Name
			array(
				'description' => esc_html__( 'Adds logo, description and social icons.', 'shopnex' ),
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$heading           = ! empty( $instance['heading'] ) ? $instance['heading'] : '';
		$subheading        = ! empty( $instance['subheading'] ) ? $instance['subheading'] : '';
		$logo_id           = ! empty( $instance['logo_id'] ) ? absint( $instance['logo_id'] ) : 0;
		$logo_width        = ! empty( $instance['logo_width'] ) ? absint( $instance['logo_width'] ) : 0;
		$cb_enable_heading = ! empty( $instance['cb_enable_heading'] );
		$cb_show_social    = ! empty( $instance['cb_show_social'] );

		$facebook_url   = ! empty( $instance['facebook_url'] ) ? $instance['facebook_url'] : '';
		$twitter_url    = ! empty( $instance['twitter_url'] ) ? $instance['twitter_url'] : '';
		$instagram_url  = ! empty( $instance['instagram_url'] ) ? $instance['instagram_url'] : '';
		$pinterest_url  = ! empty( $instance['pinterest_url'] ) ? $instance['pinterest_url'] : '';
		$youtube_url    = ! empty( $instance['youtube_url'] ) ? $instance['youtube_url'] : '';

		$has_social = ( $facebook_url || $twitter_url || $instagram_url || $pinterest_url || $youtube_url );

		?>

		<div class="footer-brand">

			<?php if ( $cb_enable_heading && $heading ) : ?>
				<h3 class="footer-widget-title"><?php echo esc_html( $heading ); ?></h3>
			<?php endif; ?>

		<?php
		if ( $logo_id ) :
			$logo_attrs = array( 'class' => 'footer-logo' );
			if ( $logo_width ) {
				$logo_attrs['style'] = 'max-width:' . $logo_width . 'px; max-height:none; height:auto;';
			}
			$logo_img = wp_get_attachment_image( $logo_id, 'full', false, $logo_attrs );
			if ( $logo_img ) :
				?>
				<div class="footer-logo-wrap">
					<?php echo wp_kses_post( $logo_img ); ?>
				</div>
				<?php
			endif;
		endif;
		?>

			<?php if ( $subheading ) : ?>
				<p class="footer-description"><?php echo esc_html( $subheading ); ?></p>
			<?php endif; ?>

			<?php if ( $cb_show_social && $has_social ) : ?>
				<div class="footer-social">
					<?php if ( $facebook_url ) : ?>
						<a href="<?php echo esc_url( $facebook_url ); ?>" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'shopnex' ); ?>">
							<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
						</a>
					<?php endif; ?>

					<?php if ( $twitter_url ) : ?>
						<a href="<?php echo esc_url( $twitter_url ); ?>" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'X (Twitter)', 'shopnex' ); ?>">
							<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
						</a>
					<?php endif; ?>

					<?php if ( $instagram_url ) : ?>
						<a href="<?php echo esc_url( $instagram_url ); ?>" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'shopnex' ); ?>">
							<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/></svg>
						</a>
					<?php endif; ?>

					<?php if ( $pinterest_url ) : ?>
						<a href="<?php echo esc_url( $pinterest_url ); ?>" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Pinterest', 'shopnex' ); ?>">
							<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.236 2.636 7.855 6.356 9.312-.088-.791-.167-2.005.035-2.868.181-.78 1.172-4.97 1.172-4.97s-.299-.598-.299-1.482c0-1.388.806-2.425 1.808-2.425.853 0 1.265.64 1.265 1.408 0 .858-.546 2.14-.828 3.33-.236.995.5 1.807 1.48 1.807 1.778 0 3.144-1.874 3.144-4.58 0-2.393-1.72-4.068-4.177-4.068-2.845 0-4.515 2.135-4.515 4.34 0 .859.331 1.781.745 2.282a.3.3 0 0 1 .069.288l-.278 1.133c-.044.183-.145.222-.335.134-1.249-.581-2.03-2.407-2.03-3.874 0-3.154 2.292-6.052 6.608-6.052 3.469 0 6.165 2.473 6.165 5.776 0 3.447-2.173 6.22-5.19 6.22-1.013 0-1.965-.527-2.291-1.148l-.623 2.378c-.226.869-.835 1.958-1.244 2.621.937.29 1.931.446 2.962.446 5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
						</a>
					<?php endif; ?>

					<?php if ( $youtube_url ) : ?>
						<a href="<?php echo esc_url( $youtube_url ); ?>" class="social-icon" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'YouTube', 'shopnex' ); ?>">
							<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="currentColor" stroke="none"/></svg>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

		<?php
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$heading           = isset( $instance['heading'] ) ? $instance['heading'] : '';
		$subheading        = isset( $instance['subheading'] ) ? $instance['subheading'] : '';
		$logo_id           = isset( $instance['logo_id'] ) ? $instance['logo_id'] : '';
		$logo_width        = isset( $instance['logo_width'] ) ? $instance['logo_width'] : '';
		$facebook_url      = isset( $instance['facebook_url'] ) ? $instance['facebook_url'] : '';
		$twitter_url       = isset( $instance['twitter_url'] ) ? $instance['twitter_url'] : '';
		$instagram_url     = isset( $instance['instagram_url'] ) ? $instance['instagram_url'] : '';
		$pinterest_url     = isset( $instance['pinterest_url'] ) ? $instance['pinterest_url'] : '';
		$youtube_url       = isset( $instance['youtube_url'] ) ? $instance['youtube_url'] : '';
		$cb_enable_heading = isset( $instance['cb_enable_heading'] ) ? (bool) $instance['cb_enable_heading'] : false;
		$cb_show_social    = isset( $instance['cb_show_social'] ) ? (bool) $instance['cb_show_social'] : true;

		$logo_url = $logo_id ? wp_get_attachment_url( absint( $logo_id ) ) : '';

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"><?php esc_html_e( 'Heading:', 'shopnex' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'heading' ) ); ?>" type="text" value="<?php echo esc_attr( $heading ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'subheading' ) ); ?>"><?php esc_html_e( 'Description:', 'shopnex' ); ?></label>
			<textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'subheading' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subheading' ) ); ?>"><?php echo esc_textarea( $subheading ); ?></textarea>
		</p>

		<p>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'cb_enable_heading' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cb_enable_heading' ) ); ?>" value="1" <?php checked( $cb_enable_heading ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cb_enable_heading' ) ); ?>"><?php esc_html_e( 'Show Heading', 'shopnex' ); ?></label>
		</p>

		<hr style="margin:15px 0;border-top:1px solid #ddd">

		<p>
			<strong><?php esc_html_e( 'Footer Logo', 'shopnex' ); ?></strong><br>
			<span class="description" style="font-size:12px;color:#999;display:block;margin:4px 0 8px;"><?php esc_html_e( 'Upload a custom footer logo. No logo is shown until one is uploaded here.', 'shopnex' ); ?></span>
			<span class="shopnex-logo-preview" style="<?php echo $logo_url ? '' : 'display:none;'; ?>margin-bottom:8px;">
				<?php if ( $logo_url ) : ?>
					<img src="<?php echo esc_url( $logo_url ); ?>" style="max-width:100%;height:auto;border:1px solid #ddd;border-radius:4px;padding:4px;">
				<?php endif; ?>
			</span>
			<input type="hidden" class="shopnex-logo-id" id="<?php echo esc_attr( $this->get_field_id( 'logo_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'logo_id' ) ); ?>" value="<?php echo esc_attr( $logo_id ); ?>">
			<button type="button" class="button shopnex-logo-upload" data-frame-title="<?php esc_attr_e( 'Select Footer Logo', 'shopnex' ); ?>" data-frame-btn="<?php esc_attr_e( 'Use as Logo', 'shopnex' ); ?>"><?php esc_html_e( 'Upload Logo', 'shopnex' ); ?></button>
			<button type="button" class="button shopnex-logo-remove" style="<?php echo $logo_url ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Remove', 'shopnex' ); ?></button>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'logo_width' ) ); ?>"><?php esc_html_e( 'Logo Width (px):', 'shopnex' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'logo_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'logo_width' ) ); ?>" type="number" min="40" max="600" step="1" value="<?php echo esc_attr( $logo_width ); ?>" placeholder="<?php esc_attr_e( 'auto', 'shopnex' ); ?>">
			<span class="description" style="font-size:12px;color:#999;display:block;margin:4px 0 0;"><?php esc_html_e( 'Set the maximum width of the footer logo. Leave empty for default size. The logo keeps its aspect ratio.', 'shopnex' ); ?></span>
		</p>

		<hr style="margin:15px 0;border-top:1px solid #ddd">

		<p><strong><?php esc_html_e( 'Social Media Icons', 'shopnex' ); ?></strong></p>

		<p>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'cb_show_social' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cb_show_social' ) ); ?>" value="1" <?php checked( $cb_show_social ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cb_show_social' ) ); ?>"><?php esc_html_e( 'Show Social Icons', 'shopnex' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'facebook_url' ) ); ?>"><?php esc_html_e( 'Facebook URL:', 'shopnex' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook_url' ) ); ?>" type="url" value="<?php echo esc_attr( $facebook_url ); ?>" placeholder="https://facebook.com/...">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_url' ) ); ?>"><?php esc_html_e( 'X (Twitter) URL:', 'shopnex' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_url' ) ); ?>" type="url" value="<?php echo esc_attr( $twitter_url ); ?>" placeholder="https://x.com/...">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'instagram_url' ) ); ?>"><?php esc_html_e( 'Instagram URL:', 'shopnex' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram_url' ) ); ?>" type="url" value="<?php echo esc_attr( $instagram_url ); ?>" placeholder="https://instagram.com/...">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'pinterest_url' ) ); ?>"><?php esc_html_e( 'Pinterest URL:', 'shopnex' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest_url' ) ); ?>" type="url" value="<?php echo esc_attr( $pinterest_url ); ?>" placeholder="https://pinterest.com/...">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'youtube_url' ) ); ?>"><?php esc_html_e( 'YouTube URL:', 'shopnex' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube_url' ) ); ?>" type="url" value="<?php echo esc_attr( $youtube_url ); ?>" placeholder="https://youtube.com/...">
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['heading']           = sanitize_text_field( $new_instance['heading'] );
		$instance['subheading']        = sanitize_textarea_field( $new_instance['subheading'] );
		$instance['logo_id']           = ! empty( $new_instance['logo_id'] ) ? absint( $new_instance['logo_id'] ) : '';
		$instance['logo_width']        = ! empty( $new_instance['logo_width'] ) ? absint( $new_instance['logo_width'] ) : '';
		$instance['facebook_url']      = isset( $new_instance['facebook_url'] ) ? esc_url_raw( $new_instance['facebook_url'] ) : '';
		$instance['twitter_url']       = isset( $new_instance['twitter_url'] ) ? esc_url_raw( $new_instance['twitter_url'] ) : '';
		$instance['instagram_url']     = isset( $new_instance['instagram_url'] ) ? esc_url_raw( $new_instance['instagram_url'] ) : '';
		$instance['pinterest_url']     = isset( $new_instance['pinterest_url'] ) ? esc_url_raw( $new_instance['pinterest_url'] ) : '';
		$instance['youtube_url']       = isset( $new_instance['youtube_url'] ) ? esc_url_raw( $new_instance['youtube_url'] ) : '';
		$instance['cb_enable_heading'] = ! empty( $new_instance['cb_enable_heading'] ) ? true : false;
		$instance['cb_show_social']    = ! empty( $new_instance['cb_show_social'] ) ? true : false;

		return $instance;
	}

}
endif;

if( ! function_exists('shopnex_register_footer_about_widget')) :
/**
 * Register widget.
 */
function shopnex_register_footer_about_widget() {
	register_widget( 'Shopnex_Footer_About_Widget' );
}
endif;

add_action( 'widgets_init', 'shopnex_register_footer_about_widget' );


/**
 * Enqueue media uploader scripts for the widget admin screen.
 */
if ( ! function_exists( 'shopnex_footer_about_widget_admin_scripts' ) ) :
function shopnex_footer_about_widget_admin_scripts( $hook ) {
	if ( 'widgets.php' !== $hook ) {
		return;
	}
	wp_enqueue_media();

	// The shared widget admin script is already enqueued by the announcement
	// bar widget. If this is the only widget loaded, enqueue it here too.
	if ( ! wp_script_is( 'shopnex-widget-admin', 'enqueued' ) ) {
		wp_enqueue_script(
			'shopnex-widget-admin',
			get_template_directory_uri() . '/assets/js/admin/widgets.js',
			array( 'jquery' ),
			wp_get_theme()->get( 'Version' ),
			true
		);


	}
}
endif;
add_action( 'admin_enqueue_scripts', 'shopnex_footer_about_widget_admin_scripts' );
