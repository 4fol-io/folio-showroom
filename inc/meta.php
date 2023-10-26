<?php

/**
 * FolioShowroom custom meta
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Meta;

use FolioShowroom\Data;

// Exit if accessed directly.
defined('ABSPATH') || exit;


/*
 *  Register metadata for posts/pages custom fields
 */
function register_metadata()
{

	register_meta(
		'post',
		'_folio_showroom_hide_title',
		array(
			'type'           => 'boolean',
			'single'         => true,
			'show_in_rest'   => true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);

	register_meta(
		'post',
		'_folio_showroom_hide_meta',
		array(
			'type'           => 'boolean',
			'single'         => true,
			'show_in_rest'   => true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);

	register_meta(
		'post',
		'_folio_showroom_hide_featured_img',
		array(
			'type'           => 'boolean',
			'single'         => true,
			'show_in_rest'   => true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);

	register_meta(
		'post',
		'_folio_showroom_featured',
		array(
			'type'           => 'boolean',
			'single'         => true,
			'show_in_rest'   => true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);

	register_meta(
		'post',
		'_folio_showroom_header_theme',
		array(
			'type'           => 'string',
			'single'         => true,
			'show_in_rest'   => true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);


	register_meta(
		'post',
		'_folio_showroom_header_overlay',
		array(
			'type'           => 'boolean',
			'single'         => true,
			'show_in_rest'   => true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);


	register_meta(
		'post',
		'_folio_showroom_author_fullname',
		array(
			'type'		=> 'string',
			'single'	=> true,
			'show_in_rest'	=> true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);

	register_meta(
		'post',
		'_folio_showroom_author_url',
		array(
			'type'		=> 'string',
			'single'	=> true,
			'show_in_rest'	=> true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);

	register_meta(
		'post',
		'_folio_showroom_author_avatar',
		array(
			'type'		=> 'integer',
			'single'	=> true,
			'show_in_rest'	=> true,
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);

	register_meta(
		'term',
		'_folio_showroom_term_slider',
		array(
			'type'		=> 'string',
			'single'	=> true,
			'show_in_rest'	=> true,
			'sanitize_callback' => 'sanitize_key',
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);

	register_meta(
		'term',
		'_folio_showroom_term_header_theme',
		array(
			'type'           => 'string',
			'single'         => true,
			'show_in_rest'   => true,
			'sanitize_callback' => 'sanitize_key',
			'auth_callback' => function () {
				return current_user_can('edit_posts');
			}
		)
	);
}
add_action('init', __NAMESPACE__ . '\\register_metadata');


add_filter('manage_post_posts_columns', function ($columns) {
	return array_merge($columns, ['featured' => __('Featured', 'folio-showroom')]);
});

add_action('manage_post_posts_custom_column', function ($column_key, $post_id) {
	if ($column_key == 'featured') {
		echo \FolioShowroom\Meta\render_featured_column($post_id);
	}
}, 10, 2);


/**
 * Render featured post status action column
 * 
 * @param int $post_id
 */
function render_featured_column($post_id)
{

	$actions = array();
	$featured = get_post_meta($post_id, '_folio_showroom_featured', true);

	if ($featured != 1) {
		$actions['folio_showroom_featured'] = array(
			'url'    => wp_nonce_url(admin_url('admin-ajax.php?action=folio_showroom_set_featured&featured=1&post_id=' . $post_id), 'folio-showroom-featured'),
			'name'   => __('Non Featured'),
			'icon'   => 'dashicons dashicons-star-empty',
			'action' => 'featured',
			'class'  => 'folio-showroom-featured-off',
			'status' => 0
		);
	} else {
		$actions['folio_showroom_featured'] = array(
			'url'    => wp_nonce_url(admin_url('admin-ajax.php?action=folio_showroom_set_featured&featured=0&post_id=' . $post_id), 'folio-showroom-featured'),
			'name'   => __('Featured'),
			'icon'   => 'dashicons dashicons-star-filled',
			'action' => 'featured',
			'class'  => 'folio-showroom-featured-on',
			'status' => 1
		);
	}
	/**
	 * Filters the featured theme column actions
	 *
	 * @param array  $actions   Actions in fea column
	 * @param int    $post_id    ID of post
	 */
	$actions = (array) apply_filters('folio_showroom_featured_column_actions', $actions, $post_id);

	$actions_html = '';

	foreach ($actions as $action) {
		if (isset($action['action'], $action['url'], $action['name'], $action['icon'])) {
			$actions_html .= sprintf(
				'<a class="btn folio-showroom-action-btn folio-showroom-action-btn-%1$s %5$s" href="%2$s" aria-label="%3$s" title="%3$s" data-status="%6$s"><span class="' . $action['icon'] . '"></span> <span class="screen-reader-text">%4$s</span></a>',
				esc_attr($action['action']),
				esc_url($action['url']),
				esc_attr($action['name']),
				esc_html($action['name']),
				esc_attr($action['class']),
				esc_attr($action['status'])
			);
		}
	}

	/**
	 * Filters the actions html output
	 *
	 * @param string  $actions_html   Actions html output
	 */
	return apply_filters('folio_showroom_featured_column_actions_html', $actions_html);
}


// Ajax actions
add_action('wp_ajax_folio_showroom_set_featured', __NAMESPACE__ . '\\update_featured_status');

/**
 * Ajax request: update post featured status
 */
function update_featured_status()
{

	if (check_admin_referer('folio-showroom-featured') && isset($_GET['featured'], $_GET['post_id'])) {
		$featured  = absint(wp_unslash($_GET['featured']));
		$post_id = absint(wp_unslash($_GET['post_id']));

		update_post_meta($post_id, '_folio_showroom_featured', $featured);
		do_action('folio_showroom_set_featured', $post_id);

		$response = json_encode(array('success' => true, 'status' => $featured));
	} else {
		$response = json_encode(array('success' => false));
	}

	// response output
	header("Content-Type: application/json");
	echo $response;

	wp_die();
}


/**
 * Add extra fields to category/tag
 */
function add_extra_term_fields()
{
	wp_nonce_field(basename(__FILE__), 'folio_showroom_term_meta_nonce');
	$sliders = Data\get_sliders_choices();
	$themes = Data\get_header_theme_choices();
?>
	<div class="form-field folio-showroom-term-field-wrap">
		<label for="folio_showroom_term_slider"><?php _e('Slider', 'folio_showroom'); ?></label>
		<select name="_folio_showroom_term_slider" id="folio_showroom_term_slider" class="folio-showroom-term-field">
			<?php foreach ($sliders as $key => $module) : ?>
				<option value="<?php echo $key ?>"><?php echo $module ?></option>
			<?php endforeach; ?>
		</select>
		<p class="description"><?php _e('Slider revolution module to display for this term page.', 'folio-showroom'); ?></p>
	</div>
	<div class="form-field folio-showroom-term-field-wrap">
		<label for="folio_showroom_term_header_theme"><?php _e('Header style', 'folio_showroom'); ?></label>
		<select name="_folio_showroom_term_header_theme" id="folio_showroom_term_header_theme" class="folio-showroom-term-field">
			<?php foreach ($themes as $key => $theme) : ?>
				<option value="<?php echo $key ?>"><?php echo $theme ?></option>
			<?php endforeach; ?>
		</select>
		<p class="description"><?php _e('Header style for this term page (transparent styles overlay the header over the content).', 'folio-showroom'); ?></p>
	</div>
<?php
}


/**
 * Add extra fields to category/tag
 */
function edit_extra_term_fields($term)
{
	wp_nonce_field(basename(__FILE__), 'folio_showroom_term_meta_nonce');
	$sliders = Data\get_sliders_choices();
	$themes = Data\get_header_theme_choices();
	$slider  = get_term_meta($term->term_id, '_folio_showroom_term_slider', true);
	if (!$slider) $slider = "";
	$theme  = get_term_meta($term->term_id, '_folio_showroom_term_header_theme', true);
	if (!$theme) $theme = "";
?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="folio_showroom_term_slider"><?php _e('Slider', 'folio_showroom'); ?></label></th>
		<td>
			<select name="_folio_showroom_term_slider" id="folio_showroom_term_slider" class="folio-showroom-term-field">
				<?php foreach ($sliders as $key => $value) : ?>
					<option value="<?php echo $key ?>" <?php selected($slider, $key); ?>><?php echo $value ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php _e('Slider revolution module to display for this term.', 'folio-showroom'); ?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="folio_showroom_term_header_theme"><?php _e('Header style', 'folio_showroom'); ?></label></th>
		<td>
			<select name="_folio_showroom_term_header_theme" id="folio_showroom_term_header_theme" class="folio-showroom-term-field">
				<?php foreach ($themes as $key => $value) : ?>
					<option value="<?php echo $key ?>" <?php selected($theme, $key); ?>><?php echo $value ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php _e('Header style for this term page (transparent styles overlay the header over the content).', 'folio-showroom'); ?></p>
		</td>
	</tr>
<?php
}


/**
 * Save term meta extra fields
 */
function save_extra_term_fields($term_id)
{

	// verify the nonce --- remove if you don't care
	if (!isset($_POST['folio_showroom_term_meta_nonce']) || !wp_verify_nonce($_POST['folio_showroom_term_meta_nonce'], basename(__FILE__)))
		return;

	$slider = isset($_POST['_folio_showroom_term_slider']) ? sanitize_key($_POST['_folio_showroom_term_slider']) : '';
	update_term_meta($term_id, '_folio_showroom_term_slider', $slider);

	$theme = isset($_POST['_folio_showroom_term_header_theme']) ? sanitize_key($_POST['_folio_showroom_term_header_theme']) : '';
	update_term_meta($term_id, '_folio_showroom_term_header_theme', $theme);
}


/**
 * Modify term columns
 */
function edit_term_columns($columns)
{

	$showroom_columns = array();
	$column = 'posts';
	$added = false;

	foreach ($columns as $key => $value) {
		if ($key === $column) {
			$showroom_columns['_folio_showroom_term_slider'] = __('Slider', 'folio-showroom');
			$added = true;
		}
		$showroom_columns[$key] = $value;
	}

	if (!$added) {
		$showroom_columns['_folio_showroom_term_slider'] = __('Slider', 'folio-showroom');
	}

	return $showroom_columns;
}

/**
 * Render term custom columms
 */
function manage_term_custom_column($out, $column, $term_id)
{

	if ('_folio_showroom_term_slider' === $column) {

		$sliders = Data\get_sliders_choices();
		$slider  = get_term_meta($term_id, '_folio_showroom_term_slider', true);

		$out = '---';
		if ($slider) {
			$out = array_key_exists($slider, $sliders) ? $sliders[$slider] : '---';
		}
	}

	return $out;
}



/**
 * Custom term meta options only if Slider Revolution is enabled
 */
function term_meta_hooks()
{

	if (class_exists('RevSlider')) {

		add_action('category_add_form_fields', __NAMESPACE__ . '\\add_extra_term_fields');
		add_action('post_tag_add_form_fields', __NAMESPACE__ . '\\add_extra_term_fields');

		add_action('category_edit_form_fields', __NAMESPACE__ . '\\edit_extra_term_fields');
		add_action('post_tag_edit_form_fields', __NAMESPACE__ . '\\edit_extra_term_fields');

		add_action('edit_category',   __NAMESPACE__ . '\\save_extra_term_fields');
		add_action('create_category', __NAMESPACE__ . '\\save_extra_term_fields');
		add_action('edit_post_tag',   __NAMESPACE__ . '\\save_extra_term_fields');
		add_action('create_post_tag', __NAMESPACE__ . '\\save_extra_term_fields');

		add_filter('manage_edit-category_columns',  __NAMESPACE__ . '\\edit_term_columns');
		add_filter('manage_edit-post_tag_columns',  __NAMESPACE__ . '\\edit_term_columns');

		add_filter('manage_category_custom_column', __NAMESPACE__ . '\\manage_term_custom_column', 10, 3);
		add_filter('manage_post_tag_custom_column', __NAMESPACE__ . '\\manage_term_custom_column', 10, 3);
	}
}
add_action('init',  __NAMESPACE__ . '\\term_meta_hooks');
