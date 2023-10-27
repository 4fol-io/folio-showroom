<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FolioShowroom
 */

use FolioShowroom\Templates;
use FolioShowroom\Utils;

$hide_title = get_post_meta(get_the_ID(), '_folio_showroom_hide_title', true);
$hide_featured_img = get_post_meta( get_the_ID(), '_folio_showroom_hide_featured_img', true );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ($hide_title != '1') : ?>
		<header class="entry-header my-5">
			<?php the_title('<h1 class="entry-title display-2 text-uppercase fw-bold mb-3">', '</h1>'); ?>
			<?php Utils\the_breadcrumb(); ?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<?php 
		if ( $hide_featured_img != '1' ) {
 			Templates\featured_image();
		}
	?>

	<div class="entry-content clearfix">
		<?php
		the_content();

		Templates\link_pages();
		?>
	</div><!-- .entry-content -->

	<?php if (get_edit_post_link()) : ?>
		<footer class="entry-footer position-relative my-5">
			<?php Templates\edit_link() ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->