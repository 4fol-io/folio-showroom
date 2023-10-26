<?php

/**
 * Template part for displaying offcanvas menu
 *
 * @package FolioShowroom
 */

use FolioShowroom\Utils;
use FolioShowroom\Data;

$container = 'container';
$header_width = Data\get_theme_option('header_width');
if (!$header_width) $header_width = 'default';

switch ($header_width) {
  case 'wide';
    $container .= ' container-wide';
    break;
  case 'full':
    $container = 'container-fluid';
    break;
}

?>
<!--<div class="offcanvas-overlay closed ms-auto d-xl-none"></div>-->

<div id="offcanvas-menu" class="offcanvas offcanvas-full" aria-labelledby="offcanvas-trigger" tabindex="-1" data-bs-backdrop="false">

  <div class="offcanvas-wrap d-flex flex-column <?php echo $container ?>">

    <div class="offcanvas-header position-sticky d-flex justify-content-between align-items-center">

      <div class="site-branding d-flex align-items-center">

        <div class="navbar-brand site-logo">
          <?php
          if (has_custom_logo()) :
            the_custom_logo();
          else :
          ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="custom-logo-link">
              <?php echo get_template_part('template-parts/svg/uoc', 'logo') ?>
            </a>
          <?php endif; ?>
        </div>

      </div>

      <button type="button" title="<?php esc_attr_e('Close', 'folio-showroom'); ?>" aria-label="<?php esc_attr_e('Close', 'folio-xarsa'); ?>" data-bs-dismiss="offcanvas" class="btn offcanvas-trigger offcanvas-menu-trigger offcanvas-close me-n1">
        <span></span>
        <span></span>
        <span></span>
      </button>

    </div>

    <nav role="navigation" class="offcanvas-menu mt-3 my-sm-auto py-2">
      <?php
      wp_nav_menu(array(
        'theme_location'    => 'offcanvas-menu',
        'menu_id'            => 'offcanvas-menu',
        'depth'             => 2, // 1 = no dropdowns, 2 = with dropdowns.
        'container'         => 'div',
        'container_class'   => 'navbar',
        'container_id'      => 'offcanvas-menu-container',
        'menu_class'        => 'navbar-nav nav',
        'fallback_cb'       => 'FolioShowroom\Nav\WP_Bootstrap_Navwalker::fallback',
        'walker'            => new FolioShowroom\Nav\WP_Bootstrap_Navwalker(),
      ));
      ?>

    </nav>

    <?php
    if (Data\get_theme_option('lang_show')) Utils\languages_offcanvas();
    ?>

  </div>

</div>