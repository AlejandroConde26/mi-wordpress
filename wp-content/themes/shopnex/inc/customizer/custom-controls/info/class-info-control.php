<?php
/**
 * Info Customizer Control
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

// Exit if Shopnex_Info_Control already exists or WP_Customize_Control does not exist.
if ( class_exists('Shopnex_Info_Control') || ! class_exists( 'WP_Customize_Control' ) ) :
	return;
endif;

/**
 * This class is used for showing the extra information about any control in the Customizer.
 *
 * @access public
 */
class Shopnex_Info_Control extends WP_Customize_Control {

	/**
	 * The type of customize control.
	 *
	 * @access public
	 * @since  1.3.4
	 * @var    string
	 */
	public $type = 'shopnex-info';


	/**
	 *  Render the content via PHP.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function render_content() {
		?>
			<p class="customizer-custom-info-text">
				<?php echo esc_html( $this->label ); ?>
			</p>
		<?php
	}
}
