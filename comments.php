<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FolioShowroom
 */

Use FolioShowroom\Pagination;
Use FolioShowroom\Templates;
Use FolioShowroom\Data;

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
if ( ! Data\get_theme_option('comments_show')){
	return;
}
?>

<section id="comments" class="comments-area text-left">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$comm_num = get_comments_number();
			if ( '1' === $comm_num ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'folio-showroom' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comm_num, 'comments title', 'folio-showroom' ) ),
					number_format_i18n( $comm_num ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->


		<ol class="comment-list <?php echo get_option('show_avatars', 0) ? 'with-avatars' : 'no-avatars'; ?>">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'avatar_size' => 70,
					'short_ping' => true,
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		Pagination\comments_pagination();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<div class="alert alert-info">
				<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'folio-showroom' ); ?></p>
			</div>
			<?php
		endif;

	endif; // Check for have_comments().

	Templates\comments_form();
	?>

</section><!-- #comments -->
