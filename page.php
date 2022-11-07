<?php
/**
 * The template for displaying all pages.
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */
get_header();
?>
<?php
while ( have_posts() ) :
the_post();
?>
<article class="post-type-<?php echo get_post_type(); ?>" id="post-<?php echo get_the_ID(); ?>">
	<section class="page-content">
		<h1 class="display-5 pb-3"><?php echo get_the_title(); ?></h1>
		<hr class="pt-0 mt-0 mb-4"/>

		<div class="row">
			<div class="col-md-8">
				<?php the_content(); ?>
			</div>

			<div class="col-md-4">
				<aside class="sidebar px-5">
					<?php dynamic_sidebar( 'sidebar' ); ?>
				</aside>
			</div>
		</div>

	</section>
</article>
<?php endwhile; ?>
<?php
get_footer();
