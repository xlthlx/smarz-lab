<?php
/**
 * Toolkit.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Includes all files from inc directory.
 */
require_once ABSPATH . 'wp-admin/includes/file.php';

$folder = get_template_directory() . '/inc/toolkit/inc/';
$files  = list_files( $folder, 2 );
foreach ( $files as $file ) {
	if ( is_file( $file ) ) {
		include_once $file;
	}
}
