<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FolioShowroom
 */

use FolioShowroom\Pagination;
use FolioShowroom\Data;
use FolioShowroom\Utils;

get_header();

$archive_width = Data\get_theme_option('archive_width');
$archive_container = 'container';
switch ($archive_width) {
	case 'wide';
		$archive_container .= ' container-wide';
		break;
	case 'full':
		$archive_container = 'container-fluid';
		break;
}

?>

<main id="primary" class="site-main layout-grid <?php echo $archive_container; ?>">
	<?php
	if (have_posts()) :

		if (is_home() && !is_front_page()) :
	?>
			<header>
				<h1 class="page-title visually-hidden"><?php single_post_title(); ?></h1>
			</header>
			<?php
		elseif (is_home() && is_front_page()) :

			$home_slider = Data\get_theme_option('home_slider');
			$home_title = Data\replace_placeholders(Data\get_theme_option('home_title'));

			Utils\add_slider_by_id( $home_slider );

			if ($home_title) :
			?>
				<header class="my-5 my-xxl-5 pt-xxl-5 pb-xxl-2">
					<h1 class="page-title display-1 text-uppercase text-end"><?php echo $home_title; ?></h1>
				</header>
		<?php
			endif;
		endif;

		?>

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
					get_template_part('template-parts/content', 'archive');

					?>
				</div>
			<?php

			endwhile;

			?>
		</div>
	<?php

		//the_posts_navigation();
		Pagination\pagination();

	else :

		get_template_part('template-parts/content', 'none');

	endif;
	?>

</main><!-- #main -->

<?php
get_footer();
