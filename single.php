<?php
/**
 * The Template for displaying all single posts.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

get_header();
?>
<?php
while ( have_posts() ) :
    the_post();
    ?>
    <article class="post-type-<?php echo get_post_type(); ?>" id="post-<?php echo get_the_ID(); ?>">

    <?php if (get_post_thumbnail_id() ) { ?>
            <picture class="col-auto d-none d-lg-block pb-5">

        <?php echo wp_get_attachment_image(
            get_post_thumbnail_id(),
            'large',
            false,
            [
                                'class'   => 'img-fluid mx-auto d-block',
                                'alt'     => get_the_title(),
                                'loading' => false,
            ]
        ); ?>
            </picture>
    <?php } ?>

        <h1 class="display-5 pb-3"><?php echo get_the_title(); ?></h1>
        <hr class="pt-0 mt-0 mb-4"/>

        <div class="row">
            <div class="col-md-8">
                <section class="article-content">

                    <p><?php echo get_the_date(); ?></p>


                    <div class="article-body pr-4">
                        <?php the_content(); ?>
                    </div>

                    <?php $cats = Sl_Get_The_terms('category');

                    if (is_home() || is_front_page() ) {
                        $cats = Sl_Get_The_terms('category', true);
                    }

                    if ('' !== $cats ) { ?>
                        <ul class="list-unstyled ml-0 pl-0 pt-4">
                        <?php echo $cats; ?>
                        </ul>
                    <?php } ?>
                    <hr/>
                </section>

                <section id="post-comments" class="comment-box">
                    <h3><?php echo esc_html__('Comments', 'smarz-lab'); ?></h3>
                    <?php comments_template(); ?>
                    <hr/>
                </section>

                <section id="comment-form" class="comment-box">
                    <?php Sl_Comment_form(); ?>
                </section>
            </div>

            <div class="col-md-4">
                <aside class="sidebar px-5">
                    <?php dynamic_sidebar('sidebar'); ?>
                </aside>
            </div>

        </div>
    </article>
<?php endwhile; ?>
<?php
get_footer();
