<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package FolioShowroom
 */

use FolioShowroom\Assets\AssetResolver;
use FolioShowroom\Utils;
use FolioShowroom\Data;

$page_id = get_queried_object_id();

$color_mode = Data\get_theme_option('color_mode');
$color_mode_switcher = Data\get_theme_option('color_switcher');

$header_container = 'container';
$header_width = Data\get_theme_option('header_width');
if (!$header_width) $header_width = 'default';

switch ($header_width) {
	case 'wide';
		$header_container .= ' container-wide';
		break;
	case 'full':
		$header_container = 'container-fluid';
		break;
}

$header_layout = 'header-layout-' . $header_width;
$header_theme = 'header-theme-' . Data\get_theme_option('header_theme');
$header_position = Data\get_theme_option('header_sticky') ? 'position-sticky' : '';
$header_overlay = Data\get_theme_option('header_overlay') ? 'header-overlay' : '';

if ($header_overlay && !$header_position) {
	$header_position = 'position-absolute';
}

$header_search_show = Data\get_theme_option('header_search_show');
$lang_menu_show = Data\get_theme_option('lang_show');
$uoc_foot_show = Data\get_theme_option('uoc_foot_show');


if (is_home() && is_front_page()) { // Is home and latest posts config

	$home_header_theme = Data\get_theme_option('home_header_theme');
	if ($home_header_theme) {
		$header_theme = 'header-theme-' . $home_header_theme;
	}
	$header_overlay = Data\get_theme_option('home_header_overlay') ? 'header-overlay' : '';
} else if (is_category() || is_tag()) { // Category/Tag overrides only if Slider Revolution is enabled

	if ($page_id && class_exists('RevSlider')) {
		$term_header_theme = get_term_meta($page_id, '_folio_showroom_term_header_theme', true);
		if ($term_header_theme) {
			$header_theme = 'header-theme-' . $term_header_theme;
			if (in_array($term_header_theme, Data\get_header_theme_transparent_list())) {
				$header_overlay = 'header-overlay';
			}
		}
	}
} else if ( is_single() || is_page() ) { // Post/page overrrides

	if ($page_id) {
		$post_header_theme = get_post_meta($page_id, '_folio_showroom_header_theme', true);
		$post_header_overlay = wp_validate_boolean(get_post_meta($page_id, '_folio_showroom_header_overlay', true));

		if ($post_header_theme) {
			$header_theme = 'header-theme-' . $post_header_theme;
		}

		if ($post_header_overlay) {
			$header_overlay = 'header-overlay';
		}
	}
}

$body_classes = $header_theme . ' ' . $header_overlay;

?>
<!doctype html>
<html <?php language_attributes(); ?> data-bs-theme="<?php echo $color_mode ?>">

<head>
	<meta charset=" <?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php if (!get_option('site_icon')) : ?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo AssetResolver::resolve('images/apple-touch-icon.png') ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo AssetResolver::resolve('images/favicon-32x32.png') ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo AssetResolver::resolve('images/favicon-16x16.png') ?>">
		<!--[if IE]>
		<link rel="shortcut icon" href="<?php echo AssetResolver::resolve('images/favicon.ico') ?>">
		<![endif]-->
	<?php endif; ?>

	<?php wp_head(); ?>

	<?php if (!$uoc_foot_show) : ?>
		<style type="text/css">
			.uoc_portafolis_footer {
				display: none !important;
			}
		</style>
	<?php endif; ?>

</head>

