<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package FolioShowroom
 */

if ( ! is_active_sidebar( 'footer' ) )
	return;
?>

<aside id="secondary" class="widget-area footer-widgets">
	<div class="row">
		<?php dynamic_sidebar( 'footer' ); ?>
	</div>
</aside><!-- #secondary -->
