<?php

/**
 * FolioShowroom utilities
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Utils;

use FolioShowroom\Data;
use FolioShowroom\Assets\AssetResolver;


// Exit if accessed directly.
defined('ABSPATH') || exit;


/**
 * Add custom query vars
 */
function add_query_vars($vars)
{
  //$vars[] = 'customvar';
  return $vars;
}
//add_filter( 'query_vars', __NAMESPACE__ . '\\add_query_vars' );


/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
function get_search_form()
{
  $form = '';
  locate_template('/template-parts/searchform.php', true, false);
  return $form;
}
add_filter('get_search_form', __NAMESPACE__ . '\\get_search_form');



/**
 * Change custom logo default class
 */
function change_logo_class($html)
{
  //$html = str_replace( 'custom-logo-link', 'navbar-brand', $html );
  $html = str_replace('custom-logo', 'img-fluid', $html);
  return $html;
}
// add_filter( 'get_custom_logo', __NAMESPACE__ . '\\change_logo_class' );


/**
 * Add No-JS Class.
 * If we're missing JavaScript support, the HTML element will have a no-js class.
 */
function toggle_js_class()
{
?>
  <script>
    document.documentElement.className = document.documentElement.className.replace('no-js', 'js');
  </script>
<?php
}
add_action('wp_head', __NAMESPACE__ . '\\toggle_js_class');


/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function skip_link_focus_fix()
{
  // The following is minified via `terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
?>
  <script>
    /(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function() {
      var t, e = location.hash.substring(1);
      /^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus())
    }, !1);
  </script>
  <?php
}
add_action('wp_print_footer_scripts', __NAMESPACE__ . '\\skip_link_focus_fix');



/**
 * Adds custom classes to the array of body classes.
 * Adds a class of hfeed to non-singular pages.
 * 
 * @param array $classes Classes for the body element.
 * @return array
 */
function body_classes($classes)
{
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array('page-' . basename(get_permalink()), $classes)) {
      $classes[] = 'page-' . basename(get_permalink());
    }
  }

  if (!is_singular()) {
    $classes[] = 'hfeed';
  }

  if (!Data\get_theme_option('uoc_tools_show')) {
    $classes[] = 'hide-uoc-tools';
  }

  return $classes;
}
add_filter('body_class',  __NAMESPACE__ . '\\body_classes');


/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function pingback_header()
{
  if (is_singular() && pings_open()) {
    printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
  }
}
add_action('wp_head',  __NAMESPACE__ . '\\pingback_header');


/**
 * Clean up the_excerpt()
 */
