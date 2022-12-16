<?php
/**
 * Functions which enhance the theme by hooking into WordPress.
 *
 * @package    WordPress
 * @subpackage Smarz Lab
 */

/**
 * Remove the very annoying jQuery Migrate notice.
 *
 * @return void
 */
function sl_remove_jquery_migrate_notice()
{
    $m                    = $GLOBALS['wp_scripts']->registered['jquery-migrate'];
    $m->extra['before'][] = 'sl_logconsole = window.console.log; window.console.log=null;';
    $m->extra['after'][]  = 'window.console.log=sl_logconsole;';
}

add_action('init', 'sl_remove_jquery_migrate_notice', 5);

/**
 * Hide SEO settings meta box for posts.
 *
 * @return void
 */
function sl_hide_slim_seo_meta_box()
{
    $context = apply_filters('slim_seo_meta_box_context', 'normal');
    remove_meta_box('slim-seo', null, $context);
}

add_action('add_meta_boxes', 'sl_hide_slim_seo_meta_box', 20);

/**
 * Change the title separator.
 *
 * @return string
 */
function sl_document_title_separator()
{
    return '|';
}

add_filter('document_title_separator', 'sl_document_title_separator');

/**
 * Removes tags from blog posts.
 *
 * @return void
 */
function sl_unregister_tags()
{
    unregister_taxonomy_for_object_type('post_tag', 'post');
}

add_action('init', 'sl_unregister_tags');

/**
 * Replace YouTube.com with the no cookie version.
 *
 * @param $html
 * @param $data
 * @param $url
 *
 * @return string
 */
function sl_youtube_oembed_filters( $html,$data,$url )
{
    if (false === $html || ! in_array($data->type, [ 'rich','video' ], true) ) {
        return $html;
    }

    if (false !== strpos($html, 'youtube') || false !== strpos($html, 'youtu.be') ) {
        $html = str_replace('youtube.com/embed', 'youtube-nocookie.com/embed', $html);
    }

    return $html;
}

add_filter('oembed_dataparse', 'sl_youtube_oembed_filters', 99, 3);

/**
 * Clean the oembed cache.
 *
 * @return int
 */
function sl_clean_oembed_cache()
{
    $GLOBALS['wp_embed']->usecache = 0;
    do_action('wpse_do_cleanup');

    return 0;
}

add_filter('oembed_ttl', 'sl_clean_oembed_cache');

/**
 * Restore the oembed cache.
 *
 * @param $discover
 *
 * @return mixed
 */
function sl_restore_oembed_cache( $discover )
{
    if (1 === did_action('wpse_do_cleanup') ) {
        $GLOBALS['wp_embed']->usecache = 1;
    }

    return $discover;
}

add_filter('embed_oembed_discover', 'sl_restore_oembed_cache');

/**
 * Hide SEO and description columns.
 *
 * @param $columns
 *
 * @return array
 */
function sl_hide_seo_columns( $columns )
{
    unset($columns['meta_title'], $columns['meta_description'], $columns['description'], $columns['noindex']);

    return $columns;
}

add_filter('manage_page_posts_columns', 'sl_hide_seo_columns', 20);
add_filter('manage_post_posts_columns', 'sl_hide_seo_columns', 20);
add_filter('manage_edit-category_columns', 'sl_hide_seo_columns', 20);
