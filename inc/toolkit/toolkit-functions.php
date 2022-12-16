<?php
/**
 * Functions for all options.
 *
 * @package    WordPress
 * @subpackage Xlthlx
 */

add_action('wp_head', 'ob_start', 1, 0);

/**
 * Clean the filename.
 *
 * @param array The file information including the filename in $file['name'].
 *
 * @return array The file information with the cleaned or original filename.
 */
function smarz_upload_filter( $file )
{

    $original_filename = pathinfo($file['name']);
    set_transient('_clean_image_filenames_original_filename', $original_filename['filename'], 60);

    $input = array(
    'ß',
    '·',
    );

    $output = array(
    'ss',
    '.'
    );

    $path         = pathinfo($file['name']);
    $new_filename = preg_replace('/.' . $path['extension'] . '$/', '', $file['name']);
    $new_filename = str_replace($input, $output, $new_filename);
    $file['name'] = sanitize_title($new_filename) . '.' . $path['extension'];


    return $file;
}

/**
 * Set attachment title to original filename.
 *
 * @param int Attachment post ID.
 *
 * @since 1.2
 */
function smarz_update_attachment_title( $attachment_id )
{

    $original_filename = get_transient('_clean_image_filenames_original_filename');

    if ($original_filename ) {
        wp_update_post(array( 'ID' => $attachment_id, 'post_title' => $original_filename ));
        delete_transient('_clean_image_filenames_original_filename');
    }
}

/**
 * Remove Emoji support
 */
function smarz_disable_emoji_support()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_tesmarz_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('emoji_svg_url', '__return_false');
    add_filter('wp_resource_hints', 'smarz_disable_emojis_remove_dns_prefetch', 10, 2);
}

/**
 * Remove emoji CDN hostname from DNS prefetch hints.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 *
 * @return array Difference between the two arrays.
 */
function smarz_disable_emojis_remove_dns_prefetch( $urls, $relation_type )
{
    if ('dns-prefetch' === $relation_type ) {
        /**
    * This filter is documented in wp-includes/formatting.php 
*/
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
        $urls          = array_diff($urls, [ $emoji_svg_url ]);
    }

    return $urls;
}

/**
 * Replace <meta .* name="generator"> like tags
 * which may contain version of
 *
 * @param $html
 *
 * @return string|string[]|null
 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
 */
function smarz_replace_meta_generators( $html )
{
    $raw_html = $html;

    $pattern = '/<meta[^>]+name=["\']generator["\'][^>]+>/i';
    $html    = preg_replace($pattern, '', $html);

    // If replacement is completed with an error, user will receive a white screen.
    // We have to prevent it.
    if (empty($html) ) {
        return $raw_html;
    }

    return $html;
}

/**
 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
 */
function smarz_clean_meta_generators()
{
    ob_start('smarz_replace_meta_generators');
}

/**
 * Remove WordPress version.
 */
function smarz_remove_wordpress_version()
{

    // Clean meta generator for Wordpress core
    remove_action('wp_head', 'wp_generator');
    add_filter('the_generator', '__return_empty_string');

    // Clean all meta generators
    add_action('wp_head', 'smarz_clean_meta_generators', 100);
}

/**
 * Disable WordPress REST API.
 */
function smarz_disable_rest_api()
{

    /**
* Disable REST API link in HTTP headers
*/
    remove_action('template_redirect', 'rest_output_link_header', 11);

    /**
* Disable REST API links in HTML <head>
*/
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');

    add_filter('rest_authentication_errors', 'smarz_disable_wp_rest_api');

}

/**
 * Disable REST API only for non logged in users.
 *
 * @param $access
 *
 * @return WP_Error
 */
function smarz_disable_wp_rest_api( $access )
{

    if (! is_user_logged_in() ) {
        $message = apply_filters(
            'disable_wp_rest_api_error',
            __('REST API restricted to authenticated users.', 'disable-wp-rest-api') 
        );

        return new WP_Error(
            'rest_login_required', $message,
            [ 'status' => rest_authorization_required_code() ] 
        );
    }

    return $access;
}

