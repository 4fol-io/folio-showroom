<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FolioShowroom
 */

use FolioShowroom\Templates;

$hide_title = get_post_meta( get_the_ID(), '_folio_showroom_hide_title', true );
$hide_meta = get_post_meta( get_the_ID(), '_folio_showroom_hide_meta', true );
$hide_featured_img = get_post_meta( get_the_ID(), '_folio_showroom_hide_featured_img', true );


$header_class = $hide_title == '1' ? 'hidden-title' : '';
$header_class .= $hide_meta == '1' ? ' hidden-meta' : '';
$header_class .= $hide_title && $hide_meta ? '' : ' my-5';

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<header class="entry-header <?php echo $header_class; ?>">

		<?php 
		if ( $hide_title != '1' ) {
 			the_title('<h1 class="entry-title display-2 fw-semibold mb-3">', '</h1>');
		}
		?>

		<?php if ('post' === get_post_type() &&  $hide_meta != '1' ) :
		?>
			<div class="entry-meta d-flex flex-nowrap align-items-start justify-content-start">
				<?php
				Templates\posted_by();
				Templates\posted_on_ago();
				Templates\cats_list_single(); 
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php 
		if ( $hide_featured_img != '1' ) {
 			Templates\featured_image();
		}
	?>

	<div class="entry-content clearfix">
		<?php
		the_content(sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__('Continue reading<span class="visually-hidden"> "%s"</span>', 'folio-showroom'),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			wp_kses_post( get_the_title() )
		));
		?>

	</div><!-- .entry-content -->


	<footer class="entry-footer position-relative my-5">
		<?php Templates\entry_footer(); ?>
		<?php Templates\post_navigation(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->