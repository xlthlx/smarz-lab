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

$archive_title = esc_html__( 'Archive', 'smarz-lab' );
$month         = get_the_time( 'F' );

if ( is_day() ) {
	$archive_title = get_the_date( 'd' ) . ' ' . $month . ' ' . get_the_date( 'Y' );
} elseif ( is_month() ) {
	$archive_title = $month . ' ' . get_the_date( 'Y' );
} elseif ( is_year() ) {
	$archive_title = get_the_date( 'Y' );
} elseif ( is_tag() ) {
	$archive_title = single_tag_title( '', false );
} elseif ( is_category() ) {
	$archive_title = single_cat_title( '', false );
} elseif ( is_post_type_archive() ) {
	$archive_title = post_type_archive_title( '', false );
}

$archive_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="display-5 pb-3"><?php echo $archive_title; ?></h1>
				<hr class="pt-0 mt-0 mb-4"/>
			</div>

			<div class="col-md-12">
				<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						get_template_part( 'parts/tease' );
					}
				}
				?>
			</div>
		</div>
	</div>
<?php sl_pagination( $wp_query, $archive_paged ); ?>
<?php
get_footer();
