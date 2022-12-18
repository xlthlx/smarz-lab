<?php
/**
 * Post columns.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Adds Thumbnail column for posts
 *
 * @param array $columns The post columns.
 *
 * @return array
 */
function sl_posts_columns( $columns ) {
	 $post_type = get_post_type();
	if ( 'post' === $post_type ) {
		unset( $columns['date'] );

		$columns = array_merge(
			$columns,
			array(
				'thumbs'   => __( 'Thumbnail', 'smarz-lab' ),
				'modified' => __( 'Last modified', 'smarz-lab' ),
				'date'     => __( 'Date', 'smarz-lab' ),
			)
		);
	}

	return $columns;
}

/**
 * Sets content for Thumbnail column and date
 *
 * @param string $column_name The column name.
 * @param int    $id          The post ID.
 *
 * @return void
 */
function sl_posts_custom_columns( $column_name, $id ) {
	if ( 'thumbs' === $column_name ) {
		echo get_the_post_thumbnail( $id, 'thumbnail' );
	}
	if ( 'modified' === $column_name ) {
		echo ucfirst( get_the_modified_time( 'd/m/Y', $id ) ) . ' alle ' . get_the_modified_time( 'H:i', $id );
	}
	if ( 'date' === $column_name ) {
		echo get_the_date( $id );
	}
}

if ( is_admin() ) {
	add_filter( 'manage_posts_columns', 'sl_posts_columns', 999999 );
	add_action( 'manage_posts_custom_column', 'sl_posts_custom_columns', 999999, 2 );
}
