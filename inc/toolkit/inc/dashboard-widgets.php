<?php
/**
 * Disable all dashboard widgets.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Disable all dashboard widgets, also Yoast SEO and Gravity Forms.
 *
 * @return void
 */
function Sl_Disable_Dashboard_widgets()
{
    global $wp_meta_boxes;

    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'], $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'], $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'], $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'], $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'], $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'], $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'], $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'], $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'], $wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget'], $wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard'], $wp_meta_boxes['dashboard']['normal']['core']['semperplugins-rss-feed'], $wp_meta_boxes['dashboard']['normal']['core']['analytify-dashboard-addon'], $wp_meta_boxes['dashboard']['normal']['core']['aj_dashboard_widget'], $wp_meta_boxes['dashboard']['normal']['core']['aioseo-rss-feed']);

    remove_action('welcome_panel', 'wp_welcome_panel');
}

add_action('wp_dashboard_setup', 'Sl_Disable_Dashboard_widgets', 999);
