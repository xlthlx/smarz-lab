<?php
/**
 * Remove archive title.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Remove archive title.
 *
 * @param string $title The title.
 *
 * @return string The modified title.
 */
function sl_remove_archive_title_prefix( $title ) {
	$single_cat_title = single_term_title( '', false );
	if ( is_category() || is_tag() || is_tax() ) {
		return esc_html( $single_cat_title );
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'sl_remove_archive_title_prefix' );
