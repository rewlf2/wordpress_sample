<?php
/*

Template Name: Page Post

*/

	get_header();
?>
    <img src="/wp-content/uploads/2016/09/WorkingwithRF.jpg" class="img-responsive">
    <div id="primary" class="content-area fullwidth">
        <main id="main" class="site-main" role="main">

            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'template-parts/content', 'post' ); ?>

                    <?php

					if ( comments_open() || '0' != get_comments_number() ) :

						comments_template();

					endif;
				?>

                        <?php endwhile; // end of the loop. ?>


        </main>
        <!-- #main -->
    </div>
    <!-- #primary -->

    <?php get_footer(); ?>