/**
 * Remove RSD link, wlwmanifest Link, Shortlink, Previous/Next Post Link in the header.
 */
function smarz_disable_links()
{
    if (! is_admin() ) {
        remove_action('wp_head', 'adjacent_posts_rel_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        remove_action('template_redirect', 'wp_shortlink_header', 11);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10);
        add_action('widgets_init', 'smarz_remove_comments_style');
    }
}

/**
 * Remove default style for comments widget.
 */
function smarz_remove_comments_style()
{
    global $wp_widget_factory;

    $widget_recent_comments = isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments']) ? $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] : null;

    if (! empty($widget_recent_comments) ) {
        remove_action(
            'wp_head', [
            $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
            'recent_comments_style'
            ] 
        );
    }
}

/**
 * Disable pingbacks and trackbacks and remove X-Pingback link.
 */
function smarz_remove_pings()
{
    add_filter('template_redirect', 'smarz_remove_xml_rpc_pingback_headers');
    add_filter('wp_headers', 'smarz_disable_xml_rpc_pingback');

    // Remove <link rel="pingback" href>
    add_action(
        'template_redirect', 'smarz_remove_xml_rpc_tag_buffer_start',
        - 1 
    );
    add_action('get_header', 'smarz_remove_xml_rpc_tag_buffer_start');
    add_action('wp_head', 'smarz_remove_xml_rpc_tag_buffer_end', 999);

    // Remove RSD link from head
    remove_action('wp_head', 'rsd_link');

    // Disable xmlrcp/pingback
    add_filter('xmlrpc_enabled', '__return_false');
    add_filter('pre_update_option_enable_xmlrpc', '__return_false');
    add_filter('pre_option_enable_xmlrpc', '__return_zero');
    add_filter('pings_open', '__return_false');

    // Force to uncheck pingbck and trackback options
    add_filter('pre_option_default_ping_status', '__return_zero');
    add_filter('pre_option_default_pingback_flag', '__return_zero');

    add_filter('xmlrpc_methods', 'smarz_remove_xml_rpc_method');
    add_action('xmlrpc_call', 'smarz_disable_xml_rpc_call');

    // Hide options on Discussion page
    add_action('admin_enqueue_scripts', 'smarz_remove_xml_rpc_hide_options');

    smarz_xml_rpc_set_disabled_header();
}

/**
 * Just disable pingback.ping functionality while leaving XMLRPC intact.
 *
 * @param $method
 */
function smarz_disable_xml_rpc_call( $method )
{
    if ($method !== 'pingback.ping' ) {
        return;
    }
    wp_die('This site does not have pingback.', 'Pingback not Enabled!', [ 'response' => 403 ]);
}

/**
 * @param $methods
 *
 * @return mixed
 */
function smarz_remove_xml_rpc_method( $methods )
{
    unset($methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'], $methods['wp.getUsersBlogs'], $methods['system.multicall'], $methods['system.listMethods'], $methods['system.getCapabilities']);

    return $methods;
}

/**
 * Disable X-Pingback HTTP Header.
 *
 * @param array $headers
 *
 * @return mixed
 */
function smarz_disable_xml_rpc_pingback( $headers )
{
    unset($headers['X-Pingback']);

    return $headers;
}

/**
 * Disable X-Pingback HTTP Header.
 */
function smarz_remove_xml_rpc_pingback_headers()
{
    if (function_exists('header_remove') ) {
        header_remove('X-Pingback');
        header_remove('Server');
    }
}

/**
 * Start buffer for remove <link rel="pingback" href>
 */
function smarz_remove_xml_rpc_tag_buffer_start()
{
    ob_start('smarz_remove_xml_rpc_tag_buffer');
}

/**
 * End buffer.
 */
function smarz_remove_xml_rpc_tag_buffer_end()
{
    ob_flush();
}

/**
 * @param $buffer
 *
 * @return mixed
 */
function smarz_remove_xml_rpc_tag_buffer( $buffer )
{
    preg_match_all(
        '/(<link([^>]+)rel=("|\')pingback("|\')([^>]+)?\/?>)/im',
        $buffer, $founds 
    );

    if (! isset($founds[0]) || count($founds[0]) < 1 ) {
        return $buffer;
    }

    if (count($founds[0]) > 0 ) {
        foreach ( $founds[0] as $found ) {
            if (empty($found) ) {
                continue;
            }

            $buffer = str_replace($found, '', $buffer);
        }
    }

    return $buffer;
}

/**
 * Hide Discussion options with CSS.
 *
 * @param $hook
 */
function smarz_remove_xml_rpc_hide_options( $hook )
{
    if ('options-discussion.php' !== $hook ) {
        return;
    }

    wp_add_inline_style(
        'dashboard',
        '.form-table td label[for="default_pingback_flag"], .form-table td label[for="default_pingback_flag"] + br, .form-table td label[for="default_ping_status"], .form-table td label[for="default_ping_status"] + br { display: none; }' 
    );
}

/**
 * Set disabled header for any XML-RPC requests.
 */
function smarz_xml_rpc_set_disabled_header()
{
    // Return immediately if SCRIPT_FILENAME not set
    if (! isset($_SERVER['SCRIPT_FILENAME']) ) {
        return;
    }

    $file = basename($_SERVER['SCRIPT_FILENAME']);

    // Break only if xmlrpc.php file was requested.
    if ('xmlrpc.php' !== $file ) {
        return;
    }

    $header = 'HTTP/1.1 403 Forbidden';

    header($header);
    echo $header;
    die();
}

/**
 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
 * @since  1.0.0
 */
function smarz_remove_comments()
{
    ob_start('smarz_replace_html_comments');
}

/**
 * !ngg_resource - can not be deleted, otherwise the plugin nextgen gallery will not work.
 *
 * @param $html
 *
 * @return mixed
 */
function smarz_replace_html_comments( $html )
{
    $raw_html = $html;

    //CLRF-166 issue fix bug with noindex (\s?\/?noindex)
    $html = preg_replace(
        '#<!--(?!<!|\s?ngg_resource|\s?\/?noindex)[^\[>].*?-->#s',
        '', $html 
    );

    // If replacement is completed with an error, user will receive a white screen.
    // We have to prevent it.
    if (empty($html) ) {
        return $raw_html;
    }

    return $html;
}

/**
 * Remove version and add file version to js/css.
 *
 * @param string $src
 *
 * @return string
 */
function smarz_change_version_from_style_js( $src )
{

    if (! is_admin() ) {

        $clean_src  = $src ? esc_url(remove_query_arg('ver', $src)) : false;
        $clean_path = $clean_path = str_replace(site_url(), ABSPATH, $clean_src);
        // Default to root

        if (strpos($clean_src, 'wp-content/plugins') !== false ) {
            $clean_path = str_replace(
                site_url() . '/wp-content/plugins',
                ABSPATH . 'wp-content/plugins', $clean_src 
            );
        }

        if (strpos($clean_src, 'wp-content/themes') !== false ) {
            $clean_path = str_replace(
                get_theme_root_uri(), get_theme_root(),
                $clean_src 
            );
        }

        if (strpos($clean_src, 'wp-includes') !== false ) {
            $clean_path = str_replace(
                site_url() . '/wp-includes/',
                ABSPATH . 'wp-includes/', $clean_src 
            );
        }

        if (0 === strpos($clean_src, "/wp-includes/") ) {
            $clean_path = str_replace(
                '/wp-includes/',
                ABSPATH . 'wp-includes/', $clean_src 
            );
        }

        $return = file_exists($clean_path) ? add_query_arg(
            'xl',
            filemtime($clean_path), $clean_src 
        ) : add_query_arg(
            'xl',
            'file-not-found', $clean_src 
        );

        //External script/css
        if (strpos($clean_src, site_url()) === false ) {
            $return = preg_replace('~(\?|&)ver=[^&]*~', '', $src);

        }

        //Internal wp-admin
        if (strpos($clean_src, 'wp-admin') !== false ) {
            $return = $clean_src;
        }

        return $return;
    }

    return $src;

}

/**
 * Disable all dashboard widgets, also Yoast SEO and Gravity Forms
 */
function smarz_disable_dashboard_widgets()
{
    global $wp_meta_boxes;

    unset(
        $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'], $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'], $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'], $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'], $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'], $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'], $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'], $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'], $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'], $wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget'], $wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard'], $wp_meta_boxes['dashboard']['normal']['core']['semperplugins-rss-feed'], $wp_meta_boxes['dashboard']['normal']['core']['analytify-dashboard-addon'], $wp_meta_boxes['dashboard']['normal']['core']['aj_dashboard_widget'],
        $wp_meta_boxes['dashboard']['normal']['core']['aioseo-rss-feed'] 
    );

    remove_action('welcome_panel', 'wp_welcome_panel');
}

