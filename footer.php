<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package FolioShowroom
 */

use FolioShowroom\Data;

$gototop_show = Data\get_theme_option('gototop_show');
$footer_text = Data\replace_placeholders(Data\get_theme_option('footer_text'));

$footer_container = 'container';
$header_width = Data\get_theme_option('header_width');
if (!$header_width) $header_width = 'default';

switch ($header_width) {
	case 'wide';
		$footer_container .= ' container-wide';
		break;
	case 'full':
		$footer_container = 'container-fluid';
		break;
}

?>
</div><!-- #site-content -->

<?php get_sidebar(); ?>

</div><!-- #site-container -->


<footer id="site-colophon" class="site-footer mt-auto text-center">

	<div class="<?php echo $footer_container ?>">


		<?php if ($gototop_show) : ?>
			<div class="sticky-scroll-foot d-none d-md-block">
				<a class="sticky-scroll off" role="button" href="#page">
					<span class="visually-hidden"><?php echo __("Go to top", "folio-showroom"); ?></span>
				</a>
			</div><!-- .sticky-scroll-foot -->
		<?php endif; ?>


		<?php if ($footer_text !== '') : ?>
			<div class="footer-text small py-4">
				<?php echo $footer_text ?>
			</div><!-- .footer_text -->
		<?php endif; ?>

	</div>

</footer><!-- #site-colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>