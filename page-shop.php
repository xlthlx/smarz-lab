<?php
/**
 * Template Name: Shop
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

if ( false === ( $ebay_items = get_transient( 'ebay_items' ) ) ) {
	$ebay_items = getAllItems();
	set_transient( 'ebay_items',$ebay_items,12 * HOUR_IN_SECONDS );
}

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
				<div class="col-md-12">
					<?php the_content(); ?>
				</div>

				<div class="row row-cols-1 row-cols-md-3 g-4">
					<?php foreach ( $ebay_items['Pagina'] as $item ) { ?>
						<?php foreach ( $item as $card ) { ?>
							<?php if ( isset( $card['Titolo'] ) ) { ?>
								<div class="col">
									<div class="card h-100">
										<img alt="<?php echo $card['Titolo']; ?>" src="<?php echo $card['Immagine']; ?>"/>
										<div class="card-body">
											<h5 class="card-title"><?php echo $card['Titolo']; ?></h5>
										</div>
										<div class="card-footer">
											<a href="<?php echo $card['Link']; ?>" target="_blank">Guarda i dettagli su eBay</a>
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
