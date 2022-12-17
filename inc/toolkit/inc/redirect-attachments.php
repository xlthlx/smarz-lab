<?php
/**
 * Redirect attachment pages.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Attachment pages redirect.
 *
 * @return void
 */
function Sl_Attachment_Pages_redirect() {
	global $post;

	if ( is_attachment() ) {
		if ( isset( $post->post_parent ) && ( 0 !== $post->post_parent ) ) {
			wp_redirect( get_permalink( $post->post_parent ), 301 );
		} else {
			wp_redirect( home_url(), 301 );
		}
		exit;
	}
}

add_action( 'template_redirect', 'Sl_Attachment_Pages_redirect' );
