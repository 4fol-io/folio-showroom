<?php

/**
 * FolioShowroom templates utilities
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Templates;

use FolioShowroom\Utils;
use FolioShowroom\Data;


// Exit if accessed directly.
defined('ABSPATH') || exit;


/**
 * Prints page links for paginated posts (i.e. including the <!--nextpage--> quicktag)
 */
function link_pages()
{
	wp_link_pages(array(
		'before' => '<div class="page-links">' . esc_html__('Pages:', 'folio-showroom'),
		'after'  => '</div>',
	));
}


/**
 * Print the customized post navigation for desktop
 */
function post_navigation()
{
	$prev_lbl = Data\get_theme_option('nav_prev_text');
	$next_lbl = Data\get_theme_option('nav_next_text');
	if (!$prev_lbl) $prev_lbl = __('Previus post', 'folio-showroom');
	if (!$next_lbl) $next_lbl = __('Next post', 'folio-showroom');
	the_post_navigation(array(
		'prev_text' => '<span class="nav-icon nav-icon-prev"></span><span class="nav-lbl">' . $prev_lbl . '<span class="visually-hidden">%title</span></span>',
		'next_text' => '<span class="nav-lbl">' . $next_lbl . '<span class="visually-hidden">%title</span></span> <span class="nav-icon nav-icon-next"></span>',
	));
}


/**
 * Comments link button
 */
function comments_link()
{

	$display_comments = Data\get_theme_option('comments_show');

	if (!$display_comments) return false;

	if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {

		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__('Leave a Comment<span class="visually-hidden"> on %s</span>', 'folio-showroom'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post(get_the_title())
			)
		);
		echo '</span>';
	}
}


/**
 * Edit link
 */
function edit_link()
{
	if (get_edit_post_link()) :
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Edit %s', 'folio-showroom'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				'<span class="visually-hidden">' . get_the_title() . '</span>'
			),
			null,
			null,
			null,
			'btn btn-outline-secondary btn-sm post-edit-link mb-2'
		);
	endif;
}


/**
 * Prints HTML with meta information for the current post-date/time.
 */
