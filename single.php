<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package FolioShowroom
 */

use FolioShowroom\Templates;

get_header();

$content_width = 'default';
$content_layout = 'content-width-default';
/*
switch ($content_width){
	case 'wide';
		$content_layout = 'content-width-wide';
		break;
	case 'full':
		$content_layout = 'content-width-full';
		break;
	default: 
		$content_layout = 'content-width-default';
}*/

?>

	<main id="primary" class="site-main <?php echo $content_layout; ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'single' );


			Templates\comments_section();
			

		endwhile; // End of the loop.
		?>

		<?php 
			Templates\related_posts();
		?>

	</main><!-- #main -->

	

<?php
get_footer();
