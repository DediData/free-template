<?php
/**
 * Add controls for arbitrary heading, description, line
 *
 * @package     Customizer_Library
 * @author      Devin Price
 */

declare(strict_types=1);

namespace FreeTemplate;

if ( ! class_exists( '\WP_Customize_Control' ) ) {
	return NULL;
}

class Customizer_Library_Content extends \WP_Customize_Control {

	/**
	 * Render the control's content.
     *
     * Allows the content to be overriden without having to rewrite the wrapper.
     *
     * @since   1.0.0
     * @return  void
     */
    public function render_content() {

        switch ( $this->type ) {

            case 'content' :

				if ( isset( $this->label ) ) {
					echo '<span class="customize-control-title">' . $this->label . '</span>';
				}

				if ( isset( $this->input_attrs['content'] ) ) {
					echo $this->input_attrs['content'];
				}

				if ( isset( $this->description ) ) {
					echo '<span class="description customize-control-description">' . $this->description . '</span>';
				}

				if ( isset( $this->input_attrs['divider'] ) ) {
					echo '<hr>';
				}

                break;

            case 'divider' :
                echo '<hr>';
                break;

        }

    }

}