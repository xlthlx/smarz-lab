<?php
/**
 * Sets the correct function when an option is activated
 *
 * @package  WordPress
 * @subpackage  Xlthlx
 */

require_once( __DIR__ . '/toolkit-functions.php' );
global $smarz_updates, $smarz_general, $smarz_dashboard, $smarz_seo, $smarz_archives, $smarz_listing, $smarz_login, $smarz_uploads;

/**
 * Updates options.
 */
if ( isset( $smarz_updates ) ) {
	foreach ( $smarz_updates as $key => $value ) {
		//Radio fields yes/no
		if ( $value === 'yes' ) {
			switch ( $key ) {
				case 'core':
					add_filter( 'auto_update_core', '__return_true' );
					break;
				case 'themes':
					add_filter( 'auto_update_theme', '__return_true' );
					break;
				case 'plugins':
					add_filter( 'auto_update_plugin', '__return_true' );
					break;
			}
		}
	}
}

/**
 * General options.
 */
if ( isset( $smarz_general ) ) {
	foreach ( $smarz_general as $key => $value ) {
		//Radio fields yes/no
		if ( $value === 'yes' ) {
			switch ( $key ) {
				case 'emoji_support':
					add_action( 'init', 'smarz_disable_emoji_support' );
					break;
				case 'rest_api':
					if ( ! is_admin() ) {
						add_action( 'init', 'smarz_disable_rest_api' );
					}
					break;
				case 'links':
					if ( ! is_admin() ) {
						add_action( 'init', 'smarz_disable_links' );
					}
					break;
				case 'wordpress_version':
					if ( ! is_admin() ) {
						add_action( 'init', 'smarz_remove_wordpress_version' );
					}
					break;
				case 'pings':
					if ( ! is_admin() ) {
						add_action( 'init', 'smarz_remove_pings' );
					}
					break;
				case 'comments':
					if ( ! is_admin() ) {
						add_action( 'init', 'smarz_remove_comments' );
					}
					break;
				case 'versions':
					if ( ! is_admin() ) {
						add_filter( 'style_loader_src',
							'smarz_change_version_from_style_js', 9999, 1 );
						add_filter( 'script_loader_src',
							'smarz_change_version_from_style_js', 9999, 1 );
					}
					break;
			}
		}
	}
}

/**
 * Dashboard options.
 */
if ( isset( $smarz_dashboard ) ) {
	foreach ( $smarz_dashboard as $key => $value ) {
		//Radio fields yes/no
		if ( $value === 'yes' ) {
			switch ( $key ) {
				case 'dashboard_widgets':
					add_action( 'wp_dashboard_setup', 'smarz_disable_dashboard_widgets', 999 );
					break;
			}
		}
	}
}

/**
 * SEO options.
 */
if ( isset( $smarz_seo ) ) {
	foreach ( $smarz_seo as $key => $value ) {
		//Radio fields yes/no
		if ( $value === 'yes' ) {
			switch ( $key ) {
				case 'pretty_search':
					add_filter( 'wpseo_json_ld_search_url', 'smarz_rewrite' );
					add_action( 'template_redirect', 'smarz_search_url_rewrite' );
					break;
				case 'header':
					add_action( 'wp_headers', 'smarz_last_mod_header' );
					break;
				case 'images_alt':
					add_filter( 'the_content', 'smarz_add_image_alt', 9999 );
					add_filter( 'wp_get_attachment_image_attributes', 'smarz_change_image_attr', 20, 2 );
					break;
			}
		}
	}
}

/**
 * Archive options.
 */
if ( isset( $smarz_archives ) ) {
	foreach ( $smarz_archives as $key => $value ) {
		//Radio fields yes/no
		if ( $value === 'yes' ) {
			switch ( $key ) {
				case 'remove_title':
					add_filter( 'get_the_archive_title',
						'smarz_remove_archive_title_prefix' );
					break;
				case 'media_redirect':
					add_action( 'template_redirect',
						'smarz_attachment_pages_redirect' );
					break;
				case 'redirect_author':
					add_action( 'template_redirect',
						'smarz_redirect_archives_author' );
					break;
			}
		}
	}
}

/**
 * Listing options.
 */
if ( isset( $smarz_listing ) ) {
	foreach ( $smarz_listing as $key => $value ) {
		//Radio fields yes/no
		if ( $value === 'yes' ) {
			switch ( $key ) {
				case 'posts_columns':
					add_filter( 'manage_posts_columns', 'smarz_posts_columns',
						9999 );
					add_action( 'manage_posts_custom_column',
						'smarz_posts_custom_columns', 9999, 2 );
					break;
				case 'pages_columns':
					add_filter( 'manage_pages_columns', 'smarz_page_column_views',
						9999 );
					add_action( 'manage_pages_custom_column',
						'smarz_page_custom_column_views', 9999, 2 );
					break;
			}
		}
	}
}

/**
 * Login options.
 */
if ( isset( $smarz_login ) ) {
	foreach ( $smarz_login as $key => $value ) {
		if ( $value !== '' ) {
			switch ( $key ) {
				case 'smarz_login':
					add_action( 'after_setup_theme', 'smarz_plugins_loaded', 1 );
					add_action( 'wp_loaded', 'smarz_wp_loaded' );

					add_filter( 'site_url', 'smarz_site_url', 10, 4 );
					add_filter( 'wp_redirect', 'smarz_wp_redirect', 10, 2 );

					add_filter( 'site_option_welcome_email', 'smarz_welcome_email' );
					add_filter( 'body_class', 'smarz_admin_bar_body_class', 10, 2 );

					remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
					break;
			}
		}
	}
}

/**
 * Uploads options.
 */
if ( isset( $smarz_uploads ) ) {
	foreach ( $smarz_uploads as $key => $value ) {
		//Radio fields yes/no
		if ( $value === 'yes' ) {
			switch ( $key ) {
				case 'clean_names':
					add_action( 'wp_handle_upload_prefilter', 'smarz_upload_filter' );
					add_action( 'add_attachment', 'smarz_update_attachment_title' );
					break;
			}
		}
	}
}


