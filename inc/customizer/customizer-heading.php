<?php

namespace FolioShowroom\Customizer;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Exit if WP_Customize_Control does not exsist.
if (!class_exists('WP_Customize_Control')) {
    return null;
}

// Exit if class exsist.
if (class_exists('Range_Custom_Control')) {
    return null;
}


class Sub_Section_Heading_Custom_Control extends \WP_Customize_Control {

    //The type of control being rendered
    public $type = 'sub_section_heading';

    //Render the control in the customizer
    public function render_content() {
    ?>
        <div class="sub-section-heading-control">
            <?php if (!empty($this->label)) { ?>
                <h4 class="customize-control-title">
                    <?php echo esc_html($this->label); ?>
                </h4>
            <?php } ?>
            <?php if (!empty($this->description)) { ?>
                <p class="description" style="margin-bottom:0">
                    <?php echo esc_html($this->description); ?>
                </p>
            <?php } ?>
        </div>
    <?php
    }
}
