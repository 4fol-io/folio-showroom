<?php

/**
 * FolioShowroom pagination
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Pagination;

// Exit if accessed directly.
defined('ABSPATH') || exit;


/**
 * Adjuns pagination links (screen readers prefix && leading zero)
 */
function pagination_link($link)
{
  $n = (int)strip_tags($link);
  $before = '';
  if ($n > 0) {
    $before = '<span class="visually-hidden">' . __('Page', 'folio-showroom') . ' </span>';
  }
  if ($n < 10) {
    $before .= '0';
  }
  echo str_replace('page-numbers', 'page-link', str_replace('<span class="before"></span>', $before, $link));
}


/**
 * Render pagination
 */
function render_pagination($current = 0, $total = 0, $links = array(), $args = array())
{

  if (empty($links) || empty($args)) return false;

  $num = count($links);

  $last = $num - 1;
  $rest = 10 - $last;
  if ($current == 1 || $current == $total) $rest -= 1;
  if ($current == $total) $last += 1;

?>
  <nav class="pagination-nav" aria-label="<?php echo $args['screen_reader_text']; ?>">
    <ul class="pagination d-flex flex-row flex-wrap justify-content-center">
      <?php
      /*if ($current == 1) {
      ?>
        <li class="pagination__item pagination__item--prev">
          <span class="btn-prev btn btn-primary disabled"><?php echo __('Previous', 'folio-showroom') ?></a>
        </li>
        <?php
      }*/
      $count = 1;
      foreach ($links as $key => $link) {

        if (strpos($link, 'prev ')) {
      ?>
          <li class="pagination__item pagination__item--prev">
            <?php echo str_replace('page-numbers', 'btn btn-primary', $link); ?>
          </li>
        <?php
        } else if (strpos($link, 'next ')) {
        ?>
          <li class="pagination__item pagination__item--next">
            <?php echo str_replace('page-numbers', 'btn btn-primary', $link); ?>
          </li>
        <?php
        } else {
          $class = $count == $last ? 'flex-grow-1' : '';
        ?>
          <li class="<?php echo $class; ?> pagination__item <?php echo strpos($link, 'current') ? 'active' : '' ?>">
            <?php pagination_link($link); ?>
          </li>
      <?php
        }
        $count++;
      }
      /*if ($current == $total) {
        ?>
        <li class="pagination__item pagination__item--next">
          <span class="btn-next btn btn-primary disabled"><?php echo __('Next', 'folio-showroom') ?></a>
        </li>
      <?php
      }*/
      ?>
    </ul>
  </nav>
<?php
}


/**
 * Posts pagination
 */
function pagination($args = array())
{

  $total = $GLOBALS['wp_query']->max_num_pages;
  if ($total <= 1) return;
  $current =  max(1, get_query_var('paged'));

  $end = 1;
  $mid = 1;
  if ($current >= $total - 2 || $current < 3) $end = 3;
  if ($current == 3 || $current == $total - 2) {
    $end = 1;
    $mid = 2;
  };
  $all = ($total <= 7) ? true : false;

  $args = wp_parse_args($args, array(
    'end_size'           => $end,
    'mid_size'           => $mid,
    'show_all'           => $all,
    'prev_next'          => true,
    'prev_text'          => __('Previous', 'folio-showroom'),
    'next_text'          => __('Next', 'folio-showroom'),
    'screen_reader_text' => __('Navigation', 'folio-showroom'),
    'type'               => 'array',
    'current'            => $current,
    'before_page_number' => '<span class="before"></span>'
  ));

  $links = paginate_links($args);

  render_pagination($current, $total, $links, $args);
}



/**
 * Comments pagination
 */
function comments_pagination($args = array())
{
  $total = get_comment_pages_count();

  if ($total <= 1) return;
  $current =  max(1, get_query_var('cpage'));

  $end = 1;
  $mid = 1;
  if ($current >= $total - 2 || $current < 3) $end = 3;
  if ($current == 3 || $current == $total - 2) {
    $end = 1;
    $mid = 2;
  };
  $all = ($total <= 7) ? true : false;

  $args = wp_parse_args($args, array(
    'end_size'           => $end,
    'mid_size'           => $mid,
    'prev_next'          => true,
    'prev_text'          => __('Previous', 'folio-showroom'),
    'next_text'          => __('Next', 'folio-showroom'),
    'screen_reader_text' => __('Debate navigation', 'folio-showroom'),
    'type'               => 'array',
    'echo'               => false,
    'current'            => $current,
    'before_page_number' => '<span class="before"></span>'
  ));

  $links = paginate_comments_links($args);

  render_pagination($current, $total, $links, $args);
}




/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function navigation()
{
  // Don't print empty markup if there's only one page.
  if ($GLOBALS['wp_query']->max_num_pages < 2) {
    return;
  }
?>
  <nav class="navigation paging-navigation" role="navigation">
    <h1 class="visually-hidden"><?php esc_html_e('Posts navigation', 'folio-showroom'); ?></h1>
    <div class="nav-links">

      <?php if (get_next_posts_link()) : ?>
        <div class="nav-previous"><span class="nav-icon nav-icon-prev"></span> <span class="nav-lbl"><?php next_posts_link(__('Older posts', 'folio-showroom')); ?></span></div>
      <?php endif; ?>

      <?php if (get_previous_posts_link()) : ?>
        <div class="nav-next"><span class="nav-lbl"><?php previous_posts_link(__('Newer posts', 'folio-showroom')); ?></span> <span class="nav-icon nav-icon-next"></span></div>
      <?php endif; ?>

    </div><!-- .nav-links -->
  </nav><!-- .navigation -->
<?php
}
