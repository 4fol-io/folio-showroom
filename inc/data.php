<?php

/**
 * FolioShowroom data
 * 
 * Some data utils (detaults, settings, etc)
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Data;

// Exit if accessed directly.
defined('ABSPATH') || exit;


/**
 * Get theme font choices
 */
function get_font_choices()
{
    return array(
        'uoc-sans'      => __('UOC Sans', 'folio-showroom'),
        'uoc-serif'     => __('UOC Serif', 'folio-showroom'),
    );
}

/**
 * Get theme align choices
 */
function get_align_choices()
{
    return array(
        ''              => __('&mdash; Select &mdash;', 'folio-showroom'),
        'text-left'     => __('Left', 'folio-showroom'),
        'text-center'   => __('Center', 'folio-showroom'),
        'text-right'    => __('Right', 'folio-showroom'),
        'text-justify'  => __('Justify', 'folio-showroom'),
    );
}

/**
 * Get theme width choices
 */
function get_layout_width_choices()
{
    return array(
        ''              => __('Normal', 'folio-showroom'),
        'wide'          => __('Wide', 'folio-showroom'),
        'full'          => __('Full width', 'folio-showroom'),
    );
}

/**
 * Get theme style choices
 */
function get_header_theme_choices()
{
    return array(
        ''                     => __('Default', 'folio-showroom'),
        'light'             => __('Light', 'folio-showroom'),
        'dark'                 => __('Dark', 'folio-showroom'),
        'transparent'         => __('Transparent Default', 'folio-showroom'),
        'transparent-light' => __('Transparent Light', 'folio-showroom'),
        'transparent-dark'     => __('Transparent Dark', 'folio-showroom'),
    );
}


/**
 * Get theme transparent styles array
 */
function get_header_theme_transparent_list()
{
    return array(
        'transparent',
        'transparent-light',
        'transparent-dark',
    );
}

/**
 * Get default theme settings
 */
function get_default_settings()
{
    return array(
        'home_title'             => '<em>' . __('Discover', 'folio-showroom') . '</em> <strong>[site_title]</strong>',
        'header_width'           => 'wide',
        'header_theme'           => '',
        'header_sticky'          => true,
        'header_overlay'         => false,
        'header_blur'            => 5,           // %
        'header_font'            => 'uoc-serif', // uoc-sans|uoc-serif
        'header_font_size'       => 24,          // px
        'header_spacing'         => 2,           // rem
        'header_search'          => false,
        'home_header_theme'      => '',
        'home_header_overlay'    => false,
        'archive_width'          => 'wide',
        'archive_cats_prefix'    => __('Discover', 'folio-showroom'),
        'archive_tags_prefix'    => __('Discover', 'folio-showroom'),
        'related_posts_title'    => __('<em>More</em> <strong>Folios</strong>', 'folio-showroom'),
        'related_posts_limit'    => 4,
        'nav_prev_text'          => __('Previus Folio', 'folio-showroom'),
        'nav_next_text'          => __('Next Folio', 'folio-showroom'),
        'cats_show'              => true,
        'tags_show'              => true,
        'comments_show'          => false,
        'breadcrumb_show'        => false,
        'gototop_show'           => true,
        'uoc_tools_show'         => false,
        'uoc_foot_show'          => false,
        'lang_show'              => false,
        'footer_width'           => 'wide',
        'footer_text'            => __('[copyright] [current_year] - [site_title]<br>Made with ❤️ by <a href="https://tresipunt.com" target="_blank"><strong>tresipunt</strong></a>', 'folio-showroom'),
        'color_mode'             => '',
        'color_switcher'         => false,
        'primary_color'          => '#333333',
        'secondary_color'        => '#777777',
        'terciary_color'         => '#e3e3e3',
        'links_color'            => '#000078',
        'primary_color_dark'     => '#f2f2f2',
        'secondary_color_dark'   => '#aaaaaa',
        'terciary_color_dark'    => '#555555',
        'links_color_dark'       => '#b5b5e5',
    );
}


/**
 * Get default setting option by key
 */
function get_default_option($setting)
{
    $settings = get_default_settings();
    if (isset($settings[$setting])) {
        return $settings[$setting];
    }
    return '';
}

/**
 * Get theme option by key
 */
function get_theme_option($option)
{
    return \get_theme_mod(FOLIO_SHOWROOM_PREFIX . $option, get_default_option($option));
}


/**
 * Get Revolution Sliders Array
 */
function get_sliders_choices()
{

    $choices = array(
        '' => __('&mdash; Select &mdash;', 'folio-showroom')
    );

    if (class_exists('RevSlider')) {
        $rev_slider = new \RevSlider();
        $sliders = $rev_slider->get_sliders_short_list();
    } else {
        $sliders = array();
    }

    foreach ($sliders as $slider) {
        $choices[$slider->id] = $slider->title;
    }

    return $choices;
}



/**
 * Replace theme placeholders
 * 
 * @return string
 */
function replace_placeholders($content)
{

    $to_replace = apply_filters('folio-showroom/placeholders', array(
        '[site_title]'        => get_option('blogname'),
        '[copyright]'         => '&copy;',
        '[current_year]'      => gmdate('Y'),
    ));

    foreach ($to_replace as $placeholder => $var) {
        $content = str_replace($placeholder, $var, $content);
    }

    return $content;
}
