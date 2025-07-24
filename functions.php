<?php
/**
 * Core functionalities.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Load vendors.
 */
require_once 'vendor.phar';

add_filter( 'login_display_language_dropdown', '__return_false' );
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
add_filter( 'enable_post_by_email_configuration', '__return_false' );

/**
 * Set theme supports and image sizes.
 *
 * @return void
 */
function sl_add_supports() {
	add_theme_support( 'block-templates' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	remove_theme_support( 'automatic-feed-links' );
	remove_theme_support( 'widgets-block-editor' );
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	add_image_size( 'hero', 800, 450, true );
}

add_action( 'init', 'sl_add_supports' );

/**
 * Register main and footer menu.
 *
 * @return void
 */
function sl_register_menus() {
	register_nav_menus(
		array(
			'primary' => 'Main',
			'footer'  => 'Footer',
		)
	);
}

add_action( 'init', 'sl_register_menus' );

/**
 * Register widget area.
 *
 * @return void
 */
function sl_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'smarz-lab' ),
			'id'            => 'sidebar',
			'description'   => esc_html__( 'Sidebar', 'smarz-lab' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s p-4 mb-3 rounded-0">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="font-italic pb-2">',
			'after_title'   => '</h4>',
		)
	);

}

add_action( 'widgets_init', 'sl_widgets_init' );

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function sl_enqueue_scripts() {
	// Styles.
	wp_deregister_style( 'classic-theme-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}

add_action( 'wp_enqueue_scripts', 'sl_enqueue_scripts' );


/**
 * Set up globals.
 *
 * @return void
 */
function sl_add_to_globals() {
	global $charset,$site_url,$site_name,$site_desc;
	$charset   = get_bloginfo( 'charset' );
	$site_url  = home_url( '/' );
	$site_name = get_bloginfo( 'name' );
	$site_desc = get_bloginfo( 'description' );
}

add_action( 'after_setup_theme', 'sl_add_to_globals' );

if ( file_exists( 'inc/cmb2/cmb2/init.php' ) ) {
	include_once 'inc/cmb2/cmb2/init.php';
}

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require_once 'inc/template-functions.php';

/**
 * Theme options.
 */
require_once 'inc/template-options.php';

/**
 * Custom template tags.
 */
require_once 'inc/template-tags.php';

/**
 * EBay API.
 */
require_once 'inc/ebay/index.php';

/**
 * Toolkit.
 */
require_once 'inc/toolkit/index.php';

/**
 * Minify HTML.
 */
require_once 'inc/minify-html.php';
