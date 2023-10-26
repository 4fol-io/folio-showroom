<?php

/**
 * Range Customizer Control
 */

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


class Range_Custom_Control extends \WP_Customize_Control
{

	public $type = 'range-value';

	/**
	 * Enqueue scripts/styles.
	 */
	public function enqueue()
	{
		wp_enqueue_style('folio-showroom-customizer-range', get_parent_theme_file_uri('inc/customizer/css/customizer-range.css'), false, '1.0.0', 'all');
		wp_enqueue_script('folio-showroom-customizer-range', get_parent_theme_file_uri('inc/customizer/js/customizer-range.js'), array('jquery'), '1.0.0', true);
	}

	/**
	 * Render the control's content.
	 */
	public function render_content()
	{
?>
		<label>
			<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
			<div class="range-slider">
				<input class="range-slider__range" type="range" value="<?php echo esc_attr($this->value()); ?>" <?php
																													$this->input_attrs();
																													$this->link();
																													?>>
				<span class="range-slider__value"><?php echo  $this->value(); ?></span>
			</div>
			<?php if (!empty($this->description)) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
<?php
	}
}
