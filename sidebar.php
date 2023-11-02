<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package FolioShowroom
 */

use FolioShowroom\Data;

if ( ! is_active_sidebar( 'footer' ) )
	return;

	$footer_container = 'container';
	$footer_width = Data\get_theme_option('footer_width');
	if (!$footer_width) $footer_width = 'default';
	
	switch ($footer_width) {
		case 'wide';
			$footer_container .= ' container-wide';
			break;
		case 'full':
			$footer_container = 'container-fluid';
			break;
	}
?>

<aside id="secondary" class="widget-area footer-widgets <?php echo $footer_container ?>">
	<?php dynamic_sidebar( 'footer' ); ?>
</aside><!-- #secondary -->
