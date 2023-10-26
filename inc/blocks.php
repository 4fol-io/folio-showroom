<?php

/**
 * FolioShowroom Gutenberg blocks utilities
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Blocks;

// Exit if accessed directly.
defined('ABSPATH') || exit;


/**
 * Applies wrapper div around aligned blocks (deprecated)
 * ONLY when using bootstrap .container to wide and full alignment 
 *
 *
 * @see   https://developer.wordpress.org/reference/hooks/render_block/
 * @link  https://codepen.io/webmandesign/post/gutenberg-full-width-alignment-in-wordpress-themes
 *
 * @param  string $block_content  The block content about to be appended.
 * @param  array  $block          The full block, including name and attributes.
 */
function wrap_alignment($block_content, $block)
{
	if (isset($block['attrs']['align']) && in_array($block['attrs']['align'], array('wide', 'full'))) {
		$block_content = sprintf(
			'<div class="%1$s">%2$s</div>',
			'align-wrap align-wrap-' . esc_attr($block['attrs']['align']),
			$block_content
		);
	}
	return $block_content;
}

//add_filter( 'render_block',  __NAMESPACE__ . '\\wrap_alignment', 10, 2 );


/**
 * Inject class column count
 * 
 * @param string 	$content 		The block content about to be appended.
 * @param array 	$block 			The full block, including name and attributes
 * @return $content;
 */
function inject_class_column_count($content, $block)
{
	if (!is_block_type($block, "core/columns")) {
		return $content;
	} else {
		$column_count = array_column($block['innerBlocks'], 'blockName');
		$modified_content = str_replace('wp-block-columns', 'wp-block-columns has-' . count($column_count) . '-columns', $content);
		return $modified_content;
	}
}

// Adjust columns rendering classes
add_filter('render_block', __NAMESPACE__ . '\\inject_class_column_count', 10, 2);



/**
 * Move the align* class from the figure to the wrapper in the image block.
 * Adds external wrapper to correct alignment
 *
 * @param string $block_content The block html.
 * @param array  $block         Information about the block being rendered.
 * @return string
 */
function move_image_align($block_content, $block)
{

	// Quit if this is not an image block.
	if ('core/image' !== $block['blockName']) {
		return $block_content;
	}

	// The classes we want to move.
	$classes = array('alignleft', 'alignright', 'aligncenter');

	foreach ($classes as $className) {

		// If the block contains one of the classes then move it around.
		if (false !== strpos($block_content, $className)) {

			// Remove the existing class name.
			$block_content = str_replace($className, '', $block_content);

			// Add the classname back on the wrapper.
			$block_content = str_replace('wp-block-image', 'wp-block-image ' . $className, $block_content);

			/**
			 * Remove any empty classes that may have been created.
			 * Not essential but nice to do.
			 */
			$block_content = str_replace('class=""', '', $block_content);

			$block_content = sprintf(
				'<div class="%1$s">%2$s</div>',
				'wp-block-image-wrap',
				$block_content
			);
		}
	}

	return $block_content;
}

add_filter('render_block',  __NAMESPACE__ . '\\move_image_align', 10, 2);

/**
 * block type aux function
 *
 * @param array $block 		A WordPress block array
 * @param string $type 		The block name being queried
 * @return bool;
 */
function is_block_type($block, $type)
{
	if ($type === $block['blockName']) {
		return true;
	}
	return false;
}
