<?php

/**
 * FolioShowroom customizer
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Customizer;

use FolioShowroom\Assets\AssetResolver;
use FolioShowroom\Utils;
use FolioShowroom\Data;

// Exit if accessed directly.
defined('ABSPATH') || exit;


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function customize_register($wp_customize)
{

	// Theme Options prefix
	$prefix = FOLIO_SHOWROOM_PREFIX;

	require_once get_parent_theme_file_path('inc/customizer/customizer-range.php');
	require_once get_parent_theme_file_path('inc/customizer/customizer-separator.php');
	require_once get_parent_theme_file_path('inc/customizer/customizer-toggle.php');
	require_once get_parent_theme_file_path('inc/customizer/customizer-heading.php');

	// Register the toggle custom control
	$wp_customize->register_control_type('FolioShowroom\Customizer\Toggle_Custom_Control');
	//$wp_customize->register_control_type('FolioShowroom\Customizer\Range_Custom_Control');

	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';

	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->selective_refresh->add_partial('blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'FolioShowroom\Customizer\customize_partial_blogname',
		));
		$wp_customize->selective_refresh->add_partial('blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'FolioShowroom\Customizer\customize_partial_blogdescription',
		));
		$wp_customize->selective_refresh->add_partial('siteheaderblur', array(
			'selector'        => '.site-header',
			'settings' 		  => array('folio_showroom_header_blur'),
			'render_callback' => 'FolioShowroom\Customizer\customize_partial_site_header_blur',
		));
	}

	/* SECTION HOMEPAGE */

	$wp_customize->add_setting('folio_showroom_home_slider', array(
		'default' 		=> '',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_select',
		'transport' => 'postMessage'
	));

	$wp_customize->add_control('folio_showroom_home_slider', array(
		'label' => __('Home Slider', 'folio-showroom'),
		'section' => 'static_front_page',
		'type' => 'select',
		'choices' => Data\get_sliders_choices(),
		'active_callback' => 'FolioShowroom\Customizer\show_on_front_active_callback',
	));


	$wp_customize->add_setting('folio_showroom_home_title', array(
		'default' 			=> Data\get_default_option('home_title'),
		'sanitize_callback' => 'wp_kses',
	));

	$wp_customize->add_control('folio_showroom_home_title', array(
		'type' 			=> 'text',
		'section' 		=> 'static_front_page',
		'label' 		=> __('Home title', 'folio-showroom'),
		'description'	=> __('Accepted placeholders:', 'folio-showroom') . ' [site_title]',
		'active_callback' => 'FolioShowroom\Customizer\show_on_front_active_callback',
	));


	/* UOC TOOLS */

	if (!function_exists('is_plugin_active')) {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
	}

	$active_create_site = \is_plugin_active_for_network('portafolis-create-site/portafolis-create-site.php') || \is_plugin_active('portafolis-create-site/portafolis-create-site.php');
	$active_uoc_access = \is_plugin_active_for_network('portafolis-uoc-access/portafolis-uoc-access.php') || \is_plugin_active('portafolis-uoc-access/portafolis-uoc-access.php');

	if ($active_create_site || $active_uoc_access) {

		$wp_customize->add_section('folio-showroom-uoc', array(
			'title' 		=> __('UOC Settings', 'folio-showroom'),
			'capability' 	=> 'edit_theme_options',
			'priority'		=> 1
		));

		$wp_customize->add_setting('folio_showroom_secc_uoc_heading', array()); // dummy

		$wp_customize->add_control(new Sub_Section_Heading_Custom_Control(
			$wp_customize,
			'folio_showroom_secc_uoc_heading',
			array(
				'label'   => __('UOC Tools Settings', 'folio-showroom'),
				'section' => 'folio-showroom-uoc',
			)
		));

		if ($active_create_site) {
			$wp_customize->add_setting('folio_showroom_uoc_foot_show', array(
				'default' 		=> Data\get_default_option('uoc_foot_show'),
				//'transport'         => 'postMessage',
				'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
			));

			$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_uoc_foot_show', array(
				'label'       => esc_html__('Display UOC Footer', 'folio-showroom'),
				'section'     => 'folio-showroom-uoc',
				'type'        => 'toggle',
			)));
		}

		if ($active_uoc_access) {

			$wp_customize->add_setting('folio_showroom_uoc_tools_show', array(
				'default' 		=>  Data\get_default_option('uoc_tools_show'),
				//'transport'         => 'postMessage',
				'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
			));

			$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_uoc_tools_show', array(
				'label'       => esc_html__('Display UOC Tools', 'folio-showroom'),
				'section'     => 'folio-showroom-uoc',
				'type'        => 'toggle',
			)));
		}
	}



	/* COLOR LAYOUT */


	$wp_customize->add_section('folio-showroom-color', array(
		'title' 		=> __('Color Settings', 'folio-showroom'),
		'capability' 	=> 'edit_theme_options',
		'priority'		=> 1
	));

	$wp_customize->add_setting('folio_showroom_color_mode', array(
		'default' 		=> Data\get_default_option('color_mode'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_select',
	));

	$wp_customize->add_control('folio_showroom_color_mode', array(
		'label' => __('Color mode', 'folio-showroom'),
		'section' => 'folio-showroom-color',
		'type' => 'select',
		'choices' => array(
			'' 				=> __('Default', 'folio-showroom'),
			'light' 		=> __(
				'Light',
				'folio-showroom'
			),
			'dark' 			=> __('Dark', 'folio-showroom')
		)
	));

	// Add Settings Light Mode 
	$wp_customize->add_setting('folio_showroom_primary_color', array(
		'default' => Data\get_default_option('primary_color'),
	));

	$wp_customize->add_setting('folio_showroom_secondary_color', array(
		'default' => Data\get_default_option('secondary_color'),
	));


	$wp_customize->add_setting('folio_showroom_links_color', array(
		'default' => Data\get_default_option('links_color'),
	));


	// Add Settings Dark mode
	$wp_customize->add_setting('folio_showroom_primary_color_dark', array(
		'default' => Data\get_default_option('primary_color_dark'),
	));

	$wp_customize->add_setting('folio_showroom_secondary_color_dark', array(
		'default' => Data\get_default_option('secondary_color_dark'),
	));


	$wp_customize->add_setting('folio_showroom_links_color_dark', array(
		'default' => Data\get_default_option('links_color_dark'),
	));


	// Add Controls Light

	$wp_customize->add_setting('folio_showroom_secc_color_light', array()); // dummy

	$wp_customize->add_control(new Sub_Section_Heading_Custom_Control(
		$wp_customize,
		'folio_showroom_secc_color_light',
		array(
			'label'   => __('Light mode', 'folio-showroom'),
			'section' => 'folio-showroom-color',
		)
	));

	$wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'folio_showroom_primary_color', array(
		'label' => __('Primary Color', 'folio-showroom'),
		'section' => 'folio-showroom-color',
		'settings' => 'folio_showroom_primary_color'

	)));

	$wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'folio_showroom_secondary_color', array(
		'label' => __('Secondary Color', 'folio-showroom'),
		'section' => 'folio-showroom-color',
		'settings' => 'folio_showroom_secondary_color'
	)));

	$wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'folio_showroom_links_color', array(
		'label' => __('Links Color', 'folio-showroom'),
		'section' => 'folio-showroom-color',
		'settings' => 'folio_showroom_links_color'
	)));


	$wp_customize->add_setting('folio_showroom_secc_color_dark', array()); // dummy

	$wp_customize->add_control(new Sub_Section_Heading_Custom_Control(
		$wp_customize,
		'folio_showroom_secc_color_dark',
		array(
			'label'   => __('Dark mode', 'folio-showroom'),
			'section' => 'folio-showroom-color',
		)
	));


	// Add Controls Dark
	$wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'folio_showroom_primary_color_dark', array(
		'label' => __('Primary Color', 'folio-showroom'),
		'section' => 'folio-showroom-color',
		'settings' => 'folio_showroom_primary_color_dark'

	)));

	$wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'folio_showroom_secondary_color_dark', array(
		'label' => __('Secondary Color', 'folio-showroom'),
		'section' => 'folio-showroom-color',
		'settings' => 'folio_showroom_secondary_color_dark'
	)));

	$wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'folio_showroom_links_color_dark', array(
		'label' => __('Links Color', 'folio-showroom'),
		'section' => 'folio-showroom-color',
		'settings' => 'folio_showroom_links_color_dark'
	)));


	/* SECTION HEADER */

	$wp_customize->add_section('folio-showroom-header', array(
		'title' 		=> __('Header Settings', 'folio-showroom'),
		'capability' 	=> 'edit_theme_options',
		'priority'		=> 1
	));

	$wp_customize->add_setting('folio_showroom_secc_header_default', array()); // dummy

	$wp_customize->add_control(new Sub_Section_Heading_Custom_Control(
		$wp_customize,
		'folio_showroom_secc_header_default',
		array(
			'label'   => __('Default header style', 'folio-showroom'),
			'section' => 'folio-showroom-header',
		)
	));

	$wp_customize->add_setting('folio_showroom_header_theme', array(
		'default' 		=> Data\get_default_option('header_theme'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_select',
	));

	$wp_customize->add_control('folio_showroom_header_theme', array(
		'label' => __('Header style', 'folio-showroom'),
		'section' => 'folio-showroom-header',
		'type' => 'select',
		'choices' => Data\get_header_theme_choices()
	));

	$wp_customize->add_setting('folio_showroom_header_sticky', array(
		'default' 		=> Data\get_default_option('header_sticky'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_setting('folio_showroom_header_overlay', array(
		'default' 		=> Data\get_default_option('header_overlay'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_header_overlay', array(
		'label'       => esc_html__('Header overlapping?', 'folio-showroom'),
		'section'     => 'folio-showroom-header',
		'type'        => 'toggle',
	)));

	$wp_customize->add_setting('folio_showroom_secc_header_home', array()); // dummy

	$wp_customize->add_control(new Sub_Section_Heading_Custom_Control(
		$wp_customize,
		'folio_showroom_secc_header_home',
		array(
			'label'   => __('Home header style (latest posts)', 'folio-showroom'),
			'section' => 'folio-showroom-header',
		)
	));

	$wp_customize->add_setting('folio_showroom_home_header_theme', array(
		'default' 		=> Data\get_default_option('home_header_theme'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_select',
	));

	$wp_customize->add_control('folio_showroom_home_header_theme', array(
		'label' => __('Header style', 'folio-showroom'),
		'section' => 'folio-showroom-header',
		'type' => 'select',
		'choices' => Data\get_header_theme_choices(),
	));

	$wp_customize->add_setting('folio_showroom_home_header_overlay', array(
		'default' 		=> Data\get_default_option('header_home_overlay'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_home_header_overlay', array(
		'label'       => esc_html__('Header overlapping?', 'folio-showroom'),
		'section'     => 'folio-showroom-header',
		'type'        => 'toggle',
	)));


	$wp_customize->add_setting('folio_showroom_secc_header_settings', array()); // dummy

	$wp_customize->add_control(new Sub_Section_Heading_Custom_Control(
		$wp_customize,
		'folio_showroom_secc_header_settings',
		array(
			'label'   => __('Header global settings', 'folio-showroom'),
			'section' => 'folio-showroom-header',
		)
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_header_sticky', array(
		'label'       => esc_html__('Sticky header?', 'folio-showroom'),
		'section'     => 'folio-showroom-header',
		'type'        => 'toggle',
	)));


	$wp_customize->add_setting('folio_showroom_color_switcher', array(
		'default' 		=> Data\get_default_option('color_switcher'),
		//'transport'         => 'postMessage',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));


	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_color_switcher', array(
		'label'       => esc_html__('Enable color mode Switcher?', 'folio-showroom'),
		'section'     => 'folio-showroom-header',
		'type'        => 'toggle',
	)));


	$wp_customize->add_setting('folio_showroom_header_search_show', array(
		'default' 		=> Data\get_default_option('header_search_show'),
		//'transport'         => 'postMessage',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_header_search_show', array(
		'label'       => esc_html__('Display search button?', 'folio-showroom'),
		'section'     => 'folio-showroom-header',
		'type'        => 'toggle',
	)));


	$wp_customize->add_setting('folio_showroom_lang_show', array(
		'default' 		=> Data\get_default_option('lang_show'),
		//'transport'         => 'postMessage',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_lang_show', array(
		'label'       => esc_html__('Use theme WPML Lang Menu?', 'folio-showroom'),
		'section'     => 'folio-showroom-header',
		'type'        => 'toggle',
	)));


	$wp_customize->add_setting('folio_showroom_separator_2');
	$wp_customize->add_control(new Separator_Custom_control($wp_customize, 'folio_showroom_separator_2', array(
		'type'		=> 'separator',
		'section' => 'folio-showroom-header',
	)));


	$wp_customize->add_setting('folio_showroom_header_width', array(
		'default' 		=> Data\get_default_option('header_width'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_select',
	));

	$wp_customize->add_control('folio_showroom_header_width', array(
		'label' => __('Header width', 'folio-showroom'),
		'section' => 'folio-showroom-header',
		'type' => 'select',
		'choices' => Data\get_layout_width_choices(),
	));


	$wp_customize->add_setting('folio_showroom_header_font', array(
		'default' 		=> Data\get_default_option('header_font'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_select',
	));

	$wp_customize->add_control('folio_showroom_header_font', array(
		'label' => __('Header menu font family', 'folio-showroom'),
		'section' => 'folio-showroom-header',
		'type' => 'select',
		'choices' => Data\get_font_choices()
	));

	$wp_customize->add_setting('folio_showroom_header_font_size', array(
		'default' 		=> Data\get_default_option('header_font_size'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_float',
	));

	$wp_customize->add_control(new Range_Custom_Control($wp_customize, 'folio_showroom_header_font_size', array(
		'type'     => 'range-value',
		'section'  => 'folio-showroom-header',
		'label'    => __('Header menu font size', 'folio-showroom'),
		'input_attrs' => array(
			'min'    => 0,
			'max'    => 50,
			'step'   => 1,
			'suffix' => 'px', //optional suffix
		),
	)));

	$wp_customize->add_setting('folio_showroom_header_spacing', array(
		'default' 		=> Data\get_default_option('header_spacing'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_float',
	));

	$wp_customize->add_control(new Range_Custom_Control($wp_customize, 'folio_showroom_header_spacing', array(
		'type'     => 'range-value',
		'section'  => 'folio-showroom-header',
		'label'    => __('Header menu spacing', 'folio-showroom'),
		'input_attrs' => array(
			'min'    => .5,
			'max'    => 5,
			'step'   => .1,
			'suffix' => 'rem', //optional suffix
		),
	)));

	$wp_customize->add_setting('folio_showroom_header_blur', array(
		'default' 		=> Data\get_default_option('header_blur'),
		'sanitize_callback' => 'absint',
		'transport' => 'postMessage',
	));

	$wp_customize->add_control(new Range_Custom_Control($wp_customize, 'folio_showroom_header_blur', array(
		'type'     => 'range-value',
		'section'  => 'folio-showroom-header',
		'label'    => __('Transparent sticky header blur', 'folio-showroom'),
		//'active_callback' => 'FolioShowroom\Customizer\header_blur_active_callback',
		'input_attrs' => array(
			'min'    => 0,
			'max'    => 30,
			'step'   => 1,
			'suffix' => '%', //optional suffix
		),
		'transport'         => 'postMessage',
	)));


	/* ARCHIVE */

	$wp_customize->add_section('folio-showroom-archive', array(
		'title' 		=> __('Archive Settings', 'folio-showroom'),
		'capability' 	=> 'edit_theme_options',
		'priority'		=> 1
	));

	$wp_customize->add_setting('folio_showroom_archive_width', array(
		'default' 		=>  Data\get_default_option('archive_width'),
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_select',
	));

	$wp_customize->add_control('folio_showroom_archive_width', array(
		'label' => __('Archive width', 'folio-showroom'),
		'section' => 'folio-showroom-archive',
		'type' => 'select',
		'choices' => Data\get_layout_width_choices()
	));

	$wp_customize->add_setting('folio_showroom_archive_cats_prefix', array(
		'default' 			=> Data\get_default_option('archive_cats_prefix'),
		'sanitize_callback' => 'wp_kses',
	));

	$wp_customize->add_control('folio_showroom_archive_cats_prefix', array(
		'type' 			=> 'text',
		'section' 		=> 'folio-showroom-archive',
		'label' 		=> __('Categories title prefix', 'folio-showroom'),
	));

	$wp_customize->add_setting('folio_showroom_archive_tags_prefix', array(
		'default' 			=> Data\get_default_option('archive_tags_prefix'),
		'sanitize_callback' => 'wp_kses',
	));

	$wp_customize->add_control('folio_showroom_archive_tags_prefix', array(
		'type' 			=> 'text',
		'section' 		=> 'folio-showroom-archive',
		'label' 		=> __('Tags title prefix', 'folio-showroom'),
	));


	/* PAGES/POSTS */

	$wp_customize->add_section('folio-showroom-posts', array(
		'title' 		=> __('Posts/Pages Settings', 'folio-showroom'),
		'capability' 	=> 'edit_theme_options',
		'priority'		=> 1
	));

	$wp_customize->add_setting('folio_showroom_cats_show', array(
		'default' 		=> Data\get_default_option('cats_show'),
		//'transport'         => 'postMessage',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_cats_show', array(
		'label'       => esc_html__('Display Categories', 'folio-showroom'),
		'section'     => 'folio-showroom-posts',
		'type'        => 'toggle',
	)));

	$wp_customize->add_setting('folio_showroom_tags_show', array(
		'default' 		=> Data\get_default_option('tags_show'),
		//'transport'         => 'postMessage',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_tags_show', array(
		'label'       => esc_html__('Display Tags', 'folio-showroom'),
		'section'     => 'folio-showroom-posts',
		'type'        => 'toggle',
	)));


	$wp_customize->add_setting('folio_showroom_comments_show', array(
		'default' 		=> Data\get_default_option('comments_show'),
		//'transport'         => 'postMessage',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_comments_show', array(
		'label'       => esc_html__('Display Comments', 'folio-showroom'),
		'section'     => 'folio-showroom-posts',
		'type'        => 'toggle',
	)));


	$wp_customize->add_setting('folio_showroom_breadcrumb_show', array(
		'default' 		=> Data\get_default_option('breadcrumb_show'),
		//'transport'         => 'postMessage',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_breadcrumb_show', array(
		'label'       => esc_html__('Display Breadcrumbs', 'folio-showroom'),
		'section'     => 'folio-showroom-posts',
		'type'        => 'toggle',
	)));

	
	$wp_customize->add_setting('folio_showroom_separator_posts_1');
	$wp_customize->add_control(new Separator_Custom_control($wp_customize, 'folio_showroom_separator_posts_1', array(
		'type'		=> 'separator',
		'section' => 'folio-showroom-posts',
	)));


	$wp_customize->add_setting('folio_showroom_nav_prev_text', array(
		'default' 			=> Data\get_default_option('nav_prev_text'),
		'sanitize_callback' => 'wp_kses',
	));

	$wp_customize->add_control('folio_showroom_nav_prev_text', array(
		'type' 			=> 'text',
		'section' 		=> 'folio-showroom-posts',
		'label' 		=> __('Pagination previous label', 'folio-showroom'),
		'input_attrs' => array(
			'placeholder' =>  __('Previous post', 'folio-showroom')
		)
	));


	$wp_customize->add_setting('folio_showroom_nav_next_text', array(
		'default' 			=> Data\get_default_option('nav_next_text'),
		'sanitize_callback' => 'wp_kses',
	));

	$wp_customize->add_control('folio_showroom_nav_next_text', array(
		'type' 			=> 'text',
		'section' 		=> 'folio-showroom-posts',
		'label' 		=> __('Pagination next label', 'folio-showroom'),
		'input_attrs' => array(
			'placeholder' =>  __('Next post', 'folio-showroom')
		)
	));


	$wp_customize->add_setting('folio_showroom_separator_posts_2');
	$wp_customize->add_control(new Separator_Custom_control($wp_customize, 'folio_showroom_separator_posts_2', array(
		'type'		=> 'separator',
		'section' => 'folio-showroom-posts',
	)));

	$wp_customize->add_setting('folio_showroom_related_posts_title', array(
		'default' 			=> Data\get_default_option('related_posts_title'),
		'sanitize_callback' => 'wp_kses',
	));

	$wp_customize->add_control('folio_showroom_related_posts_title', array(
		'type' 			=> 'text',
		'section' 		=> 'folio-showroom-posts',
		'label' 		=> __('Related Posts Title', 'folio-showroom')
	));


	$wp_customize->add_setting('folio_showroom_related_posts_limit', array(
		'default' 		=> Data\get_default_option('related_posts_limit'),
		'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control(new Range_Custom_Control($wp_customize, 'folio_showroom_related_posts_limit', array(
		'type'     => 'range-value',
		'section'  => 'folio-showroom-posts',
		'label'    => __('Limit of related posts', 'folio-showroom'),
		'input_attrs' => array(
			'min'    => 0,
			'max'    => 12,
			'step'   => 1,
			'suffix' => '', //optional suffix
		),
	)));



	/* SECTION FOOTER */


	$wp_customize->add_section('folio-showroom-footer', array(
		'title' 		=> __('Footer Settings', 'folio-showroom'),
		'capability' 	=> 'edit_theme_options',
		'priority'		=> 1
	));

	$wp_customize->add_setting('folio_showroom_footer_text', array(
		'default' 			=> Data\get_default_option('footer_text'),
		'sanitize_callback' => 'wp_kses_post',
	));

	$wp_customize->add_control('folio_showroom_footer_text', array(
		'type' 			=> 'textarea',
		'section' 		=> 'folio-showroom-footer',
		'label' 		=> __('Footer Text', 'folio-showroom'),
		'description'  => __('Accepted placeholders:', 'folio-showroom') . '<br>[copyright] [current_year] [site_title]',
	));

	$wp_customize->add_setting('folio_showroom_gototop_show', array(
		'default' 		=> Data\get_default_option('gototop_show'),
		//'transport'         => 'postMessage',
		'sanitize_callback' => 'FolioShowroom\Customizer\sanitize_checkbox',
	));

	$wp_customize->add_control(new Toggle_Custom_Control($wp_customize, 'folio_showroom_gototop_show', array(
		'label'       => esc_html__('Display Go To Top', 'folio-showroom'),
		'section'     => 'folio-showroom-footer',
		'type'        => 'toggle',
	)));
}
add_action('customize_register', __NAMESPACE__ . '\\customize_register');

