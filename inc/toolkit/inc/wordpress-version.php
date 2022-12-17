<?php
/**
 * Remove WordPress version.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Starts.
 *
 * @return void
 */
function Sl_Clean_Meta_generators() {
	ob_start( 'Sl_Replace_Meta_generators' );
}

/**
 * Replace <meta .* name="generator"> like tags
 * which may contain version of.
 *
 * @param string $html Meta HTML.
 *
 * @return string
 */
function Sl_Replace_Meta_generators( $html ) {
	$raw_html = $html;

	$pattern = '/<meta[^>]+name=["\']generator["\'][^>]+>/i';
	$html    = preg_replace( $pattern, '', $html );

	if ( empty( $html ) ) {
		return $raw_html;
	}

	return $html;
}

/**
 * Remove WordPress version.
 *
 * @return void
 */
function Sl_Remove_WordPress_version() { 
	remove_action( 'wp_head', 'wp_generator' );
	add_filter( 'the_generator', '__return_empty_string' );

	add_action( 'wp_head', 'Sl_Clean_Meta_generators', 100 );
}

if ( ! is_admin() ) {
	add_action( 'init', 'Sl_Remove_WordPress_version' );
}
