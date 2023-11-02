<?php

/**
 * FolioShowroom initialization setup
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Setup;

use FolioShowroom\Assets\AssetResolver;
use FolioShowroom\Styles;
use FolioShowroom\Data;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function setup()
{

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain('folio-showroom', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	// Body open hook
	add_theme_support('body-open');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	add_image_size('folio-showroom-featured', 1180, 640, true);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary-menu' => esc_html__('Primary menu', 'folio-showroom'),
			'secondary-menu' => esc_html__('Secondary menu', 'folio-showroom'),
			'offcanvas-menu' => esc_html__('Offcanvas menu', 'folio-showroom'),
			'footer-links' => esc_html__('Footer links', 'folio-showroom'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output validW HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);


	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'width'      => 280,
			'height'       => 65,
			'flex-width'  => true,
			'flex-height' => true,
			'header-text' => array('site-title', 'site-description'),
		)
	);


	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	// Add support for full and wide align images.
	add_theme_support('align-wide');

	// Add support for editor styles.
	add_theme_support('editor-styles');

	// Custom stylesheet for visual editor
	add_editor_style(AssetResolver::resolve('css/editor.css'));

	// Add support for responsive embedded content.
	add_theme_support('responsive-embeds');

	// Disable custom font sizes
	//add_theme_support( 'disable-custom-font-sizes' );

	// Editor Font Styles
	add_theme_support('editor-font-sizes', array(
		array(
			'name'      => __('Small', 'folio-showroom'),
			'size'      => 16,
			'slug'      => 'small'
		),
		array(
			'name'      => __('Normal', 'folio-showroom'),
			'size'      => 18,
			'slug'      => 'normal'
		),
		array(
			'name'      => __('Medium', 'folio-showroom'),
			'size'      => 22,
			'slug'      => 'medium'
		),
		array(
			'name'      => __('Large', 'folio-showroom'),
			'size'      => 28,
			'slug'      => 'large'
		),
		array(
			'name'      => __('XLarge', 'folio-showroom'),
			'size'      => 36,
			'slug'      => 'xlarge'
		),
		array(
			'name'      => __('Huge', 'folio-showroom'),
			'size'      => 42,
			'slug'      => 'huge'
		),
	));

	// Add support for block editor custom line height
	add_theme_support('custom-line-height');

	// Add support for block editor custom spacing
	add_theme_support('custom-spacing');

	// Requiere revisar wrapper
	add_theme_support('appearance-tools');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function content_width()
{
	$GLOBALS['content_width'] = apply_filters(__NAMESPACE__ . '\\content_width', 1792);
}
//add_action( 'after_setup_theme', __NAMESPACE__ . '\\content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function widgets_init()
{
	register_sidebar([
		'id'            => 'footer',
		'name'          => 	__('Footer', 'folio-showroom'),
		'description'   =>  __('Add widgets here to appear in your footer site.', 'folio-showroom'),
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	]);
}

add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');


/**
 * Remove widgets on theme activation
 */
function theme_activation($old_theme, $WP_theme = null)
{
	$widgets = array(
		'footer' => array(false)
	);
	update_option('sidebars_widgets', $widgets);
}

add_action('after_switch_theme', __NAMESPACE__ . '\\theme_activation', 10, 2);


/**
 * Enqueue scripts and styles.
 */
