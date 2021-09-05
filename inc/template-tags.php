<?php
/**
 * Custom template tags.
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

if ( ! function_exists( 'str_contains' ) ) {
	/**
	 * @param $haystack
	 * @param $needle
	 *
	 * @return bool
	 */
	function str_contains( $haystack, $needle ) {
		return '' === $needle || false !== strpos( $haystack, $needle );
	}
}

if ( ! function_exists( 'str_starts_with' ) ) {
	/**
	 * @param $haystack
	 * @param $needle
	 * @param bool $case
	 *
	 * @return bool
	 */
	function str_starts_with( $haystack, $needle, $case = true ) {
		if ( $case ) {
			return strpos( $haystack, $needle ) === 0;
		}

		return stripos( $haystack, $needle, 0 ) === 0;
	}
}

if ( ! function_exists( 'str_ends_with' ) ) {
	/**
	 * @param $haystack
	 * @param $needle
	 * @param bool $case
	 *
	 * @return bool
	 */
	function str_ends_with( $haystack, $needle, $case = true ) {
		$expectedPosition = strlen( $haystack ) - strlen( $needle );
		if ( $case ) {
			return strrpos( $haystack, $needle ) === $expectedPosition;
		}

		return strripos( $haystack, $needle, 0 ) === $expectedPosition;
	}
}

if ( ! function_exists( 'smarz_get_link' ) ) {
	/**
	 * Set up the single link.
	 *
	 * @param $args
	 * @param $link
	 * @param $name
	 * @param $position
	 *
	 * @return string
	 */
	function smarz_get_link( $args, $link, $name, $position ) {
		$return = $args['before'];
		$return .= sprintf(
			$args['link'],
			$link,
			$name,
			sprintf( $args['name'], $name )
		);
		$return .= sprintf( $args['position'], $position );

		return $return;
	}
}

/**
 * WP Bootstrap Breadcrumbs
 * @package WP-Bootstrap-Breadcrumbs
 *
 * Description: A custom WordPress nav walker class to implement the Bootstrap 4 breadcrumbs style in a custom theme using the WordPress.
 * Author: Dimox - @Dimox, Alexsander Vyshnyvetskyy - @alex-wdmg
 * Version: 1.1.0
 * Author URI: https://github.com/Dimox
 * Author URI: https://github.com/alex-wdmg
 * GitHub Gist URI: https://gist.github.com/alex-wdmg/21e150e00f327215ee3ad5d0ca669b17
 * License: MIT
 */

/**
 * Modified to be compatible with Bootstrap 5.
 */

