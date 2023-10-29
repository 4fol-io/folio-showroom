<?php

/**
 * Template part for displaying offcanvas search bar
 *
 * @package FolioShowroom
 */

use FolioShowroom\Data;

$header_container = 'container';
$header_width = Data\get_theme_option('header_width');
if (!$header_width) $header_width = 'default';

switch ($header_width) {
  case 'wide';
    $header_container .= ' container-wide';
    break;
  case 'full':
    $header_container = 'container-fluid';
    break;
}

$search_placeholder = sprintf(__('Search in %s...', 'folio-showroom'), get_bloginfo('name'));

?>

<div id="offcanvas-search" class="offcanvas offcanvas-top offcanvas-search ms-auto d-flex align-items-center" aria-labelledby="offcanvas-search-trigger" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1">

  <div class="<?php echo $header_container ?> my-auto">

    <div class="d-flex position-relative justify-content-between align-items-center">

      <form role="search" method="get" class="search-form flex-grow-1 ms-1 me-3" action="<?= esc_url(home_url('/')); ?>">
        <label class="visually-hidden"><?php _e('Search for:', 'folio-showroom'); ?></label>
        <div class="input-group">
          <input type="search" name="s" value="<?= get_search_query(); ?>" class="search-field form-control ps-1" placeholder="<?php esc_attr_e($search_placeholder); ?>" required>
          <div class="input-group-append pe-1">
            <button type="submit" class="search-submit btn-icon h-100" title="<?php esc_attr_e('Search', 'folio-showroom'); ?>">
              <?php echo get_template_part('template-parts/svg/icon', 'search') ?>
            </button>
          </div>
        </div>
      </form>

      <button type="button" title="<?php esc_attr_e('Close', 'folio-showroom'); ?>" aria-label="<?php esc_attr_e('Close', 'folio-showroom'); ?>" class="btn offcanvas-trigger offcanvas-search-trigger offcanvas-close" data-bs-dismiss="offcanvas">
        <span></span>
        <span></span>
        <span></span>
      </button>

    </div>


  </div>

</div>