/**
 * Pretty permalink for search.
 */
function smarz_search_url_rewrite()
{
    global $wp_rewrite;
    if (! isset($wp_rewrite) || ! is_object($wp_rewrite) || ! $wp_rewrite->get_search_permastruct() ) {
        return;
    }

    $search_base = $wp_rewrite->search_base;
    if (is_search() && strpos(
        $_SERVER['REQUEST_URI'],
        "/{$search_base}/" 
    ) === false && strpos(
        $_SERVER['REQUEST_URI'],
        '&' 
    ) === false 
    ) {
        wp_redirect(get_search_link());
        exit();
    }
}

/**
 * @param $url
 *
 * @return mixed
 */
function smarz_rewrite( $url )
{
    return str_replace('/?s=', '/search/', $url);
}

/**
 * Remove archive title.
 *
 * @param $title
 *
 * @return string
 */
function smarz_remove_archive_title_prefix( $title )
{
    // Get the single category title with no prefix.
    $single_cat_title = single_term_title('', false);
    if (is_category() || is_tag() || is_tax() ) {
        return esc_html($single_cat_title);
    }

    return $title;
}

/**
 * Add post title in image alt attribute.
 *
 * @param $content
 *
 * @return mixed
 */
function smarz_add_image_alt( $content )
{
    global $post;

    if ($post === null ) {
        return $content;
    }

    $old_content = $content;

    preg_match_all('/<img[^>]+>/', $content, $images);

    if ($images !== null ) {
        foreach ( $images[0] as $index => $value ) {
            if (! preg_match('/alt=/', $value) ) {
                $new_img = str_replace(
                    '<img',
                    '<img alt="' . esc_attr($post->post_title) . '"',
                    $images[0][ $index ] 
                );
                $content = str_replace(
                    $images[0][ $index ], $new_img,
                    $content 
                );
            } elseif (preg_match('/alt=[\s"\']{2,3}/', $value) ) {
                $new_img = preg_replace(
                    '/alt=[\s"\']{2,3}/',
                    'alt="' . esc_attr($post->post_title) . '"',
                    $images[0][ $index ] 
                );
                $content = str_replace(
                    $images[0][ $index ], $new_img,
                    $content 
                );
            }
        }
    }

    if (empty($content) ) {
        return $old_content;
    }

    return $content;
}

