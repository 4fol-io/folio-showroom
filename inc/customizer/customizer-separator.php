<?php
/**
 * Separator Customizer Control
 */

namespace FolioShowroom\Customizer;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if WP_Customize_Control does not exsist.
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

// Exit if class exsist.
if ( class_exists( 'Separator_Custom_Control' ) ) {
	return null;

}


class Separator_Custom_control extends \WP_Customize_Control{
      public $type = 'separator';

      // Render the control's content.
      public function render_content(){
        echo '<hr class="custom-separator">';
      } 
}