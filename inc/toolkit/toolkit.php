<?php
/**
 * Toolkit.
 *
 * @package  WordPress
 * @subpackage  Xlthlx
 */

/**
 * Sets all the default values for the options.
 */
$defaults = [
	'smarz_updates'   => [
		'core'    => 'yes',
		'themes'  => 'yes',
		'plugins' => 'yes',
	],
	'smarz_general'   => [
		'emoji_support'     => 'yes',
		'rest_api'          => 'yes',
		'links'             => 'yes',
		'wordpress_version' => 'yes',
		'pings'             => 'yes',
		'comments'          => 'yes',
		'versions'          => 'yes',
	],
	'smarz_dashboard' => [
		'dashboard_widgets'      => 'yes',
	],
	'smarz_seo'       => [
		'pretty_search' => 'yes',
		'header'        => 'yes',
		'images_alt'    => 'yes',
	],
	'smarz_archives'  => [
		'remove_title'    => 'yes',
		'media_redirect'  => 'yes',
		'redirect_author' => 'yes',
	],
	'smarz_listing'   => [
		'posts_columns' => 'yes',
		'pages_columns' => 'yes',
	],
	'smarz_login'     => [
		'smarz_login' => 'entra',
	],
	'smarz_uploads'   => [
		'clean_names' => 'yes',
	],
];

$smarz_updates   = $defaults['smarz_updates'];
$smarz_general   = $defaults['smarz_general'];
$smarz_dashboard = $defaults['smarz_dashboard'];
$smarz_seo       = $defaults['smarz_seo'];
$smarz_archives  = $defaults['smarz_archives'];
$smarz_listing   = $defaults['smarz_listing'];
$smarz_login     = $defaults['smarz_login'];
$smarz_uploads   = $defaults['smarz_uploads'];

require_once( __DIR__ . '/toolkit-options.php' );
