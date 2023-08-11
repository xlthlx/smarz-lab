<?php
/**
 * Functions which enhance the theme by hooking into WordPress.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Hide SEO settings meta box for posts.
 *
 * @return void
 */
function sl_hide_slim_seo_meta_box() {
	$context = apply_filters( 'slim_seo_meta_box_context', 'normal' );
	remove_meta_box( 'slim-seo', null, $context );
}

add_action( 'add_meta_boxes', 'sl_hide_slim_seo_meta_box', 20 );

/**
 * Change the title separator.
 *
 * @return string
 */
function sl_document_title_separator() {
	return '|';
}

add_filter( 'document_title_separator', 'sl_document_title_separator' );

/**
 * Replace YouTube.com with the no cookie version.
 *
 * @param string $html The oembed HTML.
 * @param object $data The data object.
 * @param string $url  The url.
 *
 * @return string
 */
function sl_youtube_oembed_filters( $html, $data, $url ) {
	if ( false === $html || ! in_array( $data->type, array( 'rich', 'video' ), true ) ) {
		return $html;
	}

	if ( false !== strpos( $html, 'youtube' ) || false !== strpos( $html, 'youtu.be' ) ) {
		$html = str_replace( 'youtube.com/embed', 'youtube-nocookie.com/embed', $html );
	}

	return $html;
}

add_filter( 'oembed_dataparse', 'sl_youtube_oembed_filters', 99, 3 );

/**
 * Clean the oembed cache.
 *
 * @return int
 */
function sl_clean_oembed_cache() {
	$GLOBALS['wp_embed']->usecache = 0;
	do_action( 'wpse_do_cleanup' );

	return 0;
}

add_filter( 'oembed_ttl', 'sl_clean_oembed_cache' );

/**
 * Restore the oembed cache.
 *
 * @param mixed $discover The Discover.
 *
 * @return mixed
 */
function sl_restore_oembed_cache( $discover ) {
	if ( 1 === did_action( 'wpse_do_cleanup' ) ) {
		$GLOBALS['wp_embed']->usecache = 1;
	}

	return $discover;
}

add_filter( 'embed_oembed_discover', 'sl_restore_oembed_cache' );

/**
 * Hide SEO and description columns.
 *
 * @param array $columns The admin columns.
 *
 * @return array
 */
function sl_hide_seo_columns( $columns ) {
	unset( $columns['meta_title'], $columns['meta_description'], $columns['description'], $columns['noindex'] );

	return $columns;
}

add_filter( 'manage_page_posts_columns', 'sl_hide_seo_columns', 20 );
add_filter( 'manage_post_posts_columns', 'sl_hide_seo_columns', 20 );
add_filter( 'manage_edit-category_columns', 'sl_hide_seo_columns', 20 );
add_filter( 'manage_edit-tag_columns', 'sl_hide_seo_columns', 20 );

/**
 * Insert minified CSS into header.
 *
 * @return void
 */
function sl_insert_css() {
	$file  = get_template_directory() . '/assets/css/main.min.css';
	$style = sl_get_file_content( $file );

	echo '<style id="all-styles-inline">' . $style . '</style>';
}

add_action( 'wp_head', 'sl_insert_css' );

/**
 * Insert minified JS into footer.
 *
 * @return void
 */
function sl_insert_scripts() {
	$file   = get_template_directory() . '/assets/js/main.min.js';
	$script = sl_get_file_content( $file );

	echo '<script type="text/javascript">' . $script . '</script>';
}

add_action( 'wp_footer', 'sl_insert_scripts' );