/**
 * Setting attributes for post thumbnails.
 *
 * @param $attr
 * @param $attachment
 *
 * @return mixed
 */
function smarz_change_image_attr( $attr, $attachment )
{
    // Get post parent
    $parent = get_post_field('post_parent', $attachment);

    /// Get title
    $title = get_post_field('post_title', $parent);
    if ('' === $attr['alt'] ) {
        $attr['alt'] = $title;
    }
    $attr['title'] = $title;

    return $attr;
}

/**
 * Return Last-Modified Header.
 */
function smarz_last_mod_header()
{
    if (is_singular() ) {
        $post_id = get_queried_object_id();
        if ($post_id ) {
            header(
                'Last-Modified: ' . get_the_modified_time(
                    'D, d M Y H:i:s',
                    $post_id 
                ) 
            );
        }
    }
}

/**
 * Attachment pages redirect.
 */
function smarz_attachment_pages_redirect()
{
    global $post;

    if (is_attachment() ) {
        if (isset($post->post_parent) && ( $post->post_parent !== 0 ) ) {
            wp_redirect(get_permalink($post->post_parent), 301);
        } else {
            wp_redirect(home_url(), 301);
        }
        exit;
    }
}

/**
 * Redirect archives author, date, tags
 */
function smarz_redirect_archives_author()
{
    if (is_author() ) {
        wp_redirect(home_url(), 301);

        die();
    }
}

