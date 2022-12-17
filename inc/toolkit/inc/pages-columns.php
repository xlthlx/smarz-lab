<?php
/**
 * Page columns.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Remove comments column and adds Template column for pages
 *
 * @param array $columns The pages columns.
 *
 * @return array $columns
 */
function Sl_Page_Column_views( $columns )
{
    unset($columns['comments'], $columns['date']);

    return array_merge(
        $columns,
        array(
        'page-layout' => __('Template', 'xlthlx'),
        'modified'    => __('Data ultima modifica', 'xlthlx'),
        'date'        => __('Date', 'xlthlx'),
        )
    );

}

/**
 * Sets content for Template column and date
 *
 * @param string $column_name The column name.
 * @param int    $id          The post ID.
 *
 * @return void
 */
function Sl_Page_Custom_Column_views( $column_name, $id )
{
    if ('page-layout' === $column_name ) {
        $set_template = get_post_meta(
            get_the_ID(),
            '_wp_page_template',
            true
        );
        if (( 'default' === $set_template ) || ( '' === $set_template ) ) {
            $set_template = 'Default';
        }
        $templates = wp_get_theme()->get_page_templates();
        foreach ( $templates as $key => $value ) :
            if (( $set_template === $key ) && ( '' === $set_template ) ) {
                $set_template = $value;
            }
        endforeach;

        echo $set_template;
    }
    if ('modified' === $column_name ) {
        echo ucfirst(get_the_modified_time('d/m/Y', $id)) . ' alle ' . get_the_modified_time('H:i', $id);
    }
    if ('date' === $column_name ) {
        echo get_the_modified_time('D, d M Y H:i:s', $id);
    }
}

if (is_admin() ) {
    add_filter('manage_pages_columns', 'Sl_Page_Column_views', 9999);
    add_action('manage_pages_custom_column', 'Sl_Page_Custom_Column_views', 9999, 2);
}
