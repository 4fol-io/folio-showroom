<?php

/**
 * FolioShowroom clean utilities
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Clean;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Clean up wp_head()
 *
 * Remove unnecessary <link>'s
 * Remove inline CSS and JS from WP emoji support
 * Remove inline CSS used by Recent Comments widget
 * Remove inline CSS used by posts with galleries
 * Remove self-closing tag
 */
function head_cleanup()
{
  // Originally from https://wpengineer.com/1438/wordpress-header/
  remove_action('wp_head', 'feed_links_extra', 3);
  add_action('wp_head', 'ob_start', 1, 0);
  add_action('wp_head', function () {
    $pattern = '/.*' . preg_quote(esc_url(get_feed_link('comments_' . get_default_feed())), '/') . '.*[\r\n]+/';
    echo preg_replace($pattern, '', ob_get_clean());
  }, 3, 0);
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head', 10);
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_action('wp_head', 'wp_oembed_add_discovery_links');
  remove_action('wp_head', 'wp_oembed_add_host_js');
  remove_action('wp_head', 'rest_output_link_wp_head', 10);
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  add_filter('use_default_gallery_style', '__return_false');
  add_filter('emoji_svg_url', '__return_false');
  add_filter('show_recent_comments_widget_style', '__return_false');
}
add_action('init', __NAMESPACE__ . '\\head_cleanup');


/**
 * Remove the WordPress version from RSS feeds
 */
add_filter('the_generator', '__return_false');


/**
 * Remove smileys emoticons
 */
function remove_smileys($bool)
{
  return false;
}
add_filter('option_use_smilies', __NAMESPACE__ . '\\remove_smileys', 99, 1);


/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 */
function embed_wrap($cache)
{
  return '<div class="entry-content-asset">' . $cache . '</div>';
}
add_filter('embed_oembed_html', __NAMESPACE__ . '\\embed_wrap');


/**
 * Remove unnecessary dashboard widgets
 *
 * @link http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
 */
function remove_dashboard_widgets()
{
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
  remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
  remove_meta_box('dashboard_primary', 'dashboard', 'normal');
  remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}
//add_action('admin_init', __NAMESPACE__ . '\\remove_dashboard_widgets');


/**
 * Remove unnecessary self-closing tags
 */
function remove_self_closing_tags($input)
{
  return str_replace(' />', '>', $input);
}
add_filter('get_avatar', __NAMESPACE__ . '\\remove_self_closing_tags'); // <img />
add_filter('comment_id_fields', __NAMESPACE__ . '\\remove_self_closing_tags'); // <input />
add_filter('post_thumbnail_html', __NAMESPACE__ . '\\remove_self_closing_tags'); // <img />

/**
 * Don't return the default description in the RSS feed if it hasn't been changed
 */
function remove_default_description($bloginfo)
{
  $default_tagline = 'Just another WordPress site';
  return ($bloginfo === $default_tagline) ? '' : $bloginfo;
}
add_filter('get_bloginfo_rss', __NAMESPACE__ . '\\remove_default_description');
