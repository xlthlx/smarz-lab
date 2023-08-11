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
function sl_clean_meta_generators() {
	ob_start( 'sl_replace_meta_generators' );
}

/**
 * Replace <meta .* name="generator"> like tags
 * which may contain version of.
 *
 * @param string $html Meta HTML.
 *
 * @return string
 */
function sl_replace_meta_generators( $html ) {
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
function sl_remove_word_press_version() {
	remove_action( 'wp_head', 'wp_generator' );
	add_filter( 'the_generator', '__return_empty_string' );

	add_action( 'wp_head', 'sl_clean_meta_generators', 100 );
}

if ( ! is_admin() ) {
	add_action( 'init', 'sl_remove_word_press_version' );
}
