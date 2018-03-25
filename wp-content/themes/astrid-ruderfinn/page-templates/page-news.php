<?php
/*

Template Name: What's New

*/

	get_header();
?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="panel" style="background-color: #00B047;">
                <div class="headline" style="color: black;">
                    <p style="font-size: 25px;">What’s New</p>
                    <h1 style="margin-top:0;font-family: 'Conv_segoeuil', sans-serif;    font-size: 60px; text-transform: capitalize;">The Art And Science Of Engagement</h1> </div>
            </div>
            <div class="entry-content">
                <?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'astrid' ),
				'after'  => '</div>',
			) );
		?> </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="news-wrapper">
                        <?php

add_action('pre_get_posts', 'wp_exclude_latest_post');

// The Query
if (get_query_var('paged')) {
    if (get_query_var('paged') == 0) {
        $paged = 1;
    } else {
        $paged = get_query_var('paged');
    }
} else {
    $paged = 1;
}
$the_query = new WP_Query( array( 'category_name' => 'news', 'paged' => $paged) );

// The Loop
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        get_template_part( 'template-parts/news' );
    }

?>
                            <div class="page-navigation">
                                <?php
    if ($the_query->max_num_pages > 1) { // check if the max number of pages is greater than 1 
        if ($paged > 1) {
//            echo "previous";
            echo get_previous_posts_link( '&lt;');
        }
        if ($paged < $the_query->max_num_pages) {
//            echo "next";
            echo get_next_posts_link( '&gt;', $the_query->max_num_pages );
        }
    }
?>
                            </div>
                            <?php

} else {
    echo "no post";
    // no posts found
}
?>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php echo do_shortcode("[do_widget id=wp-yearly-archive-2]"); ?>
                </div>
            </div>
        </main>
        <!-- #main -->
    </div>
    <!-- #primary -->
    <?php

    if ( get_theme_mod('fullwidth_single', 0) != 1 ) :
        get_sidebar();
    endif;

    get_footer();
?>