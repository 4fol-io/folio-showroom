<?php

/**
 * FolioShowroom image sizes & image customizations
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Images;

// Exit if accessed directly.
defined('ABSPATH') || exit;


/**
 * Unset default image sizes
 */
function turn_off_default_sizes($default_image_sizes)
{
	//unset( $default_image_sizes['thumbnail']); // turn off thumbnail size
	//unset( $default_image_sizes['medium']); // turn off medium size
	//unset( $default_image_sizes['large']); // turn off large size
	return $default_image_sizes;
}
// add_filter('intermediate_image_sizes', __NAMESPACE__ . '\\turn_off_default_sizes');

/**
 * Add custom image sizes to admin
 */
function add_image_size_names($sizes)
{
	return array_merge($sizes, array(
		'folio-showroom-featured' => __('Featured', 'folio-showroom'),
	));
}
add_filter('image_size_names_choose', __NAMESPACE__ . '\\add_image_size_names');


/**
 * Increase the image size threshold
 */
function increase_image_size_threshold($threshold)
{
	return 2560; // new threshold
}
// add_filter( 'big_image_size_threshold', __NAMESPACE__ . '\\increase_image_size_threshold' );



/**
 * Auto featured image from older posts before post is displayed on the site front-end
 */
function auto_set_featured_image($post)
{
	global $post;
	$has_thumb  		= has_post_thumbnail($post->ID);
	$post_type         	= get_post_type($post->ID);
	$exclude_types     	= array('');

	if ($has_thumb) {
		return;
	}

	if (!in_array($post_type, $exclude_types, true)) {
		$attached_image = get_children("order=ASC&post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1");
		if ($attached_image) {
			foreach ($attached_image as $attachment_id => $attachment) {
				set_post_thumbnail($post->ID, $attachment_id);
			}
		}
	}
}
// add_action('the_post',  __NAMESPACE__ . '\\auto_set_featured_image');


/**
 * Catch first image from content when not featured image set
 */
function catch_that_image()
{
	global $post;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match('/<img.+?src=[\'"]([^\'"]+)[\'"].*?>/i', $post->post_content, $matches);
	if ($output) {
		$first_img = $matches[1];
	}
	return $first_img;
}



/**
 * Responsive Image Helper Function
 *
 * @param string $img_id the id of the image
 * @param string $image_src fallback size
 * @param string $image_srcset the max size of the image
 * @param string $max_width the max width this image will be shown to build the sizes attribute
 * @param string $alt alt image title
 * @param boolean $lazy lazy loading (for swiper)
 * @param string $fancybox gallery name or false
 */
function get_responsive_image($img_id, $src_size, $srcset_size, $max_width = false, $class = 'img-fluid', $caption = '', $lazy = false)
{


	if ($img_id != '') {
		$img_src = wp_get_attachment_image_url($img_id, $src_size);
		$img_srcset = wp_get_attachment_image_srcset($img_id, $srcset_size);
		$attr = wp_get_attachment_image_src($img_id, $srcset_size);
		$width = 'auto';
		$height = 'auto';
		$prefix = 'src';
		$title = '';

		if ($attr) {
			$width = $attr[1];
			$height = $attr[2];
		}

		if ($lazy) {
			$prefix = 'data-src';
			$class .= ' swiper-lazy';
		}

		if (!$caption) {
			$caption = wp_get_attachment_caption($img_id);
		}
		if (!$caption) $caption = '';


		$sizes = $max_width ? '(max-width: ' . $max_width . ') 100vw, calc(100vw - 30px)' : 'calc(100vw - 30px)';

		$item =  '<img class="' . $class . '" ' . $prefix . '="' . $img_src . '" ' . $prefix . 'set="' . $img_srcset . '" sizes="' . $sizes . '" alt="" title="' . esc_attr($caption) . '" width="' . $width . '" height="' . $height . '">';

		return $item;
	}
	return '';
}


/**
 * Aux method to get attachment id from image src
 *
 * Usage:
 * $attachmentid = get_attachment_id_from_src ($slide['img']);
 * $custom = wp_get_attachment_image_src( $attachmentid, 'orbit-custom' );
 * <img src="' <?php echo $custom[0] ?>" />
 */
function get_attachment_id_from_src($image_src)
{
	global $wpdb;

	if (!$image_src)
		return false;

	/*$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	$id = $wpdb->get_var($query);*/

	$id = $wpdb->query($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE `guid` = %s", $image_src));

	return $id;
}
