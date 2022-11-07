<?php
/**
 * The main template file.
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */
global $wp_query;
get_header();

$paged = ( get_query_var( 'paged' ) ) ?: 1;
?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						get_template_part( 'parts/tease' );
					}
				} ?>
			</div>
		</div>
	</div>

<?php sl_pagination( $wp_query,$paged ); ?>
<?php
get_footer();