function assets()
{

	// Register jQuery in footer
	/*if (!is_admin() && !is_customize_preview()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', includes_url('/js/jquery/jquery.js'), false, NULL, true);
	}*/

	// registers scripts and stylesheets
	wp_register_style('folio-showroom-style', AssetResolver::resolve('css/style.css'), [], NULL);
	wp_register_script('folio-showroom-manifest', AssetResolver::resolve('js/manifest.js'), [], NULL, true);
	wp_register_script('folio-showroom-vendor', AssetResolver::resolve('js/vendor.js'), [], NULL, true);
	wp_register_script('folio-showroom-app', AssetResolver::resolve('js/app.js'), ['jquery'], NULL, true);
	wp_register_script('folio-showroom-theme', AssetResolver::resolve('js/theme.js'), [], NULL, true);


	// Enqueue styles
	wp_enqueue_style('folio-showroom-style');

	// Inline styles
	wp_add_inline_style('folio-showroom-style', Styles\get_generated_styles());

	// Enqueue scripts
	//wp_enqueue_script ( 'jquery' );
	wp_enqueue_script('folio-showroom-manifest');
	wp_enqueue_script('folio-showroom-vendor');
	wp_enqueue_script('folio-showroom-app');

	if (Data\get_theme_option('color_switcher')) {
		wp_enqueue_script('folio-showroom-theme');
	}

	// localization data
	wp_localize_script('folio-showroom-app', 'folioShowroomData', array(
		'ajaxurl' 	=> admin_url('admin-ajax.php'),				// ajax url
		'nonce' 	=> wp_create_nonce('folio-showroom-theme'),		// ajax nonce
		't' => array(												// translations array
			'externalLink'			 => __('(opens in new window)', 'folio-showroom'),
			'closeMenu'				 => __('Close menu', 'folio-showroom'),
			'loading'                => __('Loading', 'folio-showroom'),
			'expandCollapse'		 => __("expand / collapse", "folio-showroom"),
			'loadMore'               => __('Load more', 'folio-showroom'),
		)
	));

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets');


/**
 * Enqueue editor blocks styles and scripts
 */
function block_editor_assets()
{

	wp_register_script('folio-showroom-manifest', AssetResolver::resolve('js/manifest.js'), [], NULL, true);
	wp_register_script('folio-showroom-vendor', AssetResolver::resolve('js/vendor.js'), [], NULL, true);
	wp_register_script('folio-showroom-editor', AssetResolver::resolve('js/editor.js'), ['wp-plugins', 'wp-data', 'wp-components', 'wp-edit-post', 'wp-element', 'wp-compose', 'wp-i18n', 'wp-block-editor'], NULL, true);

	wp_set_script_translations('folio-showroom-editor', 'folio-showroom', get_template_directory() . '/languages');

	// registers stylesheet
	wp_register_style('folio-showroom-editor-style', AssetResolver::resolve('css/editor.css'), [], NULL);

	// enqueue
	wp_enqueue_style('folio-showroom-editor-style');
	wp_enqueue_script('folio-showroom-manifest');
	wp_enqueue_script('folio-showroom-vendor');
	wp_enqueue_script('folio-showroom-editor');


	// Inline styles
	wp_add_inline_style('folio-showroom-editor-style', Styles\get_editor_blocks_generated_styles());
}
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\\block_editor_assets', 100);


/*
 *  Admin assets
 */
function admin_assets()
{
	if (is_admin()) {

		wp_register_script('folio-showroom-manifest', AssetResolver::resolve('js/manifest.js'), [], NULL, true);
		wp_register_script('folio-showroom-vendor', AssetResolver::resolve('js/vendor.js'), ['jquery'], NULL, true);
		wp_register_script('folio-showroom-admin', AssetResolver::resolve('js/admin.js'), ['jquery'], NULL, true);

		wp_enqueue_script('folio-showroom-manifest');
		wp_enqueue_script('folio-showroom-vendor');
		wp_enqueue_script('folio-showroom-admin');

		// localization data
		wp_localize_script('folio-showroom-admin', 'folioShowroomAdminData', array(
			'ajaxurl' 	=> admin_url('admin-ajax.php'),					// ajax url
			'nonce' 	=> wp_create_nonce('folio-showroom-theme'),	// ajax nonce
			't' => array(												// translations array
				'featured'			 => __('Featured', 'folio-showroom'),
				'notFeatured'		 => __('Not featured', 'folio-showroom'),
			)
		));

		wp_enqueue_style("folio-showroom-admin-styles", AssetResolver::resolve('css/admin.css'), [], NULL);
	}
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\admin_assets');


function filter_login_head()
{
	$image = AssetResolver::resolve('images/logo-uoc-dark.svg');
	$width = 280;
	$height = 65;
	if (has_custom_logo()) {
		$custom = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
		$image = $custom[0];
		$width = $custom[1];
		$height = $custom[2];
	}
?>
	<style type="text/css">
		.login h1 a {
			background-image: url(<?php echo esc_url($image); ?>);
			background-size: contain;
			background-position: center;
			width: <?php echo absint($width) ?>px;
			height: <?php echo absint($height) ?>px;
			max-width: 100%;
		}
	</style>
<?php
}
add_action('login_head', __NAMESPACE__ . '\\filter_login_head', 100);


/**
 * Changing the logo link from wordpress.org to your site
 */
function login_url()
{
	return home_url('/');
}
add_filter('login_headerurl', __NAMESPACE__ . '\\login_url');


/**
 * Changing the alt text on the logo to show your site name
 */
function login_title()
{
	return get_option('blogname');
}
add_filter('login_headertext', __NAMESPACE__ . '\\login_title');
