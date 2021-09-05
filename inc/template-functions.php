<?php
/**
 * Functions which enhance the theme by hooking into WordPress.
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

function remove_jquery_migrate( $scripts ) {
	if ( isset( $scripts->registered['jquery'] ) && ! is_admin() ) {
		$script = $scripts->registered['jquery'];
		if ( $script->deps ) {
			$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
		}
	}
}

add_action( 'wp_default_scripts', 'remove_jquery_migrate' );