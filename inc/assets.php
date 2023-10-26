<?php

/**
 * FolioShowroom assets management
 *
 * @package FolioShowroom
 */

namespace FolioShowroom\Assets;

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Class AssetResolver
 *
 * This utility class resolves URIs to built assets in the /dist directory of our theme. Built assets have a version
 * hash within their file name – each time the file changes, this hash will change which changes the file name.
 *
 * This class provides the functionality to resolve the file name using the generated manifest at
 * /build/mix-manifest.json without having to specify the version hash.
 *
 * @package FolioShowroom
 */
class AssetResolver
{


	/**
	 * @var array
	 */
	private static $manifest = [];


	/**
	 * @param $path
	 *
	 * @return string
	 */
	public static function resolve($path)
	{
		if ($map = self::get_manifest()) {

			$path = self::leading_slash_it($path);

			if (isset($map[$path])) {
				return get_stylesheet_directory_uri() . '/dist' . self::leading_slash_it($map[$path]);
			}
		}

		return get_stylesheet_directory_uri() . '/dist' . self::leading_slash_it($path);
	}


	/**
	 * @return array|mixed|object
	 */
	private static function get_manifest()
	{
		if (!self::$manifest) {
			$manifest = get_stylesheet_directory() . '/dist/mix-manifest.json';

			if (
				$map = file_get_contents($manifest) and
				is_array($map = json_decode($map, true))
			) {
				self::$manifest = $map;
			}
		}

		return self::$manifest;
	}


	/**
	 * @param $string
	 *
	 * @return string
	 */
	private static function leading_slash_it($string)
	{
		return '/' . ltrim($string, '/\\');
	}
}


/**
 * Assets mix
 */
function mix($path)
{
	$pathWithOutSlash = ltrim($path, '/');
	$pathWithSlash    = '/' . ltrim($path, '/');
	$manifestFile     = get_template_directory() . '/dist/mix-manifest.json';
	$basePath = get_template_directory_uri() . '/';

	// No manifest file was found so return whatever was passed to mix().
	if (!$manifestFile) {
		return $basePath . $pathWithOutSlash;
	}

	$manifestArray = json_decode(file_get_contents($manifestFile), true);
	if (array_key_exists($pathWithSlash, $manifestArray)) {
		return $basePath . ltrim($manifestArray[$pathWithSlash], '/');
	}

	// No file was found in the manifest, return whatever was passed to mix().
	return $basePath . $pathWithOutSlash;
}


/**
 * @param $string
 *
 * @return string
 */
function leading_slash_it($string)
{
	return '/' . ltrim($string, '/\\');
}
