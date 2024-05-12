<?php
/**
 * Add controls for arbitrary heading, description, line
 *
 * @package     Customizer_Library
 */

declare(strict_types=1);

namespace FreeTemplate;

/**
 * Extends the WP_Customize_Control class for customizing content.
 */
final class Customizer_Library_Content extends \WP_Customize_Control {

	/**
	 * Render the control's content.
	 *
	 * Allows the content to be overridden without having to rewrite the wrapper.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function render_content() {
		switch ( $this->type ) {
			case 'content':
				if ( isset( $this->label ) ) {
					echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
				}
				if ( isset( $this->input_attrs['content'] ) ) {
					echo esc_html( $this->input_attrs['content'] );
				}
				if ( isset( $this->description ) ) {
					echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
				}
				if ( isset( $this->input_attrs['divider'] ) ) {
					echo '<hr>';
				}
				break;
			case 'divider':
				echo '<hr>';
				break;
			default:
				// No Default value
				break;
		}//end switch
	}
}