if ( ! function_exists( 'smarz_breadcrumbs' ) ) {
	/**
	 * Breadcrumbs
	 */
	function smarz_breadcrumbs() {

		$args = array(
			'before'        => '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">',
			'before_active' => '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">',
			'link'          => '<a href="%1$s" title="%2$s" itemscope itemtype="https://schema.org/Thing" itemprop="item" itemid="%1$s">%3$s</a>',
			'active'        => '<span itemscope itemtype="https://schema.org/Thing" itemprop="name" itemid="%1$s">%2$s</span>',
			'name'          => '<span itemprop="name">%1$s</span>',
			'position'      => '<meta itemprop="position" content="%1$s">',
			'text'          => array(
				'home'     => __( 'Home' ),
				'category' => '%s',
				'search'   => __( 'Risultati della ricerca per: %s' ),
				'tag'      => __( 'Tag: %s' ),
				'author'   => __( 'Autore: %s' ),
				'404'      => __( 'Errore 404' ),
				'page'     => __( 'Pagina %s' ),
				'cpage'    => __( 'Pagina %s' )
			)
		);

		global $post;
		$home_url  = home_url( '/' );
		$parent_id = $post->post_parent ?? 0;
		$title     = get_the_title();


		$home_link = smarz_get_link( $args, $home_url, $args['text']['home'], 1 );

		if ( ! is_home() && ! is_front_page() ) {

			$position = 0;
			echo '<ol class="breadcrumb" id="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';

			$position ++;
			echo $home_link;

			if ( is_category() ) {
				$parents = get_ancestors( get_query_var( 'cat' ), 'category' );
				foreach ( array_reverse( $parents ) as $cat ) {
					$position ++;
					echo smarz_get_link( $args, get_category_link( $cat ), get_cat_name( $cat ), $position );
				}
				if ( get_query_var( 'paged' ) ) {
					$position ++;
					echo smarz_get_link( $args, get_category_link( get_query_var( 'cat' ) ), get_cat_name( get_query_var( 'cat' ) ), $position );
					echo $args['before'] . sprintf( $args['text']['page'], get_query_var( 'paged' ) );

				} else {
					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['name'], sprintf( $args['text']['category'], single_cat_title( '', false ) ) ) ) . sprintf( $args['position'], $position );
				}
			} elseif ( is_search() ) {
				if ( get_query_var( 'paged' ) ) {

					$position ++;
					echo smarz_get_link( $args, $home_url . '?s=' . get_search_query(), sprintf( $args['text']['search'], get_search_query() ), $position );
					echo $args['before'] . sprintf( $args['text']['page'], get_query_var( 'paged' ) );

				} else {

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['search'], get_search_query() ) ) . sprintf( $args['position'], $position );


				}
			} elseif ( is_year() ) {

				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_time( 'Y' ) ) . sprintf( $args['position'], $position );


			} elseif ( is_month() ) {

				$position ++;
				echo smarz_get_link( $args, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ), $position );

				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_time( 'F' ) ) . sprintf( $args['position'], $position );

			} elseif ( is_day() ) {

				$position ++;
				echo smarz_get_link( $args, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ), $position );

				$position ++;
				echo smarz_get_link( $args, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ), $position );


				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), get_the_time( 'd' ) ) . sprintf( $args['position'], $position );

			} elseif ( is_single() && ! is_attachment() ) {
				$post_type = get_post_type_object( get_post_type() );
				if ( $post_type && get_post_type() !== 'post' ) {
					$position ++;
					echo smarz_get_link( $args, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
					$position ++;
					$args['before_active'] . sprintf( $args['active'], get_permalink(), $title ) . sprintf( $args['position'], $position );

				} else {
					$cat       = get_the_category();
					$catID     = $cat[0]->cat_ID;
					$parents   = array_reverse( get_ancestors( $catID, 'category' ) );
					$parents[] = $catID;

					foreach ( $parents as $cat ) {
						$position ++;
						echo smarz_get_link( $args, get_category_link( $cat ), get_cat_name( $cat ), $position );
					}

					if ( get_query_var( 'cpage' ) ) {
						$position ++;
						echo smarz_get_link( $args, get_permalink(), $title, $position );

						$position ++;
						echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['cpage'], get_query_var( 'cpage' ) ) ) . sprintf( $args['position'], $position );

					} else {
						$position ++;
						echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['name'], $title ) ) . sprintf( $args['position'], $position );

					}
				}
			} elseif ( is_post_type_archive() ) {
				$post_type = get_post_type_object( get_post_type() );
				if ( $post_type && get_query_var( 'paged' ) ) {

					$position ++;
					echo smarz_get_link( $args, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['page'], get_query_var( 'paged' ) ) ) . sprintf( $args['position'], $position );
				} else {

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), $post_type->label ) . sprintf( $args['position'], $position );

				}

			} elseif ( is_attachment() ) {
				$parent    = get_post( $parent_id );
				$cat       = get_the_category( $parent->ID );
				$catID     = $cat[0]->cat_ID;
				$parents   = array_reverse( get_ancestors( $catID, 'category' ) );
				$parents[] = $catID;
				foreach ( $parents as $cat ) {
					$position ++;
					echo smarz_get_link( $args, get_category_link( $cat ), get_cat_name( $cat ), $position );
				}

				$position ++;
				echo smarz_get_link( $args, get_permalink( $parent ), $parent->post_title, $position );

				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), $title ) . sprintf( $args['position'], $position );

			} elseif ( ! $parent_id && is_page() ) {

				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), $title ) . sprintf( $args['position'], $position );


			} elseif ( $parent_id && is_page() ) {
				$parents = get_post_ancestors( get_the_ID() );
				foreach ( array_reverse( $parents ) as $pageID ) {
					$position ++;
					echo smarz_get_link( $args, get_page_link( $pageID ), get_the_title( $pageID ), $position );
				}

				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), $title ) . sprintf( $args['position'], $position );

			} else if ( is_tag() ) {
				if ( get_query_var( 'paged' ) ) {
					$position ++;
					$tagID = get_query_var( 'tag_id' );
					echo smarz_get_link( $args, get_tag_link( $tagID ), single_tag_title( '', false ), $position );

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['page'], get_query_var( 'paged' ) ) ) . sprintf( $args['position'], $position );
				} else {

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['tag'], single_tag_title( '', false ) ) ) . sprintf( $args['position'], $position );

				}
			} elseif ( is_author() ) {
				$author = get_userdata( get_query_var( 'author' ) );
				if ( get_query_var( 'paged' ) ) {

					$position ++;
					echo smarz_get_link( $args, get_author_posts_url( $author->ID ), sprintf( $args['text']['author'], $author->display_name ), $position );

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['page'], get_query_var( 'paged' ) ) ) . sprintf( $args['position'], $position );

				} else {

					$position ++;
					echo $args['before_active'] . sprintf( $args['active'], get_permalink(), sprintf( $args['text']['author'], $author->display_name ) ) . sprintf( $args['position'], $position );

				}
			} elseif ( is_404() ) {

				$position ++;
				echo $args['before_active'] . sprintf( $args['active'], get_permalink(), $args['text']['404'] ) . sprintf( $args['position'], $position );

			} elseif ( has_post_format() && ! is_singular() ) {

				echo get_post_format_string( get_post_format() );
			}

			echo '</ol>';
		}
	}
}