function posted_on()
{
	//$time_string = '<span class="icon icon--calendar icon--xsmall icon--before" aria-hidden="true"></span>';
	$time_string = '';
	if (get_the_time('U') !== get_the_modified_time('U')) {
		$time_string .= '<span class="visually-hidden">%5$s </span><time class="entry-date published" datetime="%1$s">%2$s</time> <time class="updated visually-hidden" datetime="%3$s">%4$s</time>';
	} else {
		$time_string .= '<span class="visually-hidden">%3$s </span><time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr(get_the_date(DATE_W3C)),
		esc_html(get_the_date()),
		esc_attr(get_the_modified_date(DATE_W3C)),
		esc_html(get_the_modified_date() . ' ' . get_the_modified_time()),
		__('Publication date', 'folio-showroom')
	);


	//$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
	$posted_on = '<a href="' . esc_url(get_day_link(
		get_post_time('Y'),
		get_post_time('m'),
		get_post_time('j')
	)) . '" rel="bookmark">' . $time_string . '</a>';

	echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function posted_on_ago()
{
	$posted_on = '<a href="' . esc_url(get_day_link(
		get_post_time('Y'),
		get_post_time('m'),
		get_post_time('j')
	)) . '" rel="bookmark">' . Utils\format_date_time_ago() . '</a>';

	$time_string = '<span class="meta-lbl">%1$s</span> <span class="meta-value">%2$s</span>';

	$time_string = sprintf(
		$time_string,
		__('Publication date'),
		$posted_on,
	);

	echo '<span class="posted-on">' . $time_string . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}


/**
 * Prints HTML with meta information for the current author.
 */
function posted_by()
{

	$author = get_post_meta(get_the_ID(), '_folio_showroom_author_fullname', true);
	$url = get_post_meta(get_the_ID(), '_folio_showroom_author_url', true);

	if ($author) {
		if ($url) {
			$byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url($url) . '" target="_blank">' . esc_html($author) . '</a></span>';
		} else {
			$byline = '<span class="author vcard">' . esc_html($author) . '</span>';
		}
	} else {
		$byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>';
	}

	echo '<span class="byline"><span class="meta-lbl">' . __('Authorship', 'folio-showroom') . ' </span> <span class="meta-value">' . $byline . '</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
 * Overrides default author if author post custom meta exists
 */
function posted_by_archive()
{

	$author = get_post_meta(get_the_ID(), '_folio_showroom_author_fullname', true);

	if ($author) {
		$avatar_id = get_post_meta(get_the_ID(), '_folio_showroom_author_avatar', true);
		$url = get_post_meta(get_the_ID(), '_folio_showroom_author_url', true);

		if ($avatar_id) {
			$image = wp_get_attachment_image($avatar_id, 'thumbnail', false, array('class' => 'avatar avatar-64 photo'));
		} else {
			$image = '<img src="' . FOLIO_SHOWROOM_NO_AVATAR . '" class="avatar avatar-64 photo" height="64" width="64" alt="">';
		}
		if ($url) {
			$byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url($url) . '" target="_blank">' . $image . esc_html($author) . '</a></span>';
		} else {
			$byline = '<span class="author vcard">' . $image . esc_html($author) . '</span>';
		}
	} else {

		$byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . get_avatar(get_the_author_meta('ID'), 48) . esc_html(get_the_author()) . '</a></span>';
	}

	echo '<span class="byline"><span class="visually-hidden">' . __('by', 'folio-showroom') . ' </span>' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}



/**
 * Displays post tag list
 */
function tags_list()
{
	if (Data\get_theme_option('tags_show')) {
		$tags_list = get_the_tag_list('<ul class="list-unstyled list-inline" role="list"><li class="list-inline-item">', '</li><li class="list-inline-item">', '</li></ul>');
		if ($tags_list) {
			printf(
				'<div class="entry-tags"><span class="visually-hidden">%1$s </span>%2$s</div>',
				__('Tags', 'folio-showroom'),
				$tags_list
			);
		}
	}
}


/**
 * Displays post cats list
 */
function cats_list()
{
	if (Data\get_theme_option('cats_show')) {
		$categories_list = get_the_category_list(', ');
		if ($categories_list) {
			printf(
				'<div class="entry-cats"><span class="visually-hidden">%1$s </span>%2$s</div>',
				__('Categories', 'folio-showroom'),
				$categories_list
			);
		}
	}
}

/**
 * Displays post cats list
 */
function cats_list_single()
{
	if (Data\get_theme_option('cats_show')) {
		$categories_list = get_the_category_list('<span class="sep"> / </span>');
		if ($categories_list) {
			printf(
				'<span class="entry-cats"><span class="meta-lbl">%1$s</span> <span class="meta-value">%2$s</span></span>',
				__('Categories', 'folio-showroom'),
				$categories_list
			);
		}
	}
}


/**
 * Prints HTML with meta information for entry footer (tags list, edit link)
 */
function entry_footer()
{
	tags_list();
	edit_link();
}



/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function post_thumbnail($size = 'post-thumbnail')
{

	if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
		return;
	}


	if (is_singular()) :
?>
		<div class="entry-media">
			<div class="post-thumbnail cover-img">
				<?php the_post_thumbnail($size, array('class' => 'img-cover fade', 'sizes' => 'calc(100vw - 30px)')); ?>
			</div>
		</div>
	<?php else : ?>
		<div class="entry-media">
			<a class="post-thumbnail cover-img" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail($size, array(
					'class' => 'img-cover fade',
					'alt'   => the_title_attribute(array(
						'echo' => false,
					)),
				));
				?>
			</a>
		</div>
	<?php
	endif; // End is_singular().
}


/**
 * Displays featured image post thumbnail.
 *
 */
function featured_image(
	$size = 'folio-showroom-featured',
	$img_class = 'img-fluid fade',
	$container_class = 'featured-img mb-5'
) {
	if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
		return;
	}
	?>
	<div class="<?php echo esc_attr($container_class) ?>">
		<?php the_post_thumbnail($size, array('class' => $img_class, 'sizes' => 'calc(100vw - 30px)')); ?>
	</div>
	<?php
}


/**
 * Display comments template secion
 */
function comments_section()
{

	$display_comments = Data\get_theme_option('comments_show');
	if (!$display_comments) return false;

	// If comments are open or we have at least one comment, load up the comment template.
	if (comments_open() || get_comments_number()) :
		comments_template();
	endif;
}


/**
 * Custom comments form
 */
function comments_form()
{

	$user          = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';
	$user_avatar   = $user->exists() ? get_avatar($user->ID, 70) : '';
	$req_msg = '<div class="invalid-feedback">' . __('Please enter the message', 'folio-showroom') . '</div>';

	$user_link = sprintf(
		'<a href="%1$s" aria-label="%2$s" class="fn me-1">%3$s</a> <a href="%4$s" class="logout btn btn-sm btn-outline-default ms-auto">%5$s</a>',
		get_edit_user_link(),
		/* translators: %s: User name. */
		esc_attr(sprintf(__('Logged in as %s. Edit your profile.'), $user_identity)),
		$user_identity,
		wp_logout_url(apply_filters('the_permalink', get_permalink())),
		__('Log out?', 'folio-showroom')
	);

	$comments_args = array(
		'comment_field' => '<div class="form-group comment-form-comment mb-3"><label id="lbl-comment" for="comment" class="visually-hidden">' .
			__('Your comment', 'folio-showroom') . '</label> <textarea class="form-control" placeholder="' .
			__('Your message', 'folio-showroom') . '" id="comment" name="comment" rows="3" required aria-required="true"></textarea>' . $req_msg . '</div>',
		'class_form'           => 'form comment-form needs-validation',
		'class_submit'         => 'submit btn btn-primary',
		'label_submit'         => __('Send comment', 'folio-showroom'),
		'title_reply'          => __('Leave a comment', 'folio-showroom'),
		'cancel_reply_link'    => __('Cancel Reply', 'folio-showroom'),
		'submit_field'         => '<span class="form-submit d-inline-block">%1$s %2$s</span>',
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
		'logged_in_as'         => sprintf(
			'<div class="logged-in-as d-flex align-items-center mb-4">%s %s</div>',
			$user_avatar,
			$user_link
		),
	);

	comment_form($comments_args);
}


