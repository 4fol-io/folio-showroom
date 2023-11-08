<?php

namespace FolioShowroom\Styles;

use FolioShowroom\Color;
use FolioShowroom\Data;


/**
 * Compress css styles
 * @param  [string] $css styles
 */
function minimize($css)
{
  $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css);
  $css = preg_replace('/\s{2,}/', ' ', $css);
  $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
  $css = preg_replace('/;}/', '}', $css);
  return $css;
}



/**
 * Generate css custom styles
 */
function get_editor_blocks_generated_styles()
{
  ob_start();

  $adjust = new Color\ColorAdjust();

  $links_color = Data\get_theme_option('links_color');
  $primary_color = Data\get_theme_option('primary_color');
  $secondary_color = Data\get_theme_option('secondary_color');
  $terciary_color = Data\get_theme_option('terciary_color');
?>
  body .editor-styles-wrapper {
  --bs-primary: <?php echo $primary_color ?>;
  --bs-secondary: <?php echo $secondary_color ?>;
  --bs-terciary: <?php echo $terciary_color ?>;
  --bs-link-color: <?php echo $links_color ?>;
  --bs-link-hover-color: <?php echo $adjust->darken($links_color, 6); ?>;
  --bs-primary-darkness: <?php echo $adjust->darken($primary_color, 6); ?>;
  --bs-secondary-darkness: <?php echo $adjust->darken($secondary_color, 6); ?>;
  --bs-terciary-darkness: <?php echo $adjust->darken($terciary_color, 50); ?>;
  }
<?php
  $styles = ob_get_clean();
  return minimize($styles);
}


/**
 * Generate css editor custom styles
 */
function get_generated_styles()
{
  ob_start();

  $adjust = new Color\ColorAdjust();

  $header_blur = Data\get_theme_option('header_blur');
  $header_font = Data\get_theme_option('header_font');
  $header_font_size = Data\get_theme_option('header_font_size');
  $header_spacing = Data\get_theme_option('header_spacing');

  $links_color = Data\get_theme_option('links_color');
  $primary_color = Data\get_theme_option('primary_color');
  $secondary_color = Data\get_theme_option('secondary_color');
  $terciary_color = Data\get_theme_option('terciary_color');

  $links_color_dark = Data\get_theme_option('links_color_dark');
  $primary_color_dark = Data\get_theme_option('primary_color_dark');
  $secondary_color_dark = Data\get_theme_option('secondary_color_dark');
  $terciary_color_dark = Data\get_theme_option('terciary_color_dark');

?>
  :root,
  [data-bs-theme="light"] {
  --bs-main-menu-spacing: <?php echo $header_spacing . 'rem'; ?>;
  --bs-main-menu-font-size: <?php echo $header_font_size . 'px'; ?>;
  --bs-main-menu-font-family: '<?php echo $header_font ?>';
  --bs-primary: <?php echo $primary_color ?>;
  --bs-secondary: <?php echo $secondary_color ?>;
  --bs-terciary: <?php echo $terciary_color ?>;
  --bs-link-color: <?php echo $links_color ?>;
  --bs-link-hover-color: <?php echo $adjust->darken($links_color, 6); ?>;
  --bs-primary-darkness: <?php echo $adjust->darken($primary_color, 6); ?>;
  --bs-secondary-darkness: <?php echo $adjust->darken($secondary_color, 6); ?>;
  --bs-terciary-darkness: <?php echo $adjust->darken($terciary_color, 50); ?>;

  }
  .site-footer,
  [data-bs-theme="dark"] {
  --bs-primary: <?php echo $primary_color_dark ?>;
  --bs-secondary: <?php echo $secondary_color_dark ?>;
  --bs-terciary: <?php echo $terciary_color_dark ?>;
  --bs-link-color: <?php echo $links_color_dark ?>;
  --bs-link-hover-color: <?php echo $adjust->lighten($links_color_dark, 9); ?>;
  --bs-primary-darkness: <?php echo $adjust->lighten($primary_color_dark, 9); ?>;
  --bs-secondary-darkness: <?php echo $adjust->lighten($secondary_color_dark, 9); ?>;
  --bs-terciary-darkness: <?php echo $adjust->lighten($terciary_color_dark, 40); ?>;
  }

  .site-header.position-sticky.active {
  -webkit-backdrop-filter: blur(<?php echo $header_blur ?>px);
  backdrop-filter: blur(<?php echo $header_blur ?>px);
  }
<?php
  $styles = ob_get_clean();
  return minimize($styles);
}