if ( ! function_exists( 'smarz_comment_form' ) ) {
	/**
	 * Custom comments form.
	 */
	function smarz_comment_form( $post_id = false ) {

		$comments_args = array(
			'format'               => 'xhtml',
			'comment_notes_before' => '<p>' . __( 'Your email address will not be published.' ) . '</p>',
			'class_submit'         => 'btn btn-outline-dark rounded-0',
			'fields'               => array(
				'author' => '<div class="form-floating mb-3">
							<input placeholder="' . __( 'Author' ) . '" class="form-control rounded-0" type="text" id="author" name="author" required>
							<label for="author">' . __( 'Author' ) . ' (' . __( 'required' ) . ')</label>
						</div>',
				'email'  => '<div class="form-floating mb-3">
							<input placeholder="' . __( 'Email' ) . '" class="form-control rounded-0" type="email" id="email" name="email" required>
							<label for="email">' . __( 'Email' ) . ' (' . __( 'required' ) . ')</label>
						</div>',
				'url'    => '<div class="form-floating mb-3">
							<input placeholder="' . __( 'Url' ) . '" class="form-control rounded-0" type="url" id="url" name="url">
							<label for="url">' . __( 'Url' ) . '</label>
						</div>',
			),
			'comment_field'        => '<div class="form-floating mb-3">
								<textarea placeholder="' . __( 'Comment' ) . '" class="form-control rounded-0" id="comment" name="comment" style="height: 150px" required></textarea>
								<label for="comment">' . __( 'Comment' ) . ' (' . __( 'required' ) . ')</label>
								</div>',
		);

		if ( $post_id ) {
			comment_form( $comments_args, $post_id );
		} else {
			comment_form( $comments_args );
		}
	}
}
