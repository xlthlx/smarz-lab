<?php
/**
 * Template Name: Shop
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

get_header();

$ebay_items = get_transient( 'ebay_items' );
if ( false === $ebay_items ) {
	$ebay_items = sl_get_ebay_all_items();
	set_transient( 'ebay_items', $ebay_items, 12 * HOUR_IN_SECONDS );
}
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
				<div class="col-md-12">
					<?php the_content(); ?>
				</div>

				<div class="row row-cols-1 row-cols-md-3 g-4">
					<?php foreach ( $ebay_items['page'] as $item ) { ?>
						<?php foreach ( $item as $card ) { ?>
							<?php if ( isset( $card['title'] ) ) { ?>
								<div class="col">
									<div class="card h-100">
										<a title="<?php echo esc_html__( 'See details on eBay', 'smarz-lab' ); ?>" href="<?php echo $card['link']; ?>" target="_blank">
											<img alt="<?php echo $card['title']; ?>" src="<?php echo $card['image']; ?>"/>
										</a>
										<div class="card-body">
											<h5 class="card-title"><?php echo $card['title']; ?></h5>
										</div>
										<div class="card-footer">
											<a title="<?php echo esc_html__( 'See details on eBay', 'smarz-lab' ); ?>" href="<?php echo $card['link']; ?>" target="_blank"><?php echo esc_html__( 'See details on eBay', 'smarz-lab' ); ?></a>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>
			</div>

		</section>
	</article>
<?php endwhile; ?>
<?php
get_footer();
