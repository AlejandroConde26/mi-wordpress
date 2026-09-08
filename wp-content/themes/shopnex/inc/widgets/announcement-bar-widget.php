<?php
/**
 * Announcement Bar Widget.
 *
 *
 * @package shopnex
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Shopnex_Announcement_Bar_Widget' ) ) :

class Shopnex_Announcement_Bar_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'Shopnex_Announcement_Bar_Widget',
			esc_html__( 'Shopnex: Announcement Bar', 'shopnex' ),
			array(
				'description' => esc_html__( 'Add multiple announcements displayed in a centered bar with dot separators.', 'shopnex' ),
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$announcements = ! empty( $instance['announcements'] ) ? $instance['announcements'] : array();
		$bar_bg_color  = ! empty( $instance['bar_bg_color'] ) ? sanitize_hex_color( $instance['bar_bg_color'] ) : '';
		$text_color    = ! empty( $instance['text_color'] ) ? sanitize_hex_color( $instance['text_color'] ) : '';

		// Filter out empty announcements.
		$announcements = array_filter( $announcements, function( $item ) {
			return ! empty( $item['text'] );
		});

		if ( empty( $announcements ) ) {
			return;
		}

		// Re-index array after filter.
		$announcements = array_values( $announcements );

		$bg_style   = $bar_bg_color ? 'background-color:' . esc_attr( $bar_bg_color ) . ';' : '';
		$text_style = $text_color ? 'color:' . esc_attr( $text_color ) . ';' : '';

		?>
		<div class="shopnex-announcement-bar-widget"<?php echo $bg_style ? ' style="' . esc_attr( $bg_style ) . '"' : ''; ?>>
			<div class="shopnex-announcement-content"<?php echo $text_style ? ' style="' . esc_attr( $text_style ) . '"' : ''; ?>>
				<?php
				$total = count( $announcements );
				foreach ( $announcements as $index => $announcement ) :
				?>
					<span class="shopnex-announcement-item">
						<span class="shopnex-announcement-text"><?php echo esc_html( $announcement['text'] ); ?></span>
					</span>
					<?php if ( $index < $total - 1 ) : ?>
						<span class="shopnex-announcement-dot"></span>
					<?php endif; ?>
				<?php
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$announcements = ! empty( $instance['announcements'] ) ? $instance['announcements'] : array();
		$bar_bg_color  = ! empty( $instance['bar_bg_color'] ) ? $instance['bar_bg_color'] : '#1C1C1C';
		$text_color    = ! empty( $instance['text_color'] ) ? $instance['text_color'] : '#FBF9F6';

		// Ensure at least one empty announcement.
		if ( empty( $announcements ) ) {
			$announcements = array(
				array(
					'text' => '',
				),
			);
		}

		?>
		<div class="shopnex-announcement-widget-form">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'bar_bg_color' ) ); ?>"><?php esc_html_e( 'Background Color:', 'shopnex' ); ?></label>
				<input type="color" id="<?php echo esc_attr( $this->get_field_id( 'bar_bg_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bar_bg_color' ) ); ?>" value="<?php echo esc_attr( $bar_bg_color ); ?>" class="shopnex-announcement-color-picker">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>"><?php esc_html_e( 'Text Color:', 'shopnex' ); ?></label>
				<input type="color" id="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text_color' ) ); ?>" value="<?php echo esc_attr( $text_color ); ?>" class="shopnex-announcement-color-picker">
			</p>

			<hr style="margin:15px 0;border-top:1px solid #ddd">
			<p><strong><?php esc_html_e( 'Announcements', 'shopnex' ); ?></strong></p>

			<div class="shopnex-announcement-items">
				<?php foreach ( $announcements as $i => $announcement ) : ?>
				<div class="shopnex-announcement-item-field">
					<p>
						<label><?php esc_html_e( 'Text:', 'shopnex' ); ?></label>
						<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'announcements' ) ); ?>[<?php echo esc_attr( $i ); ?>][text]" value="<?php echo esc_attr( isset( $announcement['text'] ) ? $announcement['text'] : '' ); ?>" class="widefat shopnex-announcement-text-input" placeholder="<?php esc_attr_e( 'e.g. Free shipping on orders over $200', 'shopnex' ); ?>">
					</p>
					<button type="button" class="button shopnex-announcement-remove-btn" style="margin-bottom:10px;color:#d63639;"><?php esc_html_e( '✕ Remove', 'shopnex' ); ?></button>
					<hr style="margin:10px 0;border-top:1px solid #eee">
				</div>
				<?php endforeach; ?>
			</div>

			<button type="button" class="button shopnex-announcement-add-btn"><?php esc_html_e( '+ Add Announcement', 'shopnex' ); ?></button>
		</div>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['bar_bg_color'] = ! empty( $new_instance['bar_bg_color'] ) ? sanitize_hex_color( $new_instance['bar_bg_color'] ) : '#1C1C1C';
		$instance['text_color']   = ! empty( $new_instance['text_color'] ) ? sanitize_hex_color( $new_instance['text_color'] ) : '#FBF9F6';

		$instance['announcements'] = array();

		if ( ! empty( $new_instance['announcements'] ) && is_array( $new_instance['announcements'] ) ) {
			foreach ( $new_instance['announcements'] as $announcement ) {
				$text = isset( $announcement['text'] ) ? sanitize_text_field( $announcement['text'] ) : '';

				if ( ! empty( $text ) ) {
					$instance['announcements'][] = array(
						'text' => $text,
					);
				}
			}
		}

		return $instance;
	}
}
endif;

if ( ! function_exists( 'shopnex_register_announcement_bar_widget' ) ) :
/**
 * Register the Announcement Bar widget.
 */
function shopnex_register_announcement_bar_widget() {
	register_widget( 'Shopnex_Announcement_Bar_Widget' );
}
endif;
add_action( 'widgets_init', 'shopnex_register_announcement_bar_widget' );

/**
 * Enqueue admin scripts for the Announcement Bar widget.
 */
if ( ! function_exists( 'shopnex_announcement_bar_widget_admin_scripts' ) ) :
function shopnex_announcement_bar_widget_admin_scripts( $hook ) {
	if ( 'widgets.php' !== $hook ) {
		return;
	}

	wp_enqueue_script(
		'shopnex-widget-admin',
		get_template_directory_uri() . '/assets/js/admin/widgets.js',
		array( 'jquery' ),
		wp_get_theme()->get( 'Version' ),
		true
	);


}
endif;
add_action( 'admin_enqueue_scripts', 'shopnex_announcement_bar_widget_admin_scripts' );
