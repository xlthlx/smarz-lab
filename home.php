<?php
/**
 * Homepage template.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

get_header();
global $wp_query;
$home_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2 class="display-5 pb-3">News</h2>
				<hr class="pt-0 mt-0 mb-4"/>
			</div>
			<div class="col-md-8">

				<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();

						get_template_part( 'parts/tease' );

					}
				}
				?>

			</div>

			<div class="col-md-4">
				<aside class="sidebar px-5">
					<?php dynamic_sidebar( 'sidebar' ); ?>
				</aside>
			</div>

		</div>

	</div>
<?php sl_pagination( $wp_query, $home_paged ); ?>
<?php
get_footer();
