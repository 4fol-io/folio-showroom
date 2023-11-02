<?php

/**
 * Folio Showroom functions and definitions
 *
 * @package FolioShowroom
 */

define('FOLIO_SHOWROOM_THEME_VERSION',  '1.0.0');
define('FOLIO_SHOWROOM_PREFIX',         'folio_showroom_');
define('FOLIO_SHOWROOM_NO_IMAGE',       get_stylesheet_directory_uri() . '/dist/images/no-image.jpg');
define('FOLIO_SHOWROOM_NO_AVATAR',      get_stylesheet_directory_uri() . '/dist/images/default-avatar.png');

$folio_showroom_includes = array(
    '/clean.php',                           // Clean head, inline styles,...
    '/setup.php',                           // Theme setup and custom theme supports
    '/meta.php',                            // Custom metada registration
    '/data.php',                            // Theme settings defaults and data utilities
    '/images.php',                          // Theme image custom utils
    '/assets.php',                          // Assets management
    '/blocks.php',                          // Blocks column count, (alingwide, alignfull) wrapper
    '/utils.php',                           // General utils, filters, action hooks,...
    '/nav.php',                             // Custom Bootstrap Nav Walker
    '/pagination.php',                      // Custom pagination
    '/templates.php',                       // Custom templates for this theme
    '/customizer.php',                      // Customizer preview
    '/color.php',                           // Sass like color adjust methods for generated css
    '/styles.php',                          // Generated css
);



if (defined('JETPACK__VERSION')) {
    $folio_showroom_includes[] = '/jetpack.php';     // Load Jetpack compatibility file
}

/**
 * Include theme dependencies
 */
foreach ($folio_showroom_includes as $file) {
    $filepath = locate_template('/inc' . $file);
    if (!$filepath) {
        trigger_error(sprintf('Error locating /inc%s for inclusion', $file), E_USER_ERROR);
    }
    require_once $filepath;
}



/**
 * SOLO PARA PROBAR EL CUSTOM AVATAR EN ENTORNO LOCAL
 * TODO: MEJOR QUITAR EN PRODUCCIÓN
 */
add_filter('get_avatar', 'folio_showroom_localhost_avatar', 10, 5);

function folio_showroom_localhost_avatar($avatar, $id_or_email, $size, $default, $alt)
{
    $whitelist = array('localhost', '127.0.0.1');

    if (!in_array($_SERVER['SERVER_ADDR'], $whitelist))
        return $avatar;

    $doc = new DOMDocument;
    $doc->loadHTML( $avatar );
    $imgs = $doc->getElementsByTagName('img');
    if ( $imgs->length > 0 ) {
        $url = urldecode( $imgs->item(0)->getAttribute('src') );
        $url2 = explode( 'd=', $url );
        if ( count($url2) > 1){
            $url3 = explode( '&', $url2[1] );
            $avatar= "<img src='{$url3[0]}' alt='' class='avatar avatar-64 photo' height='64' width='64' />";
        }
    }

    return $avatar;
}
