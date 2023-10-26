<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FolioShowroom
 */

use FolioShowroom\Pagination;
use FolioShowroom\Data;
use FolioShowroom\Utils;

get_header();

$archive_container = 'container';
$archive_width = Data\get_theme_option('archive_width');
switch ($archive_width) {
	case 'wide';
		$archive_container .= ' container-wide';
		break;
	case 'full':
		$archive_container = 'container-fluid';
		break;
}

$slider_id = false;
if ((is_category() || is_tag()) && class_exists('RevSlider')) {
	$term_id = get_queried_object_id();
	$slider_id = $term_id ? get_term_meta($term_id, '_folio_showroom_term_slider', true) : false;
}

?>

<main id="primary" class="site-main layout-grid <?php echo $archive_container; ?>">

	<?php
	if ($slider_id) {
		Utils\add_slider_by_id($slider_id);
	}
	?>

	<?php if (have_posts()) : ?>

		<header class="page-header archive-page-header my-5">
			<?php
			the_archive_title('<h1 class="page-title display-2 text-end mb-2">', '</h1>');
			the_archive_description('<div class="archive-description display-5 fst-italic text-end">', '</div>');
			?>
		</header><!-- .page-header -->

		<div class="row grid-row">

			<?php
			/* Start the Loop */
			while (have_posts()) :
				the_post();
			?>
				<div class="grid-col col-xs-12 col-md-6 col-lg-4 col-xl-3 d-flex flex-column">
					<?php
					/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
					get_template_part('template-parts/content',  'archive');
					?>
				</div>
			<?php
			endwhile;
			?>

		</div>

	<?php

		Pagination\pagination();

	else :

		get_template_part('template-parts/content', 'none');

	endif;
	?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
