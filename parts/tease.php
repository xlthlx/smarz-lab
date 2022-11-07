<?php
global $post;
$more = esc_html__( 'Keep reading: ', 'smarz-lab');
?>
<article class="tease tease-<?php echo $post->post_type; ?>" id="tease-<?php echo $post->ID; ?>">
	<div class="p-4 p-md-5 bg-light rounded-0 mb-4">

		<h2 class="mb-1 h1"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
		<p class="text-muted"><?php echo get_the_date(); ?></p>

		<p class="pr-4"><?php echo sl_get_excerpt(); ?>
			<a title="<?php echo $more . get_the_title(); ?>" href="<?php echo get_the_permalink(); ?>"><?php echo esc_html__( 'Read more...', 'smarz-lab'); ?></a>
		</p>
		<hr/>

	</div>
</article>
