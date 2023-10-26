<?php

/**
 * Template part for displaying results in archive and search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FolioShowroom
 */

use FolioShowroom\Templates;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('mb-5 pb-3 d-flex flex-column h-100'); ?>>

	<header class="entry-header grid-entry-header mt-auto w-100">


		<?php // Templates\cats_list(); 
		?>

		<?php the_title(sprintf('<h2 class="entry-title display-4 fw-semibold pb-4 lh-2"><a href="%s" rel="bookmark" class="text-decoration-none">', esc_url(get_permalink())), '</a></h2>'); ?>

		<?php if ('post' === get_post_type()) : ?>
			<div class="entry-meta grid-entry-meta">
				<?php
				//Templates\posted_on();
				Templates\posted_by_archive();
				Templates\comments_link();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content grid-entry-content m-0">

		<?php
		if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
		?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
		<?php
		} else {
		?>
			<a href="<?php echo esc_url(get_permalink()) ?>" rel="bookmark" class="text-decoration-none">
				<?php Templates\featured_image('large', 'img-cover fade', 'featured-img grid-featured-img mb-0'); ?>
			</a>
		<?php
		}
		?>


	</div>

	<footer class="entry-footer grid-entry-footer">
		<?php //Templates\entry_footer(); 
		?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->