/**
 * Comment form default fields
 */
function comment_form_default_fields($fields)
{

	//var_dump($fields);

	$commenter     = wp_get_current_commenter();
	$req       = get_option('require_name_email');
	$aria_req  = ($req ? 'required aria-required="true"' : '');
	$req_name  = $req ? '<div class="invalid-feedback">' . __('Please enter the name', 'folio-showroom') . '</div>' : '';
	$req_email = $req ? '<div class="invalid-feedback">' . __('Please enter a valid email', 'folio-showroom') . '</div>' : '';

	$fields['author'] = '<div class="form-group comment-form-author mb-3">' .
		'<label id="lbl-author" for="author" class="visually-hidden">' . __('Name', 'folio-showroom') . '</label> ' .
		'<input class="form-control" placeholder="' . __('Name') . '" id="author" name="author" type="text" value="' .
		esc_attr($commenter['comment_author']) . '" ' . $aria_req . ' />' . $req_name . '</div>';

	$fields['email']  = '<div class="form-group comment-form-email mb-3">' .
		'<label id="lbl-email" for="email" class="visually-hidden">' . __('Your email') . '</label> ' .
		'<input class="form-control" placeholder="' . __('Your email') . '"id="email" name="email" type="email" value="' .
		esc_attr($commenter['comment_author_email']) . '" ' . $aria_req . ' />' . $req_email . '</div>';

	$fields['url']    = '<div class="form-group comment-form-url mb-3">' .
		'<label id="lbl-url" for="url" class="visually-hidden">' . __('Website') . '</label>' .
		'<input class="form-control" placeholder="' . __('Website') . '" id="url" name="url" type="url" value="' .
		esc_attr($commenter['comment_author_url']) . '" /></div>';

	if (has_action('set_comment_cookies', 'wp_set_comment_cookies') && get_option('show_comments_cookies_opt_in')) {
		$consent           = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
		$fields['cookies'] = '<div class="form-group comment-form-cookies-consent mb-3"><div class="form-check">' .
			'<input class="form-check-input" id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" ' . $consent . ' />' .
			'<label class="form-check-label" for="wp-comment-cookies-consent">' .
			__('Save my name, email, and website in this browser for the next time I comment.') . '</label></div></div>';
	}

	return $fields;
}

add_filter('comment_form_fields',  __NAMESPACE__ . '\\comment_form_default_fields');



/**
 * Render related posts by category/tag
 */
function related_posts()
{

	$limit = Data\get_theme_option('related_posts_limit');
	$post_id = get_the_ID();
	$post_type = get_post_type($post_id);
	$tax_ids = array();

	$query_args = array(
		'post_type'      		=> $post_type,
		'post__not_in'    		=> array($post_id),
		'posts_per_page'  		=> $limit,
		'ignore_sticky_posts' 	=> true,
		'orderby'             	=> 'rand',
	);

	$tax_ids = Utils\post_taxonomy_field_array('category', 'ids');

	// Discard if only has "uncategorized"
	if (count($tax_ids) === 1 && $tax_ids[0] === 1) {
		$tax_ids = array();
	}

	if (!empty($tax_ids)) {
		$query_args['category__in'] = $tax_ids;
	} else {
		$tax_ids = Utils\post_taxonomy_field_array('post_tag', 'ids');
		if (!empty($tax_ids)) {
			$query_args['tag__in'] = $tax_ids;
		}
	}

	$related_posts = new \WP_Query($query_args);

	$col_classes = 'col-xs-12 col-md-6';
	$col_classes .= $limit >= 9 ? ' col-lg-4' : '';

	if ($related_posts->have_posts()) :
		$related_posts_title = Data\replace_placeholders(Data\get_theme_option('related_posts_title'));
	?>
		<section class="related-posts layout-grid">
			<h2 class="display-3 my-5"><?php echo $related_posts_title ?></h2>
			<ul class="list-unstyled row grid-row">
				<?php
				while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
					<li class="grid-col d-flex flex-column <?php echo $col_classes ?>">
						<?php get_template_part('template-parts/content', 'archive'); ?>
					</li>
				<?php
				endwhile;
				?>
			</ul>
		</section>
<?php
		wp_reset_postdata();
	endif;
}
