<?php
/**
 * The main template file.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

global $wp_query;
get_header();

$paged = ( get_query_var('paged') ) ?: 1;
?>
    <div class="container">
        <div class="row">
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

