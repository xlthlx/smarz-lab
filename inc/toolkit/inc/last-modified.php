<?php
/**
 * Adds last modified into header.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Return Last-Modified Header.
 *
 * @return void
 */
function Sl_Last_Mod_header()
{
    if (is_singular() ) {
        $post_id = get_queried_object_id();
        if ($post_id ) {
            header('Last-Modified: ' . get_the_modified_time('D, d M Y H:i:s', $post_id));
        }
    }
}

add_action('wp_headers', 'Sl_Last_Mod_header');
