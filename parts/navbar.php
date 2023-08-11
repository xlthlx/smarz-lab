<?php
/**
 * Navbar.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

global $site_url,$site_name,$site_desc; ?>
<header>
	<nav class="navbar navbar-expand-md navbar-dark bg-dark">

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<a class="shift" title="<?php echo $site_name; ?>" href="<?php echo $site_url; ?>">
						<?php echo file_get_contents( get_template_directory() . '/assets/img/logo.svg' ); ?>
					</a>
				</div>
			</div>

			<button class="navbar-toggler border-0 text-white rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarMain">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 text-md-center">
							<?php $items = sl_get_menu_items( 'primary' ); ?>
							<ul class="shift navbar-nav justify-content-end me-3 mt-4 pt-3 mb-md-0 text-uppercase">
								<?php foreach ( $items as $item ) { ?>
									<?php if ( isset( $item['submenu'] ) ) { ?>
										<li class="nav-item dropdown<?php echo $item['classes']; ?>">
											<a class="nav-link dropdown-toggle text-white"<?php echo $item['target']; ?>
											   href="<?php echo $item['url']; ?>"
											   id="<?php echo sanitize_title( $item['title'] ); ?>" data-bs-toggle="dropdown"
											   aria-expanded="true"><?php echo $item['title']; ?></a>
											<ul class="dropdown-menu bg-dark border-0 rounded-0"
												aria-labelledby="<?php echo sanitize_title( $item['title'] ); ?>">
										<?php foreach ( $item['submenu'] as $menu_subitem ) { ?>
													<li class="white text-white">
														<a class="dropdown-item white text-white"<?php echo $menu_subitem['target']; ?> href="<?php echo $menu_subitem['url']; ?>"><?php echo $menu_subitem['title']; ?>
														</a>
													</li>
										<?php } ?>
											</ul>
										</li>
									<?php } else { ?>
										<li class="nav-item <?php echo $item['classes']; ?>">
											<a class="nav-link text-white"<?php echo $item['target']; ?> href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?>
											</a>
										</li>
									<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

		</div>
	</nav>
</header>
