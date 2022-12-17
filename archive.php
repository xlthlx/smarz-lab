<?php
/**
 * The template for displaying Archive pages.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

global $wp_query;
get_header();

$title = esc_html__('Archive', 'smarz-lab');
$month = get_the_time('F');

if (is_day() ) {
    $title = get_the_date('d') . ' ' . $month . ' ' . get_the_date('Y');
} elseif (is_month() ) {
    $title = $month . ' ' . get_the_date('Y');
} elseif (is_year() ) {
    $title = get_the_date('Y');
} elseif (is_tag() ) {
    $title = single_tag_title('', false);
} elseif (is_category() ) {
    $title = single_cat_title('', false);
} elseif (is_post_type_archive() ) {
    $title = post_type_archive_title('', false);
}

$paged = ( get_query_var('paged') ) ?: 1;
?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="display-5 pb-3"><?php echo $title; ?></h1>
                <hr class="pt-0 mt-0 mb-4"/>
            </div>

            <div class="col-md-12">
                <?php if (have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        get_template_part('parts/tease');
                    }
                } ?>
            </div>
        </div>
    </div>
<?php Sl_pagination($wp_query, $paged); ?>
<?php
get_footer();