$wp_login_php = false;
/**
 * @return bool
 */
function smarz_use_trailing_slashes()
{
    return '/' === substr(get_option('permalink_structure'), - 1, 1);
}

/**
 * @param $string
 *
 * @return string
 */
function smarz_user_trailingslashit( $string )
{
    return smarz_use_trailing_slashes() ? trailingslashit($string) : untrailingslashit($string);
}

/**
 *
 */
function smarz_plugins_loaded()
{
    global $pagenow, $smarz_login, $wp_login_php;

    $request = parse_url($_SERVER['REQUEST_URI']);

    if (! is_admin() && ( strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false || ( isset($request['path']) && untrailingslashit($request['path']) === site_url('wp-login', 'relative') ) ) ) {
        $wp_login_php           = true;
        $_SERVER['REQUEST_URI'] = smarz_user_trailingslashit('/' . str_repeat('-/', 10));
        $pagenow                = 'index.php';

    } elseif (( ! get_option('permalink_structure') && isset($_GET['smarz_login']) && empty($_GET['smarz_login']) ) || ( isset($request['path']) && untrailingslashit($request['path']) === home_url($smarz_login['smarz_login'], 'relative') ) ) {

        $pagenow = 'wp-login.php';
    }
}

/**
 *
 */
function smarz_wp_loaded()
{
    global $pagenow, $wp_login_php;

    if (! defined('DOING_AJAX') && is_admin() && ! is_user_logged_in() ) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        get_template_part(404);
        exit();
    }

    $request = parse_url($_SERVER['REQUEST_URI']);

    if ($pagenow === 'wp-login.php' 
        && $request['path'] !== smarz_user_trailingslashit($request['path']) 
        && get_option('permalink_structure') 
    ) {
        wp_safe_redirect(smarz_user_trailingslashit(smarz_new_login_url()) . ( ! empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' ));
        die;
    }

    if ($wp_login_php
    ) {
        if (( $referer = wp_get_referer() ) 
            && strpos($referer, 'wp-activate.php') !== false 
            && ( $referer = parse_url($referer) ) 
            && ! empty($referer['query'])
        ) {
            parse_str($referer['query'], $referer);

            if (! empty($referer['key']) 
                && ( $result = wpmu_activate_signup($referer['key']) ) 
                && is_wp_error($result) && (        $result->get_error_code() === 'already_active' 
                || $result->get_error_code() === 'blog_taken'       ) 
            ) {
                wp_safe_redirect(smarz_new_login_url() . ( ! empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '' ));
                die;
            }
        }

        smarz_wp_template_loader();
    } elseif ($pagenow === 'wp-login.php' ) {

        @include ABSPATH . 'wp-login.php';
        die;
    }
}

/**
 *
 */
function smarz_wp_template_loader()
{
    global $pagenow;

    $pagenow = 'index.php';

    if (! defined('WP_USE_THEMES') ) {
        define('WP_USE_THEMES', true);
    }

    wp();

    if ($_SERVER['REQUEST_URI'] === smarz_user_trailingslashit(
        str_repeat(
            '-/',
            10 
        ) 
    ) 
    ) {
        $_SERVER['REQUEST_URI'] = smarz_user_trailingslashit('/wp-login-php/');
    }

    include_once ABSPATH . WPINC . '/template-loader.php';

    die;
}

/**
 * @param $url
 * @param $path
 * @param $scheme
 * @param $blog_id
 *
 * @return string
 */
function smarz_site_url( $url, $path, $scheme, $blog_id )
{
    return smarz_filter_wp_login_php($url, $scheme);
}

/**
 * @param $location
 * @param $status
 *
 * @return string
 */
function smarz_wp_redirect( $location, $status )
{
    return smarz_filter_wp_login_php($location);
}

/**
 * @param $url
 * @param null $scheme
 *
 * @return string
 */
function smarz_filter_wp_login_php( $url, $scheme = null )
{
    if (strpos($url, 'wp-login.php') !== false ) {
        if (is_ssl() ) {
            $scheme = 'https';
        }

        $args = explode('?', $url);

        if (isset($args[1]) ) {
            parse_str($args[1], $args);
            $url = add_query_arg($args, smarz_new_login_url($scheme));
        } else {
            $url = smarz_new_login_url($scheme);
        }
    }

    return $url;
}

/**
 * @param null $scheme
 *
 * @return string
 */
function smarz_new_login_url( $scheme = null )
{
    global $smarz_login;
    if (get_option('permalink_structure') ) {
        return smarz_user_trailingslashit(
            home_url(
                '/',
                $scheme 
            ) . $smarz_login['smarz_login'] 
        );
    }

    return home_url('/', $scheme) . '?' . $smarz_login['smarz_login'];
}

/**
 * @param $value
 *
 * @return string|string[]
 */
function smarz_welcome_email( $value )
{
    global $smarz_login;

    return str_replace(
        'wp-login.php',
        trailingslashit($smarz_login['smarz_login']),
        $value 
    );
}

/**
 * @param $wp_classes
 * @param $extra_classes
 *
 * @return array
 */
function smarz_admin_bar_body_class( $wp_classes, $extra_classes )
{

    if (( is_404() ) && ( ! is_user_logged_in() ) ) {
        $wp_nobar_classes = array_diff($wp_classes, array( 'admin-bar' ));

        // Add the extra classes back untouched
        return array_merge($wp_nobar_classes, (array) $extra_classes);
    }

    return $wp_classes;

}

if (is_admin() ) {

    /**
     * Adds Thumbnail column for posts
     *
     * @param array $columns
     *
     * @return array $columns
     */
    function smarz_posts_columns( $columns )
    {
        $post_type = get_post_type();
        if ($post_type === 'post' ) {
            unset($columns['date']);

            $columns = array_merge(
                $columns,
                [ 'thumbs' => __('Thumbnail'), 'date' => __('Date') ] 
            );
        }

        return $columns;
    }

    /**
     * Sets content for Thumbnail column and date
     *
     * @param string $column_name
     * @param int    $id
     */
    function smarz_posts_custom_columns( $column_name, $id )
    {
        if ($column_name === 'thumbs' ) {
            echo get_the_post_thumbnail($id, 'thumbnail');
        }
        if ($column_name === 'date' ) {
            echo get_the_date($id);
        }
    }

    /**
     * Remove comments column and adds Template column for pages
     *
     * @param array $columns
     *
     * @return array $columns
     */
    function smarz_page_column_views( $columns )
    {
        unset($columns['comments'], $columns['date']);

        return array_merge(
            $columns,
            [ 'page-layout' => __('Template'), 'date' => __('Date') ] 
        );

    }

    /**
     * Sets content for Template column and date
     *
     * @param string $column_name
     * @param int    $id
     */
    function smarz_page_custom_column_views( $column_name, $id )
    {
        if ($column_name === 'page-layout' ) {
            $set_template = get_post_meta(
                get_the_ID(), '_wp_page_template',
                true 
            );
            if (( $set_template === 'default' ) || ( $set_template === '' ) ) {
                   $set_template = 'Default';
            }
            $templates = wp_get_theme()->get_page_templates();
            foreach ( $templates as $key => $value ) :
                if (( $set_template === $key ) && ( $set_template !== '' ) ) {
                    $set_template = $value;
                }
            endforeach;

            echo $set_template;
        }
        if ($column_name === 'date' ) {
            echo get_the_date($id);
        }
    }
}