function excerpt_more()
{
  /*if ( ! is_single() ) {
    return sprintf( '<a class="read-more" href="%1$s">%2$s</a>', get_permalink( get_the_ID() ), '<strong>+</strong>' );
  }*/
  return ' &hellip;';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');


/**
 * Customize excerpt length
 */
function excerpt_length($length)
{
  return 45;
}
add_filter('excerpt_length', __NAMESPACE__ . '\\excerpt_length', 999);


/**
 * Remove the excerpt shortcodes
 */
function remove_the_excerpt_shortcodes()
{
  return  strip_shortcodes(get_the_excerpt());
}
add_filter('the_excerpt', __NAMESPACE__ . '\\remove_the_excerpt_shortcodes');

/**
 * Clean rev slider excerpt
 */
function clean_the_excerpt_rev_slider($excerpt)
{
  $excerpt = strip_tags($excerpt, '<br><br/>');
  return $excerpt;
}
//add_filter('revslider_getExcerptById', __NAMESPACE__ . '\\clean_the_excerpt_rev_slider', 10, 1);


/** 
 * Filter revolution slider "home" slider to show only featured posts
 */
function slider_featured_posts($query, $slider_id)
{

  $home_slider = Data\get_theme_option('home_slider');

  if ($slider_id == $home_slider) {

    // only include "featured" posts
    $query['meta_query'] = array(
      'relation' => 'AND',
      'featured_clause' => array(
        'key' => '_folio_showroom_featured',
        'value' => '1',
      )
    );
  }

  return $query;
}

add_filter('revslider_get_posts', __NAMESPACE__ . '\\slider_featured_posts', 10, 2);


/**
 * Adds revolution slider by id
 */
function add_slider_by_id($id)
{
  if ($id && class_exists('RevSlider')) {
    $rev_slider = new \RevSlider();
    $alias =  $rev_slider->get_alias_by_id($id);
    if ($alias) {
      \add_revslider($alias);
    }
  }
}


/**
 * Default avatar
 */
function default_avatar($avatar_defaults)
{
  $url = AssetResolver::resolve('images/default-avatar.png');
  $avatar_defaults[$url] = "Default Folio Showroom";
  return $avatar_defaults;
}
add_filter('avatar_defaults', __NAMESPACE__ . '\\default_avatar');


/**
 * Breadcrumbs
 */
function the_breadcrumb()
{

  if (!Data\get_theme_option('breadcrumb_show')) {
    return false;
  }

  echo '<ol class="breadcrumb">';

  if (!is_front_page()) {

    // Start the breadcrumb with a link to your homepage
    echo '<li class="breadcrumb-item"><a href="' . get_option('home') . '">' . __('Home', 'folio-showroom') . '</a></li>';

    // Check if the current page is a category, an archive or a single page. If so show the category or archive name.
    if (is_category() /*|| is_single()*/) {
      //wp_list_categories('title_li=0');
      $cats = array_reverse(get_the_category());
      foreach ($cats as $cat) {
        echo '<li class="breadcrumb-item">' . $cat->cat_name . '</li>';
      }
    } elseif (is_archive() /*|| is_single()*/) {
      if (is_day()) {
        printf(__('<li class="breadcrumb-item">%s</li>', 'folio-showroom'), ucwords(get_the_date('j F, Y')));
      } elseif (is_month()) {
        printf(__('<li class="breadcrumb-item">%s</li>', 'folio-showroom'), ucwords(get_the_date(_x('F Y', 'monthly archives date format', 'folio-showroom'))));
      } elseif (is_year()) {
        printf(__('<li class="breadcrumb-item">%s</li>', 'folio-showroom'), get_the_date(_x('Y', 'yearly archives date format', 'folio-showroom')));
      } elseif (is_author()) {
        printf(__('<li class="breadcrumb-item">%s</li>', 'folio-showroom'), get_the_author());
      } else {
        printf(__('<li class="breadcrumb-item">%s</li>', 'folio-showroom'), get_the_archive_title());
      }
    } elseif (is_search()) {
      printf(__('<li class="breadcrumb-item">Search: %s</li>', 'folio-showroom'), '#' . get_search_query());
    }

    // If the current page is a single post, show its title with the separator
    if (is_single()) {
      the_title('<li class="breadcrumb-item">', '</li>');
    }

    // If the current page is a static page, show its title.
    if (is_page()) {
      the_title('<li class="breadcrumb-item">', '</li>');
    }

    // if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home >> Blog
    if (is_home()) {
      global $post;
      $page_for_posts_id = get_option('page_for_posts');
      if ($page_for_posts_id) {
        $post = get_post($page_for_posts_id);
        setup_postdata($post);
        the_title('<li class="breadcrumb-item">', '</li>');
        rewind_posts();
      }
    }
  } else {

    echo '<li class="breadcrumb-item">' . __('Home', 'folio-showroom') . '</li>';
  }

  echo '</ol>';
}


/**
 * Toggle icon svg with current color for dropdowns/collapse 
 */
function get_toggle_icon()
{
  return '<span class="collapse-icon"><svg viewBox="0 0 10 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 6.7058L0 1.47965L1.23822 0.294195L5 4.22559L8.76178 0.294195L10 1.47965L5 6.7058Z" fill="currentColor"/></svg></span><span class="visually-hidden">' . esc_html__('Open menu', 'folio-showroom') . '</span>';
}


/**
 * Get current language code 2 chars
 */
function current_language()
{
  $lang  = apply_filters('wpml_current_language', NULL);
  if (!$lang) {
    $lang = substr(get_bloginfo('language'), 0, 2);
  }
  return $lang;
}


/**
 * WPML language list
 */
function languages_offcanvas()
{
  $langs = apply_filters('wpml_active_languages', NULL, 'orderby=id&skip_missing=0');
  if (!empty($langs)) :
  ?>
    <nav role="navigation" class="offcanvas-lang d-flex mt-auto border-top pt-2 pb-3 pb-sm-4">
      <ul class="navbar-nav navbar-lang flex-row">
        <?php
        foreach ($langs as $l) :
          $class = $l['active'] ? 'nav-link active' : 'nav-link';
        ?>
          <li class="nav-item nav-lang-item">
            <a href="<?php echo esc_url($l['url']) ?>" title="<?php echo $l['translated_name'] ?>" class="<?php echo $class ?>" <?php if ($l['active']) : ?>aria-current="true" <?php endif ?>>
              <span class="lbl">
                <?php echo $l['language_code'] ?>
                <span class="visually-hidden"><?php echo $l['translated_name'] ?></span>
              </span>
            </a>
          </li>
        <?php
        endforeach;
        ?>
      </ul>
    </nav>
  <?php
  endif;
}



/**
 * WPML custom responsive language menu
 */
function languages_dropdown()
{
  $lang = current_language();
  $langs = apply_filters('wpml_active_languages', NULL, 'orderby=id&skip_missing=0');
  if ($langs && is_array($langs)) :
  ?>
    <ul class="navbar-nav navbar-lang">
      <li class="nav-item dropdown">
        <a href="#" role="button" id="lang-menu-toggle" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="lbl"><?php echo $lang ?></span>
          <?php echo get_toggle_icon() ?>
        </a>
        <ul id="lang-menu-dropdown" class="dropdown-menu dropdown-menu-end" aria-labelledby="lang-menu-toggle" role="menu" tabindex="-1">
          <?php
          foreach ($langs as $l) :
            $class = $l['active'] ? 'nav-link active' : 'nav-link';
          ?>
            <li class="nav-item nav-lang-item">
              <a href="<?php echo esc_url($l['url']) ?>" title="<?php echo $l['translated_name'] ?>" class="dropdown-item <?php echo $class ?>" <?php if ($l['active']) : ?>aria-current="true" <?php endif ?>>
                <?php echo $l['language_code'] ?>
                <span class="visually-hidden"><?php echo $l['translated_name'] ?></span>
              </a>
            </li>
          <?php
          endforeach;
          ?>
        </ul>
      </li>
    </ul>
  <?php
  endif;
}



/**
 * Filter the output of Yoast breadcrumbs so each item is an <li> with schema markup
 * @param $link_output
 * @param $link
 *
 * @return string
 */
function filter_yoast_breadcrumb_items($link_output, $link)
{
  $new_link_output = '<li class="breadcrumb-item">';
  $new_link_output .= '<a href="' . $link['url'] . '">' . $link['text'] . '</a>';
  $new_link_output .= '</li>';
  return $new_link_output;
}
add_filter('wpseo_breadcrumb_single_link', __NAMESPACE__ . '\\filter_yoast_breadcrumb_items', 10, 2);


/**
 * Filter the output of Yoast breadcrumbs to remove <span> tags added by the plugin
 * @param $output
 *
 * @return mixed
 */
function filter_yoast_breadcrumb_output($output)
{
  $from = '<span>';
  $to = '</span>';
  $output = str_replace($from, $to, $output);
  return $output;
}
add_filter('wpseo_breadcrumb_output', __NAMESPACE__ . '\\filter_yoast_breadcrumb_output');



/**
 * Add a title to posts and pages that are missing titles.
 */
function untitled($title, $id)
{
  if (is_admin()) return $title;
  return '' === $title ? esc_html_x('Untitled', 'Added to posts and pages that are missing titles', 'folio-showroom') : $title;
}
add_filter('the_title', __NAMESPACE__ . '\\untitled', 100, 2);


/**
 * Aux method to get page by template
 */
function get_page_by_template($template = '')
{
  $args = array(
    'meta_key' => '_wp_page_template',
    'meta_value' => $template
  );
  return get_pages($args);
}


/**
 * Highligtht coincidences for searched term or tag in archive
 */
function highlight_results($text)
{
  if (in_the_loop() && !is_admin()) {
    if (is_search()) {
      $sr = get_query_var('s');
      $keys = explode(" ", $sr);
      $keys = array_filter($keys);
      $text = preg_replace('/(' . implode('|', $keys) . ')/iu', '<mark>\0</mark>', $text);
    } /*else if (is_tag()) {
      $text = preg_replace('/(' . single_tag_title('', false) . ')/iu', '<mark>\0</mark>', $text);
    }*/
  }
  return $text;
}
add_filter('the_title', __NAMESPACE__ . '\\highlight_results');
add_filter('the_excerpt', __NAMESPACE__ . '\\highlight_results');
add_filter('highlight_excerpt', __NAMESPACE__ . '\\highlight_results');


/**
 * Remove tag cloud size styles
 */
function remove_tag_cloud_styles($tag_string)
{
  return preg_replace('/style=("|\')(.*?)("|\')/', '', $tag_string);
}
// add_filter('wp_generate_tag_cloud',  __NAMESPACE__ . '\\remove_tag_cloud_styles', 10, 1);


/**
 * Display tag cloud widget as list
 */
function custom_tag_cloud_widget($args)
{
  //$args['format'] = 'list';
  $args['largest']  = 2;
  $args['smallest'] = .8;
  $args['unit']     = 'rem';
  return $args;
}
add_filter('widget_tag_cloud_args',  __NAMESPACE__ . '\\custom_tag_cloud_widget');


/**
 * Remove rel nofollow for links in comments
 */
function remove_rel_nofollow_attribute($comment_text)
{
  $comment_text = str_ireplace(' rel="nofollow"', '', $comment_text);
  return $comment_text;
}
// add_filter( 'comment_text', 'remove_rel_nofollow_attribute' );


/**
 * Remove the comment reply button from it's default location
 */
function remove_comment_reply_link($link)
{
  return '';
}
// add_filter('cancel_comment_reply_link',  __NAMESPACE__ . '\\remove_comment_reply_link', 10);


/**
 * Add the comment reply button to the end of the comment form.
 * Remove the remove_comment_reply_link filter first so that it will actually output something.
 */
function after_comment_form($post_id)
{
  remove_filter('cancel_comment_reply_link',  __NAMESPACE__ . '\\remove_comment_reply_link', 10);
  cancel_comment_reply_link(__('Cancel', 'folio-showroom'));
}
// add_action('comment_form',  __NAMESPACE__ . '\\after_comment_form', 99);


/**
 * Hide admin bar
 */
function remove_admin_bar()
{
  if (!is_admin() && !is_customize_preview()) {
    show_admin_bar(false);
  }
}
add_action('after_setup_theme',  __NAMESPACE__ . '\\remove_admin_bar');



/**
 * Redirect to 404
 */
// remove_action( 'template_redirect', 'maybe_redirect_404' );

/**
 *  Disable Gutenberg Editor
 */
// add_filter( 'use_block_editor_for_post', '__return_false' );


/**
 * Get the archive title
 */
function archive_title($title)
{
  $prefix = '';
  if (is_category()) {
    $title = single_cat_title('', false);
  } elseif (is_tag()) {
    $title = '<em>' . single_tag_title('#', false) . '</em>';
  } elseif (is_author()) {
    $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif (is_post_type_archive()) {
    $title = post_type_archive_title('', false);
  } elseif (is_tax()) {
    $title = single_term_title('', false);
  } elseif (is_year()) {
    $title = get_the_date('Y');
  } elseif (is_month()) {
    $title = ucwords(get_the_date('F Y'));
  } elseif (is_day()) {
    $title = ucwords(get_the_date('j F, Y'));
  }
  return '<strong>' . $title . '</strong>';
}
add_action('get_the_archive_title',  __NAMESPACE__ . '\\archive_title');


/**
 * Aux method to load template part without echo
 */
function load_template_part($template_name, $part_name = null)
{
  ob_start();
  get_template_part($template_name, $part_name);
  $var = ob_get_contents();
  ob_end_clean();
  return $var;
}


/**
 * Aux method to forma date
 */
function format_date($date)
{
  if ($date) return date('d.m.Y', strtotime($date));
  return $date;
}


/**
 * Aux method to format date timestamp
 */
function format_date_timestamp($date)
{
  if ($date) return date('u', strtotime($date));
  return $date;
}

/**
 * Human readable date format 
 * 
 */
function format_date_time_ago()
{
  $post_time_ts = get_the_modified_time('U');
  $show_human_time = $post_time_ts >= strtotime('-1 year');
  $human_time = $show_human_time ?
    sprintf(
      _x('%s ago', '%s = human-readable time difference', 'folio-showroom'),
      human_time_diff(
        $post_time_ts,
        current_time('timestamp')
      )
    ) :
    get_the_modified_date('');

  $time_string = sprintf(
    '<time class="folio-showroom-post-time" datetime="%1$s">%2$s</time>',
    esc_attr(get_the_modified_time(DATE_W3C)),
    esc_html($human_time)
  );

  return  $time_string;
}

/**
 * Helper function to test if an array key exists.
 *
 * @param     array     $array The array to test against.
 * @param     array     $keys An array of keys to look for.
 * @return    bool
 */
function array_keys_exists($array, $keys)
{
  foreach ($keys as $k)
    if (isset($array[$k]))
      return true;

  return false;
}


/**
 * Pretty Printing for development testing purposes
 *
 * @param mixed $obj
 * @param string $label
 * @return null
 */
function pp($obj, $label = '')
{
  $data = json_encode(print_r($obj, true));
  ?>
  <style type="text/css">
    #folio-showroom-logger {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      border-left: 4px solid #bbb;
      padding: 1rem;
      background: white;
      color: #444;
      z-index: 999;
      font-size: 1rem;
      width: 25%;
      height: 100%;
      overflow: scroll;
    }
  </style>
  <script type="text/javascript">
    var doStuff = function() {
      var obj = <?php echo $data; ?>;
      var logger = document.getElementById('folio-showroom-logger');
      if (!logger) {
        logger = document.createElement('div');
        logger.id = 'folio-showroom-logger';
        document.body.appendChild(logger);
      }
      // console.log(obj);
      var pre = document.createElement('pre');
      var h2 = document.createElement('h2');
      pre.innerHTML = obj;
      h2.innerHTML = '<?php echo addslashes($label); ?>';
      logger.appendChild(h2);
      logger.appendChild(pre);
    };
    window.addEventListener("DOMContentLoaded", doStuff, false);
  </script>
<?php
}


/**
 * Display Post Blocks for development testing purposes
 */
function display_post_blocks()
{
  global $post;
  pp(esc_html($post->post_content));
}
// add_action( 'wp_footer', __NAMESPACE__ . '\\display_post_blocks' );


/**
 * Get post taxonomy array of field
 */
function post_taxonomy_field_array($tax_name = 'post-tag', $field = 'ids')
{

  $terms = wp_get_post_terms(get_the_ID(), $tax_name, array("fields" => $field));
  $term_array = array();

  if (!empty($terms) && !is_wp_error($terms)) {
    foreach ($terms as $term) {
      $term_array[] = $term;
    }
  }

  return $term_array;
}
