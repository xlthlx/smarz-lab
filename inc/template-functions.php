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
 * Remove the very annoying jQuery Migrate notice.
 *
 * @return void
 */
function Sl_Remove_Jquery_Migrate_notice()
{
    $m                    = $GLOBALS['wp_scripts']->registered['jquery-migrate'];
    $m->extra['before'][] = 'sl_logconsole = window.console.log; window.console.log=null;';
    $m->extra['after'][]  = 'window.console.log=sl_logconsole;';
}

add_action('init', 'Sl_Remove_Jquery_Migrate_notice', 5);

/**
 * Hide SEO settings meta box for posts.
 *
 * @return void
 */
function Sl_Hide_Slim_Seo_Meta_box()
{
    $context = apply_filters('slim_seo_meta_box_context', 'normal');
    remove_meta_box('slim-seo', null, $context);
}

add_action('add_meta_boxes', 'Sl_Hide_Slim_Seo_Meta_box', 20);

/**
 * Change the title separator.
 *
 * @return string
 */
function Sl_Document_Title_separator()
{
    return '|';
}

add_filter('document_title_separator', 'Sl_Document_Title_separator');

/**
 * Removes tags from blog posts.
 *
 * @return void
 */
function Sl_Unregister_tags()
{
    unregister_taxonomy_for_object_type('post_tag', 'post');
}

add_action('init', 'Sl_Unregister_tags');

/**
 * Replace YouTube.com with the no cookie version.
 *
 * @param string $html The oembed HTML.
 * @param object $data The data object.
 * @param string $url  The url.
 *
 * @return string
 */
function Sl_Youtube_Oembed_filters( $html,$data,$url )
{
    if (false === $html || ! in_array($data->type, [ 'rich','video' ], true) ) {
        return $html;
    }

    if (false !== strpos($html, 'youtube') || false !== strpos($html, 'youtu.be') ) {
        $html = str_replace('youtube.com/embed', 'youtube-nocookie.com/embed', $html);
    }

    return $html;
}

add_filter('oembed_dataparse', 'Sl_Youtube_Oembed_filters', 99, 3);

/**
 * Clean the oembed cache.
 *
 * @return int
 */
function Sl_Clean_Oembed_cache()
{
    $GLOBALS['wp_embed']->usecache = 0;
    do_action('wpse_do_cleanup');

    return 0;
}

add_filter('oembed_ttl', 'Sl_Clean_Oembed_cache');

/**
 * Restore the oembed cache.
 *
 * @param mixed $discover The Discover.
 *
 * @return mixed
 */
function Sl_Restore_Oembed_cache( $discover )
{
    if (1 === did_action('wpse_do_cleanup') ) {
        $GLOBALS['wp_embed']->usecache = 1;
    }

    return $discover;
}

add_filter('embed_oembed_discover', 'Sl_Restore_Oembed_cache');

/**
 * Hide SEO and description columns.
 *
 * @param array $columns The admin columns.
 *
 * @return array
 */
function Sl_Hide_Seo_columns( $columns )
{
    unset($columns['meta_title'], $columns['meta_description'], $columns['description'], $columns['noindex']);

    return $columns;
}

add_filter('manage_page_posts_columns', 'Sl_Hide_Seo_columns', 20);
add_filter('manage_post_posts_columns', 'Sl_Hide_Seo_columns', 20);
add_filter('manage_edit-category_columns', 'Sl_Hide_Seo_columns', 20);