<body <?php body_class($body_classes); ?>>

	<?php
	if (function_exists('wp_body_open')) {
		wp_body_open();
	}
	?>

	<div id="page" class="site d-flex flex-column min-vh-100">

		<div class="skippy overflow-hidden">
			<a class="skip-link visually-hidden visually-hidden-focusable position-absolute" href="#primary"><?php esc_html_e('Skip to content', 'folio-showroom'); ?></a>
		</div>

		<header id="masthead" class="site-header <?php echo $header_position ?> <?php echo $header_layout ?> <?php echo $header_theme ?> ">

			<div class="<?php echo $header_container ?> d-flex position-relative justify-content-between">

				<div class="site-branding d-flex align-items-center">

					<div class="navbar-brand site-logo">
						<?php
						if (has_custom_logo()) :
							the_custom_logo();
						else :
						?>
							<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="custom-logo-link">
								<?php echo get_template_part('template-parts/svg/uoc', 'logo') ?>
							</a>
						<?php endif; ?>
					</div>

					<?php
					if (is_front_page() && is_home()) :
					?>
						<h1 class="site-title visually-hidden"><?php bloginfo('name'); ?></h1>
					<?php
					else :
					?>
						<p class="site-title visually-hidden"><?php bloginfo('name'); ?></p>
					<?php
					endif;

					$site_description = get_bloginfo('description', 'display');
					if ($site_description || is_customize_preview()) :
					?>
						<p class="site-description visually-hidden"><?php echo $site_description; ?></p>
					<?php
					endif;
					?>

				</div><!-- .site-branding -->

				<?php $nav_class = ($lang_menu_show) ? '' : 'navbar-right';
				?>

				<nav id="site-navigation" class="site-menu navbar navbar-expand flex-fill py-0">

					<div class="d-none d-xl-flex flex-xl-fill">

						<?php
						wp_nav_menu(
							array(
								'theme_location' 	=> 'primary-menu',
								'menu_id'        	=> 'primary-menu',
								'depth'             => 2, // 1 = no dropdowns, > 1 top level dropdown
								'container'	=> '',
								'menu_class'        => 'navbar-nav mx-auto d-flex align-items-center flex-wrap',
								'fallback_cb'       => 'FolioShowroom\Nav\WP_Bootstrap_Navwalker::fallback',
								'walker'            => new FolioShowroom\Nav\WP_Bootstrap_Navwalker(),
							)
						);
						?>

						<div class="top-menu d-flex align-items-center justify-content-end">

							<?php
							wp_nav_menu(
								array(
									'theme_location' 	=> 'secondary-menu',
									'menu_id'        	=> 'secondary-menu',
									'depth'             => 2, // 1 = no dropdowns, > 1 top level dropdown
									'container'	=> '',
									'menu_class'        => 'navbar-nav ms-3',
									'fallback_cb'       => null,
									'walker'            => new FolioShowroom\Nav\WP_Bootstrap_Navwalker(),
								)
							);
							?>

							<?php if ($lang_menu_show) Utils\languages_dropdown(); ?>

						</div>

					</div>

					<?php $actions_class = $header_search_show || $color_mode_switcher ? 'ms-xl-2 me-2 me-xl-0' : ''; ?>

					<div class="header-actions d-flex flex-nowrap ms-auto <?php echo $actions_class ?>">

						<?php if ($header_search_show) : ?>

							<button class="btn-icon offcanvas-search-trigger mx-2" id="offcanvas-search-trigger" title="<?php esc_html_e('Search', 'folio-showroom'); ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-search" aria-controls="offcanvas-search">
								<?php get_template_part('template-parts/svg/icon', 'search') ?>
							</button>

						<?php endif; ?>

						<?php if ($color_mode_switcher) : ?>

							<button class="theme-toggle mx-2" id="theme-toggle" title="Toggles light &amp; dark" aria-label="auto" aria-live="polite">
								<?php get_template_part('template-parts/svg/icon', 'color-mode') ?>
							</button>

						<?php endif; ?>

					</div>

					<div class="d-xl-none">
						<button type="button" id="offcanvas-trigger" class="btn offcanvas-trigger offcanvas-menu-trigger closed" aria-label="<?php esc_html_e('Menu', 'folio-showroom'); ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-menu" aria-controls="offcanvas-menu">
							<span></span>
							<span></span>
							<span></span>
						</button>
					</div>

				</nav><!-- #site-navigation -->

			</div>

			<?php get_template_part('template-parts/menu', 'search'); ?>

		</header><!-- #masthead -->


		<?php get_template_part('template-parts/menu', 'offcanvas'); ?>

		<div id="site-container" class="site-container">

			<div id="site-content" class="site-content mb-5">