/**
 * Show on front active callback
 */
function show_on_front_active_callback($control)
{
	if ($control->manager->get_setting('show_on_front')->value() == 'posts') {
		return true;
	} else {
		return false;
	}
}

/**
 * Header Blur effect active callback
 */
function header_blur_active_callback($control)
{
	$transparents = array('transparent', 'transparent-light', 'transparent-dark');
	if (in_array($control->manager->get_setting('folio_showroom_header_theme')->value(), $transparents)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function customize_partial_blogname()
{
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function customize_partial_blogdescription()
{
	bloginfo('description');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function customize_partial_site_header_blur()
{
	return Data\get_theme_option('header_blur');
}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customize_preview_js()
{
	wp_enqueue_script('folio-showroom-customizer', AssetResolver::resolve('js/customizer.js'), ['customize-preview'], NULL, true);
}
add_action('customize_preview_init', __NAMESPACE__ . '\\customize_preview_js');


/**
 * Select sanitization function
 * input must be a slug: lowercase alphanumeric characters, dashes and underscores allowed
 */
function sanitize_select($input, $setting)
{
	$input = sanitize_key($input);
	$choices = $setting->manager->get_control($setting->id)->choices;
	return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Checkbox sanitization callback
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function sanitize_checkbox($checked)
{
	// Boolean check.
	return ((isset($checked) && true === $checked) ? true : false);
}


/**
 * Url sanitization callback
 *
 * @param string $url url
 * @return string saintized url
 */
function sanitize_url($url)
{
	return esc_url_raw($url);
}

/**
 * Sanitize float value
 */
function sanitize_float($input)
{
	return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}


/**
 * This function adds some styles to the WordPress Customizer
 */
function customizer_styles()
{ ?>
	<style>
		.sub-section-heading-control .customize-control-title {
			border-bottom: 1px solid #ccc;
			padding-bottom: .3rem;
			font-weight: bold;
			text-transform: uppercase;
			font-size: 12px;
			color: #232323;
		}
	</style>
<?php
}

add_action('customize_controls_print_styles', __NAMESPACE__ . '\\customizer_styles', 999);
