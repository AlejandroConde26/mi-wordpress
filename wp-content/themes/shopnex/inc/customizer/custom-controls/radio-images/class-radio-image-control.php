<?php
/**
 * Radio Image Customizer Control
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

// Exit if Shopnex_Radio_Image_Control already exists or WP_Customize_Control does not exist.
if ( class_exists('Shopnex_Radio_Image_Control') || ! class_exists( 'WP_Customize_Control' ) ) :
	return;
endif;

/**
 * This class is for the radio image control in the Customizer.
 *
 * @access public
 */
class Shopnex_Radio_Image_Control extends WP_Customize_Control {
	
	/**
	 * Declare the control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'shopnex-radio-image';
	
	/**
	 * Enqueue scripts and styles.
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue() {
	       wp_enqueue_script( 'jquery-ui-button' );
	       wp_enqueue_style( 'shopnex-radio-image-control-css', get_parent_theme_file_uri( 'inc/customizer/custom-controls/radio-images/radio-image.css' ), array(), '1.0', 'all' );
	}
	
	/**
	 * Render the control to be displayed in the Customizer.
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) :
			return;
		endif;
		
		$name = '_customize-radio-' . $this->id;
		?>
		<span class="customize-control-title">
			<?php echo esc_html( $this->label ); ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
		</span>
		<div id="input_<?php echo esc_attr( $this->id ); ?>" class="image">
			<?php foreach ( $this->choices as $value => $label ) : ?>
				<input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo esc_attr( $this->id . $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
					<label for="<?php echo esc_attr( $this->id . $value ); ?>">
						<img src="<?php echo esc_url( $label ); ?>" alt="<?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $value ); ?>">
					</label>
				</input>
			<?php endforeach; ?>
		</div>
		<script>jQuery(document).ready(function($) { $( '[id="input_<?php echo esc_attr($this->id); ?>"]' ).buttonset(); });</script>
		<?php
	}
}
	
