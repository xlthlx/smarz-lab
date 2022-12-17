<?php
/**
 * Redirect author archive.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Redirect archives author.
 *
 * @return void
 */
function Sl_Redirect_Archives_author()
{
    if (is_author() ) {
        wp_redirect(home_url(), 301);

        die();
    }
}

add_action('template_redirect', 'Sl_Redirect_Archives_author');
