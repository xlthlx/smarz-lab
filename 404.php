<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

get_header();
?>
	<article class="post-type-404" id="post-0">
		<section class="page-content">
			<h1 class="display-5 pb-3"><?php esc_html__( 'Not found', 'smarz-lab' ); ?></h1>
			<hr class="pt-0 mt-0 mb-4"/>

			<div class="row">
				<div class="col-md-8">
					<p><?php esc_html__( "Sorry, we couldn't find what you're looking for.", 'smarz-lab' ); ?></p>
				</div>

				<div class="col-md-4">
					<aside class="sidebar px-5">
						<?php dynamic_sidebar( 'sidebar' ); ?>
					</aside>
				</div>
			</div>

		</section>
	</article>
<?php
get_footer();
