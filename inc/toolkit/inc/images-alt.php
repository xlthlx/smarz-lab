<?php
/**
 * Sets images alt attribute.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Sets images alt attribute.
 *
 * @param string $content The post content.
 *
 * @return string The post content filtered.
 */
function Sl_Add_Image_alt( $content ) {
	 global $post;

	if ( null === $post ) {
		return $content;
	}

	$old_content = $content;
	preg_match_all( '/<img[^>]+>/', $content, $images );

	if ( null !== $images ) {
		foreach ( $images[0] as $index => $value ) {
			if ( ! preg_match( '/alt=/', $value ) ) {
				global $wpdb;

				preg_match_all( '/<img [^>]*src="([^"]+)"[^>]*>/m', $value, $urls, PREG_SET_ORDER );

				$attachment = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT ID FROM $wpdb->posts WHERE guid=%s;",
						$urls[0][1]
					)
				);
				$attachment = get_post( $attachment[0] );
				$alt        = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
				$title      = $attachment->post_title;

				if ( '' !== $alt ) {
					$new_img = str_replace(
						'<img',
						'<img alt="' . $alt . '"',
						$images[0][ $index ]
					);
				} else {
					if ( '' !== $title ) {
						$new_img = str_replace(
							'<img',
							'<img alt="' . $title . '"',
							$images[0][ $index ]
						);
					} else {
						$post_title = get_the_title( $post->ID );

						$new_img = str_replace(
							'<img',
							'<img alt="' . $post_title . '"',
							$images[0][ $index ]
						);
					}
				}

				$content = str_replace( $images[0][ $index ], $new_img, $content );
			}
		}
	}

	if ( empty( $content ) ) {
		return $old_content;
	}

	return $content;
}

add_filter( 'the_content', 'Sl_Add_Image_alt', 9999 );

/**
 * Sets alt attribute for post thumbnails.
 *
 * @param array   $attr       Array of attribute values for the image markup, keyed by attribute name.
 * @param WP_Post $attachment Image attachment post.
 *
 * @return array The attributes filtered.
 */
function Sl_Change_Image_attr( $attr, $attachment ) { 
	$parent = get_post_field( 'post_parent', $attachment );
	$title  = get_post_field( 'post_title', $parent );

	if ( '' === $attr['alt'] ) {
		if ( '' !== $attr['title'] ) {
			$attr['alt'] = $attr['title'];
		} else {
			$attr['alt'] = $title;
		}
	}


	return $attr;
}

add_filter( 'wp_get_attachment_image_attributes', 'Sl_Change_Image_attr', 20, 2 );
