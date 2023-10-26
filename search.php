<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package FolioShowroom
 */

use FolioShowroom\Pagination;
use FolioShowroom\Data;

get_header();


$archive_width = Data\get_theme_option('archive_width');
$archive_container = 'container';
switch ($archive_width){
	case 'wide';
		$archive_container .= ' container-wide';
		break;
	case 'full':
		$archive_container = 'container-fluid';
		break;
}

?>
	<main id="primary" class="site-main layout-grid <?php echo $archive_container; ?>">

		<?php if ( have_posts() ) : ?>

			<header class="page-header archive-page-header">
				<h1 class="page-title display-2 text-end my-5">
					<?php
					echo '<em>' . __( 'Search Results for:', 'folio-showroom' ) . '</em> <strong>' . get_search_query() . '</strong>';
					?>
				</h1>

				<?php get_search_form(); ?>

			</header><!-- .page-header -->

			<div class="row grid-row">

			<?php

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				?>
				<div class="grid-col col-xs-12 col-md-6 col-lg-4 col-xl-3 d-flex flex-column">
				<?php

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'archive' );

				?>
				</div>
				<?php

			endwhile;

			?>
			</div>
			<?php

			Pagination\pagination();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
